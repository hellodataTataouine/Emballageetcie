<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class ToastNotificationController extends Controller{

    public function getApiData()
    {
        $response = Http::get('http://87.106.135.239/hdcomercialeco/LastProduct');
        
        if ($response->failed()) {
            return response()->json(['error' => 'Request failed'], 500);
        }

        // Process the response
        return $response->json();
    }

}