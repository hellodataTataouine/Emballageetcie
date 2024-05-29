@extends('frontend.default.layouts.master')

@section('title')
    {{ localize('Paiement') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('breadcrumb-contents')
    <div class="breadcrumb-content">
        <h2 class="mb-2 text-center">{{ localize('Paiement') }}</h2>
        <nav>
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item fw-bold" aria-current="page"><a
                        href="{{ route('home') }}">{{ localize('Accueil') }}</a></li>
                <li class="breadcrumb-item fw-bold" aria-current="page">{{ localize('Paiement') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('contents')
    <!--breadcrumb-->
    @include('frontend.default.inc.breadcrumb')
    <!--breadcrumb-->
    <form class="checkout-form" action="{{ route('checkout.paymentcomplete', ['order_code' => $order_code]) }}" method="POST">
    <div class="container">
    <h4 class="mt-7">{{ localize('Moyen de Paiement') }}</h4>

    <div class="row g-4">
    <div class="col-xl-8">
    <!--COD-->
   


<!--Paypal-->
@if (getSetting('enable_paypal') == 1)
    <div class="checkout-radio d-flex align-items-center justify-content-between gap-3 bg-white rounded p-4 mt-3">
        <div class="radio-left d-inline-flex align-items-center">
            <div class="theme-radio">
                <input type="radio" name="payment_method" id="paypal" value="paypal" required>
                <span class="custom-radio"></span>
            </div>
            <label for="paypal" class="ms-2 h6 mb-0">{{ localize('Payer avec Paypal') }}</label>
        </div>
        <div class="radio-right text-end">
            <img src="{{ staticAsset('frontend/pg/paypal.svg') }}" alt="paypal" class="img-fluid">
        </div>
    </div>
@endif

<!--Stripe-->
@if (getSetting('enable_stripe') == 1)
    <div class="checkout-radio d-flex align-items-center justify-content-between gap-3 bg-white rounded p-4 mt-3">
        <div class="radio-left d-inline-flex align-items-center">
            <div class="theme-radio">
                <input type="radio" name="payment_method" id="stripe" value="stripe" required>
                <span class="custom-radio"></span>
            </div>
            <label for="stripe" class="ms-2 h6 mb-0">{{ localize('Payer avec Carte Bancaire') }}</label>
        </div>
        <div class="radio-right text-end">
            <img src="{{ staticAsset('frontend/pg/carte.png') }}" alt="stripe" class="img-fluid">
        </div>
    </div>
    <button type="submit" class="btn btn-primary " >{{ localize('Continuer Votre Paiement') }}</button>


    </div>
    </div>
</form>
@endif


@endsection