<div class="quickview-double-slider">
    @php
        if (!is_null($product->gallery_images)) {
            $galleryImages = explode(',', $product->gallery_images);
        } else {
            $galleryImages = [];
            $galleryImages[] = $product->thumbnail_image;
        }
    @endphp
    <div class="quickview-product-slider swiper">
        <div class="swiper-wrapper">
            @foreach ($galleryImages as $galleryImage)
                <div class="swiper-slide text-center">
                    <img src="{{ uploadedAsset($galleryImage) }}" alt="{{ $product->collectLocalization('name') }}"
                        class="img-fluid">
                </div>
            @endforeach
        </div>
    </div>
    <div class="product-thumbnail-slider swiper mt-80">
        <div class="swiper-wrapper">
            @foreach ($galleryImages as $galleryImage)
                <div
                    class="swiper-slide product-thumb-single rounded-2 d-flex align-items-center justify-content-center">
                    <img src="{{ uploadedAsset($galleryImage) }}?thumb"
                        alt="{{ $product->collectLocalization('name') }}" class="img-fluid">
                </div>
            @endforeach

        </div>
    </div>
</div>
