@extends('website.layouts.app')

@section('title', 'Booking Lookup - Zanzibar Bookings')

@section('meta')
<meta name="description" content="Look up your booking details using your booking code">
@endsection

@section('pages')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card border rounded-1">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="mdi mdi-magnify me-2"></i>Booking Lookup
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('booking.lookup') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="booking_code" class="form-label">Booking Code *</label>
                            <input 
                                type="text" 
                                class="form-control @error('booking_code') is-invalid @enderror" 
                                id="booking_code" 
                                name="booking_code" 
                                value="{{ old('booking_code') }}"
                                placeholder="e.g., ZBOOK-123456"
                                required
                            >
                            @error('booking_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text text-muted">
                                Your booking code was sent to your email after completing the booking
                            </small>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-magnify me-2"></i>Look Up Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Section -->
            <div class="card border rounded-1 mt-3">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="mdi mdi-help-circle me-2"></i>Need Help?
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        Can't find your booking code? Check your email inbox or contact our support team.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="mailto:support@zanzibarbookings.com" class="btn btn-outline-primary btn-sm">
                            <i class="mdi mdi-email me-1"></i>Email Support
                        </a>
                        <a href="https://wa.me/message/JMDWFIGBWX5TI1" target="_blank" class="btn btn-outline-success btn-sm">
                            <i class="mdi mdi-whatsapp me-1"></i>WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
