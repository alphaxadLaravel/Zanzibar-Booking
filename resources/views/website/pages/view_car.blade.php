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
<meta property="og:description" content="{{ $car->seo_description ?: Str::limit(strip_tags($car->description), 160) }}">
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

<section class="gallery">
    <div class="gmz-carousel-with-lightbox" data-count="{{ $car->photos->count() }}">
        @forelse($car->photos as $photo)
        <a href="{{ asset('storage/' . $photo->photo) }}">
            <img src="{{ asset('storage/' . $photo->photo) }}" alt="{{ $car->title }}" class="gallery-img"
                style="width: 100%; height: 400px; object-fit: cover; display: block; opacity: 0; transition: opacity 0.5s;"
                loading="lazy" />
        </a>
        @empty
        <a
            href="{{ $car->cover_photo ? asset('storage/' . $car->cover_photo) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop&crop=center' }}">
            <img src="{{ $car->cover_photo ? asset('storage/' . $car->cover_photo) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop&crop=center' }}"
                alt="{{ $car->title }}" class="gallery-img"
                style="width: 100%; height: 400px; object-fit: cover; display: block; opacity: 0; transition: opacity 0.5s;"
                loading="lazy" />
        </a>
        @endforelse
    </div>
</section>
<script>
    // Prevent burst/flash on page opening, fade in after page load
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            document.querySelectorAll('.gallery-img').forEach(function(img) {
                img.style.opacity = '1';
            });
        }, 100); // slight delay to ensure page is ready
    });
</script>

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
            <div class="card">
                <div class="card-body">
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
                    <hr>



                    <section class="description">
                        <h4 class="section-title">Vehicle Overview</h4>
                        <div class="section-content">
                            <p>{!! $car->description !!}</p>
                        </div>
                    </section>
                    <hr>

                    <section class="feature">
                        <h4 class="section-title">Vehicle Features & Amenities</h4>
                        <div class="section-content">
                            <div class="d-flex flex-wrap" style="gap: 10px;">
                                @forelse($car->features as $feature)
                                <div class="facility-card d-flex align-items-center px-3 py-2 mb-2"
                                    style="background: #fff; border-radius: 6px; border: 1px solid #e0e0e0; min-height: 38px; flex: 0 0 auto; min-width: 140px; max-width: 220px;">
                                    @if($feature->icon)
                                    <i class="mdi {{ $feature->icon }} me-2"
                                        style="font-size: 1.2rem; color: #2e8b57; width: 20px; text-align: center;"></i>
                                    @else
                                    <i class="mdi mdi-check-circle me-2"
                                        style="font-size: 1.2rem; color: #2e8b57; width: 20px; text-align: center;"></i>
                                    @endif
                                    <span style="font-size: 13px; font-weight: 500; color: #333; line-height: 1.3;">{{
                                        $feature->name }}</span>
                                </div>
                                @empty
                                <div class="text-muted" style="font-size: 14px;">No features listed.</div>
                                @endforelse
                            </div>
                        </div>
                    </section>

                    <hr>

                    @if($car->video_link)
                    <section class="video-section">
                        <h4 class="section-title">Video</h4>
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <div class="video-container"
                                        style="position: relative; width: 100%; height: 0; padding-bottom: 56.25%; background: #000; border-radius: 8px; overflow: hidden;">
                                        @php
                                        $videoUrl = $car->video_link;
                                        $embedUrl = '';

                                        // YouTube
                                        if (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be')
                                        !==
                                        false) {
                                        if (strpos($videoUrl, 'youtu.be') !== false) {
                                        $videoId = substr($videoUrl, strrpos($videoUrl, '/') + 1);
                                        } else {
                                        parse_str(parse_url($videoUrl, PHP_URL_QUERY), $query);
                                        $videoId = $query['v'] ?? '';
                                        }
                                        $embedUrl = 'https://www.youtube.com/embed/' . $videoId .
                                        '?rel=0&modestbranding=1';
                                        }
                                        // Vimeo
                                        elseif (strpos($videoUrl, 'vimeo.com') !== false) {
                                        $videoId = substr($videoUrl, strrpos($videoUrl, '/') + 1);
                                        $embedUrl = 'https://player.vimeo.com/video/' . $videoId .
                                        '?title=0&byline=0&portrait=0';
                                        }
                                        // Direct video file or other platforms
                                        else {
                                        $embedUrl = $videoUrl;
                                        }
                                        @endphp

                                        @if($embedUrl)
                                        <iframe src="{{ $embedUrl }}"
                                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;"
                                            frameborder="0" allowfullscreen
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                                        </iframe>
                                        @else
                                        <div
                                            style="display: flex; align-items: center; justify-content: center; height: 100%; color: white; text-align: center;">
                                            <div>
                                                <i class="fas fa-play-circle"
                                                    style="font-size: 3rem; margin-bottom: 1rem;"></i>
                                                <p>Video preview not available</p>
                                                <a href="{{ $videoUrl }}" target="_blank" class="btn btn-primary">Watch
                                                    Video</a>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    @endif
                    <hr>

                    <section class="description">
                        <h4 class="section-title">Rental Policies</h4>
                        <div class="section-content">
                            <p>
                                {!! $car->policies ?? '<strong>Driver Requirements:</strong> Valid driving license
                                (minimum 2 years experience), International driving permit required for non-Tanzanian
                                citizens, Minimum age 25 years, Credit card for security deposit.<br><br><strong>Rental
                                    Terms:</strong> Minimum rental period 1 day, Comprehensive insurance included, 24/7
                                roadside assistance, Full tank fuel policy.<br><br><strong>Cancellation:</strong> Free
                                cancellation up to 24 hours before pickup.' !!}
                            </p>
                        </div>
                    </section>
                    <hr>

                    <section class="map">
                        <h4 class="section-title mb-4">Pickup Location On Map</h4>
                        <div id="address-map-container" style="width: 100%; height: 400px">
                            @if($car->lat && $car->long)
                            <iframe width="100%" height="100%" frameborder="0" style="border:0; border-radius: 8px;"
                                src="https://www.google.com/maps?q={{ $car->lat }},{{ $car->long }}&output=embed"
                                allowfullscreen aria-hidden="false" tabindex="0" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                            @else
                            <iframe width="100%" height="100%" frameborder="0" style="border:0; border-radius: 8px;"
                                src="https://www.google.com/maps?q={{ $car->location }}&output=embed" allowfullscreen
                                aria-hidden="false" tabindex="0" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                            @endif
                        </div>
                    </section>

                </div>
            </div>
            <hr>

            <div class="reviews-section mt-4" id="review-section">
                <div class="d-flex justify-content-between align-items-center my-3">
                    <h4 class="comment-count">Reviews for this Vehicle</h4>

                    <div class="d-flex justify-content-center">
                        <a href="#leaveReviewModal" class="btn btn-primary btn-lg fw-semibold gmz-box-popup"
                            data-effect="mfp-zoom-in">
                            <i class="fa fa-pen"></i> Leave a Review
                        </a>
                    </div>
                    <div class="white-popup mfp-with-anim mfp-hide gmz-popup-form" id="leaveReviewModal">
                        <div class="popup-inner">
                            <h4 class="popup-title" id="leaveReviewModalLabel">Leave a Review</h4>
                            <div class="popup-content">
                                <div class="comment-form-wrapper">
                                    <form action="{{ route('deals.reviews.store', $car->id) }}"
                                        class="comment-form form-sm" method="post">
                                        @csrf

                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="review-title" class="form-label fw-semibold">Review
                                                        Title *</label>
                                                    <input id="review-title" type="text" name="review_title"
                                                        class="form-control" placeholder="Enter your review title"
                                                        required />
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-2">
                                                <label for="comment_rating mb-2"
                                                    class="form-label fw-semibold me-3 mb-0 d-flex align-items-center justify-content-between">
                                                    <span>
                                                        Your Rating *
                                                    </span>
                                                    <span id="star-display" class="ms-3"
                                                        style="font-size: 1.3rem; color: #ffc107;"></span>

                                                </label>
                                                <select id="rating" name="rating" class="form-select form-control"
                                                    required>
                                                    <option value="">Select rating</option>
                                                    <option value="1">1 Star</option>
                                                    <option value="2">2 Stars</option>
                                                    <option value="3">3 Stars</option>
                                                    <option value="4">4 Stars</option>
                                                    <option value="5">5 Stars</option>
                                                </select>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="review-content" class="form-label fw-semibold">Your
                                                        Review *</label>
                                                    <textarea id="review-content" name="review_content"
                                                        placeholder="Share your experience with this vehicle..."
                                                        class="form-control" required rows="5"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-grid mt-4">
                                            <button type="submit"
                                                class="btn btn-primary btn-lg text-uppercase fw-semibold">
                                                Submit Review
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Reviews List -->
                <div class="reviews-list" id="reviews-list">
                    @if(isset($paginatedReviews) && $paginatedReviews->count() > 0)
                    @foreach($paginatedReviews as $review)
                    <div class="review-item d-flex mb-4 p-3"
                        style="background: #f8f9fa; border-radius: 12px; border: 1px solid #e9ecef;">
                        <div class="review-avatar" style="flex-shrink: 0; margin-right: 2rem;">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($review->reviewer_name) }}&background=1C8D83&color=fff&size=60"
                                alt="{{ $review->reviewer_name }}"
                                style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; display: block;">
                        </div>
                        <div class="review-content flex-grow-1">
                            <div class="review-header d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="reviewer-name mb-1"
                                        style="font-size: 16px; font-weight: 600; color: #333;">{{
                                        $review->reviewer_name }}</h5>
                                    <div class="review-rating mb-1" style="font-size: 0.85rem;">
                                        {!! $review->star_rating !!}
                                    </div>
                                </div>
                                <small class="text-muted">{{ $review->formatted_date }}</small>
                            </div>
                            <h6 class="review-title mb-2" style="font-size: 14px; font-weight: 500; color: #555;">
                                {{ $review->review_title }}</h6>
                            <p class="review-text mb-0" style="font-size: 14px; color: #666; line-height: 1.5;">
                                {{ $review->review_content }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="text-center py-4">
                        <i class="mdi mdi-star-outline fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No reviews yet. Be the first to leave a review!</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Pagination for reviews --}}
            @if(isset($paginatedReviews) && $paginatedReviews->hasPages())
            <div class="d-flex justify-content-center my-4">
                {{ $paginatedReviews->links() }}
            </div>
            @endif
        </div>

        {{-- ################ BOOKING SIDEBAR ################ --}}
        <div class="col-lg-4">
            <div class="siderbar-single">
                <h4 class="post-title my-2 bold">
                    Book This Vehicle
                </h4>

                <div class="card mb-4 booking-card rounded"
                    style="overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                    <div class="card-header" style="background: #f8f9fa; padding: 15px;">
                        <h5 class="mb-0" style="font-size: 1.2rem; font-weight: 600; color: #333;">
                            <i class="mdi mdi-currency-usd me-2"></i>
                            From <span style="color: #218080;">${{ number_format($car->base_price, 2) }}</span> / day
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="#" method="POST" class="car-booking-form">
                            @csrf
                            <input type="hidden" name="deal_id" value="{{ $car->id }}">
                            <input type="hidden" name="type" value="car">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="pickup_date" class="form-label">Pickup Date</label>
                                    <input type="date" class="form-control" id="pickup_date" name="pickup_date" required
                                        min="{{ date('Y-m-d') }}" onchange="calculateCarPrice()">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="return_date" class="form-label">Return Date</label>
                                    <input type="date" class="form-control" id="return_date" name="return_date" required
                                        min="{{ date('Y-m-d', strtotime('+1 day')) }}" onchange="calculateCarPrice()">
                                </div>
                            </div>

                            <div class="booking-summary mt-4 p-3"
                                style="background: #f8f9fa; border-radius: 8px; border-left: 4px solid #ff5722;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 style="color: #333; font-weight: 600; margin-bottom: 5px;">Total Price</h6>
                                        <p class="mb-1" style="font-size: 0.9rem; color: #666;">
                                            <span id="car_days">1</span> day(s) × ${{ number_format($car->base_price, 2) }}/day
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <p class="mb-0" style="font-size: 1.5rem; font-weight: 700; color: #ff5722;">
                                            $<span id="car_total_price">{{ number_format($car->base_price, 2) }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg text-uppercase fw-semibold w-100">
                                    Book Now
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <script>
                    function calculateCarPrice() {
                        const pickupDate = document.getElementById('pickup_date').value;
                        const returnDate = document.getElementById('return_date').value;
                        const pricePerDay = {{ $car->base_price }};
                        
                        let days = 1;
                        if (pickupDate && returnDate) {
                            const pickup = new Date(pickupDate);
                            const returnD = new Date(returnDate);
                            if (returnD > pickup) {
                                days = Math.ceil((returnD - pickup) / (1000 * 60 * 60 * 24));
                            }
                        }
                        
                        const totalPrice = pricePerDay * days;
                        
                        document.getElementById('car_days').textContent = days;
                        document.getElementById('car_total_price').textContent = totalPrice.toFixed(2);
                        
                        // Update return date minimum date
                        if (pickupDate) {
                            const pickup = new Date(pickupDate);
                            pickup.setDate(pickup.getDate() + 1);
                            document.getElementById('return_date').min = pickup.toISOString().split('T')[0];
                        }
                    }
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('pickup_date').addEventListener('change', calculateCarPrice);
                        document.getElementById('return_date').addEventListener('change', calculateCarPrice);
                    });
                </script>

                {{-- Contact Information --}}
                @include('website.components.contact_card')
            </div>
        </div>
    </div>
</div>

@if(isset($nearbyCars) && $nearbyCars->isNotEmpty())
<section class="list-hotel list-hotel--grid py-40 bg-gray-100 mb-0 nearby">
    <div class="container">
        <h4 class="section-title mb-20">Nearby Cars</h4>
        <div class="row">
            @foreach($nearbyCars as $nearbyCar)
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="tour-item tour-item--grid" data-plugin="matchHeight">
                    <div class="tour-item__thumbnail position-relative">
                        @if($nearbyCar->is_featured)
                        <span class="tour-item__label position-absolute"
                            style="top: 12px; left: 12px; z-index: 2; background: #ff5722; color: #fff; padding: 4px 12px; border-radius: 6px; font-size: 14px;">Featured</span>
                        @endif
                        <a href="{{route('view-car', ['id' => $hashids->encode($nearbyCar->id)])}}"
                            style="display:block;">
                            <img src="{{ $nearbyCar->cover_photo ? asset('storage/' . $nearbyCar->cover_photo) : 'https://images.unsplash.com/photo-1549317336-206569e8475c?w=360&h=240&fit=crop&crop=center' }}"
                                alt="{{ $nearbyCar->title }}" loading="eager" width="360" height="240"
                                style="width:100%;height:220px;object-fit:cover;border-radius:12px;" />
                        </a>

                        <div class="add-wishlist-wrapper" style="position:absolute;top:12px;right:12px;z-index:2;">
                            <a href="#gmz-login-popup" class="add-wishlist gmz-box-popup" data-effect="mfp-zoom-in">
                                <i class="fal fa-heart"></i>
                            </a>
                        </div>
                    </div>
                    <div class="tour-item__details" style="padding-top:18px;">
                        <div class="star-rating mb-2">
                            <div class="star-rating">
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                            </div>
                        </div>
                        <h3 class="car-item__title" style="font-size:1.25rem;font-weight:600;">
                            <a href="{{route('view-car', ['id' => $hashids->encode($nearbyCar->id)])}}"
                                style="color:#222;text-decoration:none;">{{ $nearbyCar->title }}</a>
                        </h3>
                        <div class="tour-item__meta" style="margin:18px 0 12px 0;">
                            <div class="i-meta d-flex align-items-center" style="font-size:15px;color:#888;">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $nearbyCar->location }}
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center" style="margin-top:18px;">
                            <div class="tour-item__price">
                                <span class="_retail" style="color:#2e8b57;font-size:1.3rem;font-weight:600;">USD
                                    {{ number_format($nearbyCar->base_price, 2) }}</span>
                                <span class="_unit" style="color:#2e8b57;font-size:1rem;">/Day</span>
                            </div>
                            <a class="btn btn-primary btn-sm tour-item__view-detail"
                                href="{{route('view-car', ['id' => $hashids->encode($nearbyCar->id)])}}"
                                style="font-size:1rem;padding:8px 22px;border-radius:7px;">
                                View Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<style>
    /* Fix modal z-index to appear above header */
    .white-popup.gmz-popup-form {
        z-index: 2147483647 !important;
    }

    .mfp-bg {
        z-index: 2147483646 !important;
    }

    .mfp-wrap {
        z-index: 2147483646 !important;
    }

    .mfp-container {
        z-index: 2147483646 !important;
    }

    /* Ensure modal content is properly positioned */
    .mfp-content {
        z-index: 2147483647 !important;
    }

    /* Fix for Magnific Popup modal positioning */
    .mfp-ready .mfp-bg {
        opacity: 0.8;
    }

    .mfp-ready .mfp-wrap {
        opacity: 1;
    }

    /* Ensure modal is centered and visible */
    .white-popup {
        position: relative;
        background: white;
        padding: 0;
        width: auto;
        max-width: 500px;
        margin: 0 auto;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .popup-inner {
        padding: 15px;
    }

    .popup-title {
        margin-bottom: 15px;
        font-size: 20px;
        font-weight: 600;
        color: #333;
        text-align: center;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Fix modal z-index issues
    function fixModalZIndex() {
        // Ensure modals are above header
        const modals = document.querySelectorAll('.white-popup.gmz-popup-form');
        modals.forEach(modal => {
            modal.style.zIndex = '2147483647';
        });
        
        // Fix Magnific Popup z-index
        if (typeof $.magnificPopup !== 'undefined') {
            $.magnificPopup.instance = null;
        }
    }
    
    // Call fix on load
    fixModalZIndex();
    
    // Re-apply fix when modals are opened
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('gmz-box-popup')) {
            setTimeout(fixModalZIndex, 100);
        }
    });
});

// Simple star rating display
    document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('rating');
    const starDisplay = document.getElementById('star-display');
    if (select && starDisplay) {
        select.addEventListener('change', function () {
            let val = parseInt(this.value);
            if (!val) {
                starDisplay.innerHTML = '';
                return;
            }
            let stars = '';
            for (let i = 0; i < val; i++) {
                stars += '★';
            }
            starDisplay.textContent = stars;
        });
    }
    });
</script>
@endsection