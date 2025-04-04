<?php

namespace App\Http\Controllers\Backend\Payments;

use App\Http\Controllers\Backend\Payments\IyZico\IyZicoController;
use App\Http\Controllers\Backend\Payments\Paypal\PaypalController;
use App\Http\Controllers\Backend\Payments\Stripe\StripePaymentController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Backend\Payments\Paytm\PaytmPaymentController;
use App\Http\Controllers\Backend\Payments\Razorpay\RazorpayController;
use App\Models\OrderGroup;
use Illuminate\Http\Request;
class PaymentsController extends Controller
{
    # init payment gateway
    public function initPayment()
    {
        $payment_method = session('payment_method');
        if ($payment_method == 'paypal') {
            return (new PaypalController())->initPayment();
        } else if ($payment_method == 'stripe') {
            return (new StripePaymentController())->initPayment();
        } else if ($payment_method == 'paytm') {
            return (new PaytmPaymentController())->initPayment();
        } else if ($payment_method == 'razorpay') {
            return (new RazorpayController())->initPayment();
        } else if ($payment_method == 'iyzico') {
            return (new IyZicoController)->initPayment();
        }
        # todo::[update versions] more gateways
    }

    # payment successful
    public function payment_success($payment_details = null)
    {
        if (session('payment_type') == 'order_payment') {
            return (new CheckoutController())->updatePayments(json_encode($payment_details));
        }
        # else - other payments [update versions]
    }


   # payment failed
public function payment_failed()
{
    if (session('payment_type') == 'order_payment') {
        $orderGroup = OrderGroup::where('order_code', session('order_code'))->first();

        // Display error message
        flash(localize('Paiement échoué. votre commande est passée avec un statut non payé.'))->error();

        return redirect()->route('checkout.proceed');
    }
}

public function ContinuerPaiement($order_code){
    return view('frontend.default.pages.checkout.inc.ContinuePaymentMethods',compact('order_code'));
 

}




public function FinalContinuerPaiement(Request $request,$order_code)
{

    //$orderGroup = OrderGroup::where('order_code', $order_code)->first();



    if ($request->payment_method != "cod" && $request->payment_method != "wallet" && $request->payment_method != "vir") {
        $request->session()->put('payment_type', 'order_payment');
        $request->session()->put('order_code', $order_code);
        $request->session()->put('payment_method', $request->payment_method);
       
        

        # init payment
     
        return $this->initPayment();
    }   else {
       
        flash(localize('Votre commande a été passée avec succès.'))->success();
        return redirect()->route('checkout.success', $orderGroup->order_code);
    }















}
/*
    # payment failed
    public function payment_failed()
    {
        if (session('payment_type') == 'order_payment') {
            $orderGroup = OrderGroup::where('order_code', session('order_code'))->first();
            if (getSetting('enable_cod') == 1) {
                $orderGroup->payment_method = "cod";
                $orderGroup->save();
                # order success 
                clearOrderSession();
                flash(localize('Payment failed, Please pay in cash on delivery'))->success();
                return getView('pages.checkout.invoice', ['orderGroup' => $orderGroup]);
            } else {
                # delete order
                $orderGroup->order->orderItems()->delete();
                $orderGroup->order()->delete();
                $orderGroup->delete();
                clearOrderSession();
                flash(localize('Payment failed, please try again'))->error();
                return redirect()->route('home');
            }
        }
    }
    
*/


}
