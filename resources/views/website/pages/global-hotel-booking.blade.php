@extends('website.layouts.app')

@section('title', 'Book ' . ($offer['hotel_name'] ?? 'Hotel'))
@section('pages')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="h4 mb-3">Guest details</h1>

            <div class="border rounded p-3 mb-4 bg-light">
                <div class="fw-semibold">{{ $offer['hotel_name'] }}</div>
                <div class="small text-muted">{{ $offer['room_name'] }} @if(!empty($offer['board_name'])) · {{ $offer['board_name'] }} @endif</div>
                <div class="small mt-1">
                    {{ \Carbon\Carbon::parse($offer['check_in'])->format('M j, Y') }}
                    – {{ \Carbon\Carbon::parse($offer['check_out'])->format('M j, Y') }}
                </div>
                <div class="fw-bold text-primary mt-2">
                    {{ $offer['currency'] ?? 'USD' }} {{ \App\Support\FlightOfferMapper::formatPrice((float) $offer['price']) }}
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('hotels.global.book', ['token' => $token]) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Lead guest first name</label>
                        <input type="text" name="holder_name" class="form-control" value="{{ old('holder_name', auth()->user()?->firstname) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Lead guest last name</label>
                        <input type="text" name="holder_surname" class="form-control" value="{{ old('holder_surname', auth()->user()?->lastname) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', auth()->user()?->email) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', auth()->user()?->phone) }}" required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Continue to payment</button>
                    <a href="{{ route('hotels.global.show', ['hotelCode' => $offer['hotel_code']]) }}" class="btn btn-link">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
