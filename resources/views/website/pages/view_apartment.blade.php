@extends('website.layouts.app')

@php
use Illuminate\Support\Str;
@endphp

@section('title')
{{ $apartment->seo_title ?: $apartment->title }} - Zanzibar Bookings
@endsection

@section('meta')
<meta name="description" content="{{ $apartment->seo_description ?: Str::limit(strip_tags($apartment->description), 160) }}">
@if($apartment->seo_keywords)
<meta name="keywords" content="{{ $apartment->seo_keywords }}">
@endif

<meta property="og:type" content="website">
<meta property="og:url" content="{{ request()->url() }}">
<meta property="og:title" content="{{ $apartment->seo_title ?: $apartment->title }}">
<meta property="og:description"
    content="{{ $apartment->seo_description ?: Str::limit(strip_tags($apartment->description), 160) }}">
<meta property="og:image"
    content="{{ $apartment->seo_image ? asset('storage/' . $apartment->seo_image) : ($apartment->cover_photo ? asset('storage/' . $apartment->cover_photo) : asset('logo.png')) }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ request()->url() }}">
<meta property="twitter:title" content="{{ $apartment->seo_title ?: $apartment->title }}">
<meta property="twitter:description"
    content="{{ $apartment->seo_description ?: Str::limit(strip_tags($apartment->description), 160) }}">
<meta property="twitter:image"
    content="{{ $apartment->seo_image ? asset('storage/' . $apartment->seo_image) : ($apartment->cover_photo ? asset('storage/' . $apartment->cover_photo) : asset('logo.png')) }}">
@endsection

@section('pages')
<section class="gallery">
    <div class="gmz-carousel-with-lightbox" data-count="{{ $apartment->photos->count() }}">
        @forelse($apartment->photos as $photo)
        <a href="{{ asset('storage/' . $photo->photo) }}">
            <img 
                src="{{ asset('storage/' . $photo->photo) }}" 
                alt="{{ $apartment->title }}"
                class="gallery-img"
                style="width: 100%; height: 400px; object-fit: cover; display: block; opacity: 0; transition: opacity 0.5s;" 
                loading="lazy" 
            />
        </a>
        @empty
        <a
            href="{{ $apartment->cover_photo ? asset('storage/' . $apartment->cover_photo) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop&crop=center' }}">
            <img 
                src="{{ $apartment->cover_photo ? asset('storage/' . $apartment->cover_photo) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop&crop=center' }}"
                alt="{{ $apartment->title }}" 
                class="gallery-img"
                style="width: 100%; height: 400px; object-fit: cover; display: block; opacity: 0; transition: opacity 0.5s;" 
                loading="lazy" 
            />
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
            <li><a href="{{ route('apartments') }}">Apartments</a></li>
            <li><span>{{ $apartment->title }}</span></li>
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
                    {{ $apartment->title }}
                </h2>
            </div>
            @if ($apartment->location)
            <p class="location">
                <i class="fal fa-map-marker-alt"></i> {{ $apartment->location }}
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
                                <i class="mdi mdi-home-city" style="color: #218080; font-size: 1.2rem;"></i>
                            </span>
                            <div class="flex-grow-1" style="min-width:0;">
                                <div class="fw-bold text-dark"
                                    style="font-size: 1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    {{ $apartment->category ? $apartment->category->category : 'Apartment' }}
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
                                    USD {{ number_format($apartment->base_price, 2) }}/night
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
                                    {{ $apartment->ratings ? number_format($apartment->ratings, 1) : '5.0' }}/5
                                </div>
                                <div class="text-muted small" style="white-space:nowrap;">Rating</div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <hr>
            <section class="description">
                <h4 class="section-title">Detail</h4>
                <div class="section-content">
                    <p>
                        {!! $apartment->description !!}
                    </p>
                </div>
            </section>
            <hr>
            <section class="feature">
                <h4 class="section-title">Facilities</h4>
                <div class="section-content">
                    <div class="d-flex flex-wrap" style="gap: 10px;">
                        @forelse($apartment->features as $feature)
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
                        <div class="text-muted" style="font-size: 14px;">No facilities listed.</div>
                        @endforelse
                    </div>
                </div>
            </section>
            <hr>

            @if($apartment->video_link)
            <section class="video-section">
                <h4 class="section-title">Video</h4>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="video-container"
                                style="position: relative; width: 100%; height: 0; padding-bottom: 56.25%; background: #000; border-radius: 8px; overflow: hidden;">
                                @php
                                $videoUrl = $apartment->video_link;
                                $embedUrl = '';

                                // YouTube
                                if (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !==
                                false) {
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
                                        <i class="fas fa-play-circle" style="font-size: 3rem; margin-bottom: 1rem;"></i>
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

            @if($apartment->nearbyLocations && $apartment->nearbyLocations->count() > 0)
            <section class="nearby-locations">
                <h4 class="section-title mb-3">Nearby Locations</h4>
                <div class="section-content">
                    <div class="d-flex flex-wrap" style="gap: 10px;">
                        @foreach($apartment->nearbyLocations as $location)
                        <div class="facility-card d-flex align-items-center px-3 py-2 mb-2"
                            style="background: #fff; border-radius: 6px; border: 1px solid #e0e0e0; min-height: 38px; flex: 0 0 auto; min-width: 180px; max-width: 320px; width: calc(100%/6 - 10px);">
                            <span class="me-2" style="width: 22px; text-align: center;">
                                @php
                                $iconMap = [
                                'Airport' => 'mdi-airplane',
                                'Ferry Port' => 'mdi-ferry',
                                'Beach' => 'mdi-beach',
                                'School' => 'mdi-school',
                                'Hospital' => 'mdi-hospital',
                                'Shopping Center' => 'mdi-shopping',
                                'Restaurant' => 'mdi-food',
                                'Bank' => 'mdi-bank',
                                'ATM' => 'mdi-credit-card',
                                'Gas Station' => 'mdi-gas-station',
                                'Bus Station' => 'mdi-bus',
                                'Train Station' => 'mdi-train',
                                'Tourist Attraction' => 'mdi-camera',
                                'Market' => 'mdi-store',
                                'Pharmacy' => 'mdi-pill',
                                'Police Station' => 'mdi-shield',
                                'Post Office' => 'mdi-mail',
                                'Gym' => 'mdi-dumbbell',
                                'Park' => 'mdi-tree',
                                'Mosque' => 'mdi-mosque',
                                'Church' => 'mdi-church',
                                ];
                                $icon = $iconMap[$location->category] ?? 'mdi-map-marker';
                                @endphp
                                <i class="mdi {{ $icon }}" style="font-size: 1.2rem; color: #2e8b57;"></i>
                            </span>
                            <span style="font-size: 13px; font-weight: 500; color: #333; line-height: 1.3; flex:1;">
                                {{ $location->title }}
                            </span>
                            <span class="ms-2 text-nowrap" style="font-size: 13px; color: #2e8b57; font-weight: 600;">
                                {{ $location->formatted_distance }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>
            <hr>
            @endif

            <section class="description">
                <h4 class="section-title">Our Policies</h4>
                <div class="section-content">
                    <p>
                        {!! $apartment->policies ?? '<strong>Check-in/Check-out:</strong> Check-in: 2:00 PM, Check-out: 11:00 AM<br><br><strong>Cancellation Policy:</strong> Free cancellation up to 48 hours before arrival. Late cancellations may incur charges.<br><br><strong>House Rules:</strong> No smoking inside the apartment, No pets allowed, Quiet hours from 10 PM to 7 AM, Maximum occupancy as specified.' !!}
                    </p>
                </div>
            </section>
            <hr>

            <section class="map">
                <h4 class="section-title mb-4">Apartment Location On Map</h4>
                <div id="address-map-container" style="width: 100%; height: 400px">
                    @if($apartment->lat && $apartment->long)
                    <iframe width="100%" height="100%" frameborder="0" style="border:0; border-radius: 8px;"
                        src="https://www.google.com/maps?q={{ $apartment->lat }},{{ $apartment->long }}&output=embed"
                        allowfullscreen aria-hidden="false" tabindex="0" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                    @else
                    <iframe width="100%" height="100%" frameborder="0" style="border:0; border-radius: 8px;"
                        src="https://www.google.com/maps?q={{ $apartment->location }}&output=embed" allowfullscreen
                        aria-hidden="false" tabindex="0" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                    @endif
                </div>
            </section>
            <hr>

            <div class="reviews-section mt-4" id="review-section">
                <div class="d-flex justify-content-between align-items-center my-3">
                    <h4 class="comment-count">Reviews for this Apartment</h4>

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
                                    <form action="{{ route('deals.reviews.store', $apartment->id) }}" class="comment-form form-sm" method="post">
                                        @csrf

                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="review-title" class="form-label fw-semibold">Review Title *</label>
                                                    <input id="review-title" type="text" name="review_title"
                                                        class="form-control" placeholder="Enter your review title" required />
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
                                                <select id="rating" name="rating" class="form-select form-control" required>
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
                                                    <label for="review-content" class="form-label fw-semibold">Your Review *</label>
                                                    <textarea id="review-content" name="review_content"
                                                        placeholder="Share your experience with this apartment..."
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
                                                style="font-size: 16px; font-weight: 600; color: #333;">{{ $review->reviewer_name }}</h5>
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

        {{-- ################ BOOKING SECTION ################ --}}
        <div class="col-lg-4">
            <div class="siderbar-single">
                <h4 class="post-title my-2 bold">
                    Book This Apartment
                </h4>
                
                {{-- apartment booking card --}}
                <div class="card mb-4 apartment-card rounded"
                    style=" overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                    <div class="row g-0 align-items-center">
                        <div class="col-4 d-flex align-items-center justify-content-center"
                            style="background: #f8f9fa;">
                            <div class="rounded"
                                style="width: 80px; height: 80px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                <img src="{{ $apartment->cover_photo ? asset('storage/' . $apartment->cover_photo) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=80&h=80&fit=crop&crop=center' }}"
                                    alt="{{ $apartment->title }}" class="rounded"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>
                        <div class="col-8 px-3">
                            <div class="card-body p-3">
                                <h5 class="card-title mb-1" style="font-size: 1.1rem; font-weight: 600;">{{ $apartment->title }}</h5>
                                <div class="mb-2" style="font-size: 13px; color: #666;">
                                    <i class="fa fa-home"></i> {{ $apartment->category ? $apartment->category->category : 'Apartment' }} &nbsp; | &nbsp;
                                    <i class="fa fa-map-marker"></i> {{ $apartment->location }}
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="fw-bold" style="font-size: 1.1rem; color: #ff5722;">${{ number_format($apartment->base_price, 0) }}</span>
                                        <span style="font-size: 13px; color: #888;">/ night</span>
                                    </div>
                                    {{-- <a href="{{ route('confirm-booking', ['deal_id' => $apartment->id]) }}"
                                        class="btn btn-primary btn-sm" style="font-size: 13px;">Book Now</a> --}}
                                    <a href="#" class="btn btn-primary btn-sm" style="font-size: 13px;">Book Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="card my-4 contact-card rounded"
                    style="overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                    <div class="card-header" style="background: #f8f9fa; padding: 15px;">
                        <h5 class="mb-0" style="font-size: 1.2rem; font-weight: 600; color: #333;">
                            <i class="fas fa-phone me-2"></i>Need Help?
                        </h5>
                    </div>
                    <div class="card-body p-3">
                        <p class="mb-3" style="color: #666; font-size: 14px;">
                            Contact us for more information about this apartment.
                        </p>
                        <div class="contact-info">
                            <div class="contact-item d-flex align-items-center mb-2">
                                <i class="fas fa-phone me-2" style="color: #2e8b57; width: 20px;"></i>
                                <span style="font-size: 14px; color: #333;">+255 123 456 789</span>
                            </div>
                            <div class="contact-item d-flex align-items-center mb-2">
                                <i class="fas fa-envelope me-2" style="color: #2e8b57; width: 20px;"></i>
                                <span style="font-size: 14px; color: #333;">info@zanzibarbookings.com</span>
                            </div>
                            <div class="contact-item d-flex align-items-center">
                                <i class="fas fa-clock me-2" style="color: #2e8b57; width: 20px;"></i>
                                <span style="font-size: 14px; color: #333;">24/7 Support</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($nearbyHotels) && $nearbyHotels->isNotEmpty())
<section class="list-hotel list-hotel--grid py-40 bg-gray-100 mb-0 nearby">
    <div class="container">
        <h4 class="section-title mb-20">Nearby Hotels & Apartments</h4>
        <div class="row">
            @foreach($nearbyHotels as $nearbyHotel)
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="tour-item tour-item--grid" data-plugin="matchHeight">
                    <div class="tour-item__thumbnail position-relative">
                        @if($nearbyHotel->is_featured)
                        <span class="tour-item__label position-absolute"
                            style="top: 12px; left: 12px; z-index: 2; background: #ff5722; color: #fff; padding: 4px 12px; border-radius: 6px; font-size: 14px;">Featured</span>
                        @endif
                        <a href="{{ $nearbyHotel->type === 'apartment' ? route('view-apartment', ['id' => $hashids->encode($nearbyHotel->id)]) : route('view-hotel', ['id' => $hashids->encode($nearbyHotel->id)]) }}" style="display:block;">
                            <img src="{{ $nearbyHotel->cover_photo ? asset('storage/' . $nearbyHotel->cover_photo) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=360&h=240&fit=crop&crop=center' }}"
                                alt="{{ $nearbyHotel->title }}" loading="eager" width="360" height="240"
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
                            <a href="{{ $nearbyHotel->type === 'apartment' ? route('view-apartment', ['id' => $hashids->encode($nearbyHotel->id)]) : route('view-hotel', ['id' => $hashids->encode($nearbyHotel->id)]) }}"
                                style="color:#222;text-decoration:none;">{{ $nearbyHotel->title }}</a>
                        </h3>
                        <div class="tour-item__meta" style="margin:18px 0 12px 0;">
                            <div class="i-meta d-flex align-items-center" style="font-size:15px;color:#888;">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $nearbyHotel->location }}
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center" style="margin-top:18px;">
                            <div class="tour-item__price">
                                <span class="_retail" style="color:#2e8b57;font-size:1.3rem;font-weight:600;">USD
                                    {{ number_format($nearbyHotel->base_price, 2) }}</span>
                                <span class="_unit" style="color:#2e8b57;font-size:1rem;">/Night</span>
                            </div>
                            <a class="btn btn-primary btn-sm tour-item__view-detail"
                                href="{{ $nearbyHotel->type === 'apartment' ? route('view-apartment', ['id' => $hashids->encode($nearbyHotel->id)]) : route('view-hotel', ['id' => $hashids->encode($nearbyHotel->id)]) }}"
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

@if(isset($nearbyTours) && $nearbyTours->isNotEmpty())
<section class="list-tour list-tour--grid py-40 bg-white mb-0 nearby-activities">
    <div class="container">
        <h4 class="section-title mb-20">Nearby Activities</h4>
        <div class="row">
            @foreach($nearbyTours as $tour)
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="tour-item tour-item--grid" data-plugin="matchHeight">
                    <div class="tour-item__thumbnail position-relative">
                        @if($tour->is_featured)
                        <span class="tour-item__label position-absolute"
                            style="top: 12px; left: 12px; z-index: 2; background: #ff5722; color: #fff; padding: 4px 12px; border-radius: 6px; font-size: 14px;">Featured</span>
                        @endif
                        <a href="{{route('view-tour', ['id' => $hashids->encode($tour->id)])}}" style="display:block;">
                            <img src="{{ $tour->cover_photo ? asset('storage/' . $tour->cover_photo) : 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=360&h=240&fit=crop&crop=center' }}"
                                alt="{{ $tour->title }}" loading="eager" width="360" height="240"
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
                        <h3 class="tour-item__title" style="font-size:1.25rem;font-weight:600;">
                            <a href="{{route('view-tour', ['id' => $hashids->encode($tour->id)])}}"
                                style="color:#222;text-decoration:none;">{{ $tour->title }}</a>
                        </h3>
                        <div class="tour-item__meta" style="margin:18px 0 12px 0;">
                            <div class="i-meta d-flex align-items-center" style="font-size:15px;color:#888;">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $tour->location }}
                            </div>
                        </div>
                        @if($tour->tours)
                        <div class="tour-duration mb-2" style="font-size:14px;color:#666;">
                            <i class="fas fa-clock me-2"></i>{{ $tour->tours->duration ?? 'Full Day' }}
                        </div>
                        @endif
                        <div class="d-flex justify-content-between align-items-center" style="margin-top:18px;">
                            <div class="tour-item__price">
                                <span class="_retail" style="color:#2e8b57;font-size:1.3rem;font-weight:600;">USD
                                    {{ number_format($tour->base_price, 2) }}</span>
                                <span class="_unit" style="color:#2e8b57;font-size:1rem;">/person</span>
                            </div>
                            <a class="btn btn-primary btn-sm tour-item__view-detail"
                                href="{{route('view-tour', ['id' => $hashids->encode($tour->id)])}}"
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