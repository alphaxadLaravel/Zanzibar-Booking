<script src="{{asset('html/assets/vendor/jquery-3.5.1.min.js')}}"></script>
<script src="{{asset('html/assets/vendor/bootstrap-4.0.0/dist/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('html/assets/vendor/slick-1.8.1/slick.min.js')}}"></script>
<script src="{{asset('vendors/glow-cookies/glowCookies.js')}}"></script>
<script src="{{asset('vendors/magnific-popup/magnific-popup.js')}}"></script>
<script src="{{asset('html/assets/vendor/ion.rangeSlider-master/js/ion.rangeSlider.min.js')}}"></script>
<script src="{{asset('vendors/matchHeight.js')}}"></script>
<script src="{{asset('js/bootstrap-validate.js')}}"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.4.47/css/materialdesignicons.min.css"
rel="stylesheet">
<script src="{{asset('vendors/fotorama-4.6.4/fotorama.js')}}"></script>

<!-- Main JavaScript files for carousel functionality -->
<script src="{{asset('html/assets/js/main6782.js')}}"></script>
<script src="{{asset('html/assets/js/custom6782.js')}}"></script>

<script>
$(document).ready(function() {
    // Initialize carousel functionality
    ibookingCarouselS1();
    ibookingCarouselS2();
    
    // Initialize custom functionality
    if (typeof GmzBookingCustom !== 'undefined') {
        GmzBookingCustom.init();
    }
});

// Initialize gallery carousel after page is fully loaded
$(window).on('load', function() {
    // Small delay to ensure all CSS is applied
    setTimeout(function() {
        if (typeof ibookingCarouselWithLighbox === 'function') {
            ibookingCarouselWithLighbox();
        }
    }, 200);
});
</script>

<script src="{{asset('bot/scripts.js')}}"></script>