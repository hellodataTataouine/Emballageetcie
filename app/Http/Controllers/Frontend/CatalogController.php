<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        $catalogues = Catalog::all(); // Adjust this based on your catalog retrieval logic

        return view('frontend.pages.catalogues.index', compact('catalogues'));
    }
}
