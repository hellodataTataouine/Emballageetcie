@extends('frontend.default.layouts.master')
@section('contents')
    <section class="section-404 ptb-120 position-relative overflow-hidden z-1">
       <!--  <img src="{{ staticAsset('frontend/default/assets/img/shapes/frame-circle.svg') }}" alt="frame circle"
            class="position-absolute z--1 frame-circle d-none d-sm-block">
        <img src="{{ staticAsset('frontend/default/assets/img/shapes/cauliflower.png') }}" alt="cauliflower"
            class="position-absolute cauliflower z--1 d-none d-sm-block">
        <img src="{{ staticAsset('frontend/default/assets/img/shapes/leaf.svg') }}" alt="leaf"
            class="position-absolute leaf z--1 d-none d-sm-block">
        <img src="{{ staticAsset('frontend/default/assets/img/shapes/pata-xs.svg') }}" alt="pata"
            class="position-absolute pata z--1 d-none d-sm-block">
        <img src="{{ staticAsset('frontend/default/assets/img/shapes/tomato-half.svg') }}" alt="tomato"
            class="position-absolute tomato-half z--1 d-none d-sm-block">
        <img src="{{ staticAsset('frontend/default/assets/img/shapes/garlic-white.png') }}" alt="garlic"
            class="position-absolute garlic-white z--1 d-none d-sm-block">
        <img src="{{ staticAsset('frontend/default/assets/img/shapes/tomato-slice.svg') }}" alt="tomato"
            class="position-absolute tomato-slice z--1 d-none d-sm-block">
        <img src="{{ staticAsset('frontend/default/assets/img/shapes/onion.png') }} " alt="onion"
            class="position-absolute onion z--1 d-none d-sm-block"> -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6">
                    <div class="content-404 text-center">
                        <img src="{{ staticAsset('frontend/default/assets/img/500.png') }}" alt="not found"
                            class="img-fluid mb-5 d-none d-md-inline-block w-50">
                        <h3 class="fw-bold display-1 mb-0">500</h3>
                        <h2 class="mt-3">Désolé, Erreur interne du serveur.</h2>
                        <p class="mb-6">La page que vous recherchez a peut-être été supprimée, son nom a été modifié ou elle est temporairement indisponible..</p>
                        <a href="{{ env('APP_URL') }}" class="btn btn-secondary btn-md rounded-1">Retour à la page d'accueil</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
