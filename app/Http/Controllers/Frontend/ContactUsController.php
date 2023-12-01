<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactUsMessage;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    # store contact us form data
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

        $msg = new ContactUsMessage;
        $msg->name          = $request->name;
        $msg->email         = $request->email;
        $msg->phone         = $request->phone;
        $msg->support_for   = $request->support_for;
        $msg->message       = $request->message;
        $msg->save();
        flash(localize('Votre message a été envoyé'))->success();
        return back();
    }
}
