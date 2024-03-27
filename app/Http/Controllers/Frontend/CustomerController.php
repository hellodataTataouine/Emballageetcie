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

    public function mesProduits()
{
    $user = auth()->user();
    $mesProduits = collect(); 

    // Fetch all products from the API
    $apiUrl = env('API_CATEGORIES_URL');
    $IdClientApi = $user->IdClientApi;

    $response = Http::get($apiUrl . 'GetProduitParIdClient/' . $IdClientApi);
    $produitsApi = $response->json();

    // Filter products where 'MyPhoto' is not empty
    $mesProduits = collect(array_filter($produitsApi, function($produit) {
        return !empty($produit['MyPhoto']);
    }));

    // Pagination
    $perPage = 12;
    $page = request()->get('page', 1);
    $mesProduits = $mesProduits->slice(($page - 1) * $perPage, $perPage);
    $mesProduits = new LengthAwarePaginator($mesProduits, count($mesProduits), $perPage, $page);

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
