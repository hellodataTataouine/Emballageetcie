<div class="vertical-product-card rounded-2 position-relative d-md-flex align-items-center bg-white hr-product">

    @php
        $discountPercentage = discountPercentage($product);
    @endphp

    @if ($discountPercentage > 0)
        <span class="offer-badge text-white fw-bold fs-xxs bg-danger position-absolute start-0 top-0">
            -{{ discountPercentage($product) }}% <span class="text-uppercase">{{ localize('Off') }}</span>
        </span>
    @endif

    <div class="thumbnail position-relative text-center p-4 flex-shrink-0">
        <img src="{{ uploadedAsset($product->thumbnail_image) }}" alt="{{ $product->name }}"
            class="img-fluid">
    </div>
    <div class="card-content w-100">

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

        <h3 class="h5 mb-2">
            <a href="{{ route('products.show', $product->slug) }}"
                class="card-title fw-semibold mb-2 tt-line-clamp tt-clamp-1">{{ $product->name }}
            </a>
        </h3>
        <div class="d-flex justify-content-between">
                        <span class="fw-bold text-muted">Référence:</span>
                        <span class="fw-bold text-danger">{{ $product->slug }}</span>
                    </div>
        <h6 class="price">
            @include('frontend.default.pages.partials.products.pricing', [
                'product' => $product,
                'br' => true,
            ])
        </h6>

        <div class="card-progressbar mt-3 mb-2 rounded-pill">
            <span class="card-progress bg-primary" data-progress="{{ sellCountPercentage($product) }}%"
                style="width: {{ sellCountPercentage($product) }}%;"></span>
        </div>
        <!-- <p class="mb-0 fw-semibold">{{ localize('Total Vendu ') }}: <span
                class="fw-bold text-secondary">{{ $product->total_sale_count }}/{{ $product->sell_target }}</span></p> -->

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
        <a href="javascript:void(0);" class="btn btn-outline-secondary btn-sm border-secondary mt-4"
            onclick="showProductDetailsModal({{ $product->id }})">{{ localize('Ajouter au panier') }}</a>
            @else
            @if ($product->parents()->count() > 0)
        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-secondary btn-sm border-secondary mt-4">
        {{ __('Disponible en  :count références', ['count' => $product->parents()->where('is_published', 1)->count() + 1]) }}
    </a>
        @else
        <form action="" class="direct-add-to-cart-form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="product_price" value="{{ $product->max_price }}">

            <input type="hidden" name="product_variation_id" value="{{ $product->variations[0]->id }}">
            <input type="hidden" value="1" name="quantity">
           

            @if (!$isVariantProduct && $stock < 1)  
                <a href="javascript:void(0);" class="btn btn-outline-secondary btn-sm border-secondary mt-4">
                    {{ localize('Rupture de stock') }}</a>
            @else
                <a href="javascript:void(0);" onclick="directAddToCartFormSubmit(this)"
                    class="btn btn-outline-secondary btn-sm border-secondary mt-4 direct-add-to-cart-btn add-to-cart-text">{{ localize('Ajouter au panier') }}</a>
            @endif
        </form>
    @endif
    @endif
@else
    <!-- Omit the button entirely when the user is not authenticated -->
@endauth


    </div>
</div>
