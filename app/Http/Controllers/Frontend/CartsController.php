<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Auth;

class CartsController extends Controller
{
    # all cart items
    public function index()
    {
        $carts = null;
        if (Auth::check()) {
            $carts          = Cart::where('user_id', Auth::user()->id)->where('location_id', session('stock_location_id'))->get();
        } else {
            $carts          = Cart::where('guest_user_id', (int) $_COOKIE['guest_user_id'])->where('location_id', session('stock_location_id'))->get();
        }
        return getView('pages.checkout.carts', ['carts' => $carts]);
    }

    # add to cart
    public function store(Request $request)
    {
        $productVariation = ProductVariation::where('id', $request->product_variation_id)->first();

        if (!is_null($productVariation)) {

            $cart = null;
            $message = '';

            if (Auth::check()) {
                $cart          = Cart::where('user_id', Auth::user()->id)->where('location_id', session('stock_location_id'))->where('product_variation_id', $productVariation->id)->first();
            } else {
                $cart          = Cart::where('guest_user_id', (int) $_COOKIE['guest_user_id'])->where('location_id', session('stock_location_id'))->where('product_variation_id', $productVariation->id)->first();
            }

            if (is_null($cart)) {
                $product = $productVariation->product;
                $cart = new Cart;
                $cart->product_variation_id = $productVariation->id;
                $cart->product_price = $request->product_price;
                if($request->quantity > $product->max_purchase_qty){
                    $cart->qty                  = (int) $product->max_purchase_qty;
                }else{
                    $cart->qty                  = (int) $request->quantity;
                }
                $cart->location_id          = session('stock_location_id');

                if (Auth::check()) {
                    $cart->user_id          = Auth::user()->id;
                } else {
                    $cart->guest_user_id    = (int) $_COOKIE['guest_user_id'];
                }
                $message =  localize('Produit ajouté à votre panier');
            } else {
                $product = $cart->product_variation->product; 
                if($product->max_purchase_qty > $cart->qty){ 
                    $cart->qty                  += (int) $request->quantity;
                    $message =  localize('La quantité a été augmentée');
                }else{ 
                    $message = localize('Vous avez atteint la quantité maximale autorisée pour ce produit lors d\'une seule commande.');
                  //  return $this->getCartsInfo($message, true, '', 'Attention');
                } 
            }

            $cart->save();
            // remove coupon
            removeCoupon();
            return $this->getCartsInfo($message, false);
        }
    }

    # update cart
    public function update(Request $request)
    {
        try {
            $cart = Cart::where('id', $request->id)->first();
            if ($request->action == "increase") {
                $product = $cart->product_variation->product;

                if($product->max_purchase_qty > $cart->qty){
                    $productVariationStock = $cart->product_variation->product_variation_stock;
                    if ($productVariationStock->stock_qty > $cart->qty) {
                        $cart->qty += 1;
                        $cart->save();
                    }
                }else{ 
                    $message = localize('Vous avez atteint la quantité maximale de commande pour ce produit');
                    return $this->getCartsInfo($message, true, '', 'warning');
                }
                
            } elseif ($request->action == "decrease") {
                if ($cart->qty > 1) {
                    $cart->qty -= 1;
                    $cart->save();
                }
            } else {
                $cart->delete();
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        removeCoupon();
        return $this->getCartsInfo('', false);
    }

    # apply coupon
    public function applyCoupon(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();
        if ($coupon) {
            $date = strtotime(date('d-m-Y H:i:s'));

            # check if coupon is not expired
            if ($coupon->start_date <= $date && $coupon->end_date >= $date) {

                $carts = null;
                if (Auth::check()) {
                    $carts          = Cart::where('user_id', Auth::user()->id)->where('location_id', session('stock_location_id'))->get();
                } else {
                    $carts          = Cart::where('guest_user_id', (int) $_COOKIE['guest_user_id'])->where('location_id', session('stock_location_id'))->get();
                }

                # check min spend
                $subTotal = (float) getSubTotal($carts, false);
                if ($subTotal >= (float) $coupon->min_spend) {

                    # check if coupon is for categories or products
                    if ($coupon->product_ids || $coupon->category_ids) {
                        if ($carts && validateCouponForProductsAndCategories($carts, $coupon)) {
                            # SUCCESS:: can apply coupon
                            setCoupon($coupon);
                            return $this->getCartsInfo(localize('Coupon appliqué avec succès'), true, $coupon->code);
                        }

                        # coupon not valid for your cart items  
                        removeCoupon();
                        return $this->couponApplyFailed(localize('Coupon is only applicable for selected products or categories'));
                    }

                    # SUCCESS::can apply coupon - not product or category based
                    setCoupon($coupon);
                    return $this->getCartsInfo(localize('Coupon appliqué avec succès'), true, $coupon->code);
                }

                # min spend
                removeCoupon();
                return $this->couponApplyFailed('Veuillez faire des achats d\'au moins ' . formatPrice($coupon->min_spend));
            }

            # expired 
            removeCoupon();
            return $this->couponApplyFailed(localize('Le coupon a expiré'));
        }

        // coupon not found
        removeCoupon();
        return $this->couponApplyFailed(localize('Le coupon n\'est pas valide'));
    }

    # coupon apply failed
    private function couponApplyFailed($message = '', $success = false)
    {
        $response = $this->getCartsInfo($message, false);
        $response['success'] = $success;
        return $response;
    }

    # clear coupon
    public function clearCoupon()
    {
        removeCoupon();
        return $this->couponApplyFailed(localize('Le coupon a été retiré'), true);
    }

    # get cart information
    private function getCartsInfo($message = '', $couponDiscount = true, $couponCode = '', $alert = 'success')
    {
        $carts = null;
        if (Auth::check()) {
            $carts          = Cart::where('user_id', Auth::user()->id)->where('location_id', session('stock_location_id'))->get();
        } else {
            $carts          = Cart::where('guest_user_id', (int) $_COOKIE['guest_user_id'])->where('location_id', session('stock_location_id'))->get();
        }

        return [
            'success'           => true,
            'message'           => $message,
            'alert'             => $alert,
            'carts'             => getViewRender('pages.partials.carts.cart-listing', ['carts' => $carts]),
            'navCarts'          => getViewRender('pages.partials.carts.cart-navbar', ['carts' => $carts]),
            'cartCount'         => count($carts),
            'subTotal'          => formatPrice(getSubTotal($carts, $couponDiscount, $couponCode)),
            'couponDiscount'    => formatPrice(getCouponDiscount(getSubTotal($carts, false), $couponCode)),
        ];
    }
}
