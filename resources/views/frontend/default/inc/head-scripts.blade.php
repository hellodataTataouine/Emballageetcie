<script>
    var url = 'https://wati-integration-prod-service.clare.ai/v2/watiWidget.js?96951';
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.async = true;
    s.src = url;
    var options = {
    "enabled":true,
    "chatButtonSetting":{
        "backgroundColor":"#00e785",
        "ctaText":"Discute avec nous",
        "borderRadius":"25",
        "marginLeft": "17",
        "marginRight": "20",
        "marginBottom": "70",
        "ctaIconWATI":false,
        "position":"left"
    },
    "brandSetting":{
        "brandName":"Wati",
        "brandSubTitle":"undefined",
        "brandImg":"https://www.wati.io/wp-content/uploads/2023/04/Wati-logo.svg",
        "welcomeText":"Bonjour ! \nComment puis-je vous aider ?",
        "messageText":"Salut, %0A Que souhaitez-vous savoir à propos d'Emballage et Cie ?",
        "backgroundColor":"#00e785",
        "ctaText":"Discuter avec nous",
        "borderRadius":"25",
        "autoShow":false,
        "phoneNumber":"33344256066"
    }
    };
    s.onload = function() {
        CreateWhatsappChatWidget(options);
    };
    var x = document.getElementsByTagName('script')[0];
    x.parentNode.insertBefore(s, x);
</script>
<script>
    'use strict'

    var TT = TT || {};
    TT.localize = {
        buyNow: '{{ localize('Acheter maintenant') }}',
        addToCart: '{{ localize('Ajouter au panier') }}',
        outOfStock: '{{ localize('Rupture de stock') }}',
        addingToCart: '{{ localize('Ajout en cours....') }}',
        optionsAlert: '{{ localize('Veuillez choisir toutes les options disponibles') }}',
        applyCoupon: '{{ localize('Appliquer le coupon') }}',
        pleaseWait: '{{ localize('Veuillez patienter') }}',
    }

    TT.ProductSliders = () => {
        let quickViewProductSlider = new Swiper(".quickview-product-slider", {
            slidesPerView: 1,
            centeredSlides: true,
            speed: 700,
            loop: true,
            loopedSlides: 6,
        });
        let productThumbnailSlider = new Swiper(".product-thumbnail-slider", {
            slidesPerView: 4,
            speed: 700,
            loop: true,
            spaceBetween: 20,
            slideToClickedSlide: true,
            loopedSlides: 6,
            centeredSlides: true,
            breakpoints: {
                0: {
                    slidesPerView: 2,
                },
                380: {
                    slidesPerView: 3,
                },
                576: {
                    slidesPerView: 4,
                },
            },
        });
        if (quickViewProductSlider && quickViewProductSlider.length > 0) {
            quickViewProductSlider.forEach(function(item, index) {
                item.controller.control = productThumbnailSlider[index];
                productThumbnailSlider[index].controller.control = item;
            });
        } else {
            quickViewProductSlider.controller.control = productThumbnailSlider;
            productThumbnailSlider.controller.control = quickViewProductSlider;
        }
    }
</script>

