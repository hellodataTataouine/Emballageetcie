<?php

namespace App\Http\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class SmsServices
{
    # send sms
    public function sendSMS($to, $text, $from = null)
    {
        if (getSetting('active_sms_gateway') == 'twilio') {

            $TWILIO_SID = env('TWILIO_SID');
            $TWILIO_AUTH_TOKEN = env('TWILIO_AUTH_TOKEN');

            try {
                Http::withHeaders([
                    'Authorization' => 'Basic ' . \base64_encode("$TWILIO_SID:$TWILIO_AUTH_TOKEN")
                ])->asForm()->post("https://api.twilio.com/2010-04-01/Accounts/$TWILIO_SID/Messages.json", [
                    "Body" => $text,
                    "From" => env('VALID_TWILIO_NUMBER'),
                    "To" => $to,
                ]);
            } catch (Exception $e) {
                // dd($e);
            }
        }
    }

    # phone verification
    public function phoneVerificationSms($to, $code)
    {
        $sms = 'Votre code de vérification pour ' . env('APP_NAME') . ' est ' . $code . '.';
        $this->sendSMS($to, $sms);
    }

    # forgot password
    public function forgotPasswordSms($to, $code)
    {
        $sms = 'Votre code de réinitialisation de mot de passe pour ' . env('APP_NAME') . ' est ' . $code . '.';
        $this->sendSMS($to, $sms);
    }
}
