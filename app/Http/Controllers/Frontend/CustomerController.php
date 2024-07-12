<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Location;
use App\Models\Country;
use App\Models\MediaManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Str;
use PDF;

use App\Models\OrderItem;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Config;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{

    



	//Save data for login Popup Off
    public function loginPopupOfff(Request $request){
     
        $PopupOff = $request->input('PopupOff');
        
        session(['loginPopupOff' => $PopupOff]);
          // Log the session data for verification
Log::info('Session loginPopupOff value: ' . session('loginPopupOff'));

return response()->json([
    'success' => true,
    'message' => 'PopupOff value stored in session',
    'sessionValue' => session('loginPopupOff')
]);
    }






    
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





     

    public function extraitDetail($iddoc)
    {


        $apiUrl = env('API_CATEGORIES_URL');
        
    
        $response = Http::get($apiUrl . 'GetLigneDocByIdDoc/' . $iddoc);
        if ($response->successful()) {
            $extraitsDetails = $response->json();
            $totalHT = collect($extraitsDetails)->sum('TotaleHT');
            $totalTVA = collect($extraitsDetails)->sum('totaletva');
            $totalTTC= collect($extraitsDetails)->sum('PRIX_details');
            return getView('pages.users.extraitDetail',compact('extraitsDetails','totalHT','totalTVA','totalTTC', 'iddoc')); 
                       
        } else {
            flash(localize('Veuillez reéssayer '))->error();
           
            return redirect()->back();  
        }


       
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
    $UserEmail = $user->email;

    $response = Http::get($apiUrl . 'GetProductsByMail/' . $UserEmail);
    $produitsApi1 = $response->json();
    $produitsApi = $produitsApi1;
   // $produitsApi = collect(array_filter($produitsApi1, function($produit) {
     //   return !empty($produit['MyPhoto']);
//    }));
    $barcodes = collect($produitsApi)->pluck('Codeabarre')->toArray();
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
    $mesProduits->withPath('/mes-produits');
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



    public function downloadInvoiceFomApi($id)
    {
       // dd($id);
        if (session()->has('locale')) {
            $language_code = session()->get('locale', Config::get('app.locale'));
        } else {
            $language_code = env('DEFAULT_LANGUAGE');
        }
    
        if (session()->has('currency_code')) {
            $currency_code = session()->get('currency_code', Config::get('app.currency_code'));
        } else {
            $currency_code = env('DEFAULT_CURRENCY');
        }
    
        if (Language::where('code', $language_code)->first()->is_rtl == 1) {
            $direction = 'rtl';
            $default_text_align = 'right';
            $reverse_text_align = 'left';
        } else {
            $direction = 'ltr';
            $default_text_align = 'left';
            $reverse_text_align = 'right';
        }
    
        if ($currency_code == 'BDT' || $currency_code == 'bdt' || $language_code == 'bd' || $language_code == 'bn') {
            $font_family = "'Hind Siliguri','sans-serif'";
        } elseif ($currency_code == 'KHR' || $language_code == 'kh') {
            $font_family = "'Khmeros','sans-serif'";
        } elseif ($currency_code == 'AMD') {
            $font_family = "'arnamu','sans-serif'";
        } elseif ($currency_code == 'AED' || $currency_code == 'EGP' || $language_code == 'sa' || $currency_code == 'IQD' || $language_code == 'ir') {
            $font_family = "'XBRiyaz','sans-serif'";
        } else {
            $font_family = "'Roboto','sans-serif'";
        }
    
        $apiUrl = env('API_CATEGORIES_URL');
        $response = Http::get($apiUrl . 'GetLigneDocByIdDoc/' . $id);
    
        if ($response->successful()) {
            $extraitsDetails = $response->json();
            $totalHT = collect($extraitsDetails)->sum('TotaleHT');
            $totalTVA = collect($extraitsDetails)->sum('totaletva');
            $totalTTC = collect($extraitsDetails)->sum('PRIX_details');
            
            $user = auth()->user();
            $IdClientApi = $user->IdClientApi;
            $apiUrl = env('API_CATEGORIES_URL');
            $factureResponse = Http::get($apiUrl . 'ExtraitDeCompte/' . $IdClientApi);
            
    
            if ($factureResponse->successful()) {
                $facture = collect($factureResponse->json())->filter(function ($item) use ($id) {
                    return $item['Iddoc'] == $id;
                })->first(); // Get the first item matching the condition
            } else {
                $facture = null; // or any default value you want
            }
           // $order = Order::findOrFail((int)$id);
//dd($facture);
            return PDF::loadView('frontend.default.pages.users.invoice', [
                'commande' => $facture,
                'facture_detail' => $extraitsDetails,
                'totalHT' => $totalHT,
                'totalTVA' => $totalTVA,
                'totalTTC' => $totalTTC,
                'font_family' => $font_family,
                'direction' => $direction,
                'default_text_align' => $default_text_align,
                'user' => $user,
                'reverse_text_align' => $reverse_text_align
            ])->download(getSetting('order_code_prefix') . $extraitsDetails[0]['IDDocument'] . '.pdf');
        } else {
            return response()->json(['error' => 'Failed to retrieve data from API'], 500);
        }
    }
    


   
    public function downloadSelectedInvoices(Request $request)
    {
        $ids = $request->input('ids'); // Array of selected invoice IDs
    
        // Ensure $ids is an array
        if (!is_array($ids)) {
            return redirect()->back()->with('error', 'Invalid selection.');
        }
    
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No invoices selected.');
        }
    
        $invoices = [];
    
        foreach ($ids as $id) {
            // Fetch invoice details from API
            $apiUrl = env('API_CATEGORIES_URL');
            $response = Http::get($apiUrl . 'GetLigneDocByIdDoc/' . $id);
    
            if ($response->successful()) {
                $invoiceDetails = $response->json();
                $invoices[] = $invoiceDetails; // Store invoice details for PDF generation
            } else {
                // Log or handle the case where fetching data failed for an invoice
                Log::error('Failed to fetch invoice details for ID: ' . $id);
            }
        }
    
        if (empty($invoices)) {
            return redirect()->back()->with('error', 'Failed to retrieve invoice details.');
        }
    
        // Generate PDF files for each invoice
        $pdfs = [];
        foreach ($invoices as $invoice) {
            // Determine font family, text direction, alignment, etc.
            $font_family = "'Roboto', sans-serif"; // Adjust as per your logic
            $direction = 'ltr'; // Assume default direction is left-to-right
            $default_text_align = 'left'; // Assume default text alignment
            $reverse_text_align = 'right'; // Reverse text alignment
    
            // Build data for PDF view
            $pdfData = [
                'invoice' => $invoice,
                'font_family' => $font_family,
                'direction' => $direction,
                'default_text_align' => $default_text_align,
                'reverse_text_align' => $reverse_text_align,
            ];
    
            // Generate PDF instance
            $pdf = PDF::loadView('invoices.invoice_pdf', $pdfData); // Adjust view name as per your structure
    
            // Store PDF instance
            $pdfs[] = $pdf;
        }
    
        // Generate ZIP file containing all PDF invoices
        $zipFileName = 'selected_invoices.zip';
        $zip = new \ZipArchive();
        $zip->open(storage_path('app/'.$zipFileName), \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
    
        foreach ($pdfs as $index => $pdf) {
            $pdfFileName = 'invoice_' . $ids[$index] . '.pdf'; // Example: invoice_3096224743818666.pdf
            $pdf->save(storage_path('app/'.$pdfFileName)); // Save PDF to storage path
            $zip->addFile(storage_path('app/'.$pdfFileName), $pdfFileName); // Add PDF to ZIP archive
        }
    
        $zip->close();
    
        // Download the ZIP file
        return response()->download(storage_path('app/'.$zipFileName))->deleteFileAfterSend(true);
    }



    public function generateSimplePDF()
    {
        return PDF::loadView('testpdf')->download('simple_test.pdf');
    }






    


}
