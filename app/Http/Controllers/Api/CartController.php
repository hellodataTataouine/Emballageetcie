<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Api\CartResource;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class CartController extends Controller
{
    # all cart items
    public function index(Request $request)
    {
        return $this->getCartsInfo();
    }

    # add to cart
    public function store(Request $request)
    {
        $productVariation = ProductVariation::where('id', $request->product_variation_id)->first();

        if (!is_null($productVariation)) {

            $cart = null;
            $message = '';
            $cart          = Cart::where('user_id', auth()->user()->id)->where('location_id', $request->header("Stock-Location-Id"))->where('product_variation_id', $productVariation->id)->first();


            if (is_null($cart)) {
                $cart = new Cart;
                $cart->product_variation_id = $productVariation->id;
                $cart->qty                  = (int) $request->quantity;
                $cart->location_id          = $request->header("Stock-Location-Id");

                if (auth()->check()) {
                    $cart->user_id          = auth()->user()->id;
                } else {
                    $cart->guest_user_id    = (int) $_COOKIE['guest_user_id'];
                }
                $message =  localize('Produit ajouté à votre panier');
            } else {
                $cart->qty                  += (int) $request->quantity;
                $message =  localize('La quantité a été augmentée');
            }

            $cart->save();
            return $this->getCartsInfo($message, false);
        }
    }

    # update cart
    public function update(Request $request)
    {
        try {
            $cart = Cart::where('id', $request->id)->first();
            if ($request->action == "increase") {
                $productVariationStock = $cart->product_variation->product_variation_stock;
                if ($productVariationStock->stock_qty > $cart->qty) {
                    $cart->qty += 1;
                    $cart->save();
                }
            } elseif ($request->action == "decrease") {
                if ($cart->qty > 1) {
                    $cart->qty -= 1;
                    $cart->save();
                }else{
                    $cart->delete();
                }
            } elseif ($request->action == "delete") {
                $cart->delete();
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        //removeCoupon();
        // return $this->success('Updated');
        return $this->getCartsInfo('Updated', false);
    }

    # apply coupon
    public function applyCoupon(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();
        if ($coupon) {
            $date = strtotime(date('d-m-Y H:i:s'));

            # check if coupon is not expired
            if ($coupon->start_date <= $date && $coupon->end_date >= $date) {

                $carts  = Cart::where('user_id', auth()->user()->id)->where('location_id', $request->header("Stock-Location-Id"))->get();


                # check min spend
                $subTotal = (float) getSubTotal($carts, false);
                if ($subTotal >= (float) $coupon->min_spend) {

                    # check if coupon is for categories or products
                    if ($coupon->product_ids || $coupon->category_ids) {
                        if ($carts && validateCouponForProductsAndCategories($carts, $coupon)) {
                            # SUCCESS:: can apply coupon
                            // setCoupon($coupon);
                            // return $this->success(localize('Coupon applied successfully'));
                            return $this->getCartsInfo(localize('Coupon appliqué avec succès'), true, $coupon->code);
                        }

                        # coupon not valid for your cart items
                        // removeCoupon();
                        // return $this->couponApplyFailed(localize('Coupon is only applicable for selected products or categories'));
                        return $this->couponApplyFailed(localize('Le coupon s\'applique uniquement aux produits ou catégories sélectionnés'));
                    }

                    # SUCCESS::can apply coupon - not product or category based
                    // setCoupon($coupon);
                    return $this->getCartsInfo(localize('Coupon appliqué avec succès'), true, $coupon->code);
                }

                # min spend
                //    removeCoupon();
                return $this->couponApplyFailed('Veuillez faire des achats d\'au moins  ' . formatPrice($coupon->min_spend));
            }

            # expired
            //    removeCoupon();
            return $this->couponApplyFailed(localize('Le coupon a expiré'));
        }

        // coupon not found
        //    removeCoupon();
        return $this->couponApplyFailed(localize('Le coupon \'est pas valide'));
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
        return $this->couponApplyFailed(localize('Le coupon a été retiré'), true);
    }

    # get cart information
    private function getCartsInfo($message = '', $couponDiscount = true, $couponCode = '')
    {
        $carts          = Cart::where('user_id', auth()->user()->id)->where('location_id', request()->header("Stock-Location-Id"))->get();

        return [
            'result'           => true,
            'message'           => $message,
            'carts'             => CartResource::collection($carts),
            'cartCount'         => count($carts),
            'total'          => formatPrice(getSubTotal($carts, $couponDiscount, $couponCode)),
            'subTotal'          => formatPrice(getSubTotal($carts, false, "")),
            'couponDiscount'    => formatPrice(getCouponDiscount(getSubTotal($carts, false), $couponCode)),
        ];
    }
}
