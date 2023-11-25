<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductVariationInfoResource;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTag;
use App\Models\ProductVariation;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Location;
use App\Models\ProductVariationStock;
use App\Models\ProductLocalization;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductController extends Controller
{
    # product listing
    public function index(Request $request)
    {
        $virtualProducts = collect(); 
        $searchKey = null;
        $per_page = 9;
        $sort_by = $request->sort_by ? $request->sort_by : "new";
        $maxRange = Product::max('max_price');
        $min_value = 0;
        $max_value = formatPrice($maxRange, false, false, false, false);
        $apiUrl = env('API_CATEGORIES_URL');
        
        $response = Http::get($apiUrl . 'Produit');
        $produitsApi = $response->json();

      /*  foreach ($produitsApi as $produitApi) {
            $name = $produitApi['Libellé'];
            $barcode = $produitApi['codeabarre'];
            $apiPrice = $produitApi['PrixVTTC'];
            $apiStock = $produitApi['StockActual'];
    
      // Find products with matching barcode
      $matchingProducts = Product::where('slug', $barcode)->get();
      if ($matchingProducts->isNotEmpty()) {
        foreach ($matchingProducts as $matchingProduct) {
            if ($matchingProduct->stock_qty !== $apiStock) {
            $matchingProduct->stock_qty = $apiStock;
            $matchingProduct->save();
        }
        }
    } else {
    // Update prices for matching products
    $location = Location::where('is_default', 1)->first();
    $newProduct = new Product();
    $newProduct->name = $name;
    $newProduct->slug = $barcode; // Assuming 'slug' is your barcode field
    // Set other properties of the product
    $newProduct->min_price = $apiPrice;
    $newProduct->max_price = $apiPrice;
    
    $newProduct->stock_qty = $apiStock;
   // $newProduct->has_variation = 0;
    // Set other properties accordingly based on your product model
    
    $newProduct->save();
    
    $variation              = new ProductVariation;
    $variation->product_id  = $newProduct->id;
    //$variation->sku         = $request->sku;
    //$variation->code         = $request->code;
    $variation->price       = $apiPrice;
    $variation->save();
    $product_variation_stock                          = new ProductVariationStock;
    $product_variation_stock->product_variation_id    = $variation->id;
    $product_variation_stock->location_id             = $location->id;
    $product_variation_stock->stock_qty               = $apiStock;
    $product_variation_stock->save();
    $ProductLocalization = ProductLocalization::firstOrNew(['lang_key' => env('DEFAULT_LANGUAGE'), 'product_id' => $newProduct->id]);
    $ProductLocalization->name = $name;
    //$ProductLocalization->description = $request->description;
    $ProductLocalization->save();
    
    
    }
    
        }*/
    
        foreach ($produitsApi as $produitApi) {
            $barcode = $produitApi['codeabarre'];
            $apiPrice = $produitApi['PrixVTTC'];
            $apiStock = $produitApi['StockActual'];
            $matchingProduct = Product::where('slug', $barcode)->with('categories')->first();
        
            if ($matchingProduct !== null && $matchingProduct->is_published == 1) {
                if ($matchingProduct->min_price !== $apiPrice || $matchingProduct->max_price !== $apiPrice) {
                    $matchingProduct->min_price = $apiPrice; 
                    $matchingProduct->max_price = $apiPrice;
                   
                }
                if ($matchingProduct->stock_qty !== $apiStock) {
                    $matchingProduct->stock_qty = $apiStock;
                }
            }
            if ($matchingProduct->is_published == 1) {

                $virtualProducts->push($matchingProduct);


            }
        }
        
        $products = $virtualProducts;



       // $products = Product::isPublished();

        # conditional - search by
        if ($request->search != null) {
            $products = $products->where('name', 'like', '%' . $request->search . '%');
            $searchKey = $request->search;
        }

        # pagination
        if ($request->per_page != null) {
            $per_page = $request->per_page;
        }

        # sort by
        if ($sort_by == 'new') {
            $products = $products->sortByDesc('created_at'); // Or any timestamp field

            
        } else {
            $products = $products->sortByDesc('total_sale_count'); // Or any other criteria

            
        }

        # by price
        if ($request->min_price != null) {
            $min_value = $request->min_price;
        }
        if ($request->max_price != null) {
            $max_value = $request->max_price;
        }

        if ($request->min_price || $request->max_price) {
            $products = $products->where('min_price', '>=', priceToUsd($min_value))->where('min_price', '<=', priceToUsd($max_value));
        }

        # by category
        if ($request->category_id && $request->category_id != null) {
            $product_category_product_ids = ProductCategory::where('category_id', $request->category_id)->pluck('product_id');
            $products = $products->whereIn('id', $product_category_product_ids);
        }

        # by tag
        if ($request->tag_id && $request->tag_id != null) {
            $product_tag_product_ids = ProductTag::where('tag_id', $request->tag_id)->pluck('product_id');
            $products = $products->whereIn('id', $product_tag_product_ids);
        }
        # conditional
        $currentPage = $request->input('page', 1); 
        $perPage = 10;
        $products = new LengthAwarePaginator($products, count($products), $perPage, $currentPage);
          
        //$products = $products->paginate(paginationNumber($per_page));

        $tags = Tag::all();
        return getView('pages.products.index', [
            'products'      => $products,
            'searchKey'     => $searchKey,
            'per_page'      => $per_page,
            'sort_by'       => $sort_by,
            'max_range'     => formatPrice($maxRange, false, false, false, false),
            'min_value'     => $min_value,
            'max_value'     => $max_value,
            'tags'          => $tags,
        ]);
    }

    # product show
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->first();

        if (auth()->check() && auth()->user()->user_type == "admin") {
            // do nothing
        } else {
            if ($product->is_published == 0) {
                flash(localize('This product is not available'))->info();
                return redirect()->route('home');
            }
        }

        $productCategories              = $product->categories()->pluck('category_id');
        $productIdsWithTheseCategories  = ProductCategory::whereIn('category_id', $productCategories)->where('product_id', '!=', $product->id)->pluck('product_id');

        $relatedProducts                = Product::whereIn('id', $productIdsWithTheseCategories)->get();

        $product_page_widgets = [];
        if (getSetting('product_page_widgets') != null) {
            $product_page_widgets = json_decode(getSetting('product_page_widgets'));
        }

        return getView('pages.products.show', ['product' => $product, 'relatedProducts' => $relatedProducts, 'product_page_widgets' => $product_page_widgets]);
    }

    # product info
    public function showInfo(Request $request)
    {
        $product = Product::find($request->id);
        return getView('pages.partials.products.product-view-box', ['product' => $product]);
    }

    # product variation info
    public function getVariationInfo(Request $request)
    {
        $variationKey = "";
        foreach ($request->variation_id as $variationId) {
            $fieldName      = 'variation_value_for_variation_' . $variationId;
            $variationKey  .=  $variationId . ':' . $request[$fieldName] . '/';
        }
        $productVariation = ProductVariation::where('variation_key', $variationKey)->where('product_id', $request->product_id)->first();

        return new ProductVariationInfoResource($productVariation);
    }
}
