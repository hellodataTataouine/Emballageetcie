<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Services\SmsServices;
use App\Models\Cart;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    # registration form validation
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }
    



        /**
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function verifyClient(Request $request, $CODETIERS)
{
    $apiEndpoint = "http://51.83.131.79/hdcomercialeco/Client/CodeTiers/{$CODETIERS}";

    try {
        $response = Http::get($apiEndpoint);

        if ($response->successful()) {
            $clientData = $response->json();
            $data = [
                'exists' => true,
                'data' => $clientData,
            ];

            // Check if CodeTiers is present in the API response
            if (isset($clientData['CODETIERS'])) {
                $data['codetiers'] = $clientData['CODETIERS'];
            }
            if (isset($clientData['IDClient'])) {
                $data['IDClient'] = $clientData['IDClient'];
            }

            // Check if CodePostal is present in the API response
            if (isset($clientData['CodePostal'])) {
                $data['postal_code'] = $clientData['CodePostal'];
            }

            return response()->json($data);
        } else {
            return response()->json(['exists' => false, 'error' => $response->status()]);
        }
    } catch (\Exception $e) {
        return response()->json(['exists' => false, 'error' => $e->getMessage()]);
    }
}


   # make new registration here
   protected function create(array $data)
   {
       if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
           $user = User::create([
               'name' => $data['name'],
               'email' => $data['email'],
               'phone' => validatePhone($data['phone']),
               'password' => Hash::make($data['password']),
               'codetiers' => $data['codetiers'],
               'postal_code' => $data['postal_code'],
               'IdClientApi' => $data['IDClient'],
           ]);
   
           // set guest_user_id to user_id from carts 
           if (isset($_COOKIE['guest_user_id'])) {
               $carts = Cart::where('guest_user_id', (int)$_COOKIE['guest_user_id'])->get();
               $userId = $user->id;
               if ($carts) {
                   foreach ($carts as $cart) {
                       $existInUserCart = Cart::where('user_id', $userId)->where('product_variation_id', $cart->product_variation_id)->first();
                       if (!is_null($existInUserCart)) {
                           $existInUserCart->qty += $cart->qty;
                           $existInUserCart->save();
                           $cart->delete();
                       } else {
                           $cart->user_id = $userId;
                           $cart->guest_user_id = null;
                           $cart->save();
                       }
                   }
               }
           }
   
           // Check if postal_code exists in the API response
           if (isset($data['postal_code'])) {
               $user->postal_code = $data['postal_code'];
               $user->save();
           }
   
           // Check if address exists in the API response
           if (isset($data['address'])) {
               // Store address in user_addresses table
               $this->storeUserAddress($user, $data['address']);
           }
   
           return $user;
       }
   
       return null;
   }
   

   // New method to store address in user_addresses table
   protected function storeUserAddress(User $user, $address)
   {
       $addressModel = new UserAddress;
       $addressModel->user_id = $user->id;
       $addressModel->country_id = 0; 
       $addressModel->state_id = 0;   
       $addressModel->city_id = 0;    
       $addressModel->is_default = 1;
       $addressModel->address = $address;
       $addressModel->save();
   }
    # register new customer here
    public function register(Request $request)
    {

        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            if (User::where('email', $request->email)->first() != null) {
                flash(localize('L\'adresse e-mail ou le numéro de téléphone existe déjà.'))->error();
                return back()->withInput();
            }
        }

        if ($request->phone != null) {
            if (User::where('phone', $request->phone)->first() != null) {
                flash(localize('Un utilisateur existe déjà avec ce numéro de téléphone.'))->error();
                return back()->withInput();
            }
        }
 
        $score = recaptchaValidation($request);  
        $request->request->add([
            'score' => $score
        ]);
        $data['score'] = 'required|numeric|min:0.9'; 
         
        $request->validate($data,[
            'score.min' => localize('Erreur de validation de Google reCAPTCHA, il semble que vous ne soyez pas un humain.')
        ]);

        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                flash(localize($error))->error();
            }
            return back()->withInput();
        }

        $user = $this->create($request->all());

        if ($user) {
            $this->guard()->login($user);
        }

        # verification
        if (getSetting('registration_verification_with') == "disable") {
            $user->email_or_otp_verified = 1;
            $user->email_verified_at = Carbon::now();
            $user->save();
            flash(localize('Inscription effectuée avec succès.'))->success();
        } else {
            if (getSetting('registration_verification_with') == 'email') {
                try {
                    $user->sendVerificationNotification();
                    flash(localize('Inscription effectuée avec succès. Veuillez vérifier votre adresse e-mail'))->success();
                } catch (\Throwable $th) {
                    $user->delete();
                    flash(localize('"Inscription échouée. Veuillez réessayer ultérieurement.'))->error();
                }
            }
            // else being handled in verification controller
        }


        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    # action after registration
    protected function registered(Request $request, $user)
    {
        if ($user->email_or_otp_verified == 0) {
            if (getSetting('registration_verification_with') == 'email') {
                return redirect()->route('verification.notice');
            } else {
                return redirect()->route('verification.phone');
            }
        } elseif (session('link') != null) {
            return redirect(session('link'));
        } else {
            return redirect()->route('customers.dashboard');
        }
    }
}
