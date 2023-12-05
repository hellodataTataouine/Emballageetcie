<?php

namespace App\Http\Controllers\Frontend;
use Auth;
use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WishlistController extends Controller
{

    # customer's wishlist
    public function index()
    {
        $virtualProducts = collect();
        $wishlist = auth()->user()->wishlist;
        $apiUrl = env('API_CATEGORIES_URL');
        
        if (Auth::check() && Auth::user()->user_type == 'customer')
        {
        $response = Http::get($apiUrl . 'ListeDePrixWeb/' . Auth::user()->CODETIERS);
        }else{
           
            $response = Http::get($apiUrl . 'ListeDePrixWeb/');
        
        }
        $produitsApi = $response->json();
        $existingProducts = collect($produitsApi)->keyBy('codeabarre');

        $virtualProducts = $wishlist->map(function ($item) use ($existingProducts) {
            $barcode = $item->product->slug;
    
            if ($existingProducts->has($barcode)) {
                $apiProduct = $existingProducts[$barcode];
    
                // Update the wishlist product with API data
                $item->product->min_price = $apiProduct['PrixVTTC'];
                $item->product->max_price = $apiProduct['PrixVTTC'];
                $item->product->Prix_HT = $apiProduct['PrixVenteHT'];
                $item->product->stock_qty = $apiProduct['StockActual'];
                $item->product->Qty_Unit = $apiProduct['QTEUNITE'];
                $item->product->Unit = $apiProduct['unité_lot'];
    
                return $item->product;
            }
    
            // Handle cases where API data for a product is not found
            return null; // Or you can return $item->product as it is
        })->filter(); // Filter out null values
    
        return getView('pages.users.wishlist', ['wishlist' => $virtualProducts]);
    }

    # add to wishlist
    public function store(Request $request)
    {
        $userId = auth()->user()->id;
        $wishlist = Wishlist::where('user_id', $userId)->where('product_id', $request->product_id)->count();

        if ($wishlist < 1) {
            $wishlist = new Wishlist;
            $wishlist->user_id = $userId;
            $wishlist->product_id = $request->product_id;
            $wishlist->save();
        }

        return [
            'success' => true,
            'message'   => localize("Produit ajouté à votre liste d'envies")
        ];
    }

    # delete wishlist
    public function delete($id)
    {
        try {
            auth()->user()->wishlist()->where('id', $id)->delete();
        } catch (\Throwable $th) {
            //throw $th;
        }

        flash(localize('Le produit a été retiré de votre liste d\'envies'))->success();
        return back();
    }
}
