<style>


/* Modal */
.modal {
  display: none;
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgb(0,0,0);
  background-color: rgba(0,0,0,0.4);
}

.modal-content {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}





    </style>



@extends('frontend.default.layouts.master')

@section('title')
    {{ localize('Accueil') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')

<!-- Modal -->



    <!--hero section start-->
    @include('frontend.default.pages.partials.home.hero')
    <!--hero section end-->

    <!--category section start-->
      @include('frontend.default.pages.partials.home.category')
   <!--category section end-->

<!--banner section start-->
 @include('frontend.default.pages.partials.home.banners')
 <!--banner section end-->

 <!--metier section start-->
      @include('frontend.default.pages.partials.home.metiers')
  <!--metier section end-->

 <!--featured products start-->
    @include('frontend.default.pages.partials.home.featuredProducts')
    <!--featured products end-->

<!--trending products start-->
    @include('frontend.default.pages.partials.home.trendingProducts')
    <!--trending products end-->

<!--banner section start-->
    <!-- @include('frontend.default.pages.partials.home.bestDeals') -->
    <!--banner section end-->

<!--banner 2 section start-->
    @include('frontend.default.pages.partials.home.bannersTwo')
    <!--banner 2 section end-->

<!--feedback section start-->
    <!-- @include('frontend.default.pages.partials.home.feedback') -->
    <!--feedback section end-->
    
    <!--products listing start-->
  @include('frontend.default.pages.partials.home.products') 
  <!--products listing end-->

    @if (getSetting('enable_custom_product_section') == 1)
        <!-- start -->
        @include('frontend.default.pages.partials.home.customProductsSection')
        <!-- end -->
    @endif

<!--blog section start-->
<!--@include('frontend.default.pages.partials.home.blogs', ['blogs' => $blogs])-->
 <!--blog section end-->
    


<!-- Start of Modal -->

<div class="modal fade modal_newsletter_card" id="subscribe_popup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content" style="background-image: url({{ asset('public/uploads/media/loginpopup.jpg') }});">
			<button onclick="popup_modal_close()" type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; top: 0; right: 0;"><i class="bi bi-x-lg"></i></button>
			
			<div class="modal-body">
				<div class="newsletter-card">
               
   
					<h3>{{ localize('Merci de bien vouloir vous connecter afin de pouvoir passer vos commandes.') }}</h3>
				
					<div class="newsletter-form">
                    <button id="loginButton" class="btn btn-primary">{{ localize('Connexion') }}</button>
						<div class="newsletter_msg mt10"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /End of Modal/ -->





@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>

    <script>
        "use strict";

        // runs when the document is ready 
        $(document).ready(function() {
            @if (\App\Models\Location::where('is_published', 1)->count() > 1)
                notifyMe('info', '{{ localize('Sélectionner your location if not selected') }}');
            @endif
        });
        @if(Session::has('loginPopupOff'))
@else 
@if(!auth()->check())

        (function ($) {
		'use strict';
		var subscribePopupModal = new bootstrap.Modal(document.getElementById('subscribe_popup'), {
		  keyboard: false
		});
		
		subscribePopupModal.show();
		
		
	}(jQuery));

	function popup_modal_close() {
		$.ajax({
            type : 'POST',
            headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
			
            url: '{{ route('frontend.loginPopupOfff') }}',
           
		
			data: 'PopupOff=OFF',
			success: function (response){
                console.log('AJAX request successful');
                
            // Close modal after AJAX request is completed
            $('.modal').modal('hide');
            },
            error: function (xhr, status, error) {
            console.log('AJAX request failed:', error);
        }
		});
	}
 @endif 
 @endif 
    </script>
<script>
    $(document).ready(function() {
        // Check if the user is logged in or not, if not, show the login modal
        @if(!auth()->check())
            $('#loginModal').css('display', 'block'); // Show the modal
        @endif
        
        // Close the modal when the user clicks on the close button
        $('.close').on('click', function() {
            $('#loginModal').css('display', 'none'); // Hide the modal
        });
        
        // Redirect the user to the login page when clicking the login button
        $('#loginButton').on('click', function() {
            window.location.href = '{{ route('login') }}'; // Replace 'login' with your actual login route
        });
    });
</script>
@endsection


@push('scripts')



@endpush	

