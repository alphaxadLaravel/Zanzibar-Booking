@extends('website.layouts.app')

@php
use Illuminate\Support\Str;
@endphp

@section('title')
{{ $hotel->seo_title ?: $hotel->title }} - Zanzibar Bookings
@endsection

@section('meta')
<meta name="description" content="{{ $hotel->seo_description ?: Str::limit(strip_tags($hotel->description), 160) }}">
@if($hotel->seo_keywords)
<meta name="keywords" content="{{ $hotel->seo_keywords }}">
@endif

<meta property="og:type" content="website">
<meta property="og:url" content="{{ request()->url() }}">
<meta property="og:title" content="{{ $hotel->seo_title ?: $hotel->title }}">
<meta property="og:description"
    content="{{ $hotel->seo_description ?: Str::limit(strip_tags($hotel->description), 160) }}">
<meta property="og:image"
    content="{{ $hotel->seo_image ? asset('storage/' . $hotel->seo_image) : ($hotel->cover_photo ? asset('storage/' . $hotel->cover_photo) : asset('logo.png')) }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ request()->url() }}">
<meta property="twitter:title" content="{{ $hotel->seo_title ?: $hotel->title }}">
<meta property="twitter:description"
    content="{{ $hotel->seo_description ?: Str::limit(strip_tags($hotel->description), 160) }}">
<meta property="twitter:image"
    content="{{ $hotel->seo_image ? asset('storage/' . $hotel->seo_image) : ($hotel->cover_photo ? asset('storage/' . $hotel->cover_photo) : asset('logo.png')) }}">
@endsection

@section('pages')
<section class="gallery">
    <div class="gmz-carousel-with-lightbox" data-count="{{ $hotel->photos->count() }}">
        @forelse($hotel->photos as $photo)
        <a href="{{ asset('storage/' . $photo->photo) }}">
            <img src="{{ asset('storage/' . $photo->photo) }}" alt="{{ $hotel->title }}" class="gallery-img"
                style="width: 100%; height: 400px; object-fit: cover; display: block; opacity: 0; transition: opacity 0.5s;"
                loading="lazy" />
        </a>
        @empty
        <a
            href="{{ $hotel->cover_photo ? asset('storage/' . $hotel->cover_photo) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop&crop=center' }}">
            <img src="{{ $hotel->cover_photo ? asset('storage/' . $hotel->cover_photo) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop&crop=center' }}"
                alt="{{ $hotel->title }}" class="gallery-img"
                style="width: 100%; height: 400px; object-fit: cover; display: block; opacity: 0; transition: opacity 0.5s;"
                loading="lazy" />
        </a>
        @endforelse
    </div>
</section>
<script>
    // Prevent burst/flash on like opening, fade in after page load
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
            <li><span>{{ $hotel->title }}</span></li>
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
                            {{ $hotel->title }}
                        </h2>
                    </div>
                    @if ($hotel->location)
                    <p class="location">
                        <i class="fal fa-map-marker-alt"></i> {{ $hotel->location }}
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
                                            {{ $hotel->category ? $hotel->category->category : 'Hotel' }}
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
                                            USD {{ number_format($hotel->base_price, 2) }}/night
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
                                            {{ $hotel->ratings ? number_format($hotel->ratings, 1) : '5.0' }}/5
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
                                {!! $hotel->description !!}
                            </p>
                        </div>
                    </section>
                    <hr>
                    <section class="feature">
                        <h4 class="section-title">Facilities</h4>
                        <div class="section-content">
                            <div class="d-flex flex-wrap" style="gap: 10px;">
                                @forelse($hotel->features as $feature)
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

                    @if($hotel->video_link)
                    <section class="video-section">
                        <h4 class="section-title">Video</h4>
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <div class="video-container"
                                        style="position: relative; width: 100%; height: 0; padding-bottom: 56.25%; background: #000; border-radius: 8px; overflow: hidden;">
                                        @php
                                        $videoUrl = $hotel->video_link;
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
                    @if($hotel->nearbyLocations && $hotel->nearbyLocations->count() > 0)
                    <section class="nearby-locations">
                        <h4 class="section-title mb-3">Nearby Locations</h4>
                        <div class="section-content">
                            <div class="d-flex flex-wrap" style="gap: 10px;">
                                @foreach($hotel->nearbyLocations as $location)
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
                                    <span
                                        style="font-size: 13px; font-weight: 500; color: #333; line-height: 1.3; flex:1;">
                                        {{ $location->title }}
                                    </span>
                                    <span class="ms-2 text-nowrap"
                                        style="font-size: 13px; color: #2e8b57; font-weight: 600;">
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
                                {!! $hotel->policies !!}
                            </p>
                        </div>
                    </section>
                    <hr>

                    <section class="map">
                        <h4 class="section-title mb-4">Hotel Location On Map</h4>
                        <div id="address-map-container" style="width: 100%; height: 400px">
                            @if($hotel->lat && $hotel->long)
                            <iframe width="100%" height="100%" frameborder="0" style="border:0; border-radius: 8px;"
                                src="https://www.google.com/maps?q={{ $hotel->lat }},{{ $hotel->long }}&output=embed"
                                allowfullscreen aria-hidden="false" tabindex="0" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                            @else
                            <iframe width="100%" height="100%" frameborder="0" style="border:0; border-radius: 8px;"
                                src="https://www.google.com/maps?q={{ $hotel->location }}&output=embed" allowfullscreen
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
                    <h4 class="comment-count">Reviews for this Hotel</h4>

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
                                    <form action="{{ route('deals.reviews.store', $hotel->id) }}"
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
                                                        placeholder="Share your experience with this hotel..."
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
                    @if($paginatedReviews->count() > 0)
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
            @if($paginatedReviews->hasPages())
            <div class="d-flex justify-content-center my-4">
                {{ $paginatedReviews->links() }}
            </div>
            @endif
        </div>


        {{-- ################ ROOMS LIST ################ --}}
        <div class="col-lg-4">
            <div class="siderbar-single">
                <h4 class="post-title my-2 bold">
                    Hotel Rooms To Book
                </h4>

                @forelse($rooms as $room)
                <div class="card mb-4 room-card rounded"
                    style="overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04); cursor: pointer;"
                    onclick="openRoomDetailsModal({{ $room->id }})">
                    <div class="row g-0 align-items-center">
                        <div class="col-4 d-flex align-items-center justify-content-center"
                            style="background: #f8f9fa;">
                            <div class="rounded"
                                style="width: 80px; height: 80px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                <img src="{{ $room->photos->first() ? asset('storage/' . $room->photos->first()->photo) : ($room->cover_photo ? asset('storage/' . $room->cover_photo) : ($hotel->cover_photo ? asset('storage/' . $hotel->cover_photo) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=80&h=80&fit=crop&crop=center')) }}"
                                    alt="{{ $room->title }}" class="rounded"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>
                        <div class="col-8 px-3">
                            <div class="card-body p-3">
                                <h5 class="card-title mb-1" style="font-size: 1.1rem; font-weight: 600;">{{
                                    $room->title ?? 'Standard Room' }}</h5>
                                <div class="mb-2" style="font-size: 13px; color: #666;">
                                    <i class="fa fa-user"></i> {{ $room->people ?? '2' }} Guests &nbsp; | &nbsp;
                                    <i class="fa fa-bed"></i> {{ $room->beds ?? '1' }} {{ $room->beds == 1 ? 'Bed' :
                                    'Beds' }}
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="fw-bold" style="font-size: 1.1rem; color: #ff5722;">${{
                                            number_format($room->price ?? $hotel->base_price, 0) }}</span>
                                        <span style="font-size: 13px; color: #888;">/ night</span>
                                    </div>
                                    <button class="btn btn-primary" style="font-size: 13px;"
                                        onclick="event.stopPropagation(); openRoomDetailsModal({{ $room->id }})">
                                        <i class="mdi mdi-calendar-check me-1"></i> BOOK ROOM
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="well my-4 d-flex flex-column align-items-center justify-content-center text-center py-5"
                    style="background: #f8f9fa; border-radius: 12px; border: 1px solid #e0e0e0;">
                    <div class="mb-3">
                        <i class="mdi mdi-bed-empty" style="font-size: 2.5rem; color: #bdbdbd;"></i>
                    </div>
                    <h5 class="mb-2" style="color: #888;">No rooms available</h5>
                    <p class="mb-0" style="color: #aaa; font-size: 1rem;">Please check back later or contact the hotel
                        for more information.</p>
                </div>
                @endforelse
            </div>

            {{-- Contact Information --}}
           @include('website.components.contact_card')
        </div>
    </div>
</div>


@if($nearbyHotels->isNotEmpty())
<section class="list-hotel list-hotel--grid py-40 bg-gray-100 mb-0 nearby">
    <div class="container">
        <h4 class="section-title mb-20">Nearby Hotels & Apartments</h4>
        <div class="row">
            @foreach($nearbyHotels as $nearbyHotel)
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="tour-item tour-item--grid" data-plugin="matchHeight">
                    <div class="tour-item__thumbnail position-relative">
                        @if($nearbyHotel->is_featured)
                        <span class="tour-item__label position-absolute"
                            style="top: 12px; left: 12px; z-index: 2; background: #ff5722; color: #fff; padding: 4px 12px; border-radius: 6px; font-size: 14px;">Featured</span>
                        @endif
                        <a href="{{route('view-hotel', ['id' => $hashids->encode($nearbyHotel->id)])}}"
                            style="display:block;">
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
                            <a href="{{route('view-hotel', ['id' => $hashids->encode($nearbyHotel->id)])}}"
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
                                href="{{route('view-hotel', ['id' => $hashids->encode($nearbyHotel->id)])}}"
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

@if($nearbyTours->isNotEmpty())
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
<script>
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

<!-- Room Details Modals -->
@foreach($rooms as $room)
<div class="modal fade" id="roomDetailsModal{{ $room->id }}" tabindex="-1"
    aria-labelledby="roomDetailsModalLabel{{ $room->id }}" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between" style="gap: 1rem;">
                <div class="d-flex align-items-center" style="gap: 1.25rem;">
                    <h5 class="modal-title mb-0" id="roomDetailsModalLabel{{ $room->id }}"
                        style="font-size: 1.25rem; font-weight: 600;">
                        {{ $room->title ?? 'Room Details' }}
                    </h5>
                    <div class="price-display d-flex align-items-end" style="gap: 0.3rem;">
                        <span class="price-amount" style="font-size: 1.5rem; font-weight: 700; color: #ff5722;">
                            ${{ number_format($room->price ?? $hotel->base_price, 0) }}
                        </span>
                        <span class="price-unit" style="color: #888; font-size: 0.9rem;">/ night</span>
                    </div>
                </div>
                <button type="button" class="btn-close d-flex align-items-center justify-content-center"
                    data-bs-dismiss="modal" aria-label="Close" style="background:none; border:none; box-shadow:none;">
                    <i class="mdi mdi-close" style="font-size: 1.5rem;"></i>
                </button>
            </div>
            <div class="modal-body">
                <!-- Room Image Gallery -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="room-image-gallery position-relative">
                            @if($room->photos && $room->photos->count() > 0)
                            <div id="roomImageCarousel{{ $room->id }}" class="carousel carousel-fade"
                                data-bs-ride="carousel">
                                <div class="carousel-inner rounded" style="border-radius: 12px; overflow: hidden;">
                                    @foreach($room->photos as $index => $photo)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $photo->photo) }}" class="d-block w-100"
                                            alt="Room Photo" style="height: 250px; object-fit: cover;">
                                    </div>
                                    @endforeach
                                </div>

                                @if($room->photos->count() > 1)
                                <!-- Custom Navigation Buttons -->
                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#roomImageCarousel{{ $room->id }}" data-bs-slide="prev"
                                    style="width: 40px; height: 40px; top: 50%; left: 10px; transform: translateY(-50%); background: rgba(0,0,0,0.5); border-radius: 50%; border: none;">
                                    <span class="carousel-control-prev-icon" style="width: 20px; height: 20px;"></span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#roomImageCarousel{{ $room->id }}" data-bs-slide="next"
                                    style="width: 40px; height: 40px; top: 50%; right: 10px; transform: translateY(-50%); background: rgba(0,0,0,0.5); border-radius: 50%; border: none;">
                                    <span class="carousel-control-next-icon" style="width: 20px; height: 20px;"></span>
                                </button>

                                <!-- Dotted Indicators -->
                                <div class="carousel-indicators" style="bottom: 10px; margin-bottom: 0;">
                                    @foreach($room->photos as $index => $photo)
                                    <button type="button" data-bs-target="#roomImageCarousel{{ $room->id }}"
                                        data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"
                                        style="width: 8px; height: 8px; border-radius: 50%; border: none; background: rgba(255,255,255,0.5); margin: 0 3px;"
                                        aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-label="Slide {{ $index + 1 }}"></button>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @else
                            <img src="{{ $room->cover_photo ? asset('storage/' . $room->cover_photo) : ($hotel->cover_photo ? asset('storage/' . $hotel->cover_photo) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&h=250&fit=crop&crop=center') }}"
                                class="d-block w-100 rounded" alt="Room Photo"
                                style="height: 250px; object-fit: cover;">
                            @endif
                        </div>

                        <div class="room-info mb-3">
                            <div class="d-flex flex-wrap my-3" style="gap: 12px;">
                                <div class="flex-fill" style="min-width: 160px;">
                                    <div class="d-flex align-items-center border rounded bg-white px-3 py-2 h-100"
                                        style="min-height:70px; border-color: #218080;">
                                        <span
                                            class="d-flex align-items-center justify-content-center rounded bg-light flex-shrink-0"
                                            style="width:32px; height:32px; background: #e6f4f1 !important; margin-right: 16px;">
                                            <i class="mdi mdi-account" style="color: #218080; font-size: 1.2rem;"></i>
                                        </span>
                                        <div class="flex-grow-1" style="min-width:0;">
                                            <div class="fw-bolder text-dark"
                                                style="font-size: 1.2rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                {{ $room->people ?? '2' }}
                                            </div>
                                            <div class="text-muted small" style="white-space:nowrap;">Guests</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-fill" style="min-width: 160px;">
                                    <div class="d-flex align-items-center border rounded bg-white px-3 py-2 h-100"
                                        style="min-height:70px; border-color: #218080;">
                                        <span
                                            class="d-flex align-items-center justify-content-center rounded bg-light flex-shrink-0"
                                            style="width:32px; height:32px; background: #e6f4f1 !important; margin-right: 16px;">
                                            <i class="mdi mdi-bed" style="color: #218080; font-size: 1.2rem;"></i>
                                        </span>
                                        <div class="flex-grow-1" style="min-width:0;">
                                            <div class="fw-bolder text-dark"
                                                style="font-size: 1.2rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                {{ $room->beds ?? '1' }} {{ ($room->beds ?? 1) == 1 ? 'Bed' : 'Beds'
                                                }}
                                            </div>
                                            <div class="text-muted small" style="white-space:nowrap;">Beds</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($room->description)
                        <div class="room-description mb-3">
                            <p style="color: #666; line-height: 1.6; font-size: 0.95rem;">{!! $room->description !!}
                            </p>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="booking-form-section">
                            <h5
                                style="color: #333; font-weight: 600; margin-bottom: 20px; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px;">
                                Book This Room</h5>
                            <form action="#" method="POST">
                                @csrf
                                <input type="hidden" name="deal_id" value="{{ $hotel->id }}">
                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                                <input type="hidden" name="type" value="hotel">

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="check_in{{ $room->id }}" class="form-label">Check-in Date</label>
                                        <input type="date" class="form-control" id="check_in{{ $room->id }}"
                                            name="check_in" required min="{{ date('Y-m-d') }}"
                                            onchange="calculatePrice{{ $room->id }}()">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="check_out{{ $room->id }}" class="form-label">Check-out Date</label>
                                        <input type="date" class="form-control" id="check_out{{ $room->id }}"
                                            name="check_out" required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                            onchange="calculatePrice{{ $room->id }}()">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="number_rooms{{ $room->id }}" class="form-label">Number of
                                            Rooms</label>
                                        <select class="form-control" id="number_rooms{{ $room->id }}"
                                            name="number_rooms" required onchange="calculatePrice{{ $room->id }}()">
                                            @for($i = 1; $i <= ($room->number_of_rooms ?? 1); $i++)
                                                <option value="{{ $i }}">{{ $i }} Room{{ $i > 1 ? 's' : '' }}</option>
                                                @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="adult{{ $room->id }}" class="form-label">Adults</label>
                                        <select class="form-control" id="adult{{ $room->id }}" name="adult" required>
                                            @for($i = 1; $i <= min($room->people ?? 2, 8); $i++)
                                                <option value="{{ $i }}" {{ $i===2 ? 'selected' : '' }}>{{ $i }} Adult{{
                                                    $i > 1 ? 's' : '' }}</option>
                                                @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-12 not-even:mb-3">
                                        <label for="children{{ $room->id }}" class="form-label">Children</label>
                                        <select class="form-control" id="children{{ $room->id }}" name="children">
                                            <option value="0">No Children</option>
                                            @for($i = 1; $i <= 4; $i++) <option value="{{ $i }}">{{ $i }} Child{{ $i > 1
                                                ? 'ren' : '' }}</option>
                                                @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="booking-summary mt-4 p-3"
                                    style="background: #f8f9fa; border-radius: 8px; border-left: 4px solid #ff5722;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 style="color: #333; font-weight: 600; margin-bottom: 5px;">Total Price
                                            </h6>
                                            <p class="mb-1" style="font-size: 0.9rem; color: #666;">
                                                <span id="nights{{ $room->id }}">1</span> night(s) ×
                                                <span id="rooms{{ $room->id }}">1</span> room(s) ×
                                                ${{ number_format($room->price ?? $hotel->base_price, 2) }}/night
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            <p class="mb-0"
                                                style="font-size: 1.5rem; font-weight: 700; color: #ff5722;">
                                                $<span id="total_price{{ $room->id }}">{{ number_format($room->price ??
                                                    $hotel->base_price, 2) }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer mt-4 p-0">
                                    @if($room->availability)
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-calendar-check me-2"></i>
                                        Proceed to Book
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-secondary w-100" disabled>
                                        <i class="fas fa-times me-2"></i>
                                        Not Available
                                    </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>
</div>
@endforeach

<script>
    function openRoomDetailsModal(roomId) {
    const modal = new bootstrap.Modal(document.getElementById('roomDetailsModal' + roomId));
    modal.show();
}

@foreach($rooms as $room)
function calculatePrice{{ $room->id }}() {
    const checkIn = document.getElementById('check_in{{ $room->id }}').value;
    const checkOut = document.getElementById('check_out{{ $room->id }}').value;
    const numberRooms = parseInt(document.getElementById('number_rooms{{ $room->id }}').value);
    const pricePerNight = {{ $room->price ?? $hotel->base_price }};
    
    let nights = 1;
    if (checkIn && checkOut) {
        const checkInDate = new Date(checkIn);
        const checkOutDate = new Date(checkOut);
        if (checkOutDate > checkInDate) {
            nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
        }
    }
    
    const totalPrice = pricePerNight * numberRooms * nights;
    
    document.getElementById('nights{{ $room->id }}').textContent = nights;
    document.getElementById('rooms{{ $room->id }}').textContent = numberRooms;
    document.getElementById('total_price{{ $room->id }}').textContent = totalPrice.toFixed(2);
    
    // Update check-out minimum date
    if (checkIn) {
        const checkInDate = new Date(checkIn);
        checkInDate.setDate(checkInDate.getDate() + 1);
        document.getElementById('check_out{{ $room->id }}').min = checkInDate.toISOString().split('T')[0];
    }
}
@endforeach
</script>

@endsection