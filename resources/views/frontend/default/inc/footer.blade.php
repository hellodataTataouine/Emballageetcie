<style>

    .whatsapp-left {
        position: fixed;
        left: 30px;
        bottom: 30px;
        z-index: 11;
        width: 65px;
        height: 65px;
    }
    .whatsapp-right {
        position: fixed;
        right: 30px;
        bottom: 100px;
        z-index: 11;
        width: 65px;
        height: 65px;
    }
    .whatsapp-left .whatsapp-btn,
    .whatsapp-right .whatsapp-btn {
        width: 65px;
        height: 65px;
        background: #40c351;
        text-align: center;
        line-height: 30px;
        border-radius: 50%;
    }   
    /* Adjust the size of social media icons */
    .social-btn {
        font-size: 24px; /* Adjust the size as needed */
        margin-right: 20px; /* Adjust the spacing between icons */
    }
    
    /* Optional: Increase the space between the list items */
    .social-media li {
        margin-bottom: 10px; /* Adjust the vertical space between list items */
    }
        </style>
    
    
    {{-- <div class="footer-curve position-relative overflow-hidden">
        <span class="position-absolute section-curve-wrapper top-0 h-100"
            data-background="{{ staticAsset('frontend/default/assets/img/shapes/section-curve.png') }}"></span>
    </div> --}}
    
<footer class="gshop-footer position-relative pt-8 bg-dark z-1 overflow-hidden">
    <img src="{{ staticAsset('frontend/default/assets/img/shapes/Bag.png') }}" alt="Bag"
        class="position-absolute z--1 tomato vector-shape">
        <img src="{{ staticAsset('frontend/default/assets/img/shapes/16 lune.png') }}" alt="16 lune"
        class="position-absolute z--1 tomato-1 vector-shape">
        <img src="{{ staticAsset('frontend/default/assets/img/shapes/Photo 31.png') }}" alt="Photo 31"
        class="position-absolute z--1 pata-lg vector-shape">
    <!-- <img src="{{ staticAsset('frontend/default/assets/img/shapes/pata-xs.svg') }}" alt="pata"
        class="position-absolute z--1 pata-xs vector-shape"> -->
    <img src="{{ staticAsset('frontend/default/assets/img/shapes/Paper roll.png') }}" alt="Paper roll"
        class="position-absolute z--1 frame-circle vector-shape">
    <img src="{{ staticAsset('frontend/default/assets/img/shapes/recycle.png') }}" alt="recycle"
        class="position-absolute z--1 leaf vector-shape">
        <img src="{{ staticAsset('frontend/default/assets/img/shapes/15 smiley.png') }}" alt="15 smiley"
        class="position-absolute z--1 leaf-1 vector-shape">
    <!--shape right -->
    <img src="{{ staticAsset('frontend/default/assets/img/shapes/Photo 612.png') }}" alt="Photo 612"
        class="position-absolute leaf-3 z--1 vector-shape">
        <img src="{{ staticAsset('frontend/default/assets/img/shapes/Photo 56.png') }}" alt="Photo 56"
        class="position-absolute pata-xs-2 z--1 vector-shape">
        <img src="{{ staticAsset('frontend/default/assets/img/shapes/Photo 41.png') }}" alt="Photo 41"
        class="position-absolute tomato-slice vector-shape z--1">
    <img src="{{ staticAsset('frontend/default/assets/img/shapes/Kit-couvert-4-en-1.png') }}" alt="Kit-couvert-4-en-1"
        class="position-absolute tomato-half z--1 vector-shape">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6">
                <div class="gshop_subscribe_form text-center">
                    <h4 class="text-white gshop-title">{{ localize('Abonnez-vous') }}<mark
                            class="p-0 position-relative text-secondary bg-transparent"> {{ localize('Nouveautés') }}
                            <img src="{{ staticAsset('frontend/default/assets/img/shapes/border-line.svg') }}"
                                alt="border line" class="position-absolute border-line"></mark><br
                            class="d-none d-sm-block">{{ localize('& Autres Informations.') }}</h4>
                    <form class="mt-5 d-flex align-items-center bg-white rounded subscribe_form"
                        action="{{ route('subscribe.store') }}" method="POST">
                        @csrf
                        {!! RecaptchaV3::field('recaptcha_token') !!}
                        <input type="email" class="form-control" placeholder="{{ localize('Entrer votre adresse e-mail') }}"
                            type="email" name="email" required>
                        <button type="submit"
                            class="btn btn-primary flex-shrink-0">{{ localize('Abonnez-vous maintenant') }}</button>
                    </form>
                </div>
            </div>
        </div>
        <span class="gradient-spacer my-8 d-block"></span>
        <div class="row g-5">
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                <div class="footer-widget">
                    <h5 class="text-white mb-4">{{ localize('Catégorie') }}</h5>
                    @php
                        $footer_categories = getSetting('footer_categories') != null ? json_decode(getSetting('footer_categories')) : [];
                        $categories = \App\Models\Category::whereIn('id', $footer_categories)->get();
                    @endphp
                    <ul class="footer-nav">
                        @foreach ($categories as $category)
                            <li><a
                                    href="{{ route('products.index') }}?&category_id={{ $category->id }}">{{ $category->collectLocalization('name') }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                <div class="footer-widget">
                    <h5 class="text-white mb-4">{{ localize('Liens Rapides') }}</h5>
                    @php
                        $quick_links = getSetting('quick_links') != null ? json_decode(getSetting('quick_links')) : [];
                        $pages = \App\Models\Page::whereIn('id', $quick_links)->get();
                    @endphp
                    <ul class="footer-nav">
                        @foreach ($pages as $page)
                            <li><a
                                    href="{{ route('home.pages.show', $page->slug) }}">{{ $page->collectLocalization('title') }}</a>
                                    
                            </li>
                            
                        @endforeach
                        <li><a href="{{ route('home.pages.contactUs') }}">{{ localize('Contact') }}</a></li>
                        <li><a href="{{ route('home.pages.aboutUs') }}">{{ localize('À propos') }}</a> </li>
                        <!-- <li><a href="{{ route('home.blogs') }}">{{ localize('Blogs') }}</a></li> --> 

                    </ul>
                    <br>
                    
                    <h5 class="text-white mb-4">Réseaux sociaux</h5>
<ul class="social-media list-unstyled d-flex justify-content-start align-items-center">
<li class="me-4">
    <a href="{{ getSetting('facebook_link') }}" target="_blank" class="social-btn"><i class="fab fa-facebook-f"></i></a>
</li>
<li class="me-4">
    <a href="{{ getSetting('twitter_link') }}" target="_blank" class="social-btn"><i class="fab fa-instagram"></i></a>
</li>
<li class="me-4">
    <a href="{{ getSetting('linkedin_link') }}" target="_blank" class="social-btn"><i class="fab fa-linkedin"></i></a>
</li>
<li>
    <a href="{{ getSetting('youtube_link') }}" target="_blank" class="social-btn"><i class="fab fa-tiktok"></i></a>
</li>
</ul>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                <div class="footer-widget">
                    <h5 class="text-white mb-4">{{ localize('Pages Clients') }}</h5>
                    <ul class="footer-nav">
                    @if(auth()->check())
                                        <li><a href="{{ route('customers.mesProduits') }}">{{ localize('Ma Sélection') }}</a></li>
                                    @endif
                        <li><a href="{{ route('customers.dashboard') }}">{{ localize('Votre Compte') }}</a></li>
                        <li><a href="{{ route('customers.orderHistory') }}">{{ localize('Vos Commandes') }}</a></li>
                        <li><a href="{{ route('customers.wishlist') }}">{{ localize('Liste d\'envies') }}</a></li>
                        <li><a href="{{ route('customers.address') }}">{{ localize('Carnet d\'adresses') }}</a></li>
                        <li><a href="{{ route('customers.profile') }}">{{ localize('Modifier Profil') }}</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                <div class="footer-widget">
                    <h5 class="text-white mb-4">{{ localize('Informations de Contact') }}</h5>
                    <ul class="footer-nav">
                        <li class="text-white pb-2 fs-xs">{{ getSetting('topbar_location') }}</li>
                        <li class="text-white pb-2 fs-xs"><a href="tel:{{ getSetting('navbar_contact_number') }}">{{ getSetting('navbar_contact_number') }}</a></li>
                        <li class="text-white pb-2 fs-xs"><a href="mailto:{{ getSetting('topbar_email') }}">{{ getSetting('topbar_email') }}</a></li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-copyright pt-120 pb-3">
        <span class="gradient-spacer d-block mb-3"></span>
        <div class="container">
            <div class="row align-items-center g-3">
                <div class="col-lg-4">
                   
                </div>
                <div class="col-lg-4 d-none d-lg-block">
                    <div class="logo-wrapper text-center">
                        <a href="{{ route('home') }}" class="logo"><img
                                src="{{ uploadedAsset(getSetting('footer_logo')) }}" alt="footer logo"
                                class="img-fluid"></a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="footer-payments-info d-flex align-items-center justify-content-lg-end gap-2">
                        <div
                            class="rounded-1 d-inline-flex align-items-center justify-content-center p-2 flex-shrink-0">
                            <img src="{{ uploadedAsset(getSetting('accepted_payment_banner')) }}"
                                alt="accepted_payment" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
@if (getSetting('navbar_contact_number'))
{{--<div class="whatsapp-left">
 <a href="https://api.whatsapp.com/send?phone={{ getSetting('navbar_contact_number') }}&text=Bonjour,%20c'est%20l'équipe%20de%20Sweet%20and%20Soda.%20Comment%20pouvons-nous%20vous%20aider%20?" target="_blank" class="whatsapp-btn"> 


		<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="48" height="48" viewBox="0 0 48 48" style=" fill:#000000;"><path fill="#fff" d="M4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98c-0.001,0,0,0,0,0h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303z"></path><path fill="#fff" d="M4.868,43.803c-0.132,0-0.26-0.052-0.355-0.148c-0.125-0.127-0.174-0.312-0.127-0.483l2.639-9.636c-1.636-2.906-2.499-6.206-2.497-9.556C4.532,13.238,13.273,4.5,24.014,4.5c5.21,0.002,10.105,2.031,13.784,5.713c3.679,3.683,5.704,8.577,5.702,13.781c-0.004,10.741-8.746,19.48-19.486,19.48c-3.189-0.001-6.344-0.788-9.144-2.277l-9.875,2.589C4.953,43.798,4.911,43.803,4.868,43.803z"></path><path fill="#cfd8dc" d="M24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5 M24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974 M24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974 M24.014,4C24.014,4,24.014,4,24.014,4C12.998,4,4.032,12.962,4.027,23.979c-0.001,3.367,0.849,6.685,2.461,9.622l-2.585,9.439c-0.094,0.345,0.002,0.713,0.254,0.967c0.19,0.192,0.447,0.297,0.711,0.297c0.085,0,0.17-0.011,0.254-0.033l9.687-2.54c2.828,1.468,5.998,2.243,9.197,2.244c11.024,0,19.99-8.963,19.995-19.98c0.002-5.339-2.075-10.359-5.848-14.135C34.378,6.083,29.357,4.002,24.014,4L24.014,4z"></path><path fill="#40c351" d="M35.176,12.832c-2.98-2.982-6.941-4.625-11.157-4.626c-8.704,0-15.783,7.076-15.787,15.774c-0.001,2.981,0.833,5.883,2.413,8.396l0.376,0.597l-1.595,5.821l5.973-1.566l0.577,0.342c2.422,1.438,5.2,2.198,8.032,2.199h0.006c8.698,0,15.777-7.077,15.78-15.776C39.795,19.778,38.156,15.814,35.176,12.832z"></path><path fill="#fff" fill-rule="evenodd" d="M19.268,16.045c-0.355-0.79-0.729-0.806-1.068-0.82c-0.277-0.012-0.593-0.011-0.909-0.011c-0.316,0-0.83,0.119-1.265,0.594c-0.435,0.475-1.661,1.622-1.661,3.956c0,2.334,1.7,4.59,1.937,4.906c0.237,0.316,3.282,5.259,8.104,7.161c4.007,1.58,4.823,1.266,5.693,1.187c0.87-0.079,2.807-1.147,3.202-2.255c0.395-1.108,0.395-2.057,0.277-2.255c-0.119-0.198-0.435-0.316-0.909-0.554s-2.807-1.385-3.242-1.543c-0.435-0.158-0.751-0.237-1.068,0.238c-0.316,0.474-1.225,1.543-1.502,1.859c-0.277,0.317-0.554,0.357-1.028,0.119c-0.474-0.238-2.002-0.738-3.815-2.354c-1.41-1.257-2.362-2.81-2.639-3.285c-0.277-0.474-0.03-0.731,0.208-0.968c0.213-0.213,0.474-0.554,0.712-0.831c0.237-0.277,0.316-0.475,0.474-0.791c0.158-0.317,0.079-0.594-0.04-0.831C20.612,19.329,19.69,16.983,19.268,16.045z" clip-rule="evenodd"></path></svg>
	</a>
</div>--}}
@endif	 
