<?php

namespace App\Http\Controllers\Backend\Products;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Brand;
use App\Models\ProductParents;
use App\Models\Unit;
use App\Models\Tax;
use App\Models\Category;
use App\Models\Location;
use App\Models\Variation;
use App\Models\VariationValue;
use App\Models\Product;

use App\Models\ProductLocalization;
use App\Models\ProductVariation;
use App\Models\ProductVariationStock;
use App\Models\ProductVariationCombination;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;



class ProductsController extends Controller
{
    
    # construct
    public function __construct()
    {
        $this->middleware(['permission:products'])->only('index');
        $this->middleware(['permission:add_products'])->only(['create', 'store']);
        $this->middleware(['permission:edit_products'])->only(['edit', 'update']);
        $this->middleware(['permission:publish_products'])->only(['updatePublishedStatus']);
        $this->middleware(['permission:publish_products'])->only(['updateAfficherStatus']);
    }

    # product list
    public function index(Request $request)
    {
        $virtualProducts = collect(); 
    
        // Fetch all products from the API
        $apiUrl = env('API_CATEGORIES_URL');
        $response = Http::get($apiUrl . 'ListeDePrixWeb/');
        $produitsApi = $response->json();
    
        // Retrieve all existing products and organize them by slug
        $barcodes = collect($produitsApi)->pluck('codeabarre')->toArray();
        
        $notExistingProducts = Product::whereNotIn('slug', $barcodes)
            ->with('categories')
            ->get()
            ->keyBy('slug');

            //dd($notExistingProducts);
    
    
            foreach ($notExistingProducts as $notExistingProduct) {
                
                // Check if the existing product is not found in the API l
                    $notExistingProduct->is_published = 0;
                    $notExistingProduct->afficher = 0;
                    



                    $virtualProducts->push($notExistingProduct);
                    
                    $notExistingProduct->save();
                
            }


            $existingProducts = Product::whereIn('slug', $barcodes)
            ->with('categories')
            ->get()
            ->keyBy('slug');


            foreach ($existingProducts as $existingProduct) {
                // Check if the existing product is not found in the API list
                  
                    $existingProduct->is_published = 1;
                    
                   // $virtualProducts->push($existingProduct);
                    $existingProduct->save();

                
            }

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
        $newProduct->max_purchase_qty = 1000;
        $newProduct->is_published = 1;
        $newProduct->afficher = 1;

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
        
        
       
        
            


        // foreach ($produitsApi as $produitApi) {
        //     $name = $produitApi['Libellé'];
        //     $barcode = $produitApi['codeabarre'];
        //     $apiPrice = $produitApi['PrixVTTC'];
        //     $apiPriceHT = $produitApi['PrixVenteHT'];
        //     $apiStock = $produitApi['StockActual'];
        //     $apiunité = $produitApi['unité_lot'];
        //     $apiQTEUNITE = $produitApi['QTEUNITE'];
        //     if (isset($existingProducts[$barcode])) {
        } else {    
            $matchingProduct = $existingProducts[$barcode];
            if ($matchingProduct->Unit != $name) {
                $matchingProduct->name = $name;
                $matchingProduct->save();
                }
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
                    $matchingProduct->Unit = $apiQTEUNITE;
                }
                
                $virtualProducts->push($matchingProduct);
                
          
               
            }
        }
        
          // Fetch all products from the database
  /*  $dbProducts = Product::with('categories')
    ->when($request->search, function ($query) use ($request) {
        $query->where('slug', 'like', '%' . $request->search . '%');
    })
    ->when($request->is_published, function ($query) use ($request) {
        $query->where('is_published', $request->is_published);
    })
    ->get();

$virtualProducts = $virtualProducts->merge($dbProducts)->unique('slug');*/


       
      

        if ($request->search != null) {
            $searchTerm = $request->search;
            
            // Split the search term into words
            $keywords = explode(' ', $searchTerm);
        
            $filteredProducts = $virtualProducts->filter(function ($product) use ($keywords) {
                // Check if all keywords are present in the product name or other attributes
                return collect($keywords)->every(function ($keyword) use ($product) {
                    return (
                        stripos($product->name, $keyword) !== false ||
                        stripos($product->slug, $keyword) !== false
                        
                    );
                });
            });
        
            // Reassign the filtered products to $virtualProducts
            $virtualProducts = $filteredProducts->values();
            $searchKey = $searchTerm;
        }

        if ($request->is_published != null) {
            $virtualProducts = $virtualProducts->where('is_published', $request->is_published);
            $is_published    = $request->is_published;
        }

          // Fetch all products from the database
         /* $dbProducts = Product::with('categories')
          ->when($request->search, function ($query) use ($request) {
              $query->where('slug', 'like', '%' . $request->search . '%');
          })
          ->when($request->is_published, function ($query) use ($request) {
              $query->where('is_published', $request->is_published);
          })
          ->get();
      
      $virtualProducts = $virtualProducts->merge($dbProducts)->unique('slug');*/

            // Paginate the combined products
        $page = $request->input('page', 1);
        $perPage = 15;
        $slicedProducts = $virtualProducts->slice(($page - 1) * $perPage, paginationNumber())->values();
        $paginatedProducts = new LengthAwarePaginator($slicedProducts, $virtualProducts->count(), $perPage, $page);
        $paginatedProducts->withPath('/admin/products'); 


        $brands = Brand::latest()->get();
    
        $searchKey = null;
        $brand_id = null;
        $is_published = null;

        




        
    
        return view('backend.pages.products.products.index', compact('paginatedProducts', 'brands', 'searchKey', 'brand_id', 'is_published', ));
    }
    
    # return view of create form
    public function create()
    {
        $categories = Category::where('parent_id', 0)
            ->orderBy('sorting_order_level', 'desc')
            ->with('childrenCategories')
            ->get();
        $brands = Brand::isActive()->get();
        $units = Unit::isActive()->get();
        $variations = Variation::isActive()->whereNotIn('id', [1, 2])->get();
        $taxes = Tax::isActive()->get();
        $tags = Tag::all();
        return view('backend.pages.products.products.create', compact('categories', 'brands', 'units', 'variations', 'taxes', 'tags'));  
    }

    # get variation values to add new product
    public function getVariationValues(Request $request)
    {
        $variation_id = $request->variation_id;
        $variation_values = VariationValue::isActive()->where('variation_id', $variation_id)->get();

        return view('backend.pages.products.products.new_variation_values', compact('variation_values', 'variation_id'));
    }

    # new chosen variation
    public function getNewVariation(Request $request)
    {
        $variations = Variation::query();
        if ($request->has('chosen_variations')) {
            $variations = $variations->whereNotIn('id', $request->chosen_variations)->get();
        } else {
            $variations = $variations->get();
        }
        if (count($variations) > 0) {
            return array(
                'count' => count($variations),
                'view' => view('backend.pages.products.products.new_variation', compact('variations'))->render(),
            );
        } else {
            return false;
        }
    }

    # generate variation combinations
    public function generateVariationCombinations(Request $request)
    {
        $variations_and_values = array();

        if ($request->has('chosen_variations')) {
            $chosen_variations = $request->chosen_variations;
            sort($chosen_variations, SORT_NUMERIC);

            foreach ($chosen_variations as $key => $option) {

                $option_name = 'option_' . $option . '_choices'; # $option = variation_id 
                $value_ids = array(); 

                if ($request->has($option_name)) {

                    $variation_option_values = $request[$option_name];
                    sort($variation_option_values, SORT_NUMERIC);

                    foreach ($variation_option_values as $item) {
                        array_push($value_ids, $item);
                    }
                    $variations_and_values[$option] =  $value_ids;
                }
            }
        }

        $combinations = array(array());
        foreach ($variations_and_values as $variation => $variation_values) {
            $tempArray = array();
            foreach ($combinations as $combination_item) {
                foreach ($variation_values as $variation_value) {
                    $tempArray[] = $combination_item + array($variation => $variation_value);
                }
            }
            $combinations = $tempArray;
        }
        return view('backend.pages.products.products.new_variation_combinations', compact('combinations'))->render();
    }

    # add new data
    public function store(Request $request)
    {
        if ($request->has('is_variant') && !$request->has('variations')) {
            flash(localize('Invalid product variations, please check again'))->error();
            return redirect()->back();
        }

        $product                    = new Product;
        $product->shop_id           = auth()->user()->shop_id;
        $product->name              = $request->name;
        $product->slug              = Str::slug($request->name, '-') . '-' . strtolower(Str::random(5));
        $product->brand_id          = $request->brand_id;
        $product->unit_id           = $request->unit_id;
        $product->sell_target       = $request->sell_target;

        $product->thumbnail_image   = $request->image;
        $product->gallery_images    = $request->images;
        $product->size_guide        = $request->size_guide;

        $product->description       = $request->description;
        $product->short_description = $request->short_description;

        # min-max price
        if ($request->has('is_variant') && $request->has('variations')) {
            $product->min_price =  priceToUsd(min(array_column($request->variations, 'price')));
            $product->max_price =  priceToUsd(max(array_column($request->variations, 'price')));
        } else {
            $product->min_price =  priceToUsd($request->price);
            $product->max_price =  priceToUsd($request->price);
        }

        # discount
        $product->discount_value    = $request->discount_value ?? 0;
        $product->discount_type     = $request->discount_type;


        if ($request->date_range != null) {
            if (Str::contains($request->date_range, 'to')) {
                $date_var = explode(" to ", $request->date_range);
            } else {
                $date_var = [date("d-m-Y"), date("d-m-Y")];
            }
            $product->discount_start_date = strtotime($date_var[0]);
            $product->discount_end_date   = strtotime($date_var[1]);
        }

        # stock qty based on all variations / no variation 
        $product->stock_qty   = ($request->has('is_variant') && $request->has('variations')) ? max(array_column($request->variations, 'stock')) : $request->stock;

        $product->is_published         = $request->is_published;
        $product->has_variation        = ($request->has('is_variant') && $request->has('variations')) ? 1 : 0;

        # shipping info
        $product->standard_delivery_hours    = $request->standard_delivery_hours;
        $product->express_delivery_hours     = $request->express_delivery_hours;
        $product->min_purchase_qty     = $request->min_purchase_qty;
        $product->max_purchase_qty     = $request->max_purchase_qty;


        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->meta_img = $request->meta_image;

        $product->save();
        # Product Localization
        $ProductLocalization = ProductLocalization::firstOrNew(['lang_key' => env('DEFAULT_LANGUAGE'), 'product_id' => $product->id]);
        $ProductLocalization->name = $request->name;
        $ProductLocalization->description = $request->description;
        $ProductLocalization->save();

        # tags
        $product->tags()->sync($request->tag_ids);

        # category
        $product->categories()->sync($request->category_ids);

        # taxes
        $tax_data = array();
        $tax_ids = array();
        if ($request->has('taxes')) {
            foreach ($request->taxes as $key => $tax) {
                array_push($tax_data, [
                    'tax_value' => $tax,
                    'tax_type' => $request->tax_types[$key]
                ]);
            }
            $tax_ids = $request->tax_ids;
        }
        $taxes = array_combine($tax_ids, $tax_data);
        $product->product_taxes()->sync($taxes);

        $location = Location::where('is_default', 1)->first();

        if ($request->has('is_variant') && $request->has('variations')) {
            foreach ($request->variations as $variation) {
                $product_variation              = new ProductVariation;
                $product_variation->product_id  = $product->id;
                $product_variation->variation_key        = $variation['variation_key'];
                $product_variation->price       = priceToUsd($variation['price']);
                $product_variation->sku         = $variation['sku'];
                $product_variation->code         = $variation['code'];
                $product_variation->save();

                $product_variation_stock                              = new ProductVariationStock;
                $product_variation_stock->product_variation_id        = $product_variation->id;
                $product_variation_stock->location_id                 = $location->id;
                $product_variation_stock->stock_qty                   = $variation['stock'];
                $product_variation_stock->save();

                foreach (array_filter(explode("/", $variation['variation_key'])) as $combination) {
                    $product_variation_combination                         = new ProductVariationCombination;
                    $product_variation_combination->product_id             = $product->id;
                    $product_variation_combination->product_variation_id   = $product_variation->id;
                    $product_variation_combination->variation_id           = explode(":", $combination)[0];
                    $product_variation_combination->variation_value_id     = explode(":", $combination)[1];
                    $product_variation_combination->save();
                }
            }
        } else {
            $variation              = new ProductVariation;
            $variation->product_id  = $product->id;
            $variation->sku         = $request->sku;
            $variation->code         = $request->code;
            $variation->price       = priceToUsd($request->price);
            $variation->save();
            $product_variation_stock                          = new ProductVariationStock;
            $product_variation_stock->product_variation_id    = $variation->id;
            $product_variation_stock->location_id             = $location->id;
            $product_variation_stock->stock_qty               = $request->stock;
            $product_variation_stock->save();
        }

        flash(localize('Product has been inserted successfully'))->success();
        return redirect()->route('admin.products.index');
    }

    # return view of edit form
    public function edit(Request $request, $id)
    {
        $location = Location::where('is_default', 1)->first();
        $request->session()->put('stock_location_id',  $location->id);

       $lang_key = $request->lang_key;
       /* $language = Language::where('is_active', 1)->where('code', $lang_key)->first();
        if (!$language) {
            flash(localize('Language you are trying to translate is not available or not active'))->error();
            return redirect()->route('admin.products.index');
        }*/
        //dd($id);
        $product = Product::findOrFail($id);
        $products = Product::all();

       $currentIsParent = $product->parents()->count()>0;
        $currentChildren = ProductParents::select('product_parent.child_position', 'product_parent.product_id', 'product_parent.child_id')
        ->join('products', 'product_parent.product_id', '=', 'products.id')
        ->where('product_parent.product_id', $id)
        ->get();

//dd($currentChildren);
        $currentFicheTechnique = $product->fiche_technique;


        
        //dd($product);
        $categories = Category::where('parent_id', 0)
            ->orderBy('sorting_order_level', 'desc')
            ->with('childrenCategories')
            ->get();
        $brands = Brand::isActive()->get();
        $units = Unit::isActive()->get();
        $variations = Variation::isActive()->whereNotIn('id', [1, 2])->get();
        $taxes = Tax::isActive()->get();
        $tags = Tag::all();

        $temporaryOrder = $currentChildren->pluck('child_position', 'child_id')->toArray();
//dd($temporaryOrder);
        return view('backend.pages.products.products.edit', compact('product', 'products', 'categories', 'brands', 'units', 'variations', 'taxes', 'tags', 'lang_key', 'currentIsParent', 'currentChildren', 'temporaryOrder', 'currentFicheTechnique'));
    }

    

    # update product
    public function update(Request $request)
    {
        if ($request->has('is_variant') && !$request->has('variations')) {
            flash(localize('Invalid product variations, please check again'))->error();
            return redirect()->back();
        }

     //   $product                    = Product::where('id', $request->id)->first();

         $product = Product::findOrFail($request->id);
//dd($request->id);
        $oldProduct= clone $product;

        if ($request->lang_key == env("DEFAULT_LANGUAGE")) {
           // $product->name              = $request->name;
            //$product->slug              = (!is_null($request->slug)) ? Str::slug($request->slug, '-') : Str::slug($request->name, '-') . '-' . strtolower(Str::random(5));
            $product->description       = $request->description;
            $product->sell_target       = $request->sell_target;
            $product->brand_id          = $request->brand_id;
            $product->unit_id           = $request->unit_id;
            $product->short_description = $request->short_description;
           // $product->parent_id = $request->parent_id;
            //$product->is_parent = $request->is_parent;
            $product->total_volume = $request->total_volume;
            $product->dimensions = $request->dimensions;
            $product->color = $request->color;


            $product->thumbnail_image   = $request->image;
            $product->gallery_images   = $request->images;
            //$product->fiche_technique   = $request->fiche_technique;
            if ($request->hasFile('fiche_technique')) {
                $file = $request->file('fiche_technique');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                
                // Define the storage path
                $storagePath = 'storage/fiche_technique';
            
                // Store the file in the specified location
                $path = $file->storeAs($storagePath, $filename);
            // Remove the 'storage' part from the path
                $trimmedPath = str_replace('storage/', '', $path);

                // Assign the file path to the product and save
                $product->fiche_technique = $trimmedPath;
            } elseif ($request->has('remove_fiche_technique') && $request->remove_fiche_technique) {
                // User has requested to remove the Fiche Technique
                $product->fiche_technique = null;
            }
        
          $product->save(); 
                        
            //dd($product->fiche_technique); 
         
            $product->size_guide        = $request->size_guide;

            # min-max price
            if ($request->has('is_variant') && $request->has('variations')) {
                $product->min_price =  priceToUsd(min(array_column($request->variations, 'price')));
                $product->max_price =  priceToUsd(max(array_column($request->variations, 'price')));
            } else {
               // $product->min_price =  priceToUsd($request->price);
              //  $product->max_price =  priceToUsd($request->price);
            }

            # discount
            $product->discount_value    = $request->discount_value;
            $product->discount_type     = $request->discount_type;
            if ($request->date_range != null) {

                if (Str::contains($request->date_range, 'to')) {
                    $date_var = explode(" to ", $request->date_range);
                } else {
                    $date_var = [date("d-m-Y"), date("d-m-Y")];
                }

                $product->discount_start_date = strtotime($date_var[0]);
                $product->discount_end_date   = strtotime($date_var[1]);
            }

            # stock qty based on all variations / no variation 
            //$product->stock_qty   = ($request->has('is_variant') && $request->has('variations')) ? max(array_column($request->variations, 'stock')) : $request->stock;

            //$product->is_published         = $request->is_published;
             $product->afficher         = $request->afficher;
            $product->has_variation        = ($request->has('is_variant') && $request->has('variations')) ? 1 : 0;

            # shipping info
            $product->standard_delivery_hours    = $request->standard_delivery_hours;
            $product->express_delivery_hours     = $request->express_delivery_hours;
            $product->min_purchase_qty     = $request->min_purchase_qty;
            $product->max_purchase_qty     = $request->max_purchase_qty;

            $product->meta_title = $request->meta_title;
            $product->meta_description = $request->meta_description;
            $product->meta_img = $request->meta_image;

            $product->save();


            if ($request->has('child_product_ids')) {
                $childProductIds = $request->child_product_ids;
                $temporaryOrder = json_decode($request->temporary_order, true);
                //dd($temporaryOrder);
                $removedChildIds = collect($oldProduct->children->pluck('id'))->diff($childProductIds)->filter(); // Filter out null values
            
                // Remove the parent_id association for removed child products
                if ($removedChildIds->isNotEmpty()) {
                    ProductParents::where('product_id', $product->id)
                        ->whereIn('child_id', $removedChildIds)
                        ->delete();
                }
            
                foreach ($childProductIds as $index => $childProductId) {
                    $childProduct = Product::findOrFail($childProductId);
                    $childProduct->is_child = 1;
                    $childProduct->save();
                }
            } else {
                ProductParents::where('product_id', $product->id)
                
                ->delete();
                //  
            }        

          /* if ($request->is_parent == 0) {
                // Set the parent_id of associated child products to null
                ProductParents::where('product_id', $product->id)
                
                ->delete();
            }

           /* if ($request->has('child_product_ids')) {
                foreach ($request->child_product_ids as $childProductId) {
                    $childProduct = Product::findOrFail($childProductId);
                    $childProduct->parent_id = $request->id; 
                    $childProduct->save();
                }
            }*/
        
            # tags
            $product->tags()->sync($request->tag_ids);

            # category
            $product->categories()->sync($request->category_ids);
            $product->parents()->sync($request->child_product_ids);
$childs= ProductParents::where('product_id', $request->id)->get();
//dd($childs);
if($childs != null){
      foreach($childs as $child) {
        //dd($child->child_id, $temporaryOrder);
        
        $child->child_position = $temporaryOrder[$child->child_id];
        $child->save();
        //dd($temporaryOrder);

      }    

    }
            # taxes
            $tax_data = array();
            $tax_ids = array();
            if ($request->has('taxes')) {
                foreach ($request->taxes as $key => $tax) {
                    array_push($tax_data, [
                        'tax_value' => $tax,
                        'tax_type' => $request->tax_types[$key]
                    ]);
                }
                $tax_ids = $request->tax_ids;
            }
            $taxes = array_combine($tax_ids, $tax_data);
            $product->product_taxes()->sync($taxes);


            $location = Location::where('is_default', 1)->first();

            if ($request->has('is_variant') && $request->has('variations')) {

                $new_requested_variations = collect($request->variations);
                $new_requested_variations_key = $new_requested_variations->pluck('variation_key')->toArray();
                $old_variations_keys = $product->variations->pluck('variation_key')->toArray();
                $old_matched_variations = $new_requested_variations->whereIn('variation_key', $old_variations_keys);
                $new_variations = $new_requested_variations->whereNotIn('variation_key', $old_variations_keys);

                # delete old variations that isn't requested
                $product->variations->whereNotIn('variation_key', $new_requested_variations_key)->each(function ($variation) use ($location) {
                    foreach ($variation->combinations as $comb) {
                        $comb->delete();
                    }
                    $variation->product_variation_stock_without_location()->where('location_id', $location->id)->delete();
                    $variation->delete();
                });

                # update old matched variations
                foreach ($old_matched_variations as $variation) {
                    $p_variation              = ProductVariation::where('product_id', $product->id)->where('variation_key', $variation['variation_key'])->first();
                    $p_variation->price       = priceToUsd($variation['price']);
                    $p_variation->sku         = $variation['sku'];
                    $p_variation->code         = $variation['code'];
                    $p_variation->save();

                    # update stock of this variation
                    $productVariationStock = $p_variation->product_variation_stock_without_location()->where('location_id', $location->id)->first();
                    if (is_null($productVariationStock)) {
                        $productVariationStock = new ProductVariationStock;
                        $productVariationStock->product_variation_id    = $p_variation->id;
                    }
                    $productVariationStock->stock_qty = $variation['stock'];
                    $productVariationStock->location_id = $location->id;
                    $productVariationStock->save();
                }

                # store new requested variations
                foreach ($new_variations as $variation) {
                    $product_variation                      = new ProductVariation;
                    $product_variation->product_id          = $product->id;
                    $product_variation->variation_key       = $variation['variation_key'];
                    $product_variation->price               = priceToUsd($variation['price']);
                    $product_variation->sku                 = $variation['sku'];
                    $product_variation->code                 = $variation['code'];
                    $product_variation->save();

                    $product_variation_stock                              = new ProductVariationStock;
                    $product_variation_stock->product_variation_id        = $product_variation->id;
                    $product_variation_stock->stock_qty                   = $variation['stock'];
                    $product_variation_stock->save();

                    foreach (array_filter(explode("/", $variation['variation_key'])) as $combination) {
                        $product_variation_combination                         = new ProductVariationCombination;
                        $product_variation_combination->product_id             = $product->id;
                        $product_variation_combination->product_variation_id   = $product_variation->id;
                        $product_variation_combination->variation_id           = explode(":", $combination)[0];
                        $product_variation_combination->variation_value_id     = explode(":", $combination)[1];
                        $product_variation_combination->save();
                    }
                }
            } else {
                # check if old product is variant then delete all old variation & combinations
                if ($oldProduct->is_variant) {
                    foreach ($product->variations as $variation) {
                        foreach ($variation->combinations as $comb) {
                            $comb->delete();
                        }
                        $variation->delete();
                    }
                }

                $variation                       = $product->variations->first();
                $variation->product_id           = $product->id;
                $variation->variation_key        = null;
                $variation->sku                  = $request->sku;
                $variation->code                  = $request->code;
                $variation->price                = priceToUsd($request->price);
                $variation->save();


                if ($variation->product_variation_stock) {
                    $productVariationStock = $variation->product_variation_stock_without_location()->where('location_id', $location->id)->first();

                    if (is_null($productVariationStock)) {
                        $productVariationStock = new ProductVariationStock;
                    }

                    $productVariationStock->product_variation_id    = $variation->id;
                   // $productVariationStock->stock_qty               = $request->stock;
                    $productVariationStock->location_id = $location->id;
                    $productVariationStock->save();
                } else {
                    $product_variation_stock                          = new ProductVariationStock;
                    $product_variation_stock->product_variation_id    = $variation->id;
                    //$product_variation_stock->stock_qty               = $request->stock;
                    $product_variation_stock->save();
                }
            }
        }
       
        # Product Localization
        $ProductLocalization = ProductLocalization::firstOrNew(['lang_key' => $request->lang_key, 'product_id' => $product->id]);
        $ProductLocalization->name = $request->name;
        $ProductLocalization->description = $request->description;
        $ProductLocalization->short_description = $request->short_description;
        $ProductLocalization->save();

        flash(localize('Le produit a été mis à jour avec succès'))->success();
        return back();
    }

    # update status
    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->is_featured = $request->status;
        if ($product->save()) {
            return 1;
        }
        return 0;
    }

    # update published
    public function updatePublishedStatus(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->is_published = $request->status;
        if ($product->save()) {
            return 1;
        }
        return 0;
    }

     # update afficher
     public function updateAfficherStatus(Request $request)
     {
         $product = Product::findOrFail($request->id);
         $product->afficher = $request->status;
         if ($product->save()) {
             return 1;
         }
         return 0;
     }

//    # delete product
// public function delete(Request $request)
// {
//     $product = Product::findOrFail($request->id);

//     $product->delete();
//     //dd($product);

//     flash(localize('Le produit a été supprimé avec succès'))->success(); 
//     return back();
// }



# Delete product
public function delete(Request $request)
{
    $product = Product::findOrFail($request->id);

    $product->children()->delete();

    // Delete product variations and associated data
    if ($product->is_variant) {
        foreach ($product->variations as $variation) {
            foreach ($variation->combinations as $combination) {
                $combination->delete();
            }
            $variation->delete();
        }
    }

    $product->product_taxes()->detach();

    $product->categories()->detach();
    $product->tags()->detach();

    
    // Delete product images and files
    if ($product->thumbnail_image) {
        Storage::delete('storage/' . $product->thumbnail_image);
    }

    if ($product->gallery_images) {
        $galleryImages = json_decode($product->gallery_images, true);
    
        if (is_array($galleryImages)) {
            foreach ($galleryImages as $image) {
                Storage::delete('storage/' . $image);
            }
        }
    }
    
    if ($product->fiche_technique) {
        Storage::delete('storage/' . $product->fiche_technique);
    }

    $product->delete();

    flash(localize('Le produit a été supprimé avec succès'))->success();
    return back();
}


}




// public function SynchronizeProducts(Request $request)
// {
//     $virtualProducts = collect(); 
    
//     // Fetch all products from the API
//     $apiUrl = env('API_CATEGORIES_URL');
//     $response = Http::get($apiUrl . 'ListeDePrixWeb/');
//     $produitsApi = $response->json();

//     // Retrieve all existing products and organize them by slug
//     $barcodes = collect($produitsApi)->pluck('codeabarre')->toArray();
//     $existingProducts = Product::whereIn('slug', $barcodes)
//         ->with('categories')
//         ->get()
//         ->keyBy('slug');

//     $notExistingProducts = Product::whereNotIn('slug', $barcodes)
//         ->with('categories')
//         ->get()
//         ->keyBy('slug');


//         foreach ($notExistingProducts as $notExistingProduct) {
            
//             // Check if the existing product is not found in the API l
//                 $notExistingProduct->is_published = 0;
//                 $virtualProducts->push($notExistingProduct);
                
//                 $notExistingProduct->save();
            
//         }

//              // Loop through each product from the API
//         foreach ($produitsApi as $produitApi) {
//             $name = $produitApi['Libellé'];
//             $barcode = $produitApi['codeabarre'];
//             $apiPrice = $produitApi['PrixVTTC'];
//             $apiPriceHT = $produitApi['PrixVenteHT'];
//             $apiStock = $produitApi['StockActual'];
//             $apiunité = $produitApi['unité_lot'];
//         $apiQTEUNITE = $produitApi['QTEUNITE'];
    
//       // Find products with matching barcode
//       if (!(isset($existingProducts[$barcode]))) {
     
//     // Update prices for matching products
//     $location = Location::where('is_default', 1)->first();
//     $newProduct = new Product();
//     $newProduct->name = $name;
//     $newProduct->slug = $barcode; 
//     $newProduct->min_price = $apiPrice;
//     $newProduct->max_price = $apiPrice;
//     $newProduct->Prix_HT = $apiPrice;
//     $newProduct->stock_qty = $apiStock;
//     $newProduct->has_variation = 0;
//     $newProduct->Qty_Unit = $apiQTEUNITE;
// $newProduct->Unit = $apiunité;
// $newProduct->max_purchase_qty = 1000;
// $newProduct->is_published = 1;
//     // Set other properties accordingly based on your product model
    
//     $newProduct->save();
    
//     $variation              = new ProductVariation;
//     $variation->product_id  = $newProduct->id;
//    // $variation->sku         = $request->sku;
//    // $variation->code         = $request->code;
//     $variation->price       = $apiPrice;
//     $variation->save();
//     $product_variation_stock = new ProductVariationStock;
//     $product_variation_stock->product_variation_id    = $variation->id;
//     $product_variation_stock->location_id             = $location->id;
//     $product_variation_stock->stock_qty               = $apiStock;
//     $product_variation_stock->save();
//     $ProductLocalization = ProductLocalization::firstOrNew(['lang_key' => env('DEFAULT_LANGUAGE'), 'product_id' => $newProduct->id]);
//     $ProductLocalization->name = $name;
//     //$ProductLocalization->description = $request->description;
//     $ProductLocalization->save();
    
    
//     }
    
//         }
    
//         // Retrieve all existing products and organize them by slug
//         foreach ($produitsApi as $produitApi) {
//             $name = $produitApi['Libellé'];
//             $barcode = $produitApi['codeabarre'];
//             $apiPrice = $produitApi['PrixVTTC'];
//             $apiPriceHT = $produitApi['PrixVenteHT'];
//             $apiStock = $produitApi['StockActual'];
            
//             // Check if the API product exists in the existing products
//             if (isset($existingProducts[$barcode])) {
//                 $matchingProduct = $existingProducts[$barcode];
//                 if ($matchingProduct->stock_qty != $apiStock) {
//                     $matchingProduct->stock_qty = $apiStock;
//                 }
                
//                 if ($matchingProduct->min_price != $apiPrice || $matchingProduct->max_price != $apiPrice || $matchingProduct->Prix_HT != $apiPriceHT) {
//                     $matchingProduct->min_price = $apiPrice; 
//                     $matchingProduct->max_price = $apiPrice;
//                     $matchingProduct->Prix_HT = $apiPriceHT;
//                 }
                
//                 if ($matchingProduct->stock_qty != $apiStock) {
//                     $matchingProduct->stock_qty = $apiStock;
//                 }
//                 if ($matchingProduct->Qty_Unit != $apiQTEUNITE) {
//                     $matchingProduct->Qty_Unit = $apiQTEUNITE;
//                 }
//                 if ($matchingProduct->Unit != $apiunité) {
//                     $matchingProduct->Unit = $apiQTEUNITE;
//                 }
//                 $matchingProduct->name = $name;
//                 $matchingProduct->is_published = 1;
//                 $matchingProduct->save();
//                 $virtualProducts->push($matchingProduct);
//             } else {
//                 // set exisiting product is_published to 0 if not found in the API list
//                 $existingProduct = Product::where('slug', $barcode)->first();
//                 $existingProduct->is_published = 0;
//                 $virtualProducts->push($existingProduct);
//                 $existingProduct->save();
               
//             }
//         }
    

       

        

        

//         if ($request->brand_id != null) {
//             $virtualProducts = $virtualProducts->where('brand_id', $request->brand_id);
//             $brand_id    = $request->brand_id;
//         }

//         if ($request->is_published != null) {
//             $virtualProducts = $virtualProducts->where('is_published', $request->is_published);
//             $is_published    = $request->is_published;
//         }
//         $dbProducts = Product::with('categories')
//         ->when($request->search, function ($query) use ($request) {
//             $query->where('slug', 'like', '%' . $request->search . '%');
//         })
//         ->when($request->is_published, function ($query) use ($request) {
//             $query->where('is_published', $request->is_published);
//         })
//         ->get();
    
//     $virtualProducts = $virtualProducts->merge($dbProducts)->unique('slug');

    




//        // Paginate the combined products
//         $page = $request->input('page', 1);
//         $perPage = 15;
//         $slicedProducts = $virtualProducts->slice(($page - 1) * $perPage, paginationNumber())->values();
//         $paginatedProducts = new LengthAwarePaginator($slicedProducts, $virtualProducts->count(), $perPage, $page);
//         $paginatedProducts->withPath('/admin/products'); 



//      $brands = Brand::latest()->get();
    
//         $searchKey = null;
//         $brand_id = null;
//         $is_published = null;
    
//         return view('backend.pages.products.products.index', compact('paginatedProducts', 'brands', 'searchKey', 'brand_id', 'is_published'));
       
// }




