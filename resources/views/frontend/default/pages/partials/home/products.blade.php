<section class="pt-80 pb-100">
    <div class="container">
        <div class="row justify-content-center justify-content-xl-between g-4">
            <div class="col-xl-9">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="product-listing-box bg-white rounded-2">
                            <div class="d-flex align-items-center justify-content-between gap-3 mb-5 flex-wrap">
                                <h4 class="mb-0">{{ localize("Nouveaux Articles") }}</h4>
                                <a href="{{ route('products.index') }}"
                                    class="explore-btn text-secondary fw-bold">{{ localize('Voir plus') }}<span
                                        class="ms-2"><i class="fas fa-arrow-right"></i></span></a>
                            </div>

                            @foreach ($products->take(3) as $product)
                                <div class="mb-3">

                                    @include(
                                        'frontend.default.pages.partials.products.horizontal-product-card',
                                        [
                                            'product' => $product,
                                        ]
                                    )
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="product-listing-box bg-white rounded-2">
                            <div class="d-flex align-items-center justify-content-between gap-3 mb-5 flex-wrap">
                                <h4 class="mb-0">{{ localize('Meilleures ventes') }}</h4>
                                <a href="{{ route('products.index') }}"
                                    class="explore-btn text-secondary fw-bold">{{ localize('Tous les produits') }}<span
                                        class="ms-2"><i class="fas fa-arrow-right"></i></span></a>
                            </div>
                            @php
                                $best_selling_products = getSetting('best_selling_products') != null ? json_decode(getSetting('best_selling_products')) : [];
                                $products = \App\Models\Product::whereIn('id', $best_selling_products)->get();
                            @endphp

                            @foreach ($products as $product)
                                <div class="mb-3">
                                    @include(
                                        'frontend.default.pages.partials.products.horizontal-product-card',
                                        [
                                            'product' => $product,
                                        ]
                                    )
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 d-none d-xl-block">
                <a href="{{ getSetting('best_selling_banner_link') }}" class=""><img
                        data-src="{{ uploadedAsset(getSetting('best_selling_banner')) }}" alt=""
                        class="img-fluid rounded-2 d-flex flex-column h-100 object-fit-cover lazyload"></a>
            </div>
        </div>
    </div>
</section>
