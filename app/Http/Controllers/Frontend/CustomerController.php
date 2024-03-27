<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\MediaManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Str;

use App\Models\OrderItem;
use App\Models\ProductVariation;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;


class CustomerController extends Controller
{

    
    
    # customer extraitDeCompte
    public function extraitDeCompte()
    {

        $user = auth()->user();

        $apiUrl = env('API_CATEGORIES_URL');
        $IdClientApi = $user->IdClientApi;
    
        $response = Http::get($apiUrl . 'ExtraitDeCompte/' . $IdClientApi);
        $extraits = $response->json();
// Calculate total debit and credit amounts
$totalDebit = collect($extraits)->sum('Debit');
$totalCredit = collect($extraits)->sum('Credit');

// Pass data to the view
return getView('pages.users.extraitDeCompte', compact('extraits', 'totalDebit', 'totalCredit'));
}





     

    public function extraitDetail()
    {





        return getView('pages.users.extraitDetail'); 
    }


    # customer dashbaord
    public function index()
    {
        return getView('pages.users.dashboard');
    }

    public function mesProduits(Request $request)
{
    $user = auth()->user();
    $virtualProducts = collect();
    $mesProduits = collect(); 
    $searchKey = null;
    $per_page = 12;
    $sort_by = $request->sort_by ? $request->sort_by : "new";
    $maxRange = Product::max('max_price');
    $min_value = 0;
    $max_value = formatPrice($maxRange, false, false, false, false);
    // Fetch all products from the API
    $apiUrl = env('API_CATEGORIES_URL');
    $IdClientApi = $user->IdClientApi;

    $response = Http::get($apiUrl . 'GetProduitParIdClient/' . $IdClientApi);
    $produitsApi1 = $response->json();
    $produitsApi = collect(array_filter($produitsApi1, function($produit) {
        return !empty($produit['MyPhoto']);
    }));
    $barcodes = collect($produitsApi)->pluck('Codeabarre')->toArray();
    $existingProducts = Product::whereIn('slug', $barcodes)
    ->with('categories')
    ->get()
    ->keyBy('slug');
    foreach ($produitsApi as $produitApi) {
        $name = $produitApi['Libellé'];

        $barcode = $produitApi['Codeabarre'];
        $apiPrice = $produitApi['PrixVTTC'];
        $apiPriceHT = $produitApi['PrixVenteHT'];
        //$apiStock = $produitApi['StockActual'];
       // $apiunité = $produitApi['unité_lot'];
        //$apiQTEUNITE = $produitApi['QTEUNITE'];
        if (isset($existingProducts[$barcode])) {
   
            $matchingProduct = $existingProducts[$barcode];
                
            if ($matchingProduct->min_price != $apiPrice || $matchingProduct->max_price != $apiPrice || $matchingProduct->Prix_HT != $apiPriceHT) {
                $matchingProduct->min_price = $apiPrice; 
                $matchingProduct->max_price = $apiPrice;
                $matchingProduct->Prix_HT = $apiPriceHT;
            }
            
            // if ($matchingProduct->stock_qty != $apiStock) {
            //     $matchingProduct->stock_qty = $apiStock;
            // }
            // if ($matchingProduct->Qty_Unit != $apiQTEUNITE) {
            //     $matchingProduct->Qty_Unit = $apiQTEUNITE;
            // }
            // if ($matchingProduct->Unit != $apiunité) {
            //     $matchingProduct->Unit = $apiunité;
            // }
            $matchingProduct->name = $name;
            $virtualProducts->push($matchingProduct);
        } else {
        }
    }
    // Filter products where 'MyPhoto' is not empty
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
   
        $virtualProducts = $virtualProducts->sortByDesc('total_sale_count'); 

        
   
    // Pagination
    if ($request->per_page != null) {
        $per_page = $request->per_page;
    }

    # sort by
    if ($sort_by == 'new') {
        $virtualProducts = $virtualProducts->sortByDesc('created_at'); 

        
    } else {
        $virtualProducts = $virtualProducts->sortByDesc('total_sale_count'); 

        
    }
    $currentPage = $request->input('page', 1); 
    $perPage = $request->input('per_page', 12); // Updated line



    $slicedProducts = $virtualProducts->slice(($currentPage - 1) * paginationNumber($per_page), paginationNumber($per_page))->values();


    $mesProduits = new LengthAwarePaginator($slicedProducts, count($virtualProducts),paginationNumber($per_page), $currentPage);
//dd($mesProduits);
    return getView('pages.users.mesProduits', compact('mesProduits'));
}



    # customer's order history
    public function orderHistory()
    {
        $orders = auth()->user()->orders()->latest()->paginate(paginationNumber());
        return getView('pages.users.orderHistory', ['orders' => $orders]);
    }

    # customer's address
    public function address()
    {
        $user = auth()->user();
        $addresses = $user->addresses()->latest()->get();
        $countries = Country::isActive()->get();

        return getView('pages.users.address', [
            'addresses' => $addresses,
            'countries' => $countries,
        ]);
    }

    # customer's profile
    public function profile()
    {
        $user = auth()->user();
        return getView('pages.users.profile', ['user' => $user]);
    }

    # update profile
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        if ($request->type == "info") {
            # update info
            $request->validate(
                [
                    'avatar' => 'nullable|max:4000|mimes:jpeg,png,webp,jpg'
                ],
                [
                    'avatar.max' => 'Max file size is 4MB!'
                ]
            );

            if ($request->hasFile('avatar')) {
                $mediaFile = new MediaManager;
                $mediaFile->user_id = auth()->user()->id;
                $mediaFile->media_file = $request->file('avatar')->store('uploads/media');
                $mediaFile->media_size = $request->file('avatar')->getSize();
                $mediaFile->media_name = $request->file('avatar')->getClientOriginalName();
                $mediaFile->media_extension = $request->file('avatar')->getClientOriginalExtension();

                if (getFileType(Str::lower($mediaFile->media_extension)) != null) {
                    $mediaFile->media_type = getFileType(Str::lower($mediaFile->media_extension));
                } else {
                    $mediaFile->media_type = "unknown";
                }
                $mediaFile->save();
                $user->avatar = $mediaFile->id;
            }

            $user->name = $request->name;
            $user->phone = validatePhone($request->phone);
            $user->save();
            flash(localize('Profil mis à jour avec succès'))->success();
            return back();
        }
        else {
            # update password
            $request->validate(
                [
                    'password' => 'required|confirmed|min:6'
                ]
            );
            $user->password = Hash::make($request->password);
            $user->save();
            flash(localize('Mot de passe mis à jour avec succès'))->success();
            return back();
        }
    }
}
