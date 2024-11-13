<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Http;

class ToastNotificationController extends Controller{

    public function getApiData()
    {
        $response = Http::get('http://87.106.135.239/hdcomercialeco/LastProduct');
        if($response->successful()){
            $data = $response->json();
            $slug = $data[0]['Codeabarre'];
            $product = Product::where('slug',$slug)->select('name','slug','thumbnail_image','description')->first();
            if (!is_null($product)){
                $product->description = strip_tags($product->description);
                $product->thumbnail_image = uploadedAsset($product->thumbnail_image);
            }
            
           return $product->toJson();
        }
        if ($response->failed()) {
            return response()->json(['error' => 'Request failed'], 500);
        }

        
    }

}