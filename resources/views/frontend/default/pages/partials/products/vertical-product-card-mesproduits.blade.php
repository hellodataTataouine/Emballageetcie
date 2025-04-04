<div class="vertical-product-card rounded-2 position-relative swiper-slide {{ isset($bgClass) ? $bgClass : '' }}">

 

    <div class="thumbnail position-relative text-center p-4">
        <img data-src="{{ uploadedAsset($product->thumbnail_image) }}" alt="{{ $product->name }}"
            class="img-fluid lazyload">
        <div class="product-btns position-absolute d-flex gap-2 flex-column">
            @if (Auth::check() && Auth::user()->user_type == 'customer')
                <a href="javascript:void(0);" class="rounded-btn"><i class="fa-regular fa-heart"
                        onclick="addToWishlist({{ $product->id }})"></i></a>
            @elseif(!Auth::check())
                <a href="javascript:void(0);" class="rounded-btn"><i class="fa-regular fa-heart"
                        onclick="addToWishlist({{ $product->id }})"></i></a>
            @endif

            <a href="javascript:void(0);" class="rounded-btn" onclick="showProductDetailsModal({{ $product->id }})"><i
                    class="fa-regular fa-eye"></i></a>
        </div>
    </div>
    <div class="card-content">
        @if (getSetting('enable_reward_points') == 1)
            <span class="fs-xxs fw-bold" data-bs-toggle="tooltip" data-bs-placement="top"
                data-bs-title="{{ localize('Reward Points') }}">
                <i class="fas fa-medal"></i> {{ $product->reward_points }}
            </span>
        @endif
        <!--product category start-->
        <div class="mb-2 tt-category tt-line-clamp tt-clamp-1">
            @if ($product->categories()->count() > 0)
                @foreach ($product->categories as $category)
                    <a href="{{ route('products.index') }}?&category_id={{ $category->id }}"
                        class="d-inline-block text-muted fs-xxs">{{ $category->collectLocalization('name') }}
                        @if (!$loop->last)
                            ,
                        @endif
                    </a>
                @endforeach
            @endif
        </div>
        <!--product category end-->

        <a href="{{ route('products.show', $product->slug) }}"
        class="card-title fw-semibold mb-2 tt-line-clamp tt-clamp-2" style="-webkit-box-orient: vertical;">
{{ $product->name }}
        </a>

        <h6 class="price">
            @include('frontend.default.pages.partials.products.pricing', [
                'product' => $product,
                'onlyPrice' => true,
            ])
        </h6>

        @isset($showSold)
            <div class="card-progressbar mb-2 mt-3 rounded-pill">
                <span class="card-progress bg-primary" data-progress="{{ sellCountPercentage($product) }}%"
                    style="width: {{ sellCountPercentage($product) }}%;"></span>
            </div>
            <p class="mb-0 fw-semibold">{{ localize('Total Vendu ') }}: <span
                    class="fw-bold text-secondary">{{ $product->total_sale_count }}/{{ $product->sell_target }}</span>
            </p>
        @endisset


        @php
            $isVariantProduct = 0;
            $stock = 0;
            if ($product->variations()->count() > 1) {
                $isVariantProduct = 1;
            } else {
                $stock = $product->stock_qty ?: 0;
            }
        @endphp
        @auth
        @if ($isVariantProduct)
        <a href="javascript:void(0);" class="btn btn-outline-secondary btn-md border-secondary d-block mt-4"
            onclick="showProductDetailsModal({{ $product->id }})">
            {{ localize('Ajouter au panier') }}
        </a>
    @else
    @if ($product->parents()->where('is_published', 1)->where('afficher', 1)->count() > 0)
    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-secondary btn-md border-secondary d-block mt-4">
        {{ __('Disponible en  :count références', ['count' => $product->parents()->where('is_published', 1)->where('afficher', 1)->count() + 1])  }}
    </a>
        @else
            <form action="" class="direct-add-to-cart-form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="product_price" value="{{ $product->max_price }}">
                <input type="hidden" name="product_variation_id" value="{{ $product->variations[0]->id }}">
                <input type="hidden" value="1" name="quantity">
            
                @if (!$isVariantProduct && $product->stock_qty < 1)
                    <a href="javascript:void(0);"
                        class="btn btn-outline-secondary btn-md border-secondary d-block mt-4 w-100">
                        {{ localize('Rupture de stock') }}
                    </a>
                @else
                    <a href="javascript:void(0);"
                        onclick="showProductDetailsModal({{ $product->id }})"
                        class="btn btn-outline-secondary btn-md border-secondary d-block mt-4 w-100 direct-add-to-cart-btn add-to-cart-text">
                        {{ localize('Ajouter au panier') }}
                    </a>
                @endif
            </form>
        @endif
    @endif

@else
    <!-- Omit the button entirely when the user is not authenticated -->
@endauth



    </div>
</div>
