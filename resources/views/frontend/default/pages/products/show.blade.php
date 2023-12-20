@extends('frontend.default.layouts.master')

@php
    $detailedProduct = $product;
@endphp

@section('title')
    @if ($detailedProduct->meta_title)
        {{ $detailedProduct->meta_title }}
    @else
        {{ localize('Détails du produit') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
    @endif
@endsection

@section('meta_description')
    {{ $detailedProduct->meta_description }}
@endsection

@section('meta_keywords')
    @foreach ($detailedProduct->tags as $tag)
        {{ $tag->name }} @if (!$loop->last)
            ,
        @endif
    @endforeach
@endsection

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $detailedProduct->meta_title }}">
    <meta itemprop="description" content="{{ $detailedProduct->meta_description }}">
    <meta itemprop="image" content="{{ uploadedAsset($detailedProduct->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $detailedProduct->meta_title }}">
    <meta name="twitter:description" content="{{ $detailedProduct->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploadedAsset($detailedProduct->meta_img) }}">
    <meta name="twitter:data1" content="{{ formatPrice($detailedProduct->min_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $detailedProduct->meta_title }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="{{ route('products.show', $detailedProduct->slug) }}" />
    <meta property="og:image" content="{{ uploadedAsset($detailedProduct->meta_img) }}" />
    <meta property="og:description" content="{{ $detailedProduct->meta_description }}" />
    <meta property="og:site_name" content="{{ getSetting('meta_title') }}" />
    <meta property="og:price:amount" content="{{ formatPrice($detailedProduct->min_price) }}" />
    <meta property="product:price:currency" content="{{ env('DEFAULT_CURRENCY') }}" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
@endsection


@section('breadcrumb-contents')
    <div class="breadcrumb-content">
        <h2 class="mb-2 text-center">{{ localize('Détails du produit') }}</h2>
        <nav>
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item fw-bold" aria-current="page"><a
                        href="{{ route('home') }}">{{ localize('Accueil') }}</a></li>
                <li class="breadcrumb-item fw-bold" aria-current="page">{{ localize('Produits') }}</li>
                <li class="breadcrumb-item active fw-bold" aria-current="page">{{ localize('Détails du produit') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('contents')
    <!--breadcrumb-->
    @include('frontend.default.inc.breadcrumb')
    <!--breadcrumb-->

    <!--product details start-->
    <section class="product-details-area ptb-120">
        <div class="container">
            <div class="row g-4">
                <div class="col-xl-13">
                    <div class="product-details">
                        <!-- product-view-box -->
                        @include(
                            'frontend.default.pages.partials.products.product-view-box',
                            compact('product'))
                        <!-- product-view-box -->

                        <!-- Réferences -->
                        @if ($product->parents->isNotEmpty())
                            <div class="mt-4">
                                <h2 class="mb-4">{{ localize('Réferences') }}</h2>
                                <div class="table-responsive">
                                <table class="table table-bordered table-hover text-center">
                                        <thead>
                                            <tr>
                                                <th>{{ localize('Image') }}</th>
                                                <th>{{ localize('Réference') }}</th>
                                                <th>{{ localize('Désignation') }}</th>
                                                <th>{{ localize('Volume') }}</th>
                                                <th>{{ localize('Dimension') }}</th>
                                                <th>{{ localize('Couleur') }}</th>
                                                <th>{{ localize('Quantité') }}</th>
                                                <th>{{ localize('Disponibilité') }}</th>
                                                @auth
                                                <th>{{ localize('Prix TTC') }}</th>
                                                @endauth
                                                <th>{{ localize('Fiche Technique') }}</th> 
                                                <th>{{ localize('Action') }}</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Parent Product Row -->
                                            <tr>
                                                <td class="align-middle">
                                                    @if($product->thumbnail_image)
                                                        <a href="{{ route('products.show', $product->slug) }}">
                                                            <img src="{{ uploadedAsset($product->thumbnail_image) }}" alt="{{ $product->name }}" class="img-fluid" style="max-width: 40px; max-height: 40px;">
                                                        </a>
                                                    @else
                                                        {{ $product->slug }}
                                                    @endif
                                                </td>
                                                <td class="align-middle">
                                                    <a href="{{ route('products.show', $product->slug) }}">{{ $product->slug }}</a>
                                                </td>
                                                <td class="align-middle">
                                                    <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                                </td>
                                                <td class="align-middle">{{ $product->total_volume ? $product->total_volume : '-' }}</td>
                                                <td class="align-middle">{{ $product->dimensions  ? $product->dimensions : '-'}}</td>
                                                <td class="align-middle">{{ $product->color  ? $product->color : '-'}}</td>
                                                <td class="align-middle">{{ $product->Qty_Unit }}</td>
                                                <td class="align-middle">
                                                    @if($product->stock_qty > 0)
                                                        <span class="text-success h1">&bull;</span>
                                                    @else
                                                        <span class="text-danger h1">&bull;</span>
                                                    @endif
                                                </td>
                                                @auth
                                                <td class="align-middle">
                                               
                                                        {{ formatPrice($product->min_price) }}
                                                  
                                                    
                                                </td>
                                                @endauth
                                                <td class="align-middle">
                                                    <!-- Fiche Technique  -->
                                                    @if (!empty($product->fiche_technique))
                                                    <a href="{{ asset('public/storage/' . $product->fiche_technique) }}" target="_blank" class="btn btn-info btn-sm " >
                                                        <i class="fas fa-file-pdf fa-sm"></i> 
                                                    </a>
                                                    @else
                                                        
                                                    @endif
                                                </td>
                                                <td class="align-middle">
                                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                                        <button type="button" class="btn btn-primary btn-sm me-2" onclick="showProductDetailsModal({{ $product->id }})">
                                                            <i class="fas fa-shopping-cart fa-sm"></i>
                                                        </button> 
                                                        <button type="button" class="btn btn-success btn-sm" onclick="addToWishlist({{ $product->id }})">
                                                            <i class="fas fa-heart fa-sm"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Child Products Rows -->
                                            @foreach ($childrenProducts as $childProduct)
                                                                       <tr>
                                                    <td class="align-middle">
                                                        @if($childProduct->thumbnail_image)
                                                            <a href="{{ route('products.show', $childProduct->slug) }}">
                                                                <img src="{{ uploadedAsset($childProduct->thumbnail_image) }}" alt="{{ $childProduct->name }}" class="img-fluid" style="max-width: 40px; max-height: 40px;">
                                                            </a>
                                                        @else
                                                            {{ $childProduct->slug }}
                                                        @endif
                                                    </td>
                                                    <td class="align-middle">
                                                        <a href="{{ route('products.show', $childProduct->slug) }}">{{ $childProduct->slug }}</a>
                                                    </td>
                                                    <td class="align-middle">
                                                        <a href="{{ route('products.show', $childProduct->slug) }}">{{ $childProduct->name }}</a>
                                                    </td>
                                                    <td class="align-middle">{{ $childProduct->total_volume ? $childProduct->total_volume : '-'}}</td>
                                                    <td class="align-middle">{{ $childProduct->dimensions ? $childProduct->dimensions : '-'}}</td>
                                                    <td class="align-middle">{{ $childProduct->color ? $childProduct->color : '-'}}</td>
                                                    <td class="align-middle">{{ $childProduct->Qty_Unit }}</td>
                                                    <td class="align-middle">
                                                        @if($childProduct->stock_qty > 0)
                                                            <span class="text-success h1">&bull;</span>
                                                        @else
                                                            <span class="text-danger h1">&bull;</span>
                                                        @endif
                                                    </td>
                                                    @auth
                                                    <td class="align-middle">
                                                   
                                                        {{ formatPrice($childProduct->max_price) }}
                                                   
                                                    </td>
                                                    @endauth
                                                    <td class="align-middle">
                                                        <!-- Fiche Technique  -->
                                                        @if (!empty($childProduct->fiche_technique))
                                                        <a href="{{ asset('public/storage/' . $childProduct->fiche_technique) }}" target="_blank" class="btn btn-info btn-sm">
                                                            <i class="fas fa-file-pdf fa-sm"></i>
                                                        </a>
                                                        @else
                                                          
                                                        @endif
                                                    </td>
                                                    <td class="align-middle">
                                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                                            <button type="button" class="btn btn-primary btn-sm me-2" onclick="showProductDetailsModal({{ $childProduct->id }})">
                                                                <i class="fas fa-shopping-cart fa-sm"></i>
                                                            </button> 
                                                            <button type="button" class="btn btn-success btn-sm" onclick="addToWishlist({{ $childProduct->id }})">
                                                                <i class="fas fa-heart fa-sm"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <!-- No child products available -->
                        @endif
                        <!-- Réferences -->

                        <!-- description -->
                        @include(
                            'frontend.default.pages.partials.products.description',
                            compact('product'))
                        <!-- description -->
                    </div>

                    <!-- <div class="col-xl-3 col-lg-6 col-md-8 d-none d-xl-block">
                        <div class="gshop-sidebar">
                            <div class="sidebar-widget info-sidebar bg-white rounded-3 py-3">
                                @foreach ($product_page_widgets as $widget)
                                    <div class="sidebar-info-list d-flex align-items-center gap-3 p-4">
                                        <span
                                            class="icon-wrapper d-inline-flex align-items-center justify-content-center rounded-circle text-primary">
                                            <img src="{{ uploadedAsset($widget->image) }}" class="img-fluid"
                                                alt="">
                                        </span>
                                        <div class="info-right">
                                            <h6 class="mb-1 fs-md">{{ $widget->title }}</h6>
                                            <span class="fw-medium fs-xs">{{ $widget->sub_title }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="sidebar-widget banner-widget mt-4">
                                <a href="{{ getSetting('product_page_banner_link') }}">
                                    <img src="{{ uploadedAsset(getSetting('product_page_banner')) }}" alt=""
                                        class="img-fluid">
                                </a>
                            </div>

                        </div>
                    </div> -->
                </div>
            </div>
            <script>
    // Script to handle quantity increase and decrease
    function handleQuantity(action, productId) {
        const quantityInput = document.getElementById(`quantityInput_${productId}`);
        let currentQuantity = parseInt(quantityInput.value);

        if (action === 'increase') {
            currentQuantity++;
        } else if (action === 'decrease' && currentQuantity > 1) {
            currentQuantity--;
        }

        quantityInput.value = currentQuantity;
    }

</script>
    </section>
    <!--product details end-->

    <!--related product slider start -->
    @include('frontend.default.pages.partials.products.related-products', [
        'relatedProducts' => $relatedProducts,
    ])
    <!--related products slider end-->
@endsection


