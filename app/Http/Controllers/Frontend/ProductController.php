<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductVariationInfoResource;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTag;
use App\Models\Category;
use App\Models\ProductVariation;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Location;
use App\Models\ProductVariationStock;
use App\Models\ProductLocalization;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\ProductParents;
use App\Helpers\Constant\HelperFunctions; 
use Auth;
class ProductController extends Controller
{
   
    public function index(Request $request)
    {


       
       //dd( request()->view);
        if ($request->has('per_page')) {
            session(['per_page' => $request->get('per_page')]);
        }
       // dd($request);
        $virtualProducts = collect(); 
        $searchKey = null;
        $per_page = session('per_page', 12);
        $sort_by = $request->sort_by ? $request->sort_by : "new";
        $maxRange = Product::max('max_price');
        $min_value = 0;
        $max_value = formatPrice($maxRange, false, false, false, false);
        $apiUrl = env('API_CATEGORIES_URL');
        if (Auth::check() && Auth::user()->user_type == 'customer' && Auth::user()->email != null)
        {
       
        
        $response = Http::get($apiUrl . 'ListeDePrixWeb/' . Auth::user()->email);
 
    }else{
            $response = Http::get($apiUrl . 'ListeDePrixWeb/');

        }
        $produitsApi = $response->json();

        $barcodes = collect($produitsApi)->pluck('codeabarre')->toArray();
     
    
        $existingProducts = Product::whereIn('slug', $barcodes)
        ->with('categories')
        ->get()
        ->keyBy('slug');

        $productsNotInApi = Product::whereNotIn('slug', $barcodes)->get();
   
        foreach ($productsNotInApi as $product) {
           // dd($product);
            $product->is_publish = 0;
            $product->save();
        }

        // foreach ($existingProducts as $existingProduct) {
        //     // Check if the existing product is not found in the API list
        //     if (!in_array($existingProduct->slug, $barcodes)) {
            
        //         $existingProduct->is_published = 0;
                
        //         $existingProduct->save();
        //     }
        // }

        foreach ($produitsApi as $produitApi) {
            $name = $produitApi['Libellé'];
            $barcode = $produitApi['codeabarre'];
            $apiPrice = $produitApi['PrixVTTC'];
            $apiPriceHT = $produitApi['PrixVenteHT'];
            $apiStock = $produitApi['StockActual'];
            $apiunité = $produitApi['unité_lot'];
            $apiQTEUNITE = $produitApi['QTEUNITE'];

            // Find products with matching barcode
            if (!(isset($existingProducts[$barcode]))) {
            
            // Update prices for matching products
            $location = Location::where('is_default', 1)->first();
            $newProduct = new Product();
            $newProduct->name = $name;
            $newProduct->slug = $barcode; 
            $newProduct->min_price = $apiPrice;
            $newProduct->max_price = $apiPrice;
            $newProduct->Prix_HT = $apiPrice;
            $newProduct->stock_qty = $apiStock;
            $newProduct->has_variation = 0;
            $newProduct->Qty_Unit = $apiQTEUNITE;
            $newProduct->Unit = $apiunité;
            // Set other properties accordingly based on your product model

            $newProduct->save();

            $variation              = new ProductVariation;
            $variation->product_id  = $newProduct->id;
            // $variation->sku         = $request->sku;
            // $variation->code         = $request->code;
            $variation->price       = $apiPrice;
            $variation->save();
            $product_variation_stock = new ProductVariationStock;
            $product_variation_stock->product_variation_id    = $variation->id;
            $product_variation_stock->location_id             = $location->id;
            $product_variation_stock->stock_qty               = $apiStock;
            $product_variation_stock->save();
            $ProductLocalization = ProductLocalization::firstOrNew(['lang_key' => env('DEFAULT_LANGUAGE'), 'product_id' => $newProduct->id]);
            $ProductLocalization->name = $name;
            //$ProductLocalization->description = $request->description;
            $ProductLocalization->save();

            }

         }

        $existingProducts = $existingProducts
        ->where('is_published', 1);
    
        foreach ($produitsApi as $produitApi) {
            $name = $produitApi['Libellé'];

            $barcode = $produitApi['codeabarre'];
            $apiPrice = $produitApi['PrixVTTC'];
            $apiPriceHT = $produitApi['PrixVenteHT'];
            $apiStock = $produitApi['StockActual'];
            $apiunité = $produitApi['unité_lot'];
            $apiQTEUNITE = $produitApi['QTEUNITE'];
            if (isset($existingProducts[$barcode])) {
                $matchingProduct = $existingProducts[$barcode];
                
                if ($matchingProduct->min_price != $apiPrice || $matchingProduct->max_price != $apiPrice || $matchingProduct->Prix_HT != $apiPriceHT) {
                    $matchingProduct->min_price = $apiPrice; 
                    $matchingProduct->max_price = $apiPrice;
                    $matchingProduct->Prix_HT = $apiPriceHT;
                }
                
                if ($matchingProduct->stock_qty != $apiStock) {
                    $matchingProduct->stock_qty = $apiStock;
                }
                if ($matchingProduct->Qty_Unit != $apiQTEUNITE) {
                    $matchingProduct->Qty_Unit = $apiQTEUNITE;
                }
                if ($matchingProduct->Unit != $apiunité) {
                    $matchingProduct->Unit = $apiunité;
                }
                $matchingProduct->name = $name;
                $virtualProducts->push($matchingProduct);
            } 
        }


       

        if ($request->search != null) {
            if (!function_exists('removeAccents')) {
                function removeAccents($str) {
                    if (!preg_match('//u', $str)) {
                        $str = utf8_encode($str);
                    }
                    $str = \Normalizer::normalize($str, \Normalizer::FORM_D);
                    return preg_replace('/\pM/u', '', $str);
                }
            }
        
            $searchTerm = strtolower($request->search); // Convert to lowercase for case-insensitive search
            $searchTermWithoutAccents = removeAccents($searchTerm); // Remove accents from the search term
            $keywords = explode(' ', $searchTermWithoutAccents); // Split the search term into words
            
            // $filteredProducts = $virtualProducts->filter(function ($product) use ($searchTermWithoutAccents) {
            //     return collect($searchTermWithoutAccents)->some(function ($searchTermWithoutAccents) use ($product) {
            //         // Convert product attributes to lowercase and remove accents for comparison
            //         $productName = removeAccents(strtolower($product->name));
            //         // $productDescription = removeAccents(strtolower($product->description));
            //         $productSlug = removeAccents(strtolower($product->slug));
        
            //         // Check if the keyword is present in the product name, description, or slug
            //         $inProductAttributes = (
            //             stripos($productName, $searchTermWithoutAccents) !== false ||
            //             stripos($productSlug, $searchTermWithoutAccents) !== false
            //         );
        
            //         // Check if the keyword is present in any part of the tag names
            //         $inTagNames = collect($product->tags)->pluck('name')->some(function ($tagName) use ($searchTermWithoutAccents) {
            //             return stripos(removeAccents(strtolower($tagName)), $searchTermWithoutAccents) !== false;
            //         });
        
            //         return $inProductAttributes || $inTagNames;
            //     });
                
            // });
            $filteredProducts = $virtualProducts->filter(function ($product) use ($keywords) {
                // Check if all keywords are present in the product name or other attributes
                return collect($keywords)->every(function ($keyword) use ($product) {
                    $productName = removeAccents(strtolower($product->name));
                    $productSlug = removeAccents(strtolower($product->slug));
                    return (
                        stripos($productName, $keyword) !== false ||
                        stripos($productSlug, $keyword) !== false
                        
                    );
                });
            });
        
            // Reassign the filtered products to $virtualProducts
            $virtualProducts = $filteredProducts->values();
            $searchKey = $searchTerm;
        }
        

        
        # pagination
        if ($request->per_page != null) {
           // dd($request->per_page);
            $per_page = $request->per_page;
        }

        # sort by
        if ($sort_by == 'new') {
            $virtualProducts = $virtualProducts->sortByDesc('created_at'); 

            
        } else {
            $virtualProducts = $virtualProducts->sortByDesc('total_sale_count'); 

            
        }

        # by price
        if ($request->min_price != null) {
            $min_value = $request->min_price;
        }
        if ($request->max_price != null) {
            $max_value = $request->max_price;
        }

        if ($request->min_price || $request->max_price) {
            $virtualProducts = $virtualProducts->where('min_price', '>=', priceToUsd($min_value))->where('min_price', '<=', priceToUsd($max_value));
        }

        # by category
//dd( $request->category_id);
        $selectedCategoryId = $request->category_id;
        $selectedCategory = 0;
        if ($selectedCategoryId && $selectedCategoryId != null) {
            $product_category_product_ids = ProductCategory::where('category_id', $selectedCategoryId)->pluck('product_id');
            $virtualProducts = $virtualProducts->whereIn('id', $product_category_product_ids);
            $selectedCategory = Category::where('id', $selectedCategoryId)->first();
           
        }
       
        # by tag
        if ($request->tag_id && $request->tag_id != null) {
            $product_tag_product_ids = ProductTag::where('tag_id', $request->tag_id)->pluck('product_id');
            $virtualProducts = $virtualProducts->whereIn('id', $product_tag_product_ids);
        }

        # conditional
        $currentPage = $request->input('page', 1); 
        $perPage = $request->input('per_page', 12); // Updated line
        
        

        // if ($request->search != null  || $request->tag_id != null || $selectedCategoryId != null || request()->route()->getName() === 'customers.mesProduits' ){
// $visibleProducts = $virtualProducts->where('afficher', 1);
        // }else{
        //     $visibleProducts = $virtualProducts->filter(function ($product) {
        //     return $product->parents()->count() > 0;
        //     }); 
        //        }



        
         $visibleProducts = $virtualProducts->where('afficher', 1);
        // // dd($visibleProducts);
       
        $slicedProducts = $visibleProducts->slice(($currentPage - 1) * paginationNumber($per_page), paginationNumber($per_page))->values();
        $products = new LengthAwarePaginator($slicedProducts,$visibleProducts ->count(), paginationNumber($per_page), $currentPage);

        $products->withPath('/products');

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
            'selectedCategoryId' => $selectedCategoryId,
            'selectedCategory' => $selectedCategory,
        ]);   
    }

 
    # product show
  


    public function show($slug)
    {
        $product = Product::where('slug', $slug)->first();
        $virtualChidrenProducts = collect(); 
        $apiUrl = env('API_CATEGORIES_URL');
        
        if (Auth::check() && Auth::user()->user_type == 'customer' && Auth::user()->email != null)
        {
       
        
        $response = Http::get($apiUrl . 'ListeDePrixWeb/' . Auth::user()->email);
 
    }else{
            $response = Http::get($apiUrl . 'ListeDePrixWeb/');

        }



        $produitsApi = $response->json();



       




       
        
        
        foreach ($produitsApi as $produitApi) {
            
            $apiPrice = $produitApi['PrixVTTC'];
            $apiPriceHT = $produitApi['PrixVenteHT'];
            $apiStock = $produitApi['StockActual'];
            $apiunité = $produitApi['unité_lot'];
            $apiQTEUNITE = $produitApi['QTEUNITE'];
            $name = $produitApi['Libellé'];

            if($produitApi['codeabarre'] == $slug ){

                
                $product->min_price = $apiPrice; 
                $product->max_price = $apiPrice;
                $product->Prix_HT = $apiPriceHT;
                $product->stock_qty = $apiStock;
                $product->Unit = $apiunité;
                $product->Qty_Unit = $apiQTEUNITE;
                $product->name = $name;

                break;
                
            }
        }

        if (auth()->check() && auth()->user()->user_type == "admin") {
            // do nothing
        } else {
            if ($product->is_published == 0) {
                flash(localize('This product is not available'))->info();
                return redirect()->route('home');
            }
        }
       // $barcodes = collect($produitsApi)->pluck('codeabarre')->toArray();
        // $existingProducts = Product::whereIn('slug', $barcodes)
        //     ->get()
        //     ->keyBy('slug');
            // foreach ($existingProducts as $existingProduct) {
            //     // Check if the existing product is not found in the API list
            //     if (!in_array($existingProduct->slug, $barcodes)) {
                   
            //         $existingProduct->is_published = 0;
                    
            //         $existingProduct->save();
            //     }
            // }
        $productCategories= $product->categories()->pluck('category_id');
        $productIdsWithTheseCategories  = ProductCategory::whereIn('category_id', $productCategories)->where('product_id', '!=', $product->id)->pluck('product_id');
        //old one
        //$relatedProducts                = Product::whereIn('id', $productIdsWithTheseCategories)->where('is_published', 1)->where('afficher', 1)->get();
        $relatedProducts                = Product::whereIn('id', $productIdsWithTheseCategories)->where('is_published', 1)->get();
        //old one
        //$currentChildren = $product->parents()->where('is_published', 1)->where('afficher', 1)->get();
        $currentChildren = $product->parents()->where('is_published', 1)->get();
        $virtualRelatedProducts = collect();
        $virtualChildrenProducts = collect();

        foreach ($relatedProducts as $relatedProduct) {
            $matchingApiData = collect($produitsApi)->firstWhere('codeabarre', $relatedProduct->slug);
        
            if ($matchingApiData) {
                // Update related product details from the API
                $relatedProduct->min_price = $matchingApiData['PrixVTTC'];
                $relatedProduct->max_price = $matchingApiData['PrixVTTC'];
                $relatedProduct->Prix_HT = $matchingApiData['PrixVenteHT'];
                $relatedProduct->stock_qty = $matchingApiData['StockActual'];
                $relatedProduct->Qty_Unit = $matchingApiData['QTEUNITE'];
                $relatedProduct->Unit = $matchingApiData['unité_lot'];
                $relatedProduct->name = $matchingApiData['Libellé'];
        
                // Only add the related product if it is published
                $virtualRelatedProducts->push($relatedProduct);
               
            }
        }
        // $currentChildren = ProductParents::select('product_parent.child_position', 'product_parent.product_id', 'product_parent.child_id')
        // ->join('products', 'product_parent.product_id', '=', 'products.id')
        // ->get();



        foreach ($currentChildren as $matchingChild) {

            $matchingApiData = collect($produitsApi)->firstWhere('codeabarre', $matchingChild->slug);
        
            if ($matchingApiData) {
                // Update related product details from the API
                $matchingChild->min_price = $matchingApiData['PrixVTTC'];
                $matchingChild->max_price = $matchingApiData['PrixVTTC'];
                $matchingChild->Prix_HT = $matchingApiData['PrixVenteHT'];
                $matchingChild->stock_qty = $matchingApiData['StockActual'];
                $matchingChild->Qty_Unit = $matchingApiData['QTEUNITE'];
                $matchingChild->Unit = $matchingApiData['unité_lot'];
                $matchingChild->name = $matchingApiData['Libellé'];
        
                // Only add the related product if it is published
                $virtualChildrenProducts->push($matchingChild);
               
            }
        }
        
       

        $product_page_widgets = [];
        if (getSetting('product_page_widgets') != null) {
            $product_page_widgets = json_decode(getSetting('product_page_widgets'));
        }
        $sortedChildren = $virtualChildrenProducts->sortBy(function ($child) {
            return $child->pivot->child_position;
        });
       // dd($sortedChildren);
        return getView('pages.products.show', ['product' => $product, 'relatedProducts' => $virtualRelatedProducts, 'product_page_widgets' => $product_page_widgets, 'childrenProducts' => $sortedChildren]);
    }





    # product info
    public function showInfo(Request $request)
    {
        $product = Product::find($request->id);



        $apiUrl = env('API_CATEGORIES_URL');
        
        if (Auth::check() && Auth::user()->user_type == 'customer' && Auth::user()->email != null)
        {
       
        
        $response = Http::get($apiUrl . 'ListeDePrixWeb/' . Auth::user()->email);
 
    }else{
            $response = Http::get($apiUrl . 'ListeDePrixWeb/');

        }


        
        $produitsApi = $response->json();

        
        
        
        foreach ($produitsApi as $produitApi) {
            
            $apiPrice = $produitApi['PrixVTTC'];
            $apiPriceHT = $produitApi['PrixVenteHT'];
            $apiStock = $produitApi['StockActual'];
            $apiunité = $produitApi['unité_lot'];
            $apiQTEUNITE = $produitApi['QTEUNITE'];
            $name = $produitApi['Libellé'];

            if($produitApi['codeabarre'] == $product->slug ){

                
                $product->min_price = $apiPrice; 
                $product->max_price = $apiPrice;
                $product->Prix_HT = $apiPriceHT;
                $product->stock_qty = $apiStock;
                $product->Unit = $apiunité;
                $product->Qty_Unit = $apiQTEUNITE;
                $product->name = $name;

            }
        }
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