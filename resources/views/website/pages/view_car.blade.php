@extends('website.layouts.app')

@php
use Illuminate\Support\Str;
@endphp

@section('title')
{{ $car->seo_title ?: $car->title }} - Zanzibar Bookings
@endsection

@section('meta')
<meta name="description" content="{{ $car->seo_description ?: Str::limit(strip_tags($car->description), 160) }}">
@if($car->seo_keywords)
<meta name="keywords" content="{{ $car->seo_keywords }}">
@endif

<meta property="og:type" content="website">
<meta property="og:url" content="{{ request()->url() }}">
<meta property="og:title" content="{{ $car->seo_title ?: $car->title }}">
<meta property="og:description"
    content="{{ $car->seo_description ?: Str::limit(strip_tags($car->description), 160) }}">
<meta property="og:image"
    content="{{ $car->seo_image ? asset('storage/' . $car->seo_image) : ($car->cover_photo ? asset('storage/' . $car->cover_photo) : asset('logo.png')) }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ request()->url() }}">
<meta property="twitter:title" content="{{ $car->seo_title ?: $car->title }}">
<meta property="twitter:description"
    content="{{ $car->seo_description ?: Str::limit(strip_tags($car->description), 160) }}">
<meta property="twitter:image"
    content="{{ $car->seo_image ? asset('storage/' . $car->seo_image) : ($car->cover_photo ? asset('storage/' . $car->cover_photo) : asset('logo.png')) }}">
@endsection

@section('pages')
<style>
    /* Prevent image distortion during loading */
    .gallery img {
        width: 100% !important;
        height: 400px !important;
        object-fit: cover !important;
        display: block !important;
        background-color: #f5f5f5;
        transition: opacity 0.3s ease;
    }
    
    .gallery img[loading="lazy"] {
        opacity: 0;
    }
    
    .gallery img.loaded {
        opacity: 1;
    }
</style>
<section class="gallery">
    <div class="gmz-carousel-with-lightbox" data-count="{{ $car->photos->count() }}">
        @forelse($car->photos as $photo)
        <a href="{{ asset('storage/' . $photo->photo) }}">
            <img src="{{ asset('storage/' . $photo->photo) }}" 
                 alt="{{ $car->title }}" 
                 style="width: 100%; height: 400px; object-fit: cover; display: block;"
                 loading="lazy" />
        </a>
        @empty
        <a href="{{ $car->cover_photo ? asset('storage/' . $car->cover_photo) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop&crop=center' }}">
            <img src="{{ $car->cover_photo ? asset('storage/' . $car->cover_photo) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop&crop=center' }}" 
                 alt="{{ $car->title }}" 
                 style="width: 100%; height: 400px; object-fit: cover; display: block;"
                 loading="lazy" />
        </a>
        @endforelse
    </div>
</section>

@if($car->video_link)
<!-- Video Section -->
<section class="video-section" style="background: #f8f9fa; padding: 40px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="video-container" style="position: relative; width: 100%; height: 0; padding-bottom: 56.25%; background: #000; border-radius: 8px; overflow: hidden;">
                    @php
                        $videoUrl = $car->video_link;
                        $embedUrl = '';
                        
                        // YouTube
                        if (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false) {
                            if (strpos($videoUrl, 'youtu.be') !== false) {
                                $videoId = substr($videoUrl, strrpos($videoUrl, '/') + 1);
                            } else {
                                parse_str(parse_url($videoUrl, PHP_URL_QUERY), $query);
                                $videoId = $query['v'] ?? '';
                            }
                            $embedUrl = 'https://www.youtube.com/embed/' . $videoId . '?rel=0&modestbranding=1';
                        }
                        // Vimeo
                        elseif (strpos($videoUrl, 'vimeo.com') !== false) {
                            $videoId = substr($videoUrl, strrpos($videoUrl, '/') + 1);
                            $embedUrl = 'https://player.vimeo.com/video/' . $videoId . '?title=0&byline=0&portrait=0';
                        }
                        // Direct video file or other platforms
                        else {
                            $embedUrl = $videoUrl;
                        }
                    @endphp
                    
                    @if($embedUrl)
                        <iframe src="{{ $embedUrl }}" 
                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" 
                                frameborder="0" 
                                allowfullscreen
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                        </iframe>
                    @else
                        <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: white; text-align: center;">
                            <div>
                                <i class="fas fa-play-circle" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                                <p>Video preview not available</p>
                                <a href="{{ $videoUrl }}" target="_blank" class="btn btn-primary">Watch Video</a>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="text-center mt-3">
                    <p class="text-muted mb-0">
                        <i class="fas fa-video me-2"></i>
                        Promotional video for {{ $car->title }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="{{ route('index') }}">Home</a></li>
            <li><a href="{{ route('cars') }}">Cars</a></li>
            <li><span>{{ $car->title }}</span></li>
        </ul>
    </div>
</div>

<div class="container">
    <div class="row">
        {{-- ############## MAIN ############################# --}}
        <div class="col-lg-8 pb-5">
            <div class="hotel-star">
                <div class="star-rating">
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                </div>
            </div>
            <div class="d-flex align-items-center" style="gap: 16px;">
                <h2 class="post-title bold">
                    {{ $car->title }}
                </h2>
            </div>
            @if ($car->location)
            <p class="location">
                <i class="fal fa-map-marker-alt"></i> Available for pickup in {{ $car->location }}
            </p>
            @endif

            <div class="meta">
                <ul class="meta row  gy-2 mb-4" style="list-style: none; padding: 0; margin: 0;">
                    <li class="col-6 col-md-4 d-flex align-items-stretch mb-3 mb-md-0">
                        <div class="d-flex flex-nowrap align-items-center w-100 border rounded bg-white pl-3 py-2 h-100"
                            style="min-height:70px; border-color: #218080;">
                            <span
                                class="d-flex align-items-center justify-content-center rounded bg-light flex-shrink-0"
                                style="width:32px; height:32px; background: #e6f4f1 !important; margin-right: 18px;">
                                <i class="mdi mdi-car" style="color: #218080; font-size: 1.2rem;"></i>
                            </span>
                            <div class="flex-grow-1" style="min-width:0;">
                                <div class="fw-bold text-dark"
                                    style="font-size: 1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    {{ $car->category ? $car->category->category : 'Vehicle' }}
                                </div>
                                <div class="text-muted small" style="white-space:nowrap;">Type</div>
                            </div>
                        </div>
                    </li>
                    <li class="col-6 col-md-4 d-flex align-items-stretch mb-3 mb-md-0">
                        <div class="d-flex flex-nowrap align-items-center w-100 border rounded bg-white px-3 py-2 h-100"
                            style="min-height:70px; border-color: #218080;">
                            <span
                                class="d-flex align-items-center justify-content-center rounded bg-light flex-shrink-0"
                                style="width:32px; height:32px; background: #e6f4f1 !important; margin-right: 18px;">
                                <i class="mdi mdi-currency-usd" style="color: #218080; font-size: 1.2rem;"></i>
                            </span>
                            <div class="flex-grow-1" style="min-width:0;">
                                <div class="fw-bold text-dark"
                                    style="font-size: 1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    USD {{ number_format($car->base_price, 2) }}/day
                                </div>
                                <div class="text-muted small" style="white-space:nowrap;">Price</div>
                            </div>
                        </div>
                    </li>
                    <li class="col-6 col-md-4 d-flex align-items-stretch mb-3 mb-md-0">
                        <div class="d-flex flex-nowrap align-items-center w-100 border rounded bg-white px-3 py-2 h-100"
                            style="min-height:70px; border-color: #218080;">
                            <span
                                class="d-flex align-items-center justify-content-center rounded bg-light flex-shrink-0"
                                style="width:32px; height:32px; background: #e6f4f1 !important; margin-right: 18px;">
                                <i class="mdi mdi-star" style="color: #218080; font-size: 1.2rem;"></i>
                            </span>
                            <div class="flex-grow-1" style="min-width:0;">
                                <div class="fw-bold text-dark"
                                    style="font-size: 1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    {{ $car->ratings ? number_format($car->ratings, 1) : '5.0' }}/5
                                </div>
                                <div class="text-muted small" style="white-space:nowrap;">Rating</div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <section class="description">
                <h4 class="section-title">Vehicle Description</h4>
                <div class="section-content">
                    <p>{{ $car->description ?: 'Experience the ultimate comfort and reliability with our ' . $car->title . ' rental. This premium vehicle is perfect for exploring Zanzibar\'s diverse landscapes, from pristine beaches to historic Stone Town. With its modern amenities and reliable performance, it\'s the ideal choice for families and groups seeking both adventure and comfort.' }}</p>
                </div>
            </section>

            <section class="feature">
                <h4 class="section-title mb-4">Vehicle Features & Amenities</h4>
                <div class="section-content">
                    @if($car->features->count() > 0)
                    <div class="row">
                        @foreach($car->features->chunk(ceil($car->features->count() / 2)) as $chunk)
                        <div class="col-md-6">
                            <ul class="feature-list" style="list-style: none; padding: 0;">
                                @foreach($chunk as $feature)
                                <li class="mb-2">
                                    <i class="mdi {{ $feature->icon ?: 'mdi-check-circle' }} text-success me-2"></i>
                                    {{ $feature->name }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="row">
                        <div class="col-12">
                            <p class="text-muted">No features available for this vehicle.</p>
                        </div>
                    </div>
                    @endif
                </div>
            </section>
            <hr>
            <section class="feature">
                <h4 class="section-title mb-4">Rental Terms & Conditions</h4>
                <div class="section-content">
                    <div class="accordion" id="accordionTerms">
                        <div class="card">
                            <div class="card-header" id="heading1">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                        <i class="fa fa-info-circle mr-2"></i>
                                        Driver Requirements
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse1" class="collapse show" aria-labelledby="heading1" data-parent="#accordionTerms">
                                <div class="card-body">
                                    <ul>
                                        <li>Valid driving license (minimum 2 years)</li>
                                        <li>International driving permit required for non-Tanzanian citizens</li>
                                        <li>Minimum age: 25 years</li>
                                        <li>Credit card for security deposit</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header" id="heading2">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                        <i class="fa fa-calendar mr-2"></i>
                                        Rental Duration & Pricing
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse2" class="collapse" aria-labelledby="heading2" data-parent="#accordionTerms">
                                <div class="card-body">
                                    <ul>
                                        <li>Minimum rental period: 1 day</li>
                                        <li>Daily rate: $120 per day</li>
                                        <li>Weekly rate: $750 (7 days)</li>
                                        <li>Monthly rate: $2,800 (30 days)</li>
                                        <li>Additional driver: $10 per day</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header" id="heading3">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                        <i class="fa fa-shield-alt mr-2"></i>
                                        Insurance & Coverage
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse3" class="collapse" aria-labelledby="heading3" data-parent="#accordionTerms">
                                <div class="card-body">
                                    <ul>
                                        <li>Comprehensive insurance included</li>
                                        <li>Third-party liability coverage</li>
                                        <li>Collision damage waiver available</li>
                                        <li>Personal accident insurance</li>
                                        <li>24/7 roadside assistance</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="post-comment parent-form" id="gmz-comment-section">
                <div class="comment-form-wrapper">
                    <form action="https://www.zanzibarbookings.com/add-comment" class="comment-form form-sm gmz-form-action form-add-post-comment" method="post" data-reload-time="1000">
                        <h3 class="comment-title mb-4">Leave a Review</h3>
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <input type="text" name="comment_name" class="form-control" placeholder="Your Name *" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <input type="email" name="comment_email" class="form-control" placeholder="Your Email *" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <textarea name="comment_content" class="form-control" rows="5" placeholder="Your Review *" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="siderbar-single">

                {{-- Car Booking Form --}}
                <div class="card mb-4 car-booking-card rounded" style="box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                    <div class="card-header" style="background: #2e8b57; color: white; padding: 15px;">
                        <h5 class="mb-0" style="font-weight: 600;">
                            <i class="fas fa-car me-2"></i>Book This Vehicle
                        </h5>
                    </div>
                    
                    {{-- Pricing Display --}}
                    <div class="pricing-section" style="background: #f8f9fa; padding: 20px; border-bottom: 1px solid #e9ecef;">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="pricing-item">
                                    <h4 class="price-amount mb-1" style="color: #2e8b57; font-weight: 700; font-size: 1.6rem;">${{ number_format($car->base_price, 0) }}</h4>
                                    <p class="price-label mb-0" style="color: #666; font-size: 13px; font-weight: 500;">Daily Rate</p>
                                    <small class="text-muted" style="font-size: 11px;">Per Day</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="pricing-item">
                                    <h4 class="price-amount mb-1" style="color: #2e8b57; font-weight: 700; font-size: 1.6rem;">${{ number_format($car->base_price * 7 * 0.9, 0) }}</h4>
                                    <p class="price-label mb-0" style="color: #666; font-size: 13px; font-weight: 500;">Weekly Rate</p>
                                    <small class="text-muted" style="font-size: 11px;">7 Days (10% off)</small>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            <small class="text-muted" style="font-size: 12px;">
                                <i class="fas fa-info-circle me-1"></i>
                                Includes insurance, GPS & 24/7 support
                            </small>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <form class="car-booking-form" id="car-booking-form">
                            <div class="gmz-loader">
                                <div class="loader-inner">
                                    <div class="spinner-grow text-info align-self-center loader-lg"></div>
                                </div>
                            </div>
                            
                            {{-- Form Section Title --}}
                            <div class="form-section-title mb-4">
                                <h6 class="mb-0" style="color: #333; font-weight: 600; font-size: 16px;">
                                    <i class="fas fa-calendar-alt me-2" style="color: #2e8b57;"></i>Rental Details
                                </h6>
                                <hr style="margin: 8px 0 0 0; border-color: #e9ecef;">
                            </div>
                            
                            <div class="form">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="field-wrapper input">
                                            <label for="pickup_date">PICKUP DATE</label>
                                            <i class="fal fa-calendar-alt"></i>
                                            <input id="pickup_date" name="pickup_date" type="date" class="form-control gmz-validation" data-validation="required" min="">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="field-wrapper input">
                                            <label for="return_date">RETURN DATE</label>
                                            <i class="fal fa-calendar-alt"></i>
                                            <input id="return_date" name="return_date" type="date" class="form-control gmz-validation" data-validation="required" min="">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="field-wrapper input">
                                            <label for="pickup_location">PICKUP LOCATION</label>
                                            <i class="fal fa-map-marker-alt"></i>
                                            <select id="pickup_location" name="pickup_location" class="form-control gmz-validation" data-validation="required">
                                                <option value="">Select pickup location</option>
                                                <option value="stone_town">Stone Town</option>
                                                <option value="airport">Zanzibar Airport</option>
                                                <option value="nungwi">Nungwi</option>
                                                <option value="paje">Paje</option>
                                                <option value="jambiani">Jambiani</option>
                                                <option value="other">Other Location</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="field-wrapper input">
                                            <label for="return_location">RETURN LOCATION</label>
                                            <i class="fal fa-map-marker-alt"></i>
                                            <select id="return_location" name="return_location" class="form-control gmz-validation" data-validation="required">
                                                <option value="">Select return location</option>
                                                <option value="stone_town">Stone Town</option>
                                                <option value="airport">Zanzibar Airport</option>
                                                <option value="nungwi">Nungwi</option>
                                                <option value="paje">Paje</option>
                                                <option value="jambiani">Jambiani</option>
                                                <option value="other">Other Location</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="field-wrapper input">
                                            <label for="pickup_time">PICKUP TIME</label>
                                            <i class="fal fa-clock"></i>
                                            <select id="pickup_time" name="pickup_time" class="form-control gmz-validation" data-validation="required">
                                                <option value="">Select time</option>
                                                <option value="08:00">8:00 AM</option>
                                                <option value="09:00">9:00 AM</option>
                                                <option value="10:00">10:00 AM</option>
                                                <option value="11:00">11:00 AM</option>
                                                <option value="12:00">12:00 PM</option>
                                                <option value="13:00">1:00 PM</option>
                                                <option value="14:00">2:00 PM</option>
                                                <option value="15:00">3:00 PM</option>
                                                <option value="16:00">4:00 PM</option>
                                                <option value="17:00">5:00 PM</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="field-wrapper input">
                                            <label for="return_time">RETURN TIME</label>
                                            <i class="fal fa-clock"></i>
                                            <select id="return_time" name="return_time" class="form-control gmz-validation" data-validation="required">
                                                <option value="">Select time</option>
                                                <option value="08:00">8:00 AM</option>
                                                <option value="09:00">9:00 AM</option>
                                                <option value="10:00">10:00 AM</option>
                                                <option value="11:00">11:00 AM</option>
                                                <option value="12:00">12:00 PM</option>
                                                <option value="13:00">1:00 PM</option>
                                                <option value="14:00">2:00 PM</option>
                                                <option value="15:00">3:00 PM</option>
                                                <option value="16:00">4:00 PM</option>
                                                <option value="17:00">5:00 PM</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="field-wrapper input">
                                            <label for="need_driver">NEED DRIVER?</label>
                                            <i class="fal fa-user"></i>
                                            <select id="need_driver" name="need_driver" class="form-control gmz-validation" data-validation="required">
                                                <option value="">Select option</option>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- Price Calculation Section --}}
                                <div class="price-section-title mb-3 mt-4">
                                    <h6 class="mb-0" style="color: #333; font-weight: 600; font-size: 16px;">
                                        <i class="fas fa-calculator me-2" style="color: #2e8b57;"></i>Price Calculation
                                    </h6>
                                    <hr style="margin: 8px 0 0 0; border-color: #e9ecef;">
                                </div>
                                
                                <div class="price-calculation my-3 p-3" style="background: #e8f5e8; border-radius: 8px; border-left: 4px solid #28a745;">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="calculation-item">
                                                <span class="label">Daily Rate:</span>
                                                <span class="value" id="daily-rate-display">$120</span>
                                            </div>
                                            <div class="calculation-item">
                                                <span class="label">Rental Days:</span>
                                                <span class="value" id="rental-days-count">0</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="calculation-item">
                                                <span class="label">Additional Driver:</span>
                                                <span class="value" id="additional-driver-cost">$0</span>
                                            </div>
                                            <div class="calculation-item">
                                                <span class="label">Subtotal:</span>
                                                <span class="value" id="subtotal-price">$0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="margin: 10px 0;">
                                    <div class="total-price text-center">
                                        <h5 class="mb-0">
                                            <span class="label">Total Price: </span>
                                            <span class="value text-success" id="total-price" style="font-size: 1.5rem; font-weight: 700;">$0</span>
                                        </h5>
                                        <small class="text-muted">(Daily Rate × Days) + Additional Driver Fee</small>
                                    </div>
                                </div>

                                <div class="gmz-message"></div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('confirm-booking', ['deal_id' => $car->deal_id]) }}" class="btn btn-success w-100" style="
                                        text-align: center;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        padding: 12px 24px;
                                        font-size: 16px;
                                        font-weight: 600;
                                        background: #2e8b57;
                                        border: none;
                                        border-radius: 8px;
                                        transition: all 0.3s ease;
                                        box-shadow: 0 2px 4px rgba(46, 139, 87, 0.2);
                                        text-decoration: none;
                                    ">
                                        <i class="fas fa-car me-2"></i>
                                        Book This Vehicle
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="card mb-4">
                    <div class="card-header" style="background: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                        <h6 class="mb-0" style="color: #333; font-weight: 600;">
                            <i class="fas fa-phone me-2" style="color: #2e8b57;"></i>Need Help?
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="contact-info">
                            <div class="contact-item mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-phone text-success me-3" style="width: 20px;"></i>
                                    <div>
                                        <strong>Phone:</strong><br>
                                        <a href="tel:+255777123456">+255 777 123 456</a>
                                    </div>
                                </div>
                            </div>
                            <div class="contact-item mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-envelope text-success me-3" style="width: 20px;"></i>
                                    <div>
                                        <strong>Email:</strong><br>
                                        <a href="mailto:cars@zanzibarbookings.com">cars@zanzibarbookings.com</a>
                                    </div>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock text-success me-3" style="width: 20px;"></i>
                                    <div>
                                        <strong>Available:</strong><br>
                                        24/7 Support
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Car Booking Form Styles */
    .car-booking-card {
        border: none;
        overflow: hidden;
    }

    .car-booking-card .card-header {
        border-bottom: none;
    }

    .car-booking-card .btn-success:hover {
        background: #228b22;
        border-color: #228b22;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15);
    }

    .car-booking-form .field-wrapper {
        position: relative;
        margin-bottom: 15px;
    }

    .car-booking-form .field-wrapper input,
    .car-booking-form .field-wrapper select,
    .car-booking-form .field-wrapper textarea {
        padding: 8px 12px 8px 35px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s ease;
        width: 100%;
    }

    .car-booking-form .field-wrapper input:focus,
    .car-booking-form .field-wrapper select:focus,
    .car-booking-form .field-wrapper textarea:focus {
        border-color: #2e8b57;
        box-shadow: 0 0 0 0.2rem rgba(46, 139, 87, 0.25);
        outline: none;
    }

    .car-booking-form label {
        font-size: 11px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .car-booking-form .field-wrapper i {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
        font-size: 12px;
    }

    .car-booking-form .gmz-loader {
        display: none;
        text-align: center;
        padding: 20px;
    }

    .car-booking-form .gmz-message {
        margin-top: 15px;
        padding: 10px;
        border-radius: 4px;
        display: none;
    }

    .car-booking-form .gmz-message.success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .car-booking-form .gmz-message.error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .calculation-item {
        margin-bottom: 8px;
        font-size: 13px;
    }

    .calculation-item .label {
        color: #666;
        font-weight: 500;
    }

    .calculation-item .value {
        color: #333;
        font-weight: 600;
        float: right;
    }

    .contact-info .contact-item {
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 15px;
    }

    .contact-info .contact-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .contact-info a {
        color: #2e8b57;
        text-decoration: none;
    }

    .contact-info a:hover {
        text-decoration: underline;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .car-booking-form .field-wrapper {
            margin-bottom: 12px;
        }
        
        .car-booking-form .field-wrapper input,
        .car-booking-form .field-wrapper select {
            padding: 10px 12px 10px 35px;
            font-size: 16px;
        }
        
        .pricing-section {
            padding: 15px !important;
        }
        
        .price-calculation {
            padding: 15px !important;
        }
    }
</style>

<script>
    // Set minimum date to today
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('pickup_date').min = today;
        document.getElementById('return_date').min = today;
        
        // Set default pickup date to today
        document.getElementById('pickup_date').value = today;
        
        // Calculate initial price
        calculateCarPrice();
    });

    // Price calculation function
    function calculateCarPrice() {
        const pickupDate = document.getElementById('pickup_date').value;
        const returnDate = document.getElementById('return_date').value;
        const additionalDrivers = parseInt(document.getElementById('additional_driver').value) || 0;
        
        if (!pickupDate || !returnDate) {
            resetCarPriceDisplay();
            return;
        }
        
        const pickup = new Date(pickupDate);
        const returnDateObj = new Date(returnDate);
        
        if (returnDateObj <= pickup) {
            resetCarPriceDisplay();
            return;
        }
        
        const timeDiff = returnDateObj.getTime() - pickup.getTime();
        const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
        
        const dailyRate = {{ $car->base_price }};
        const additionalDriverRate = 10;
        
        const basePrice = daysDiff * dailyRate;
        const additionalDriverCost = additionalDrivers * additionalDriverRate * daysDiff;
        const totalPrice = basePrice + additionalDriverCost;
        
        // Update display
        document.getElementById('rental-days-count').textContent = daysDiff;
        document.getElementById('additional-driver-cost').textContent = `$${additionalDriverCost}`;
        document.getElementById('subtotal-price').textContent = `$${basePrice}`;
        document.getElementById('total-price').textContent = `$${totalPrice}`;
        
        // Update daily rate display based on days
        if (daysDiff >= 7) {
            document.getElementById('daily-rate-display').textContent = '${{ number_format($car->base_price * 0.9, 0) }} (Weekly)';
        } else {
            document.getElementById('daily-rate-display').textContent = '${{ number_format($car->base_price, 0) }}';
        }
    }

    function resetCarPriceDisplay() {
        document.getElementById('rental-days-count').textContent = '0';
        document.getElementById('additional-driver-cost').textContent = '$0';
        document.getElementById('subtotal-price').textContent = '$0';
        document.getElementById('total-price').textContent = '$0';
        document.getElementById('daily-rate-display').textContent = '${{ number_format($car->base_price, 0) }}';
    }

    // Event listeners for price calculation
    document.getElementById('pickup_date').addEventListener('change', function() {
        const pickupDate = this.value;
        const returnDateInput = document.getElementById('return_date');
        
        if (pickupDate) {
            returnDateInput.min = pickupDate;
            if (returnDateInput.value && returnDateInput.value <= pickupDate) {
                returnDateInput.value = '';
            }
        }
        calculateCarPrice();
    });

    document.getElementById('return_date').addEventListener('change', calculateCarPrice);
    document.getElementById('additional_driver').addEventListener('change', calculateCarPrice);
    
    // Form submission
    document.getElementById('car-booking-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(this);
        const bookingData = {
            pickup_date: formData.get('pickup_date'),
            return_date: formData.get('return_date'),
            pickup_location: formData.get('pickup_location'),
            return_location: formData.get('return_location'),
            pickup_time: formData.get('pickup_time'),
            return_time: formData.get('return_time'),
            drivers: formData.get('drivers'),
            additional_driver: formData.get('additional_driver')
        };
        
        // Show loading
        const loader = document.querySelector('.gmz-loader');
        loader.style.display = 'block';
        
        // Simulate booking process (replace with actual API call)
        setTimeout(() => {
            loader.style.display = 'none';
            alert('Car rental booking confirmed! You will receive a confirmation email shortly with pickup details.');
            
            // Reset form
            document.getElementById('car-booking-form').reset();
            resetCarPriceDisplay();
            
            // Reset dates
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('pickup_date').value = today;
        }, 2000);
    });
    
    // Handle image loading to prevent distortion
    document.addEventListener('DOMContentLoaded', function() {
        const images = document.querySelectorAll('.gallery img');
        
        images.forEach(img => {
            // Set initial dimensions to prevent layout shift
            img.style.width = '100%';
            img.style.height = '400px';
            img.style.objectFit = 'cover';
            img.style.display = 'block';
            img.style.backgroundColor = '#f5f5f5';
            
            // Add loaded class when image loads
            img.addEventListener('load', function() {
                this.classList.add('loaded');
                this.style.opacity = '1';
            });
            
            // Handle error case
            img.addEventListener('error', function() {
                this.style.opacity = '1';
                this.style.backgroundColor = '#f0f0f0';
            });
        });
    });
</script>

<!-- Nearby Cars Section -->
<section class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h3 class="section-title mb-4">Cars Near By</h3>
            <div class="row">
                @forelse($nearbyCars as $nearbyCar)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="position-relative">
                            <img src="{{ $nearbyCar->cover_photo ? asset('storage/' . $nearbyCar->cover_photo) : 'https://images.unsplash.com/photo-1549317336-206569e8475c?w=400&h=250&fit=crop&crop=center' }}" 
                                 class="card-img-top" 
                                 alt="{{ $nearbyCar->title }}"
                                 style="height: 200px; object-fit: cover;">
                            @if($nearbyCar->is_featured)
                            <span class="badge bg-success position-absolute" style="top: 10px; left: 10px;">Featured</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $nearbyCar->title }}</h5>
                            <p class="card-text text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $nearbyCar->location }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-success fw-bold fs-5">USD {{ number_format($nearbyCar->base_price, 2) }}</span>
                                    <small class="text-muted">/day</small>
                                </div>
                                <a href="{{ route('view-car', ['id' => $hashids->encode($nearbyCar->id)]) }}" class="btn btn-primary btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-4">
                        <p class="text-muted">No nearby cars found</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endsection
