<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Api\ProductDetailsResource;
use App\Http\Resources\Api\ProductMiniResource;
use App\Models\Category;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductLocalization;
use App\Models\ProductParents;
use App\Models\ProductTag;
use App\Models\ProductVariation;
use App\Models\ProductVariationStock;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $virtualProducts = collect(); 
        $searchKey = null;
        $per_page = 24;
        $sort_by = $request->sort_by ? $request->sort_by : "new";
        $maxRange = Product::max('max_price');
        $min_value = 0;
        $max_value = formatPrice($maxRange, false, false, false, false);
        $apiUrl = env('API_CATEGORIES_URL');
        $user = auth()->user();
        if (!is_null($user) && $user->user_type == 'customer')
        {
        $response = Http::get($apiUrl . 'ListeDePrixWeb/' . Auth::user()->name);
        }else{
        
            $response = Http::get($apiUrl . 'ListeDePrixWeb/');

        }
        $produitsApi = $response->json();
        dd($produitsApi);
        $barcodes = collect($produitsApi)->pluck('codeabarre')->toArray();
        $existingProducts = Product::whereIn('slug', $barcodes)
        ->with('categories')
        ->get()
        ->keyBy('slug');
        foreach ($existingProducts as $existingProduct) {
            // Check if the existing product is not found in the API list
            if (!in_array($existingProduct->slug, $barcodes)) {
            
                $existingProduct->is_published = 0;
                
                $existingProduct->save();
            }
        }

        foreach ($produitsApi as $produitApi) {
            $name = $produitApi['Libellé'];
            $barcode = $produitApi['codeabarre'];
            $apiPrice = $produitApi['PrixVTTC'];
            $apiPriceHT = $produitApi['PrixVenteHT'];
            $apiStock = $produitApi['StockActual'];
            $apiunité = $produitApi['unité_lot'];
            $apiQTEUNITE = $produitApi['QTEUNITE'];
            $apiPoids = $produitApi['Poids'];
            $apiFamille = $produitApi['Famille'];
            $OldPrice = $produitApi['OldPrice'];
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
            $newProduct->max_purchase_qty = 1000;
            $newProduct->is_published = 1;
            $newProduct->afficher = 1;
            $newProduct->Poids = $apiPoids;
            $newProduct->OldPrice = $OldPrice;
            // Set other properties accordingly based on your product model

            $newProduct->save();
            $category = Category::firstOrCreate(
                ['name' => $apiFamille],
                [
                    'parent_id' => 0,

                    'sorting_order_level' => 0,
                    'level' => 0,
                    'is_featured' => 0,
                    'is_top' => 0,
                    'total_sale_count' => 0,
                    'meta_title' => $apiFamille,
                ]
            );
            // Attach the category to the product
            $newProduct->categories()->syncWithoutDetaching([$category->id]);
       
    
            $variation              = new ProductVariation();
            $variation->product_id  = $newProduct->id;
            $variation->price       = $apiPrice;
            $variation->save();
            $product_variation_stock = new ProductVariationStock();
            $product_variation_stock->product_variation_id    = $variation->id;
            $product_variation_stock->location_id             = $location->id;
            $product_variation_stock->stock_qty               = $apiStock;
            $product_variation_stock->save();
            $ProductLocalization = ProductLocalization::firstOrNew(['lang_key' => env('DEFAULT_LANGUAGE'), 'product_id' => $newProduct->id]);
            $ProductLocalization->name = $name;
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
            $apiPoids = $produitApi['Poids'];
            $OldPrice = $produitApi['OldPrice'];
           // dd($OldPrice) ;  
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
            if ($matchingProduct->Poids != $apiPoids) {
                   $matchingProduct->Poids = $apiPoids;
                }
                
                    $matchingProduct->OldPrice = $OldPrice;
              
                $matchingProduct->name = $name;


                $virtualProducts->push($matchingProduct);
              //  dd($virtualProducts);
            } else {
            }
        }
        if (is_null($user)) {
            foreach ($virtualProducts as $product) {
                $product->Prix_HT = 0; // Set price to 0 for unauthenticated users
            }
        }

        if ($request->search != null) {
            $searchTerm = $request->search;
        
            // Split the search term into words
            $keywords = explode(' ', $searchTerm);
        
            $filteredProducts = $virtualProducts->filter(function ($product) use ($keywords) {
                // Check if any part of the keywords matches the product name, description, slug, or tag names
                return collect($keywords)->every(function ($keyword) use ($product) {
                    // Check if the keyword is present in the product name, description, or slug
                    $inProductAttributes = (
                        stripos($product->name, $keyword) !== false ||
                        stripos($product->description, $keyword) !== false ||
                        stripos($product->slug, $keyword) !== false
                    );
        
                    // Check if the keyword is present in any part of the tag names
                    $inTagNames = collect($product->tags)->pluck('name')->some(function ($tagName) use ($keyword) {
                        return stripos($tagName, $keyword) !== false;
                    });
        
                    return $inProductAttributes || $inTagNames;
                });
            });
        
            // Reassign the filtered products to $virtualProducts
            $virtualProducts = $filteredProducts->values();
            $searchKey = $searchTerm;
        }
        # pagination
        if ($request->per_page != null) {
            $per_page = $request->per_page;
        }




        


        # sort by
        if ($sort_by == 'populaire') {
            $trending_products = getSetting('top_trending_products') != null ? json_decode(getSetting('top_trending_products')) : [];

            // Filter the $virtualProducts to keep only those whose id exists in $trending_products
            $virtualProducts = $virtualProducts->filter(function($product) use ($trending_products) {
                return in_array($product->id, $trending_products);
            });

            
        }
        if ($sort_by == 'new') {
            $virtualProducts = $virtualProducts->sortByDesc('created_at'); 

            
        } else if($sort_by =="best_selling") {
            $virtualProducts = $virtualProducts->sortByDesc('total_sale_count'); 
        }else if ($sort_by == "in_stock"){
            $virtualProducts = $virtualProducts->filter(function ($product) {
                return $product->stock_qty > 0;
            });
        }
        else if($sort_by =="price_asc"){
            $virtualProducts = $virtualProducts->sortBy('Prix_HT')->values();
        }
        else if($sort_by =="price_desc"){
            $virtualProducts = $virtualProducts->sortByDesc('Prix_HT')->values();
        }
        else if($sort_by == "out_stock"){
            $virtualProducts = $virtualProducts->filter(function ($product) {
                return $product->stock_qty <= 0;
            });
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

        $selectedCategoryId = $request->category_id;

        if ($selectedCategoryId && $selectedCategoryId != null) {
           // $promo_category =Category::where('name', 'Promotions')->firstOrFail();
           // $promo_categoryId = $promo_category->id;
           
            // if ($promo_category != null && $promo_category->id = $selectedCategoryId) {
            //    // $product_category_product_ids = ProductCategory::where('category_id', $selectedCategoryId)->pluck('product_id');
            //    $virtualProducts = $virtualProducts->where('OldPrice', '>', 0);
            // } else {
            $product_category_product_ids = ProductCategory::where('category_id', $selectedCategoryId)->pluck('product_id');
            $virtualProducts = $virtualProducts->whereIn('id', $product_category_product_ids);
            // }
        }

        # by tag
        if ($request->tag_id && $request->tag_id != null) {
            $product_tag_product_ids = ProductTag::where('tag_id', $request->tag_id)->pluck('product_id');
            $virtualProducts = $virtualProducts->whereIn('id', $product_tag_product_ids);
        }

        
        # conditional
        $currentPage = $request->input('page', 1); 
        $perPage = $request->input('per_page', 24); // Updated line
        # Separate in-stock and out-of-stock products
        $visibleProducts = $virtualProducts;
        if($sort_by !=="price_asc" && $sort_by !=="price_desc"){
            $inStockProducts = $virtualProducts->filter(function ($product) {
                return $product->stock_qty > 0;
            });
            
            $outOfStockProducts = $virtualProducts->filter(function ($product) {
                return $product->stock_qty <= 0;
            });
            # Merge and sort
            $sortedProducts = $inStockProducts->sortByDesc('created_at')->merge($outOfStockProducts->sortByDesc('created_at'));
            $visibleProducts = $sortedProducts->where('afficher', 1);
        }
        
       
        $slicedProducts = $visibleProducts->slice(($currentPage - 1) * paginationNumber($perPage), paginationNumber($perPage))->values();
        $products = new LengthAwarePaginator($slicedProducts,$visibleProducts ->count(), paginationNumber($perPage), $currentPage);

        //$products->withPath('/products');
        foreach($products as $product){
            $product->thumbnail_image!==null ? $product->thumbnail_image= uploadedAsset($product->thumbnail_image) :"";
            if (!is_null($product->gallery_images)){
                $images=[];
                $gallery = explode(',',$product->gallery_images);
                foreach ($gallery as $img){
                    $images[]= uploadedAsset($img);
                }
                $product->gallery_images=$images;
            }
            $product->meta_img!==null ? $product->meta_img= uploadedAsset($product->meta_img) :"";
           
        }
        
        $tags = Tag::all();
        
        return response()->json([
            'products'      => $products,
            'searchKey'     => $searchKey,
            'per_page'      => $per_page,
            'sort_by'       => $sort_by,
            'max_range'     => formatPrice($maxRange, false, false, false, false),
            'min_value'     => $min_value,
            'max_value'     => $max_value,
            'tags'          => $tags,
            'selectedCategoryId' => $selectedCategoryId,
        ]);  
    }

    public function featured()
    {
        $featured_products = getSetting('featured_products_left') != null ? json_decode(getSetting('featured_products_left')) : [];
        $featured_products[] = getSetting('featured_products_right') != null ? json_decode(getSetting('featured_products_right')) : [];

        $products = Product::whereIn('id', $featured_products)->get();
        foreach($products as $product){
            $product->thumbnail_image!==null ? $product->thumbnail_image= uploadedAsset($product->thumbnail_image) :"";
            if (!is_null($product->gallery_images)){
                $images=[];
                $gallery = explode(',',$product->gallery_images);
                foreach ($gallery as $img){
                    $images[]= uploadedAsset($img);
                }
                $product->gallery_images=$images;
            }
            $product->meta_img!==null ? $product->meta_img= uploadedAsset($product->meta_img) :"";
        }
        return response()->json([
            'products' => $products,
        ]);
        //return ProductMiniResource::collection($products);
    }
    public function trendingProducts()
    {

        $trending_products = getSetting('top_trending_products') != null ? json_decode(getSetting('top_trending_products')) : [];
        $products = Product::whereIn('id', $trending_products)->with('categories')->get();


        foreach($products as $product){
            $product->thumbnail_image!==null ? $product->thumbnail_image= uploadedAsset($product->thumbnail_image) :"";
            if (!is_null($product->gallery_images)){
                $images=[];
                $gallery = explode(',',$product->gallery_images);
                foreach ($gallery as $img){
                    $images[]= uploadedAsset($img);
                }
                $product->gallery_images=$images;
            }
            $product->meta_img!==null ? $product->meta_img= uploadedAsset($product->meta_img) :"";
        }
        return response()->json([
            $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $virtualChidrenProducts = collect(); 
        $apiUrl=env('API_CATEGORIES_URL');;
        
        if (Auth::check() && Auth::user()->user_type == 'customer')
            {
                $response = Http::get($apiUrl . 'ListeDePrixWeb/' . Auth::user()->name);
                
            }else{
            
                $response = Http::get($apiUrl . 'ListeDePrixWeb/');

            }

          $response1 = Http::get($apiUrl . 'ValeurNitr/' . $slug );
          $response2  = Http::get('http://87.106.135.239/INGRIDIANTS/' . $slug );
          $response3= Http::get($apiUrl . 'ProduitsEquivalent/' . $slug );


          $valeurNitrition = $response1->json();
          $ingredients = $response2->json();
          $CodesAbares = $response3->json();


        $produitsApi = $response->json();

        $product = Product::where('slug', $slug)->first();
        
        
        foreach ($produitsApi as $produitApi) {
            
            $apiPrice = $produitApi['PrixVTTC'];
            $apiPriceHT = $produitApi['PrixVenteHT'];
            $apiStock = $produitApi['StockActual'];
            $apiunité = $produitApi['unité_lot'];
            $apiQTEUNITE = $produitApi['QTEUNITE'];
            $name = $produitApi['Libellé'];
            $apiPoids = $produitApi['Poids'];
            if($produitApi['codeabarre'] == $slug ){

                
                $product->min_price = $apiPrice; 
                $product->max_price = $apiPrice;
                $product->Prix_HT = $apiPriceHT;
                $product->stock_qty = $apiStock;
                $product->Unit = $apiunité;
                $product->Qty_Unit = $apiQTEUNITE;
                $product->name = $name;
                $product->Poids = $apiPoids;
                break;
                
            }
        }

        if (auth()->check() && auth()->user()->user_type == "admin") {
            // do nothing
        } else {
            if ($product->is_published == 0) {
                flash(localize('This product is not available'))->info();
                return response()->json(null);
            }
        }
        $barcodes = collect($produitsApi)->pluck('codeabarre')->toArray();
        $existingProducts = Product::whereIn('slug', $barcodes)
            ->get()
            ->keyBy('slug');
            foreach ($existingProducts as $existingProduct) {
                // Check if the existing product is not found in the API list
                if (!in_array($existingProduct->slug, $barcodes)) {
                   
                    $existingProduct->is_published = 0;
                    
                    $existingProduct->save();
                }
            }
        $productCategories= $product->categories()->pluck('category_id');
        $productIdsWithTheseCategories  = ProductCategory::whereIn('category_id', $productCategories)->where('product_id', '!=', $product->id)->pluck('product_id');

        $relatedProducts                = Product::whereIn('id', $productIdsWithTheseCategories)->where('is_published', 1)->get();
        $currentChildren = ProductParents::select('product_parent.child_position', 'product_parent.product_id', 'product_parent.child_id')
        ->join('products', 'product_parent.product_id', '=', 'products.id')
        ->get();
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
        
        
        foreach ($produitsApi as $produitApi) {
            $apiPrice = $produitApi['PrixVTTC'];
            $apiPriceHT = $produitApi['PrixVenteHT'];
            $apiStock = $produitApi['StockActual'];
            $apiunité = $produitApi['unité_lot'];
            $apiQTEUNITE = $produitApi['QTEUNITE'];
            $name = $produitApi['Libellé'];
            $apiPoids = $produitApi['Poids'];
            $barcode = $produitApi['codeabarre'];
            $matchingChild = $product->parents()->where('slug', $barcode)->where('is_published', 1)->first();
            $matchingrelatedProduct = $relatedProducts->where('slug', $barcode)->first();

           
            if ($matchingChild) {
                // Update child product details from the API
                $matchingChild->min_price = $apiPrice; 
                $matchingChild->max_price = $apiPrice;
                $matchingChild->Prix_HT = $apiPriceHT;
           
                $matchingChild->stock_qty = $apiStock;
            
                $matchingChild->Qty_Unit = $apiQTEUNITE;
           
                $matchingChild->Unit = $apiunité;
                $matchingChild->name = $name;
                $matchingChild->Poids = $apiPoids;
                 $virtualChildrenProducts->push($matchingChild);

                 //dd($matchingChild);

                   
                
            }

            else if ($matchingrelatedProduct){
                $matchingrelatedProduct->min_price = $apiPrice; 
                $matchingrelatedProduct->max_price = $apiPrice;
                $matchingrelatedProduct->Prix_HT = $apiPriceHT;
           
                $matchingrelatedProduct->stock_qty = $apiStock;
            
                $matchingrelatedProduct->Qty_Unit = $apiQTEUNITE;
           
                $matchingrelatedProduct->Unit = $apiunité;
                $matchingrelatedProduct->name = $name;
                $matchingrelatedProduct->Poids = $apiPoids;
                $virtualRelatedProducts->push($matchingrelatedProduct);

            }
        }
        
        $product_page_widgets = [];
        if (getSetting('product_page_widgets') != null) {
            $product_page_widgets = json_decode(getSetting('product_page_widgets'));
        }
        $sortedChildren = $virtualChildrenProducts->sortBy(function ($child) {
            return $child->pivot->child_position;
        });
        $product->thumbnail_image!==null ? $product->thumbnail_image= uploadedAsset($product->thumbnail_image) :"";
            if (!is_null($product->gallery_images)){
                $images=[];
                $gallery = explode(',',$product->gallery_images);
                foreach ($gallery as $img){
                    $images[]= uploadedAsset($img);
                }
                $product->gallery_images=$images;
            }
        $product->meta_img!==null ? $product->meta_img= uploadedAsset($product->meta_img) :"";
        
        foreach($virtualRelatedProducts as $vrproduct){
            
            $vrproduct->thumbnail_image!==null ? $vrproduct->thumbnail_image= uploadedAsset($vrproduct->thumbnail_image) :"";
            
            if (!is_null($vrproduct->gallery_images)&& !is_array($vrproduct->gallery_images)){
                $images=[];
                $gallery = explode(',',$vrproduct->gallery_images);
                
                foreach ($gallery as $img){
                    $images[]= uploadedAsset($img);
                }
                $vrproduct->gallery_images=$images;
            }
            $vrproduct->meta_img!==null ? $vrproduct->meta_img= uploadedAsset($vrproduct->meta_img) :"";
        }
        
        return response()->json([
            'product' => $product, 
            'relatedProducts' => $virtualRelatedProducts, 
            'product_page_widgets' => $product_page_widgets, 
            'childrenProducts' => $sortedChildren, 
            'ingredients' => $ingredients, 
            'valeurNitrition' => $valeurNitrition, 
            'CodesAbares' => $CodesAbares
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function relatedProducts(Request $request)
    {
        $product = Product::where('slug', $request->slug)->first();
        $productCategories              = $product->categories()->pluck('category_id');
        $productIdsWithTheseCategories  = ProductCategory::whereIn('category_id', $productCategories)->where('product_id', '!=', $product->id)->pluck('product_id');

        $relatedProducts                = Product::whereIn('id', $productIdsWithTheseCategories)->get();
        foreach($relatedProducts as $product){
            $product->thumbnail_image!==null ? $product->thumbnail_image= uploadedAsset($product->thumbnail_image) :"";
            if (!is_null($product->gallery_images)){
                $images=[];
                $gallery = explode(',',$product->gallery_images);
                foreach ($gallery as $img){
                    $images[]= uploadedAsset($img);
                }
                $product->gallery_images=$images;
            }
            $product->meta_img!==null ? $product->meta_img= uploadedAsset($product->meta_img) :"";
        }

        return response()->json([
            'products'=>$relatedProducts
        ]);
        //return ProductMiniResource::collection($relatedProducts);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function productPageWidgets(Request $request, $id)
    {
        $product_page_widgets = [];
        if (getSetting('product_page_widgets') != null) {
            $product_page_widgets = json_decode(getSetting('product_page_widgets'));
        }
        return response()->json($product_page_widgets);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bestSelling()
    {
        $bestsellingproducts = collect();
        $best_selling_products = getSetting('best_selling_products') != null ? json_decode(getSetting('best_selling_products')) : [];
        $products = $this->getAllProducts();
        foreach($products as $product)
        {
            foreach($best_selling_products as $best_selling_product)
            {
                if($product->id == $best_selling_product)
                {
                    $bestsellingproducts->push($product);
                }

            }
        }
        return response()->json([
           $bestsellingproducts
        ]);
    }


    public function getAllProducts()
    {
        $virtualProducts = collect(); 
        $searchKey = null;
        $per_page = 24;
        //$sort_by = $request->sort_by ? $request->sort_by : "new";
        $maxRange = Product::max('max_price');
        $min_value = 0;
        $max_value = formatPrice($maxRange, false, false, false, false);
        $apiUrl = env('API_CATEGORIES_URL');
        $user = auth()->user();
        if (!is_null($user) && $user->user_type == 'customer')
        {
        $response = Http::get($apiUrl . 'ListeDePrixWeb/' . Auth::user()->name);
        }else{
        
            $response = Http::get($apiUrl . 'ListeDePrixWeb/');

        }
        $produitsApi = $response->json();
        dd($produitsApi);
        $barcodes = collect($produitsApi)->pluck('codeabarre')->toArray();
        $existingProducts = Product::whereIn('slug', $barcodes)
        ->with('categories')
        ->get()
        ->keyBy('slug');
        foreach ($existingProducts as $existingProduct) {
            // Check if the existing product is not found in the API list
            if (!in_array($existingProduct->slug, $barcodes)) {
            
                $existingProduct->is_published = 0;
                
                $existingProduct->save();
            }
        }

        foreach ($produitsApi as $produitApi) {
            $name = $produitApi['Libellé'];
            $barcode = $produitApi['codeabarre'];
            $apiPrice = $produitApi['PrixVTTC'];
            $apiPriceHT = $produitApi['PrixVenteHT'];
            $apiStock = $produitApi['StockActual'];
            $apiunité = $produitApi['unité_lot'];
            $apiQTEUNITE = $produitApi['QTEUNITE'];
            $apiPoids = $produitApi['Poids'];
            $apiFamille = $produitApi['Famille'];
            $OldPrice = $produitApi['OldPrice'];
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
            $newProduct->max_purchase_qty = 1000;
            $newProduct->is_published = 1;
            $newProduct->afficher = 1;
            $newProduct->Poids = $apiPoids;
            $newProduct->OldPrice = $OldPrice;
            // Set other properties accordingly based on your product model

            $newProduct->save();
            $category = Category::firstOrCreate(
                ['name' => $apiFamille],
                [
                    'parent_id' => 0,

                    'sorting_order_level' => 0,
                    'level' => 0,
                    'is_featured' => 0,
                    'is_top' => 0,
                    'total_sale_count' => 0,
                    'meta_title' => $apiFamille,
                ]
            );
            // Attach the category to the product
            $newProduct->categories()->syncWithoutDetaching([$category->id]);
       
    
            $variation              = new ProductVariation;
            $variation->product_id  = $newProduct->id;
            $variation->price       = $apiPrice;
            $variation->save();
            $product_variation_stock = new ProductVariationStock;
            $product_variation_stock->product_variation_id    = $variation->id;
            $product_variation_stock->location_id             = $location->id;
            $product_variation_stock->stock_qty               = $apiStock;
            $product_variation_stock->save();
            $ProductLocalization = ProductLocalization::firstOrNew(['lang_key' => env('DEFAULT_LANGUAGE'), 'product_id' => $newProduct->id]);
            $ProductLocalization->name = $name;
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
            $apiPoids = $produitApi['Poids'];
            $OldPrice = $produitApi['OldPrice'];
           // dd($OldPrice) ;  
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
            if ($matchingProduct->Poids != $apiPoids) {
                   $matchingProduct->Poids = $apiPoids;
                }
                
                    $matchingProduct->OldPrice = $OldPrice;
              
                $matchingProduct->name = $name;


                $virtualProducts->push($matchingProduct);
              //  dd($virtualProducts);
            } else {
            }
        }
        if (is_null($user)) {
            foreach ($virtualProducts as $product) {
                $product->Prix_HT = 0; // Set price to 0 for unauthenticated users
            }
        }

        
        
       
        $products = $virtualProducts;

        //$products->withPath('/products');
        foreach($products as $product){
            $product->thumbnail_image!==null ? $product->thumbnail_image= uploadedAsset($product->thumbnail_image) :"";
            if (!is_null($product->gallery_images)){
                $images=[];
                $gallery = explode(',',$product->gallery_images);
                foreach ($gallery as $img){
                    $images[]= uploadedAsset($img);
                }
                $product->gallery_images=$images;
            }
            $product->meta_img!==null ? $product->meta_img= uploadedAsset($product->meta_img) :"";
           
        }
        return $products;
    }

    
}
