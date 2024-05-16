<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Campaign;
use App\Models\Page;
use App\Models\Catalog; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use Auth;
class HomeController extends Controller
{
    # set theme
    public function theme($name = "")
    {
        session(['theme' => $name]);
        return redirect()->route('home');
    }

    # homepage
    public function index()
    {
        $TrendingProducts = collect();
        $left_products = collect();
        $virtualProducts = collect();
        $blogs = Blog::isActive()->latest()->take(3)->get();

        $sliders = [];
        if (getSetting('hero_sliders') != null) {
            $sliders = json_decode(getSetting('hero_sliders'));
        }

        $banner_section_one_banners = [];
        if (getSetting('banner_section_one_banners') != null) {
            $banner_section_one_banners = json_decode(getSetting('banner_section_one_banners'));
        }

        $client_feedback = [];
        if (getSetting('client_feedback') != null) {
            $client_feedback = json_decode(getSetting('client_feedback'));
        }
        $apiUrl = env('API_CATEGORIES_URL');
        
        if (Auth::check() && Auth::user()->user_type == 'customer' && Auth::user()->email != null)
        {
       
        
        $response = Http::get($apiUrl . 'ListeDePrixWeb/' . Auth::user()->email);
 
    }else{
            $response = Http::get($apiUrl . 'ListeDePrixWeb/');

        }


        
        $produitsApi = $response->json();

        foreach ($produitsApi as $produitApi) {
            $name = $produitApi['Libellé'];

            $barcode = $produitApi['codeabarre'];
            $apiPrice = $produitApi['PrixVTTC'];
            $apiPriceHT = $produitApi['PrixVenteHT'];
            $apiStock = $produitApi['StockActual'];
            $apiunité = $produitApi['unité_lot'];
            $apiQTEUNITE = $produitApi['QTEUNITE'];
    
            $matchingProduct = Product::where('slug', $barcode)->with('categories')->first();
        
            if ($matchingProduct !== null && $matchingProduct->is_published == 1) {
                if ($matchingProduct->min_price !== $apiPrice || $matchingProduct->max_price !== $apiPrice || $matchingProduct->Prix_HT !== $apiPriceHT) {
                    $matchingProduct->min_price = $apiPrice; 
                    $matchingProduct->max_price = $apiPrice;
                    $matchingProduct->Prix_HT = $apiPriceHT;
                }
                if ($matchingProduct->stock_qty !== $apiStock) {
                    $matchingProduct->stock_qty = $apiStock;
                }
                if ($matchingProduct->Qty_Unit != $apiQTEUNITE) {
                    $matchingProduct->Qty_Unit = $apiQTEUNITE;
                }
                if ($matchingProduct->Unit != $apiunité) {
                    $matchingProduct->Unit = $apiunité;
                }
                $matchingProduct->name = $name;

            }
            if ($matchingProduct !== null && $matchingProduct->is_published == 1) {
                $virtualProducts->push($matchingProduct);
            }

        }


        $sortedProducts = $virtualProducts->sortByDesc('created_at');

        return getView('pages.home', ['blogs' => $blogs, 'sliders' => $sliders, 'banner_section_one_banners' => $banner_section_one_banners, 'client_feedback' => $client_feedback, 'products' => $sortedProducts, 'trendingProducts' => $TrendingProducts, 'left_products' => $left_products]);
    }

    # all brands
    public function allBrands()
    {
        return getView('pages.brands');
    }

    # all categories
    public function allCategories()
    {
        return getView('pages.categories');
    }

    # all coupons
    public function allCoupons()
    {
        return getView('pages.coupons.index');
    }

    # all offers
    public function allOffers()
    {
        return getView('pages.offers');
    }

    # all blogs
    public function allBlogs(Request $request)
    {
        $searchKey  = null;
        $blogs = Blog::isActive()->latest();

        if ($request->search != null) {
            $blogs = $blogs->where('title', 'like', '%' . $request->search . '%');
            $searchKey = $request->search;
        }

        if ($request->category_id != null) {
            $blogs = $blogs->where('blog_category_id', $request->category_id);
        }

        $blogs = $blogs->paginate(paginationNumber(5));
        return getView('pages.blogs.index', ['blogs' => $blogs, 'searchKey' => $searchKey]);
    }

    # blog details
    public function showBlog($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        return getView('pages.blogs.blogDetails', ['blog' => $blog]);
    }

    # get all campaigns
    public function campaignIndex()
    {
        return getView('pages.campaigns.index');
    }
     # all catalogues
     public function allcatalogues()
     {
         $catalogues = Catalog::all();
         return view('frontend.default.pages.catalogues.index', ['catalogues' => $catalogues]);
     }
     
     
     
 

    # campaign details
    public function showCampaign($slug)
    {
        $campaign = Campaign::where('slug', $slug)->first();
        return getView('pages.campaigns.show', ['campaign' => $campaign]);
    }

    # about us page
    public function aboutUs()
    {
        $features = [];

        if (getSetting('about_us_features') != null) {
            $features = json_decode(getSetting('about_us_features'));
        }

        $why_choose_us = [];

        if (getSetting('about_us_why_choose_us') != null) {
            $why_choose_us = json_decode(getSetting('about_us_why_choose_us'));
        }

        return getView('pages.quickLinks.aboutUs', ['features' => $features, 'why_choose_us' => $why_choose_us]);
    }

    # contact us page
    public function contactUs()
    {
        return getView('pages.quickLinks.contactUs');
    }

    # quick link / dynamic pages
    public function showPage($slug)
    {
        $page = Page::where('slug', $slug)->first();
        return getView('pages.quickLinks.index', ['page' => $page]);
    }
}
