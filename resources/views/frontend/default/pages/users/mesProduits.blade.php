@extends('frontend.default.layouts.master')

@section('title')
    {{ localize('Mes Produits') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('breadcrumb-contents')
    <div class="breadcrumb-content">
        <h2 class="mb-2 text-center">{{ localize('Mes Produits') }}</h2>
        <nav>
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item fw-bold" aria-current="page"><a
                        href="{{ route('home') }}">{{ localize('Accueil') }}</a></li>
                <li class="breadcrumb-item fw-bold" aria-current="page">{{ localize('Mes Produits') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('contents')
    <!--breadcrumb-->
    @include('frontend.default.inc.breadcrumb')
    <!--breadcrumb-->

    <form class="filter-form" action="{{ Request::fullUrl() }}" method="GET" style="padding-left: 100px;">
        <!--shop grid section start-->
        <section class="gshop-gshop-grid ptb-120">
            <div class="col-xl-11">
                <div class="row g-4">
                    <div class="col-xl-12">
                        <div class="shop-grid">
                            <!--filter-->
                            <div class="listing-top d-flex align-items-center justify-content-between flex-wrap gap-3 bg-white rounded-2 px-4 py-4 mb-5">
                                <p class="mb-0 fw-bold">{{ localize('Affichage de') }}
                                    {{ count($mesProduits) }} {{ localize('produits') }}</p>
                            </div>
                            <!--filter-->

                             <!--products-->
                             <div class="row g-4">
                                @if (count($mesProduits) > 0)
                                    @if (request()->has('view') && request()->view == 'list')
                                        @foreach ($mesProduits as $product)
                                            @if ($product->parent_id === null) <!-- Add this condition to hide products with parent_id not null -->
                                                <div class="col-xl-12">
                                                    @include(
                                                        'frontend.default.pages.partials.products.product-card-list',
                                                        [
                                                            'product' => $product,
                                                        ]
                                                    )
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="row">
                                            @foreach ($mesProduits as $index => $product)
                                                @if ($product->parent_id === null) <!-- Add this condition to hide products with parent_id not null -->
                                                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                                                        @include(
                                                            'frontend.default.pages.partials.products.vertical-product-card',
                                                            [
                                                                'product' => $product,
                                                                'bgClass' => 'bg-white',
                                                            ]
                                                        )
                                                    </div>

                                                    @if (($index + 1) % 4 === 0 && $index + 1 !== count($mesProduits))
                                                        <div class="w-100"></div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                    <div class="col-6 mx-auto">
                                        <img src="{{ staticAsset('frontend/default/assets/img/empty-cart.svg') }}" alt="" srcset="" class="img-fluid">
                                    </div>
                                @endif
                            </div>
                            <ul class="d-flex align-items-center gap-3 mt-7">
                                {{ $mesProduits->appends(request()->input())->links() }}
                            </ul>
                            <!--products-->


                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--shop grid section end-->
    </form>
@endsection

@section('scripts')
    <script>
        "use strict";

        $('.product-listing-pagination').on('focusout', function() {
            $('.filter-form').submit();
        });

        $('.sort_by').on('change', function() {
            $('.filter-form').submit();
        });
    </script>
@endsection
