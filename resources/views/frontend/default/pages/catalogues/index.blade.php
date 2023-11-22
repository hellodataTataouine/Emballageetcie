@extends('frontend.default.layouts.master')

@section('title')
    {{ localize('catalogues') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('breadcrumb-contents')
    <div class="breadcrumb-content">
        <h2 class="mb-2 text-center">{{ localize('Tous les Catalogues') }}</h2>
        <nav>
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item fw-bold" aria-current="page"><a
                        href="{{ route('home') }}">{{ localize('Accueil') }}</a></li>
                <li class="breadcrumb-item fw-bold" aria-current="page">{{ localize('Catalogues') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('contents')
    <!--breadcrumb-->
    @include('frontend.default.inc.breadcrumb')
    <!--breadcrumb-->

    <!--catalog section start-->
    <section class="tt-catalogues ptb-100">
        <div class="container">
            <div class="row g-4">

                @php
                    $catalogues = \App\Models\Catalog::all();
                @endphp

                @forelse ($catalogues as $catalog)
                    <div class="col-lg-4 col-md-6">
                        <div class="card shadow-lg border-0 tt-catalog-single tt-gradient-top">
                            <div class="card-body text-center py-5 px-4" style="background:
                                    url('{{ uploadedAsset($catalog->cover_image) }}') no-repeat center center / cover;">
                                <div class="catalog-text mb-2 justify-content-center">
                                    <h5 class="fw-bold text-light">{{ $catalog->name }}</h5>
                                    <p class="mb-0 fw-bold text-light">
                                        {{ $catalog->description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 col-md-6 mx-auto">
                        <img src="{{ staticAsset('frontend/default/assets/img/no-data-found.png') }}" class="img-fluid"
                            alt="">
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <!--catalog section end-->
@endsection
