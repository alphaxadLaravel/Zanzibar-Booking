@extends('website.layouts.app')

@include('website.components.deal_seo', ['deal' => $hotel])

@section('pages')
@include('website.components.deal_gallery', ['deal' => $hotel])


<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="{{ route('index') }}">Home</a></li>
            <li><span>{{ $hotel->title }}</span></li>
        </ul>
    </div>
</div>

<style>
    /* View Hotel: mobile/tablet order = Overview → Rooms → Features → Map → Need Help? → Video → Reviews */
    @media (max-width: 991.98px) {
        .view-hotel-row {
            display: flex;
            flex-direction: column;
        }
        .view-hotel-row .view-hotel-order-1 { order: 1; }
        .view-hotel-row .view-hotel-order-2 { order: 2; }
        .view-hotel-row .view-hotel-order-3 { order: 3; }
        .view-hotel-row .view-hotel-order-4 { order: 4; }
        .view-hotel-row .view-hotel-order-5 { order: 5; }
        .view-hotel-row .view-hotel-order-6 { order: 6; }
        .view-hotel-row .view-hotel-order-7 { order: 7; }
    }
</style>
<div class="container">
    <div class="row view-hotel-row">
        {{-- ############## MAIN CONTENT (mobile order 1) ############################# --}}
        <div class="col-lg-8 pb-5 view-hotel-order-1">
            <div class="card">
                <div class="card-body">
                    <div class="hotel-star">
                        @include('website.components.star_rating', ['rating' => $hotel->star_rating ?? 5])
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


                    @include('website.components.deal_meta_info', ['deal' => $hotel, 'type' => 'hotel'])
                    <hr>
                    @include('website.components.deal_description', ['deal' => $hotel, 'title' => 'Hotel Overview'])
                    {{-- Features, nearby, policies, video, map: desktop here; mobile shown after Rooms/Map/Video/Reviews (order 6) --}}
                    <div class="d-none d-lg-block">
                        <hr>
                        @include('website.components.deal_features', ['deal' => $hotel, 'type' => 'include', 'title' =>
                        'Features & Facilities'])
                        <hr>
                        @include('website.components.deal_video', ['deal' => $hotel])
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
                        @include('website.components.deal_policies', ['deal' => $hotel])
                        <hr>
                        @include('website.components.deal_map', ['deal' => $hotel, 'title' => 'Hotel Location On Map'])
                    </div>

                </div>
            </div>
            {{-- Reviews: desktop here, mobile order 5 (shown in block below) --}}
            <div class="d-none d-lg-block">
                <hr>
                @include('website.components.deal_reviews', ['deal' => $hotel, 'paginatedReviews' => $paginatedReviews ?? collect(), 'reviewTitle' => 'Reviews for this Hotel'])
            </div>
        </div>

        {{-- ################ ROOMS LIST (mobile order 2) ################ --}}
        <div class="col-lg-4 view-hotel-order-2">
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
                                        <span class="fw-bold" style="font-size: 1.1rem; color: #ff5722;">{{ priceForUser($room->getMinPrice() ?: ($room->price ?? $hotel->base_price), 0) }}</span>
                                        <span style="font-size: 13px; color: #888;">{{ $room->getPriceUnitLabel() }}</span>
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

            {{-- Contact Information (desktop only in sidebar; on mobile shown after Map) --}}
            <div class="d-none d-lg-block">
                @include('website.components.contact_card')
            </div>
        </div>

        {{-- ############## FEATURES, NEARBY, POLICIES (mobile only, order 3 — after Rooms) ############################# --}}
        <div class="col-12 col-lg-8 d-lg-none view-hotel-order-3 pb-4">
            <div class="card">
                <div class="card-body">
                    <hr>
                    @include('website.components.deal_features', ['deal' => $hotel, 'type' => 'include', 'title' => 'Features & Facilities'])
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
                                    <span style="font-size: 13px; font-weight: 500; color: #333; line-height: 1.3; flex:1;">{{ $location->title }}</span>
                                    <span class="ms-2 text-nowrap" style="font-size: 13px; color: #2e8b57; font-weight: 600;">{{ $location->formatted_distance }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                    <hr>
                    @endif
                    @include('website.components.deal_policies', ['deal' => $hotel])
                </div>
            </div>
        </div>

        {{-- ############## MAP (mobile only, order 4) ############################# --}}
        <div class="col-12 col-lg-8 d-lg-none view-hotel-order-4 pb-4">
            <div class="card">
                <div class="card-body">
                    @include('website.components.deal_map', ['deal' => $hotel, 'title' => 'Hotel Location On Map'])
                </div>
            </div>
        </div>

        {{-- ############## NEED HELP? (mobile only, order 5 — after Map) ############################# --}}
        <div class="col-12 col-lg-8 d-lg-none view-hotel-order-5 pb-4">
            @include('website.components.contact_card')
        </div>

        {{-- ############## VIDEO (mobile only, order 6) ############################# --}}
        <div class="col-12 col-lg-8 d-lg-none view-hotel-order-6 pb-4">
            <div class="card">
                <div class="card-body">
                    @include('website.components.deal_video', ['deal' => $hotel])
                </div>
            </div>
        </div>

        {{-- ############## REVIEWS (mobile only, order 7) ############################# --}}
        <div class="col-12 col-lg-8 d-lg-none view-hotel-order-7 pb-5">
            @include('website.components.deal_reviews', ['deal' => $hotel, 'paginatedReviews' => $paginatedReviews ?? collect(), 'reviewTitle' => 'Reviews for this Hotel'])
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
                            @include('website.components.star_rating', ['rating' => $hotel->star_rating ?? 5, 'size' =>
                            'small'])
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
                                <span class="_retail" style="color:#2e8b57;font-size:1.3rem;font-weight:600;">{{ priceForUser($nearbyHotel->base_price, 2) }}</span>
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
                            @include('website.components.star_rating', ['rating' => $hotel->star_rating ?? 5, 'size' =>
                            'small'])
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
                                <span class="_retail" style="color:#2e8b57;font-size:1.3rem;font-weight:600;">{{ priceForUser($tour->base_price, 2) }}</span>
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
                    <div class="price-display d-flex flex-column align-items-start" style="gap: 0.15rem;">
                        <span class="d-flex align-items-end" style="gap: 0.3rem;">
                            <span class="price-amount" style="font-size: 1.5rem; font-weight: 700; color: #ff5722;">{{ priceForUser($room->getMinPrice() ?: ($room->price ?? $hotel->base_price), 0) }}</span>
                            <span class="price-unit" style="color: #888; font-size: 0.9rem;">{{ $room->getPriceUnitLabel() }}</span>
                        </span>
                        <small class="text-muted" style="font-size: 0.75rem;">{{ ($room->price_type ?? 'per_night') === 'per_person_per_night' ? 'Pricing per person per night' : 'Pricing per room per night' }}</small>
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
                            <h5 style="color: #333; font-weight: 600; margin-bottom: 20px; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px;">Book This Room</h5>
                            <div class="alert alert-light border mb-3 py-2" style="font-size: 0.9rem;">
                                <i class="mdi mdi-information-outline me-1"></i>
                                <strong>Pricing:</strong> {{ ($room->price_type ?? 'per_night') === 'per_person_per_night' ? 'Per person per night' : 'Per room per night' }}
                            </div>
                            <form action="{{ route('book-room') }}" method="POST">
                                @csrf
                                <input type="hidden" name="deal_id" value="{{ $hotel->id }}">
                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                                <input type="hidden" name="price" value="">
                                

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Select Check-in & Check-out Dates</label>
                                    <p class="text-muted small mb-2">Click on the calendar to select your stay. Prices shown per night.</p>
                                    <div id="inlineCalendar{{ $room->id }}" class="mb-2"></div>
                                    <input type="hidden" name="check_in" id="check_in{{ $room->id }}" value="{{ old('check_in', date('Y-m-d')) }}" required>
                                    <input type="hidden" name="check_out" id="check_out{{ $room->id }}" value="{{ old('check_out', date('Y-m-d', strtotime('+1 day'))) }}" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="number_rooms{{ $room->id }}" class="form-label">Number of Rooms</label>
                                        <select 
                                            class="form-control" 
                                            id="number_rooms{{ $room->id }}"
                                            name="number_of_rooms" 
                                            required 
                                            onchange="calculatePrice{{ $room->id }}()"
                                        >
                                            @for($i = 1; $i <= ($room->number_of_rooms ?? 1); $i++)
                                                <option value="{{ $i }}" {{ old('number_of_rooms', 1) == $i ? 'selected' : '' }}>
                                                    {{ $i }} Room{{ $i > 1 ? 's' : '' }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="adult{{ $room->id }}" class="form-label">Adults</label>
                                        <select 
                                            class="form-control" 
                                            id="adult{{ $room->id }}" 
                                            name="adults" 
                                            required
                                            onchange="calculatePrice{{ $room->id }}()"
                                        >
                                            @for($i = 1; $i <= 20; $i++)
                                                <option value="{{ $i }}" {{ old('adults', 2) == $i ? 'selected' : '' }}>
                                                    {{ $i }} Adult{{ $i > 1 ? 's' : '' }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-12 not-even:mb-3">
                                        <label for="children{{ $room->id }}" class="form-label">Children</label>
                                        <select 
                                            class="form-control" 
                                            id="children{{ $room->id }}" 
                                            name="children"
                                            onchange="calculatePrice{{ $room->id }}()"
                                        >
                                            <option value="0" {{ old('children', 0) == 0 ? 'selected' : '' }}>No Children</option>
                                            @for($i = 1; $i <= 20; $i++)
                                                <option value="{{ $i }}" {{ old('children') == $i ? 'selected' : '' }}>
                                                    {{ $i }} Child{{ $i > 1 ? 'ren' : '' }}
                                                </option>
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
                                                <span id="rooms{{ $room->id }}">1</span> room(s)
                                                <span id="price_detail{{ $room->id }}">—</span>
                                                <span class="text-muted d-block mt-1" style="font-size: 0.8rem;">{{ ($room->price_type ?? 'per_night') === 'per_person_per_night' ? '(per person/night)' : '(per room/night)' }}</span>
                                                <span id="selected_range{{ $room->id }}" class="d-block mt-1 text-muted" style="font-size: 0.8rem;"></span>
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            <p class="mb-0"
                                                style="font-size: 1.5rem; font-weight: 700; color: #ff5722;">
                                                @php
                                                    $roomPriceUsd = $room->price ?? $hotel->base_price;
                                                    $roomPriceData = priceForUser($roomPriceUsd, 2, true);
                                                @endphp
                                                <span id="total_price{{ $room->id }}"
                                                    data-rate="{{ userCurrencyRate() }}"
                                                    data-symbol="{{ e($roomPriceData['symbol']) }}"
                                                    data-currency="{{ e($roomPriceData['currency']) }}"
                                                    data-decimals="2">
                                                    {{ $roomPriceData['formatted'] }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer mt-4 p-0">
                                    <button type="submit" class="btn btn-primary w-100" name="book_room" >
                                        <i class="fas fa-calendar-check me-2"></i>
                                       PROCEED TO BOOK
                                    </button>

                                    <button type="submit" class="btn btn-outline-secondary w-100" style="font-size: 13px;" name="add_cart" >
                                        <i class="mdi mdi-cart-plus me-1"></i> ADD TO CART
                                    </button>
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

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-day .flatpickr-day-price { margin-top: 2px; }
    .flatpickr-day { min-height: 52px; display: flex; flex-direction: column; align-items: center; justify-content: center; }
</style>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    function openRoomDetailsModal(roomId) {
        const modal = new bootstrap.Modal(document.getElementById('roomDetailsModal' + roomId));
        modal.show();
        if (typeof window['calculatePrice' + roomId] === 'function') {
            window['calculatePrice' + roomId]();
        }
    }

function formatPriceForUserHotel(usdTotal, rate, symbol, currency, decimals) {
    const amount = usdTotal * rate;
    const formatted = amount.toLocaleString(undefined, { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
    return currency === 'USD' ? (symbol + formatted) : (symbol + ' ' + formatted);
}
@foreach($rooms as $room)
function calculatePrice{{ $room->id }}() {
    const checkIn = document.getElementById('check_in{{ $room->id }}')?.value;
    const checkOut = document.getElementById('check_out{{ $room->id }}')?.value;
    const numberRooms = parseInt(document.getElementById('number_rooms{{ $room->id }}')?.value || 1);
    const adults = parseInt(document.getElementById('adult{{ $room->id }}')?.value || 1);
    const children = parseInt(document.getElementById('children{{ $room->id }}')?.value || 0);

    let nights = 1;
    if (checkIn && checkOut) {
        const checkInDate = new Date(checkIn);
        const checkOutDate = new Date(checkOut);
        if (checkOutDate > checkInDate) {
            nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
        }
    }

    document.getElementById('nights{{ $room->id }}').textContent = nights;
    document.getElementById('rooms{{ $room->id }}').textContent = numberRooms;

    if (!checkIn || !checkOut) {
        document.getElementById('price_detail{{ $room->id }}').textContent = '—';
        const totalEl = document.getElementById('total_price{{ $room->id }}');
        if (totalEl) totalEl.textContent = '—';
        return;
    }

    document.getElementById('price_detail{{ $room->id }}').textContent = '...';
    const totalEl = document.getElementById('total_price{{ $room->id }}');
    if (totalEl) totalEl.textContent = '...';

    fetch('{{ url("/room/{$room->id}/price") }}?check_in=' + encodeURIComponent(checkIn) + '&check_out=' + encodeURIComponent(checkOut) + '&number_rooms=' + numberRooms + '&adults=' + adults + '&children=' + children)
        .then(r => r.json())
        .then(data => {
            const totalPrice = data.total_price || 0;
            if (totalEl) {
                const rate = parseFloat(totalEl.dataset.rate) || 1;
                const symbol = totalEl.dataset.symbol || '$';
                const currency = totalEl.dataset.currency || 'USD';
                const decimals = parseInt(totalEl.dataset.decimals, 10) || 2;
                totalEl.textContent = formatPriceForUserHotel(totalPrice, rate, symbol, currency, decimals);
            }
            document.getElementById('price_detail{{ $room->id }}').textContent = '';
            const rangeEl = document.getElementById('selected_range{{ $room->id }}');
            if (rangeEl) rangeEl.textContent = checkIn + ' to ' + checkOut;

            const form = document.querySelector('#roomDetailsModal{{ $room->id }} form');
            const priceInput = form?.querySelector('input[name="price"]');
            if (priceInput) priceInput.value = totalPrice.toFixed(2);
        })
        .catch(() => {
            document.getElementById('price_detail{{ $room->id }}').textContent = '(select dates)';
            if (totalEl) totalEl.textContent = '—';
        });

    if (checkIn) {
        const checkInDate = new Date(checkIn);
        checkInDate.setDate(checkInDate.getDate() + 1);
        const coEl = document.getElementById('check_out{{ $room->id }}');
        if (coEl) coEl.min = checkInDate.toISOString().split('T')[0];
    }
}
@endforeach

    (function initBookingFlatpickr() {
        const priceCache = {};
        @foreach($rooms as $room)
        (function() {
            const roomId = {{ $room->id }};
            const calendarEl = document.getElementById('inlineCalendar' + roomId);
            if (!calendarEl) return;
            const checkIn = '{{ old("check_in", date("Y-m-d")) }}';
            const checkOut = '{{ old("check_out", date("Y-m-d", strtotime("+1 day"))) }}';

            const fetchPrices = (year, month) => {
                const adults = parseInt(document.getElementById('adult' + roomId)?.value || 1);
                const children = parseInt(document.getElementById('children' + roomId)?.value || 0);
                const key = year + '-' + month;
                if (priceCache[roomId] && priceCache[roomId][key]) return Promise.resolve(priceCache[roomId][key]);
                return fetch('{{ url("/room") }}/' + roomId + '/prices-calendar?year=' + year + '&month=' + month + '&adults=' + adults + '&children=' + children)
                    .then(r => r.json())
                    .then(data => {
                        if (!priceCache[roomId]) priceCache[roomId] = {};
                        (data.prices || []).forEach(p => { priceCache[roomId][p.date] = p.price; });
                        priceCache[roomId][key] = true;
                        return data.prices || [];
                    });
            };

            flatpickr(calendarEl, {
                inline: true,
                mode: 'range',
                dateFormat: 'Y-m-d',
                minDate: 'today',
                defaultDate: [checkIn, checkOut],
                onChange: function(sel) {
                    const checkInEl = document.getElementById('check_in' + roomId);
                    const checkOutEl = document.getElementById('check_out' + roomId);
                    const rangeEl = document.getElementById('selected_range' + roomId);
                    if (sel[0]) checkInEl && (checkInEl.value = sel[0]);
                    if (sel[1]) checkOutEl && (checkOutEl.value = sel[1]);
                    if (rangeEl) rangeEl.textContent = sel[0] && sel[1] ? sel[0] + ' to ' + sel[1] : '';
                    if (typeof window['calculatePrice' + roomId] === 'function') window['calculatePrice' + roomId]();
                },
                onReady: function(sel, dateStr, fp) {
                    fetchPrices(fp.currentYear, fp.currentMonth + 1).then(() => fp.redraw());
                    const checkInEl = document.getElementById('check_in' + roomId);
                    const checkOutEl = document.getElementById('check_out' + roomId);
                    const rangeEl = document.getElementById('selected_range' + roomId);
                    if (checkIn && checkOut && rangeEl) rangeEl.textContent = checkIn + ' to ' + checkOut;
                },
                onMonthChange: function(sel, dateStr, fp) {
                    fetchPrices(fp.currentYear, fp.currentMonth + 1).then(() => fp.redraw());
                },
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    const totalEl = document.getElementById('total_price' + roomId);
                    const rate = totalEl ? parseFloat(totalEl.dataset.rate) || 1 : 1;
                    const symbol = totalEl?.dataset.symbol || '$';
                    const currency = totalEl?.dataset.currency || 'USD';
                    const price = (priceCache[roomId] || {})[dStr];
                    if (price !== undefined) {
                        const fmt = (currency === 'USD' ? symbol : symbol + ' ') + (price * rate).toFixed(0);
                        const span = document.createElement('span');
                        span.className = 'flatpickr-day-price';
                        span.style.cssText = 'display:block;font-size:9px;color:#ff5722;font-weight:600;';
                        span.textContent = fmt;
                        dayElem.appendChild(span);
                    }
                }
            });
        })();
        @endforeach
    })();
</script>
@endpush

@endsection