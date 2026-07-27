@extends('website.layouts.app')

@section('title', ($profile['name'] ?? $hotel['hotel_name']) . ' - Hotel & Beds')
@section('meta')
<meta name="description" content="{{ Str::limit(strip_tags($profile['description'] ?? ''), 160) }}">
@endsection

@section('pages')
@include('website.components.hotelbeds_gallery', [
    'images' => $profile['images'] ?? [],
    'title' => $profile['name'] ?? $hotel['hotel_name'],
])

<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="{{ route('index') }}">Home</a></li>
            <li><a href="{{ route('hotels.global.index') }}">Hotel &amp; Beds</a></li>
            <li><span>{{ $profile['name'] ?? $hotel['hotel_name'] }}</span></li>
        </ul>
    </div>
</div>

@php
    $checkIn = $criteria['checkIn'] ?? $hotel['check_in'] ?? now()->addDays(7)->format('Y-m-d');
    $checkOut = $criteria['checkOut'] ?? $hotel['check_out'] ?? now()->addDays(9)->format('Y-m-d');
    $rooms = (int) ($criteria['rooms'] ?? 1);
    $adults = (int) ($criteria['adults'] ?? 2);
    $children = (int) ($criteria['children'] ?? 0);
    $location = $profile['address'] ?: ($profile['destination'] ?: ($hotel['destination_name'] ?? ''));
    $galleryImages = ! empty($profile['images']) ? $profile['images'] : [\App\Support\HotelOfferMapper::defaultHotelImage()];
    $roomImage = $galleryImages[0];
    $mapLat = $profile['latitude'] ?? $hotel['latitude'] ?? null;
    $mapLng = $profile['longitude'] ?? $hotel['longitude'] ?? null;
    $nights = max(1, \Carbon\Carbon::parse($checkIn)->diffInDays(\Carbon\Carbon::parse($checkOut)));
@endphp

<style>
    @media (max-width: 991.98px) {
        .view-hotel-row { display: flex; flex-direction: column; }
        .view-hotel-row .view-hotel-order-1 { order: 1; }
        .view-hotel-row .view-hotel-order-2 { order: 2; }
        .view-hotel-row .view-hotel-order-3 { order: 3; }
        .view-hotel-row .view-hotel-order-4 { order: 4; }
    }
</style>

<div class="container">
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row view-hotel-row">
        {{-- Main content --}}
        <div class="col-lg-8 pb-5 view-hotel-order-1">
            <div class="card">
                <div class="card-body">
                    <div class="hotel-star">
                        @include('website.components.star_rating', ['rating' => $starRating])
                    </div>
                    <h2 class="post-title bold mb-0">{{ $profile['name'] ?? $hotel['hotel_name'] }}</h2>
                    @if($location)
                        <p class="location mt-2">
                            <i class="fal fa-map-marker-alt"></i> {{ $location }}
                        </p>
                    @endif

                    <div class="meta">
                        <ul class="meta row gy-2 mb-4" style="list-style: none; padding: 0; margin: 0;">
                            <li class="col-6 col-md-4 d-flex align-items-stretch mb-3 mb-md-0">
                                <div class="d-flex flex-nowrap align-items-center w-100 border rounded bg-white pl-3 py-2 h-100"
                                    style="min-height:70px; border-color: #218080;">
                                    <span class="d-flex align-items-center justify-content-center rounded flex-shrink-0"
                                        style="width:32px; height:32px; background: #e6f4f1 !important; margin-right: 18px;">
                                        <i class="mdi mdi-home-city" style="color: #218080; font-size: 1.2rem;"></i>
                                    </span>
                                    <div class="flex-grow-1" style="min-width:0;">
                                        <div class="fw-bold text-dark" style="font-size: 1rem;">{{ $starRating }} Star Hotel</div>
                                        <div class="text-muted small">Category</div>
                                    </div>
                                </div>
                            </li>
                            <li class="col-6 col-md-4 d-flex align-items-stretch mb-3 mb-md-0">
                                <div class="d-flex flex-nowrap align-items-center w-100 border rounded bg-white px-3 py-2 h-100"
                                    style="min-height:70px; border-color: #218080;">
                                    <span class="d-flex align-items-center justify-content-center rounded flex-shrink-0"
                                        style="width:32px; height:32px; background: #e6f4f1 !important; margin-right: 18px;">
                                        <i class="mdi mdi-currency-usd" style="color: #218080; font-size: 1.2rem;"></i>
                                    </span>
                                    <div class="flex-grow-1" style="min-width:0;">
                                        <div class="fw-bold text-dark" style="font-size: 1rem;">
                                            {{ $currency }} {{ \App\Support\FlightOfferMapper::formatPrice($minPrice) }}
                                        </div>
                                        <div class="text-muted small">From / stay</div>
                                    </div>
                                </div>
                            </li>
                            <li class="col-6 col-md-4 d-flex align-items-stretch mb-3 mb-md-0">
                                <div class="d-flex flex-nowrap align-items-center w-100 border rounded bg-white px-3 py-2 h-100"
                                    style="min-height:70px; border-color: #218080;">
                                    <span class="d-flex align-items-center justify-content-center rounded flex-shrink-0"
                                        style="width:32px; height:32px; background: #e6f4f1 !important; margin-right: 18px;">
                                        <i class="mdi mdi-calendar-range" style="color: #218080; font-size: 1.2rem;"></i>
                                    </span>
                                    <div class="flex-grow-1" style="min-width:0;">
                                        <div class="fw-bold text-dark" style="font-size: 0.95rem;">
                                            {{ \Carbon\Carbon::parse($checkIn)->format('M j') }} – {{ \Carbon\Carbon::parse($checkOut)->format('M j, Y') }}
                                        </div>
                                        <div class="text-muted small">{{ $rooms }} room · {{ $adults }} adult(s)</div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <hr>

                    <section class="description">
                        <h4 class="section-title">Hotel Overview</h4>
                        <div class="section-content">
                            @if(!empty($profile['description']))
                                <p>{!! nl2br(e($profile['description'])) !!}</p>
                            @else
                                <p class="text-muted">Book this partner hotel with live availability and instant confirmation through our Hotelbeds network.</p>
                            @endif
                        </div>
                    </section>

                    <div class="d-none d-lg-block">
                        @if(!empty($profile['facilities']))
                            <hr>
                            <section class="feature">
                                <h4 class="section-title">Features &amp; Facilities</h4>
                                <div class="section-content">
                                    <div class="d-flex flex-wrap" style="gap: 10px;">
                                        @foreach($profile['facilities'] as $facility)
                                            <div class="facility-card d-flex align-items-center px-3 py-2 mb-2"
                                                style="background: #fff; border-radius: 6px; border: 1px solid #e0e0e0; min-height: 38px; flex: 0 0 auto; min-width: 140px; max-width: 280px;">
                                                <i class="mdi mdi-check-circle me-2" style="font-size: 1.2rem; color: #2e8b57; width: 20px; text-align: center;"></i>
                                                <span style="font-size: 13px; font-weight: 500; color: #333; line-height: 1.3;">{{ $facility }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </section>
                        @endif

                        @if($mapLat && $mapLng)
                            <hr>
                            <section class="map">
                                <h4 class="section-title mb-4">Hotel Location On Map</h4>
                                <div id="address-map-container" style="width: 100%; height: 400px">
                                    <iframe width="100%" height="100%" frameborder="0" style="border:0; border-radius: 8px;"
                                        src="https://www.google.com/maps?q={{ $mapLat }},{{ $mapLng }}&output=embed"
                                        allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </section>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Rooms sidebar --}}
        <div class="col-lg-4 view-hotel-order-2">
            <div class="siderbar-single">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                    <h4 class="post-title bold mb-0">Hotel Rooms To Book</h4>
                    <a href="{{ route('hotels.global.index', ['destination' => $criteria['destination'] ?? 'TZ_ALL', 'checkIn' => $checkIn, 'checkOut' => $checkOut, 'rooms' => $rooms, 'adults' => $adults, 'children' => $children]) }}"
                        class="small text-primary">Change dates</a>
                </div>
                <p class="text-muted small mb-3">
                    {{ count($rates) }} option(s) for your selected dates
                </p>

                @foreach($rates as $rateIndex => $rate)
                    @php
                        $rateCurrency = strtoupper((string) ($rate['currency'] ?? $currency));
                        $rateTotal = (float) ($rate['price'] ?? 0);
                        $ratePerNight = $nights > 0 ? $rateTotal / $nights : $rateTotal;
                    @endphp
                    <div class="card mb-4 room-card rounded"
                        style="overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04); cursor: pointer;"
                        onclick="openPartnerRoomModal({{ $rateIndex }})">
                        <div class="row g-0 align-items-center">
                            <div class="col-4 d-flex align-items-center justify-content-center" style="background: #f8f9fa;">
                                <div class="rounded" style="width: 80px; height: 80px; overflow: hidden;">
                                    <img src="{{ $roomImage }}" alt="{{ $rate['room_name'] ?? 'Room' }}"
                                        class="rounded" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </div>
                            <div class="col-8 px-3">
                                <div class="card-body p-3">
                                    <h5 class="card-title mb-1" style="font-size: 1.1rem; font-weight: 600;">
                                        {{ $rate['room_name'] ?? 'Room' }}
                                    </h5>
                                    @if(!empty($rate['board_name']))
                                        <div class="mb-1" style="font-size: 12px; color: #2e8b57; font-weight: 600;">
                                            {{ $rate['board_name'] }}
                                        </div>
                                    @endif
                                    <div class="mb-2" style="font-size: 13px; color: #666;">
                                        <i class="fa fa-user"></i> {{ $adults }} Adult(s)
                                        @if($children > 0)
                                            &nbsp;|&nbsp; <i class="fa fa-child"></i> {{ $children }} Child(ren)
                                        @endif
                                        &nbsp;|&nbsp; <i class="fa fa-door-open"></i> {{ $rooms }} Room(s)
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div>
                                            <span class="fw-bold" style="font-size: 1.1rem; color: #ff5722;">
                                                {{ $rateCurrency }} {{ \App\Support\FlightOfferMapper::formatPrice($ratePerNight) }}
                                            </span>
                                            <span style="font-size: 13px; color: #888;">/ night</span>
                                            <div class="text-muted" style="font-size: 11px;">
                                                {{ $rateCurrency }} {{ \App\Support\FlightOfferMapper::formatPrice($rateTotal) }} / {{ $nights }} night(s)
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary" style="font-size: 13px;"
                                            onclick="event.stopPropagation(); openPartnerRoomModal({{ $rateIndex }})">
                                            <i class="mdi mdi-calendar-check me-1"></i> BOOK ROOM
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-none d-lg-block">
                @include('website.components.contact_card')
            </div>
        </div>

        {{-- Mobile: facilities + map --}}
        <div class="col-12 col-lg-8 d-lg-none view-hotel-order-3 pb-4">
            @if(!empty($profile['facilities']))
                <div class="card">
                    <div class="card-body">
                        <hr>
                        <section class="feature">
                            <h4 class="section-title">Features &amp; Facilities</h4>
                            <div class="section-content">
                                <div class="d-flex flex-wrap" style="gap: 10px;">
                                    @foreach($profile['facilities'] as $facility)
                                        <div class="facility-card d-flex align-items-center px-3 py-2 mb-2"
                                            style="background: #fff; border-radius: 6px; border: 1px solid #e0e0e0; min-height: 38px; flex: 0 0 auto; min-width: 140px; max-width: 280px;">
                                            <i class="mdi mdi-check-circle me-2" style="font-size: 1.2rem; color: #2e8b57;"></i>
                                            <span style="font-size: 13px; font-weight: 500; color: #333;">{{ $facility }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            @endif
        </div>

        @if($mapLat && $mapLng)
            <div class="col-12 col-lg-8 d-lg-none view-hotel-order-4 pb-4">
                <div class="card">
                    <div class="card-body">
                        <section class="map">
                            <h4 class="section-title mb-4">Hotel Location On Map</h4>
                            <div style="width: 100%; height: 320px">
                                <iframe width="100%" height="100%" frameborder="0" style="border:0; border-radius: 8px;"
                                    src="https://www.google.com/maps?q={{ $mapLat }},{{ $mapLng }}&output=embed"
                                    allowfullscreen loading="lazy"></iframe>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-12 col-lg-8 d-lg-none pb-4">
            @include('website.components.contact_card')
        </div>
    </div>
</div>

{{-- Partner room detail modals --}}
@foreach($rates as $rateIndex => $rate)
    @php
        $rateCurrency = strtoupper((string) ($rate['currency'] ?? $currency));
        $rateTotal = (float) ($rate['price'] ?? 0);
        $ratePerNight = $nights > 0 ? $rateTotal / $nights : $rateTotal;
        $stayDates = [];
        $cursor = \Carbon\Carbon::parse($checkIn);
        $lastNight = \Carbon\Carbon::parse($checkOut);
        while ($cursor->lt($lastNight)) {
            $stayDates[] = $cursor->copy();
            $cursor->addDay();
        }
    @endphp
    <div class="modal fade" id="partnerRoomModal{{ $rateIndex }}" tabindex="-1"
        aria-labelledby="partnerRoomModalLabel{{ $rateIndex }}" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center justify-content-between" style="gap: 1rem;">
                    <div class="d-flex align-items-center flex-wrap" style="gap: 1.25rem;">
                        <h5 class="modal-title mb-0" id="partnerRoomModalLabel{{ $rateIndex }}"
                            style="font-size: 1.25rem; font-weight: 600;">
                            {{ $rate['room_name'] ?? 'Room' }}
                        </h5>
                        <div class="price-display d-flex flex-column align-items-start" style="gap: 0.15rem;">
                            <span class="d-flex align-items-end" style="gap: 0.3rem;">
                                <span class="price-amount" style="font-size: 1.5rem; font-weight: 700; color: #ff5722;">
                                    {{ $rateCurrency }} {{ \App\Support\FlightOfferMapper::formatPrice($ratePerNight) }}
                                </span>
                                <span class="price-unit" style="color: #888; font-size: 0.9rem;">/ night</span>
                            </span>
                            <small class="text-muted" style="font-size: 0.75rem;">Pricing per room per night (avg. for your stay)</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close d-flex align-items-center justify-content-center"
                        data-bs-dismiss="modal" aria-label="Close" style="background:none; border:none; box-shadow:none;">
                        <i class="mdi mdi-close" style="font-size: 1.5rem;"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="room-image-gallery position-relative">
                                @if(count($galleryImages) > 1)
                                    <div id="partnerRoomCarousel{{ $rateIndex }}" class="carousel carousel-fade" data-bs-ride="carousel">
                                        <div class="carousel-inner rounded" style="border-radius: 12px; overflow: hidden;">
                                            @foreach($galleryImages as $imgIndex => $imageUrl)
                                                <div class="carousel-item {{ $imgIndex === 0 ? 'active' : '' }}">
                                                    <img src="{{ $imageUrl }}" class="d-block w-100" alt="Room photo"
                                                        style="height: 250px; object-fit: cover;">
                                                </div>
                                            @endforeach
                                        </div>
                                        <button class="carousel-control-prev" type="button"
                                            data-bs-target="#partnerRoomCarousel{{ $rateIndex }}" data-bs-slide="prev"
                                            style="width: 40px; height: 40px; top: 50%; left: 10px; transform: translateY(-50%); background: rgba(0,0,0,0.5); border-radius: 50%; border: none;">
                                            <span class="carousel-control-prev-icon" style="width: 20px; height: 20px;"></span>
                                        </button>
                                        <button class="carousel-control-next" type="button"
                                            data-bs-target="#partnerRoomCarousel{{ $rateIndex }}" data-bs-slide="next"
                                            style="width: 40px; height: 40px; top: 50%; right: 10px; transform: translateY(-50%); background: rgba(0,0,0,0.5); border-radius: 50%; border: none;">
                                            <span class="carousel-control-next-icon" style="width: 20px; height: 20px;"></span>
                                        </button>
                                        <div class="carousel-indicators" style="bottom: 10px; margin-bottom: 0;">
                                            @foreach($galleryImages as $imgIndex => $imageUrl)
                                                <button type="button" data-bs-target="#partnerRoomCarousel{{ $rateIndex }}"
                                                    data-bs-slide-to="{{ $imgIndex }}" class="{{ $imgIndex === 0 ? 'active' : '' }}"
                                                    style="width: 8px; height: 8px; border-radius: 50%; border: none; background: rgba(255,255,255,0.5); margin: 0 3px;"
                                                    aria-label="Slide {{ $imgIndex + 1 }}"></button>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <img src="{{ $galleryImages[0] }}" class="d-block w-100 rounded" alt="Room photo"
                                        style="height: 250px; object-fit: cover;">
                                @endif
                            </div>

                            <div class="d-flex flex-wrap my-3" style="gap: 12px;">
                                <div class="flex-fill" style="min-width: 140px;">
                                    <div class="d-flex align-items-center border rounded bg-white px-3 py-2 h-100"
                                        style="min-height:70px; border-color: #218080;">
                                        <span class="d-flex align-items-center justify-content-center rounded flex-shrink-0"
                                            style="width:32px; height:32px; background: #e6f4f1 !important; margin-right: 16px;">
                                            <i class="mdi mdi-account" style="color: #218080; font-size: 1.2rem;"></i>
                                        </span>
                                        <div>
                                            <div class="fw-bolder text-dark" style="font-size: 1.1rem;">{{ $adults }}</div>
                                            <div class="text-muted small">Adult(s)</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-fill" style="min-width: 140px;">
                                    <div class="d-flex align-items-center border rounded bg-white px-3 py-2 h-100"
                                        style="min-height:70px; border-color: #218080;">
                                        <span class="d-flex align-items-center justify-content-center rounded flex-shrink-0"
                                            style="width:32px; height:32px; background: #e6f4f1 !important; margin-right: 16px;">
                                            <i class="mdi mdi-door-open" style="color: #218080; font-size: 1.2rem;"></i>
                                        </span>
                                        <div>
                                            <div class="fw-bolder text-dark" style="font-size: 1.1rem;">{{ $rooms }}</div>
                                            <div class="text-muted small">Room(s)</div>
                                        </div>
                                    </div>
                                </div>
                                @if(!empty($rate['board_name']))
                                    <div class="flex-fill" style="min-width: 140px;">
                                        <div class="d-flex align-items-center border rounded bg-white px-3 py-2 h-100"
                                            style="min-height:70px; border-color: #218080;">
                                            <span class="d-flex align-items-center justify-content-center rounded flex-shrink-0"
                                                style="width:32px; height:32px; background: #e6f4f1 !important; margin-right: 16px;">
                                                <i class="mdi mdi-silverware-fork-knife" style="color: #218080; font-size: 1.2rem;"></i>
                                            </span>
                                            <div>
                                                <div class="fw-bolder text-dark" style="font-size: 0.95rem;">{{ $rate['board_name'] }}</div>
                                                <div class="text-muted small">Board</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="booking-form-section">
                                <h5 style="color: #333; font-weight: 600; margin-bottom: 20px; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px;">
                                    Book This Room
                                </h5>
                                <div class="alert alert-light border mb-3 py-2" style="font-size: 0.9rem;">
                                    <i class="mdi mdi-information-outline me-1"></i>
                                    <strong>Your search dates:</strong>
                                    {{ \Carbon\Carbon::parse($checkIn)->format('M j, Y') }} –
                                    {{ \Carbon\Carbon::parse($checkOut)->format('M j, Y') }}
                                    ({{ $nights }} night{{ $nights === 1 ? '' : 's' }})
                                    <a href="{{ route('hotels.global.index', ['destination' => $criteria['destination'] ?? 'TZ_ALL', 'checkIn' => $checkIn, 'checkOut' => $checkOut, 'rooms' => $rooms, 'adults' => $adults, 'children' => $children]) }}"
                                        class="ms-1">Change dates</a>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Daily prices per night</label>
                                    <div class="card card-body p-2 border">
                                        <div class="d-flex flex-wrap" style="gap: 4px;">
                                            @foreach($stayDates as $stayDate)
                                                <div class="border rounded p-2 text-center flex-fill"
                                                    style="min-width: 72px; font-size: 0.75rem;">
                                                    <div class="fw-semibold">{{ $stayDate->format('M j') }}</div>
                                                    <div style="color: #dc3545; font-weight: 600; font-size: 0.7rem;">
                                                        {{ $rateCurrency }} {{ \App\Support\FlightOfferMapper::formatPrice($ratePerNight) }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="small text-muted mt-2">
                                            Average nightly rate for your selected stay
                                        </div>
                                    </div>
                                </div>

                                <div class="booking-summary mt-4 p-3"
                                    style="background: #f8f9fa; border-radius: 8px; border-left: 4px solid #ff5722;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 style="color: #333; font-weight: 600; margin-bottom: 5px;">Total Price</h6>
                                            <p class="mb-0" style="font-size: 0.9rem; color: #666;">
                                                {{ $nights }} night(s) × {{ $rooms }} room(s)
                                                <span class="text-muted d-block mt-1" style="font-size: 0.8rem;">(per room/night avg.)</span>
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            <p class="mb-0" style="font-size: 1.5rem; font-weight: 700; color: #ff5722;">
                                                {{ $rateCurrency }} {{ \App\Support\FlightOfferMapper::formatPrice($rateTotal) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('hotels.global.select-rate') }}"
                                    class="partner-room-booking-form mt-4" data-require-login="1">
                                    @csrf
                                    <input type="hidden" name="rate_key" value="{{ $rate['rate_key'] }}">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-calendar-check me-2"></i>
                                        PROCEED TO BOOK
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
<script>
    function openPartnerRoomModal(rateIndex) {
        var modalEl = document.getElementById('partnerRoomModal' + rateIndex);
        if (!modalEl || typeof bootstrap === 'undefined') return;
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    }
</script>
@endpush
