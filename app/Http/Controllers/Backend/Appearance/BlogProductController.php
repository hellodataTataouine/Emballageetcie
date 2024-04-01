<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;
class BlogProductController extends Controller
{
    
 /**
     * Affiche les produits les plus récents.
     *
     * @return \Illuminate\View\View
     */
    public function latestProducts()
    {
        // Récupérer les produits les plus récents
        $latest_products = Product::latest()->isPublished()->get();

        // Passer les données à la vue
        return view('backend.pages.appearance.products.blog', compact('latest_products'));
    }
}

