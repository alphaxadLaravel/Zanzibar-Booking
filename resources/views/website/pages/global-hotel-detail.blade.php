@extends('website.layouts.app')

@section('title', $hotel['hotel_name'] . ' - Partner Hotels')
@section('pages')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('hotels.global.index') }}">Partner Hotels</a></li>
            <li class="breadcrumb-item active">{{ $hotel['hotel_name'] }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <h1 class="h3 mb-1">{{ $hotel['hotel_name'] }}</h1>
            <p class="text-muted">
                {{ $hotel['destination_name'] ?? '' }}
                @php $stars = \App\Support\HotelOfferMapper::categoryStars($hotel['category_code'] ?? null); @endphp
                @if($stars) · {{ str_repeat('★', $stars) }} @endif
            </p>
            <p class="small text-muted mb-4">
                {{ \Carbon\Carbon::parse($criteria['checkIn'] ?? $hotel['check_in'])->format('M j, Y') }}
                –
                {{ \Carbon\Carbon::parse($criteria['checkOut'] ?? $hotel['check_out'])->format('M j, Y') }}
                · {{ $criteria['rooms'] ?? 1 }} room(s) · {{ $criteria['adults'] ?? 2 }} adult(s)
            </p>

            <h2 class="h5 mb-3">Available rooms</h2>

            @foreach($rates as $rate)
                <div class="border rounded p-3 mb-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <div class="fw-semibold">{{ $rate['room_name'] }}</div>
                        @if(!empty($rate['board_name']))
                            <div class="text-muted small">{{ $rate['board_name'] }}</div>
                        @endif
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-primary">{{ $rate['currency'] ?? 'USD' }} {{ \App\Support\FlightOfferMapper::formatPrice((float) $rate['price']) }}</div>
                        <form method="POST" action="{{ route('hotels.global.select-rate') }}" class="mt-2">
                            @csrf
                            <input type="hidden" name="rate_key" value="{{ $rate['rate_key'] }}">
                            <button type="submit" class="btn btn-primary btn-sm">Book this room</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
