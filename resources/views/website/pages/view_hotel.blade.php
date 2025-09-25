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
            <img src="{{ asset('storage/' . $photo->photo) }}" alt="{{ $hotel->title }}"
                style="width: 100%; height: 400px; object-fit: cover; display: block;" loading="lazy" />
        </a>
        @empty
        <a
            href="{{ $hotel->cover_photo ? asset('storage/' . $hotel->cover_photo) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop&crop=center' }}">
            <img src="{{ $hotel->cover_photo ? asset('storage/' . $hotel->cover_photo) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop&crop=center' }}"
                alt="{{ $hotel->title }}" style="width: 100%; height: 400px; object-fit: cover; display: block;"
                loading="lazy" />
        </a>
        @endforelse
    </div>
</section>


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
                                    <form action="{{ route('deals.reviews.store', $hotel->id) }}" class="comment-form form-sm" method="post">
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
                    style=" overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
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
                                    <a href="{{ route('confirm-booking', ['deal_id' => $hotel->id, 'room_id' => $room->id]) }}"
                                        class="btn btn-primary btn-sm" style="font-size: 13px;">Book Now</a>
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
        </div>
    </div>
</div>


@if($nearbyHotels->isNotEmpty())
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

<!-- Booking Modal -->
@foreach($rooms as $room)
<div id="book-room-{{ $room->id }}" class="white-popup gmz-popup-form" style="display: none;">
    <div class="popup-inner">
        <h3 class="popup-title">Book {{ $room->title }}</h3>
        <form id="booking-form-{{ $room->id }}" class="booking-form">
            <div class="gmz-loader" style="display: none;">
                <div class="loader-inner">
                    <div class="spinner-grow text-info align-self-center loader-lg"></div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="field-wrapper">
                        <label>Check-in Date *</label>
                        <i class="fas fa-calendar-alt"></i>
                        <input type="date" id="checkin-{{ $room->id }}" name="checkin" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-wrapper">
                        <label>Check-out Date *</label>
                        <i class="fas fa-calendar-alt"></i>
                        <input type="date" id="checkout-{{ $room->id }}" name="checkout" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-wrapper">
                        <label>Number of Rooms *</label>
                        <i class="fas fa-bed"></i>
                        <select id="rooms-{{ $room->id }}" name="rooms" required>
                            <option value="">Select rooms</option>
                            @for($i = 1; $i <= $room->number_of_rooms; $i++)
                                <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'Room' : 'Rooms' }}</option>
                                @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-wrapper">
                        <label>Number of Guests *</label>
                        <i class="fas fa-user"></i>
                        <select id="guests-{{ $room->id }}" name="guests" required>
                            <option value="">Select guests</option>
                            @for($i = 1; $i <= $room->people; $i++)
                                <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'Guest' : 'Guests' }}</option>
                                @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-wrapper">
                        <label>First Name *</label>
                        <i class="fas fa-user"></i>
                        <input type="text" id="first_name-{{ $room->id }}" name="first_name" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-wrapper">
                        <label>Last Name *</label>
                        <i class="fas fa-user"></i>
                        <input type="text" id="last_name-{{ $room->id }}" name="last_name" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-wrapper">
                        <label>Email Address *</label>
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email-{{ $room->id }}" name="email" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-wrapper">
                        <label>Phone Number *</label>
                        <i class="fas fa-phone"></i>
                        <input type="tel" id="phone-{{ $room->id }}" name="phone" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="field-wrapper">
                        <label>Special Requests</label>
                        <i class="fas fa-comment"></i>
                        <textarea id="special_requests-{{ $room->id }}" name="special_requests" rows="3"
                            placeholder="Any special requests or notes..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Price Calculation -->
            <div class="price-calculation mt-4 p-3"
                style="background: #f8f9fa; border-radius: 8px; border: 1px solid #e0e0e0;">
                <h6 class="mb-3">Price Summary</h6>
                <div class="calculation-item">
                    <span class="label">Room Price:</span>
                    <span class="value" id="room-price-{{ $room->id }}">${{ number_format($room->price, 0) }}</span>
                </div>
                <div class="calculation-item">
                    <span class="label">Rooms:</span>
                    <span class="value" id="rooms-count-{{ $room->id }}">0</span>
                </div>
                <div class="calculation-item">
                    <span class="label">Nights:</span>
                    <span class="value" id="nights-{{ $room->id }}">0</span>
                </div>
                <div class="calculation-item">
                    <span class="label">Calculation:</span>
                    <span class="value" id="calculation-{{ $room->id }}">0 × 0 = $0</span>
                </div>
                <hr>
                <div class="calculation-item">
                    <span class="label"><strong>Total Price:</strong></span>
                    <span class="value" id="total-price-{{ $room->id }}"
                        style="color: #2e8b57; font-weight: bold;">$0</span>
                </div>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-credit-card me-2"></i>Confirm Booking
                </button>
            </div>
        </form>
    </div>
</div>
@endforeach

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

    .calculation-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
        font-size: 13px;
    }

    .calculation-item .label {
        color: #666;
        font-weight: 500;
    }

    .calculation-item .value {
        color: #333;
        font-weight: 600;
    }

    .price-calculation {
        transition: all 0.3s ease;
    }

    .price-calculation:hover {
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15);
    }

    .booking-form .field-wrapper {
        position: relative;
    }

    .booking-form .field-wrapper input,
    .booking-form .field-wrapper select,
    .booking-form .field-wrapper textarea {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        transition: border-color 0.3s ease;
        font-size: 14px;
    }

    .booking-form .field-wrapper input:focus,
    .booking-form .field-wrapper select:focus,
    .booking-form .field-wrapper textarea:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .booking-form label {
        font-size: 11px;
        font-weight: 600;
        color: #333;
        margin-bottom: 2px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .booking-form {
        margin-bottom: 10px !important;
    }

    .booking-form .mb-4 {
        margin-bottom: 15px !important;
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

    .booking-form .field-wrapper {
        position: relative;
    }

    .booking-form .field-wrapper i {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
        z-index: 2;
        font-size: 12px;
    }

    .booking-form .field-wrapper input,
    .booking-form .field-wrapper select,
    .booking-form .field-wrapper textarea {
        padding: 6px 6px 6px 35px;
        border: 1px solid #ddd;
        border-radius: 6px;
        transition: border-color 0.3s ease;
        font-size: 13px;
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
    
    // Set minimum date to today for all booking forms
    const today = new Date().toISOString().split('T')[0];
    
    // Initialize all booking forms
    const roomPrices = {
        @foreach($rooms as $room)
        {{ $room->id }}: {{ $room->price }},
        @endforeach
    };
    
    // Price calculation function for specific room
    function calculatePrice(roomId) {
        const rooms = parseInt(document.getElementById(`rooms-${roomId}`).value) || 0;
        const checkin = document.getElementById(`checkin-${roomId}`).value;
        const checkout = document.getElementById(`checkout-${roomId}`).value;
        const roomPrice = roomPrices[roomId];
        
        if (rooms > 0 && checkin && checkout) {
            const checkinDate = new Date(checkin);
            const checkoutDate = new Date(checkout);
            
            // Calculate number of nights
            const timeDiff = checkoutDate.getTime() - checkinDate.getTime();
            const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
            
            if (nights > 0) {
                const total = rooms * nights * roomPrice;
                
                // Update display
                document.getElementById(`room-price-${roomId}`).textContent = `$${roomPrice}`;
                document.getElementById(`rooms-count-${roomId}`).textContent = rooms;
                document.getElementById(`nights-${roomId}`).textContent = nights;
                document.getElementById(`calculation-${roomId}`).textContent = `${rooms} × ${nights} × $${roomPrice} = $${total}`;
                document.getElementById(`total-price-${roomId}`).textContent = `$${total}`;
            } else {
                resetPriceDisplay(roomId);
            }
        } else {
            resetPriceDisplay(roomId);
        }
    }
    
    // Reset price display for specific room
    function resetPriceDisplay(roomId) {
        const roomPrice = roomPrices[roomId];
        document.getElementById(`room-price-${roomId}`).textContent = `$${roomPrice}`;
        document.getElementById(`rooms-count-${roomId}`).textContent = '0';
        document.getElementById(`nights-${roomId}`).textContent = '0';
        document.getElementById(`calculation-${roomId}`).textContent = '0 × 0 = $0';
        document.getElementById(`total-price-${roomId}`).textContent = '$0';
    }
    
    // Initialize event listeners for all booking forms
    @foreach($rooms as $room)
    (function() {
        const roomId = {{ $room->id }};
        
        // Set minimum dates
        document.getElementById(`checkin-${roomId}`).min = today;
        document.getElementById(`checkout-${roomId}`).min = today;
        
        // Event listeners for price calculation
        document.getElementById(`rooms-${roomId}`).addEventListener('change', () => calculatePrice(roomId));
        document.getElementById(`checkin-${roomId}`).addEventListener('change', function() {
            // Set minimum checkout date to day after checkin
            const checkinDate = new Date(this.value);
            const nextDay = new Date(checkinDate);
            nextDay.setDate(nextDay.getDate() + 1);
            document.getElementById(`checkout-${roomId}`).min = nextDay.toISOString().split('T')[0];
            calculatePrice(roomId);
        });
        document.getElementById(`checkout-${roomId}`).addEventListener('change', () => calculatePrice(roomId));
        
        // Form submission
        document.getElementById(`booking-form-${roomId}`).addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                room_id: roomId,
                rooms: document.getElementById(`rooms-${roomId}`).value,
                guests: document.getElementById(`guests-${roomId}`).value,
                checkin: document.getElementById(`checkin-${roomId}`).value,
                checkout: document.getElementById(`checkout-${roomId}`).value,
                first_name: document.getElementById(`first_name-${roomId}`).value,
                last_name: document.getElementById(`last_name-${roomId}`).value,
                email: document.getElementById(`email-${roomId}`).value,
                phone: document.getElementById(`phone-${roomId}`).value,
                special_requests: document.getElementById(`special_requests-${roomId}`).value,
                total_price: document.getElementById(`total-price-${roomId}`).textContent
            };
            
            // Show loading
            const loader = document.querySelector(`#booking-form-${roomId} .gmz-loader`);
            loader.style.display = 'block';
            
            // Simulate booking process (replace with actual API call)
            setTimeout(() => {
                loader.style.display = 'none';
                alert('Booking confirmed! You will receive a confirmation email shortly.');
                
                // Reset form
                document.getElementById(`booking-form-${roomId}`).reset();
                resetPriceDisplay(roomId);
                
                // Close modal (if using Magnific Popup)
                if (typeof $.magnificPopup !== 'undefined') {
                    $.magnificPopup.close();
                }
            }, 2000);
        });
    })();
    @endforeach
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