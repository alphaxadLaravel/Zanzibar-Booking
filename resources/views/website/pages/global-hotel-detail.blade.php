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
    $roomImage = $profile['images'][0] ?? \App\Support\HotelOfferMapper::defaultHotelImage();
    $mapLat = $profile['latitude'] ?? $hotel['latitude'] ?? null;
    $mapLng = $profile['longitude'] ?? $hotel['longitude'] ?? null;
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
                    <div class="d-flex align-items-center flex-wrap" style="gap: 16px;">
                        <h2 class="post-title bold mb-0">{{ $profile['name'] ?? $hotel['hotel_name'] }}</h2>
                        <span class="badge bg-primary" style="font-size: 13px; font-weight: 500;">Hotelbeds Partner</span>
                    </div>
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
                    <a href="{{ route('hotels.global.index', ['destination' => $criteria['destination'] ?? 'ZNZ', 'checkIn' => $checkIn, 'checkOut' => $checkOut, 'rooms' => $rooms, 'adults' => $adults, 'children' => $children]) }}"
                        class="small text-primary">Change dates</a>
                </div>
                <p class="text-muted small mb-3">
                    {{ count($rates) }} option(s) for your selected dates
                </p>

                @foreach($rates as $rate)
                    <div class="card mb-4 room-card rounded"
                        style="overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
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
                                                {{ $rate['currency'] ?? 'USD' }} {{ \App\Support\FlightOfferMapper::formatPrice((float) $rate['price']) }}
                                            </span>
                                            <span style="font-size: 13px; color: #888;">/ stay</span>
                                        </div>
                                        @auth
                                            <form method="POST" action="{{ route('hotels.global.select-rate') }}" class="m-0">
                                                @csrf
                                                <input type="hidden" name="rate_key" value="{{ $rate['rate_key'] }}">
                                                <button type="submit" class="btn btn-primary" style="font-size: 13px;">
                                                    <i class="mdi mdi-calendar-check me-1"></i> BOOK ROOM
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-primary" style="font-size: 13px;">
                                                <i class="mdi mdi-calendar-check me-1"></i> BOOK ROOM
                                            </a>
                                        @endauth
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
@endsection
