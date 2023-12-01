<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\SubscribedUser;
use App\Http\Controllers\Controller;

class SubscribersController extends Controller
{
    # store new subscribers
    public function store(Request $request)
    {
        $score = recaptchaValidation($request);  
        $request->request->add([
            'score' => $score
        ]);
        $data['score'] = 'required|numeric|min:0.9';  
        $request->validate($data,[
            'score.min' => localize('Erreur de validation Google reCAPTCHA, il semble que vous ne soyez pas un humain.')
        ]);

        $subscriber = SubscribedUser::where('email', $request->email)->first();
        if($subscriber == null){
            $subscriber = new SubscribedUser;
            $subscriber->email = $request->email;
            $subscriber->save();
            flash(localize('Vous vous êtes abonné avec succès'))->success();
        }
        else{
            flash(localize('Vous êtes déjà abonné'))->error();
        }
        return back();
    }
}
