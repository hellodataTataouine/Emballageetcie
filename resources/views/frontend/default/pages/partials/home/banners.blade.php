<section class="banner-section position-relative z-1 overflow-hidden">
    <img data-src="{{ staticAsset('frontend/default/assets/img/shapes/bg-shape-3.png') }}" alt="bg shape"
        class="position-absolute start-0 bottom-0 z--1 w-100 lazyload">
    <div class="container">
        <div class="row align-items-center g-4">
            @foreach ($banner_section_one_banners as $banner)
                <div class="col-xl-4 col-md-6">
                    <div class="bg-shade">
                        <label>



                        </label>   
                    <a href="{{ $banner->link }}" class="d-block">
                        <img data-src="{{ uploadedAsset($banner->image) }}" class="img-fluid lazyload" alt="" srcset="">
                    </a>
                </div>
                </div>
            @endforeach
        </div>
    </div>
</section>