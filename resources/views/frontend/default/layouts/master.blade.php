<!DOCTYPE html>
@php
    $locale = str_replace('_', '-', app()->getLocale()) ?? 'en';
    $localLang = \App\Models\Language::where('code', $locale)->first();
    if ($localLang == null) {
        $localLang = \App\Models\Language::where('code', 'en')->first();
    }
@endphp
@if ($localLang->is_rtl == 1)
    <html dir="rtl" lang="{{ $locale }}" data-bs-theme="light">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
@endif

<head>
    <!--required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!--meta-->
    <meta name="robots" content="index, follow">
    <meta name="description" content="{{ getSetting('global_meta_description') }}">
    <meta name="keywords" content="{{ getSetting('global_meta_keywords') }}">

    <!--favicon icon-->
    <link rel="icon" href="{{ uploadedAsset(getSetting('favicon')) }}" type="image/png" sizes="16x16">

    <!--title-->
    <title>
        @yield('title', getSetting('system_title'))
    </title>

    @yield('meta')

    @if (!isset($detailedProduct) && !isset($blog))
        <!-- Schema.org markup for Google+ -->
        <meta itemprop="name" content="{{ getSetting('global_meta_title') }}" />
        <meta itemprop="description" content="{{ getSetting('global_meta_description') }}" />
        <meta itemprop="image" content="{{ uploadedAsset(getSetting('global_meta_image')) }}" />

        <!-- Twitter Card data -->
        <meta name="twitter:card" content="product" />
        <meta name="twitter:site" content="@publisher_handle" />
        <meta name="twitter:title" content="{{ getSetting('global_meta_title') }}" />
        <meta name="twitter:description" content="{{ getSetting('global_meta_description') }}" />
        <meta name="twitter:creator"
            content="@author_handle"/>
    <meta name="twitter:image" content="{{ uploadedAsset(getSetting('global_meta_image')) }}"/>

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ getSetting('global_meta_title') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ route('home') }}" />
    <meta property="og:image" content="{{ uploadedAsset(getSetting('global_meta_image')) }}" />
    <meta property="og:description" content="{{ getSetting('global_meta_description') }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" /> 
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
@endif

    <!-- head-scripts -->
    @include('frontend.default.inc.head-scripts')
    <!-- head-scripts -->

    <!--build:css-->
    @include('frontend.default.inc.css', ['localLang' => $localLang])
    <!-- endbuild --> 

    <!-- PWA  -->
    <meta name="theme-color" content="#6eb356"/>
    <link rel="apple-touch-icon" href="{{ staticAsset('/pwa.png') }}"/>
    <link rel="manifest" href="{{ staticAsset('/manifest.json') }}"/>
 
    <!-- recaptcha -->
    @if (getSetting('enable_recaptcha') == 1)
        {!! RecaptchaV3::initJs() !!}
    @endif
    <!-- recaptcha -->
    <style>
       .toast {
            position: fixed;
            bottom: -100px; /* Start off-screen */
            right: 20px;
            /* Background color */
            color: white; /* Text color */
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            z-index: 9999;
            transition: transform 0.5s ease, opacity 0.5s ease; /* Transition for slide and fade */
            opacity: 0; /* Initially hidden */
        }

        .toast.showing {
            transform: translateY(-150px); /* Slide up slightly */
            opacity: 1; /* Fully visible when showing */
        }
    </style>
</head>

<body>

    @php
        // for visitors to add to cart
        $tempValue = strtotime('now') . rand(10, 1000);
        $theTime = time() + 86400 * 365;
        if (!isset($_COOKIE['guest_user_id'])) {
            setcookie('guest_user_id', $tempValue, $theTime, '/'); // 86400 = 1 day
        }
        
    @endphp

    <!--preloader start-->
    <div id="preloader">
    <img src="{{ uploadedAsset(getSetting('navbar_logo')) }}" alt="preloader" class="img-fluid">
        <!-- <img src="{{ staticAsset('frontend/default/assets/img/embalage etcie.png') }}" alt="preloader" class="img-fluid"> -->
    </div>
    <!--preloader end-->

    <!--main content wrapper start-->
    <div class="main-wrapper">
        <!--header section start-->
        @if (isset($exception))
            @if ($exception->getStatusCode() != 503)
                @include('frontend.default.inc.header')
            @endif
        @else
            @include('frontend.default.inc.header')
        @endif
        <!--header section end-->

        <!--breadcrumb section start-->
        @yield('breadcrumb')
        <!--breadcrumb section end-->

        <!--offcanvas menu start-->
        @include('frontend.default.inc.offcanvas')
        <!--offcanvas menu end-->

        @yield('contents')

        <!-- modals -->
        @include('frontend.default.pages.partials.products.quickViewModal')
        <!-- modals -->


        <!--footer section start-->
        @if (isset($exception))
            @if ($exception->getStatusCode() != 503)
                @include('frontend.default.inc.footer')
                @include('frontend.default.inc.bottomToolbar')
            @endif
        @else
            @include('frontend.default.inc.footer')
            @include('frontend.default.inc.bottomToolbar') @endif
        <!--footer section end-->

    </div>
    {{-- <div id="toast" class="toast" style="display:none; position: fixed; bottom: 20px; left: 20px; z-index: 1000; background-color: #404a3d; padding: 10px; border-radius: 8px; color: white; width: 500px;">
        <div style="display: flex; align-items: center;">
            <!-- Image section -->
            <img id="toast-image" src="" alt="Notification Image" style="width: 100px; height: 100px; margin-right: 20px; border-radius: 5px;">
            
            <!-- Text content -->
            <div style="display: inline-block; color: white; width: calc(100% - 100px);">
                <strong id="toast-title" style="font-size: 16px; display: block;">Un client à commander ce produit</strong>
                <h6 id="toast-product" style="margin: 0; font-size: 14px; color: #f5a623;">Product Title</h6>
                <p id="toast-description" style="margin: 0; font-size: 12px;">This is a description of the notification.</p>
            </div>
        </div>
        <button id="toast-close" style="position: absolute; top: -2px; right: -2px; background: none; border: none; color: white; font-size: 20px; cursor: pointer;">&times;</button>
    </div>
    <script>
        // Function to show the toast
        function showToast() {
            var toast = $("#toast");
            toast.css('display','block');
            setTimeout(function() {
                toast.addClass('showing'); // Trigger the slide effect
            }, 10);
            /* $.ajax({
                url: "/notification",  // Use the localized ajax_url
                method: 'GET',
                success: function(data) {
                    console.log(data);
                },
                error: function() {
                    console.error('Failed to fetch data for toast notification.');
                    //showToast();  // Show a default toast if there's an error
                }
            }); */
            // After 10 seconds, fade out the toast
            setTimeout(function() {
                toast.removeClass('showing'); // Remove the slide effect
                // Fade out after the transition
                setTimeout(function() {
                    toast.css('display', 'none'); // Hide the toast completely
                }, 500); // Match this to your CSS transition duration
            }, 10000); // Display for 10 seconds

        }
        // Show toast message every 40 seconds
        setInterval(function() {
            showToast();
        }, 4000); // 40,000 ms = 40 seconds
        $('#toast-close').on('click', function() {
            $('#toast').removeClass('showing'); // Remove the slide effect
            setTimeout(function() {
                $('#toast').css('display', 'none'); // Hide the toast completely
            }, 500);
        });
        // Optional: Show the first toast immediately when the page loads
       /*  window.onload = function() {
            showToast();
        }; */
    </script> --}}
    <div id="toast" class="toast" style="display:none; position: fixed; bottom: 20px; left: 20px; z-index: 1000; background-color: #404a3d; padding: 10px; border-radius: 8px; color: white; width: 500px;">
        <div style="display: flex; align-items: center;">
            <!-- Image section -->
            <img id="toast-image" src="" alt="Notification Image" style="width: 100px; height: 100px; margin-right: 20px; border-radius: 5px;">
            
            <!-- Text content -->
            <div style="display: inline-block; color: white; width: calc(100% - 100px);">
                <strong id="toast-title" style="font-size: 16px; display: block;">Un client à commander ce produit</strong>
                <h6 id="toast-product" style="margin: 0; font-size: 14px; color: #f5a623;">Product Title</h6>
                <p id="toast-description" style="margin: 0; font-size: 12px;">This is a description of the notification.</p>
            </div>
        </div>
        <button id="toast-close" style="position: absolute; top: -2px; right: -2px; background: none; border: none; color: white; font-size: 20px; cursor: pointer;">&times;</button>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            var toastTimeout; // Declare a variable to hold the timeout reference
            function stripHtmlTags(input) {
                return input.replace(/<[^>]*>/g, '');  // Remove HTML tags
            }
            // Function to show the toast
            function showToast() {
                var toast = $("#toast");
                toast.css('display', 'block');
                $.ajax({
                    url: "/notification",  // Use the localized ajax_url
                    method: 'GET',
                    success: function(data) {
                        const productData = JSON.parse(data);
                        try {
                            
                                
                                $('#toast-image').attr('src', productData.thumbnail_image);
                                
                                $('#toast-product').text(productData.name); 
                                var description = productData.description;
                            
                                // Check if description is longer than 200 characters
                                if (description.length > 200) {
                                    description = description.substring(0, 200) + '...'; // Truncate and add ellipsis
                                }
                                var productUrl ="/products/"+ productData.slug;
                                if (productUrl) {
                                    description += ' <a href="' + productUrl + '" style="color: #f5a623;" target="_blank">Voir produit</a>';
                                }
                                $('#toast-description').html(description);  // Using product title or any field you want
                                // Trigger the slide-in effect
                                setTimeout(function() {
                                    toast.addClass('showing');
                                }, 10);
                        
                                // Clear any previous timeout to hide the toast
                                clearTimeout(toastTimeout);
                        
                                // Set a new timeout to hide the toast after 10 seconds
                                toastTimeout = setTimeout(function() {
                                    toast.removeClass('showing'); // Remove the slide effect
                                    setTimeout(function() {
                                        toast.css('display', 'none'); // Hide the toast completely
                                    }, 500); // Match this to your CSS transition duration
                                }, 10000); // Display for 10 seconds
                            
                            
                        } catch (e) {
                            console.error('Failed to parse response:', e);
                        }
                    },
                    error: function() {
                        console.error('Failed to fetch data for toast notification.');
                        //showToast();  // Show a default toast if there's an error
                    }
                }); 
                
            }
            
            // Show toast message every 40 seconds
            if (!localStorage.getItem('toastShown')) {
            // Show the toast immediately when the page loads
                showToast();

                // Set the flag in localStorage to indicate that the toast has been shown
                localStorage.setItem('toastShown', 'true');
            }

            // Show toast message every 2 minutes (120,000 ms) if it's been shown before
            setInterval(function() {
                // Only show toast if the user has seen it once
                if (localStorage.getItem('toastShown')) {
                    showToast();
                }
            }, 120000); // 120,000 ms = 2 minutes
        
            // Close button functionality
            $('#toast-close').on('click', function() {
                
                var toast = $('#toast');
                toast.removeClass('showing'); // Remove the slide effect immediately
                clearTimeout(toastTimeout); // Clear any timeout that hides the toast
                setTimeout(function() {
                    toast.css('display', 'none'); // Hide the toast completely
                }, 500);
            });
        
            
        });
    </script>
    <!--scroll bottom to top button start-->
    <button class="scroll-top-btn">
        <i class="fa-regular fa-hand-pointer"></i></button>
        <!--scroll bottom to top button end-->

        <!--build:js-->
        @include('frontend.default.inc.scripts')
        <!--endbuild-->

        <!--page's scripts-->
        @yield('scripts')
        <!--page's script-->

        <!--for pwa-->
        <script src="{{ url('/') . '/public/sw.js' }}"></script>
        <script>
            if (!navigator.serviceWorker?.controller) {
                navigator.serviceWorker?.register("./public/sw.js").then(function(reg) {
                    // console.log("Service worker has been registered for scope: " + reg.scope);
                });
            }
        </script>
        <!--for pwa-->

        </body>

        </html>
