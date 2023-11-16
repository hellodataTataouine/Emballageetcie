<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{

    # customer's wishlist
    public function index()
    {
        $wishlist = auth()->user()->wishlist;
        return getView('pages.users.wishlist', ['wishlist' => $wishlist]);
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
