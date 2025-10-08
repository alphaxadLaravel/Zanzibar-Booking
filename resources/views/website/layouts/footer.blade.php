<footer class="site-footer pt-60 pb-40">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="widget widget-nav">
                        <h4 class="widget__title">Quick Links</h4>
                        <ul class="menu">
                            <li class="menu-item menu-item-1">
                                <a href="{{ route('contact-us') }}">Contact Us</a>
                            </li>
                            <li class="menu-item menu-item-2">
                                <a href="{{ route('page.show', 'become-a-partner') }}">Become A Partner</a>
                            </li>
                            <li class="menu-item menu-item-3">
                                <a href="{{ route('blog') }}">Blog</a>
                            </li>
                            <li class="menu-item menu-item-4">
                                <a href="{{ route('booking.lookup') }}">Look Up Booking</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="widget widget-nav">
                        <h4 class="widget__title">Company</h4>
                        <ul class="menu">
                            <li class="menu-item menu-item-2">
                                <a href="{{ route('page.show', 'about-us') }}">About Us</a>
                            </li>
                            <li class="menu-item menu-item-6">
                                <a href="{{ route('page.show', 'our-commitment') }}">Our Commitment</a>
                            </li>
                            <li class="menu-item menu-item-4">
                                <a href="{{ route('page.show', 'terms-conditions') }}">Terms &amp; Conditions</a>
                            </li>
                            <li class="menu-item menu-item-6">
                                <a href="{{ route('page.show', 'privacy-policy') }}">Privacy Policy</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="widget widget-nav">
                        <h4 class="widget__title">Our Services</h4>
                        <ul class="menu">
                            <li class="menu-item menu-item-1">
                                <a href="{{ route('hotels') }}">Hotels</a>
                            </li>
                            <li class="menu-item menu-item-2">
                                <a href="{{ route('tours') }}">Tours & Packages</a>
                            </li>
                            <li class="menu-item menu-item-3">
                                <a href="{{ route('activities') }}">Activities</a>
                            </li>
                            <li class="menu-item menu-item-4">
                                <a href="{{ route('cars') }}">Car Rentals</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="widget widget-select">
                        <h4 class="widget__title">BOOK & PAY VIA PESAPAL</h4>

                        <img src="https://mac-more.com/wp-content/uploads/2023/05/Pesapal_Logos_large.webp" style="height: 150px; width: 100%" class="img-fluid my-2" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom pt-30 pb-30">
        <div class="container">
            <div class="row my-3 align-items-bottom">
                <div class="col-md-3">
                    <img src="{{asset('images/atta.png')}}" style="height: 50px" class="img-fluid" />
                    <h6 class="text-white mt-2">
                        <strong>Endorsed by ATTA</strong>
                    </h6>
                </div>
                <div class="col-md-3">
                    <img src="{{asset('images/geog.png')}}" style="height: 50px" class="img-fluid" />
                    <h6 class="text-white mt-2">
                        <strong>Featured in National Geographic</strong>
                    </h6>
                </div>
                <div class="col-md-3">
                    <img src="{{asset('images/travel.png')}}" style="height: 50px" class="img-fluid" />
                    <h6 class="text-white mt-2">
                        <strong>World Travel Awards Winner</strong>
                    </h6>
                </div>
                <div class="col-md-3">
                    <img src="{{asset('images/trip.png')}}" style="height: 50px" class="img-fluid" />
                    <h6 class="text-white mt-2">
                        <strong>Rated Excellent by Tripadvisor</strong>
                    </h6>
                </div>
            </div>
            <div class="copyright text-center">
                © {{date('Y')}} Zanzibar Bookings - All rights reserved.
            </div>
            <ul class="social-footer mt-4">
                <li>
                    @if($systemSettings && $systemSettings->facebook_url)
                    <a href="{{ $systemSettings->facebook_url }}" target="_blank" title="Facebook" class="mx-3">
                        <i class="fab fa-facebook-f" style="font-size: 25px"></i>
                    </a>
                    @endif

                    @if($systemSettings && $systemSettings->instagram_url)
                    <a href="{{ $systemSettings->instagram_url }}" target="_blank" title="Instagram" class="mx-3">
                        <i class="fab fa-instagram" style="font-size: 25px"></i>
                    </a>
                    @endif

                    @if($systemSettings && $systemSettings->youtube_url)
                    <a href="{{ $systemSettings->youtube_url }}" target="_blank" title="Youtube" class="mx-3">
                        <i class="fab fa-youtube" style="font-size: 25px"></i>
                    </a>
                    @endif

                    @if($systemSettings && $systemSettings->tripadvisor_url)
                    <a href="{{ $systemSettings->tripadvisor_url }}" target="_blank" title="Tripadvisor" class="mx-3">
                        <i class="fab fa-tripadvisor" style="font-size: 25px"></i>
                    </a>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</footer>
<div class="whatsapp-widget">
    <div class="widget-circle" onclick="openWidget()">
        <svg xmlns="http://www.w3.org/2000/svg" width="6em" height="6em" viewBox="0 0 256 258">
            <rect width="256" height="258" fill="none" />
            <defs>
                <linearGradient id="logosWhatsappIcon0" x1="50%" x2="50%" y1="100%" y2="0%">
                    <stop offset="0%" stop-color="#1faf38" />
                    <stop offset="100%" stop-color="#60d669" />
                </linearGradient>
                <linearGradient id="logosWhatsappIcon1" x1="50%" x2="50%" y1="100%" y2="0%">
                    <stop offset="0%" stop-color="#f9f9f9" />
                    <stop offset="100%" stop-color="#fff" />
                </linearGradient>
            </defs>
            <path fill="url(#logosWhatsappIcon0)"
                d="M5.463 127.456c-.006 21.677 5.658 42.843 16.428 61.499L4.433 252.697l65.232-17.104a122.994 122.994 0 0 0 58.8 14.97h.054c67.815 0 123.018-55.183 123.047-123.01c.013-32.867-12.775-63.773-36.009-87.025c-23.23-23.25-54.125-36.061-87.043-36.076c-67.823 0-123.022 55.18-123.05 123.004" />
            <path fill="url(#logosWhatsappIcon1)"
                d="M1.07 127.416c-.007 22.457 5.86 44.38 17.014 63.704L0 257.147l67.571-17.717c18.618 10.151 39.58 15.503 60.91 15.511h.055c70.248 0 127.434-57.168 127.464-127.423c.012-34.048-13.236-66.065-37.3-90.15C194.633 13.286 162.633.014 128.536 0C58.276 0 1.099 57.16 1.071 127.416m40.24 60.376l-2.523-4.005c-10.606-16.864-16.204-36.352-16.196-56.363C22.614 69.029 70.138 21.52 128.576 21.52c28.3.012 54.896 11.044 74.9 31.06c20.003 20.018 31.01 46.628 31.003 74.93c-.026 58.395-47.551 105.91-105.943 105.91h-.042c-19.013-.01-37.66-5.116-53.922-14.765l-3.87-2.295l-40.098 10.513z" />
            <path fill="#fff"
                d="M96.678 74.148c-2.386-5.303-4.897-5.41-7.166-5.503c-1.858-.08-3.982-.074-6.104-.074c-2.124 0-5.575.799-8.492 3.984c-2.92 3.188-11.148 10.892-11.148 26.561c0 15.67 11.413 30.813 13.004 32.94c1.593 2.123 22.033 35.307 54.405 48.073c26.904 10.609 32.379 8.499 38.218 7.967c5.84-.53 18.844-7.702 21.497-15.139c2.655-7.436 2.655-13.81 1.859-15.142c-.796-1.327-2.92-2.124-6.105-3.716c-3.186-1.593-18.844-9.298-21.763-10.361c-2.92-1.062-5.043-1.592-7.167 1.597c-2.124 3.184-8.223 10.356-10.082 12.48c-1.857 2.129-3.716 2.394-6.9.801c-3.187-1.598-13.444-4.957-25.613-15.806c-9.468-8.442-15.86-18.867-17.718-22.056c-1.858-3.184-.199-4.91 1.398-6.497c1.431-1.427 3.186-3.719 4.78-5.578c1.588-1.86 2.118-3.187 3.18-5.311c1.063-2.126.531-3.986-.264-5.579c-.798-1.593-6.987-17.343-9.819-23.64" />
        </svg>
    </div>
    <div class="widget-popup" id="widgetPopup">
        <div class="widget-header">
            <span class="widget-title">Chat with us on WhatsApp</span>
            <button class="close-btn" onclick="closeWidget()">×</button>
        </div>
        <div class="widget-body">
           
            <div class="chat-bubble">
                <p>
                    Click the button below to chat with <br />
                    <b>Zanzibar Bookings</b> on WhatsApp.
                </p>
            </div>
            <a href="{{ $systemSettings && $systemSettings->whatsapp_url ? $systemSettings->whatsapp_url : 'https://wa.me/message/JMDWFIGBWX5TI1' }}" target="_blank" class="whatsapp-btn">Chat Now</a>
        </div>
    </div>
</div>