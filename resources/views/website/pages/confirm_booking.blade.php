@extends('website.layouts.app')

@section('title', 'Book Now - Zanzibar Bookings')
@section('meta')
<meta name="description" content="Book your Zanzibar adventure - tours, hotels, cars and more">
@endsection

@section('pages')
<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="{{ route('index') }}">Home</a></li>
            <li><span>Book Now</span></li>
        </ul>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <!-- Deal Details Section (4 columns) -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle mr-2"></i>
                        Deal Details
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Deal Image -->
                    <div class="mb-3">
                        <img src="{{ $deal->cover_photo ? asset('storage/' . $deal->cover_photo) : asset('images/default-placeholder.jpg') }}" 
                             alt="{{ $deal->title }}" class="img-fluid rounded" style="height: 180px; object-fit: cover;">
                    </div>
                    
                    <!-- Deal Info -->
                    <div>
                        <h5 class="mb-3">{{ $deal->title }}</h5>
                        
                        <div class="mb-3">
                            <div class="mb-2">
                                <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                                <span class="text-muted">{{ $deal->location }}</span>
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-tag text-primary mr-2"></i>
                                <span class="text-muted">{{ ucfirst($deal->type) }}</span>
                            </div>
                            @if($deal->ratings)
                            <div class="mb-3">
                                <i class="fas fa-star text-warning mr-2"></i>
                                <span class="text-muted">{{ number_format($deal->ratings, 1) }} Rating</span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="bg-light border rounded p-3 text-center">
                            <div class="mb-1">
                                <span class="text-muted">USD</span>
                                <span class="h4 text-primary font-weight-bold">{{ number_format($deal->base_price, 0) }}</span>
                            </div>
                            <small class="text-muted">
                                @if($deal->type == 'tour')
                                    per person
                                @elseif($deal->type == 'car')
                                    per day
                                @elseif(in_array($deal->type, ['hotel', 'apartment']))
                                    per night
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Booking Form Section (8 columns) -->
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Complete Your Booking
                    </h5>
                </div>
                <div class="card-body">

                    <!-- Booking Form -->
                    <form id="bookingForm" method="POST" action="{{ route('process.booking') }}">
                        @csrf
                        <input type="hidden" name="deal_id" value="{{ $deal->id }}">
                        <input type="hidden" name="deal_type" value="{{ $deal->type }}">
                        
                        <!-- Booking Details -->
                        <div class="border-bottom pb-3 mb-4">
                            <h6 class="mb-3">
                                <i class="fas fa-calendar mr-2"></i>Booking Details
                            </h6>
                            @if($deal->type == 'tour')
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="check_in" class="form-label">Tour Date *</label>
                                    <input type="date" class="form-control" id="check_in" name="check_in" placeholder="Select tour date" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="guests" class="form-label">Guests *</label>
                                    <select class="form-control" id="guests" name="guests" required>
                                        <option value="">Select number of guests</option>
                                        @for($i = 1; $i <= 8; $i++)
                                            <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'Guest' : 'Guests' }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            @elseif($deal->type == 'car')
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="pickup_date" class="form-label">Pickup Date *</label>
                                    <input type="date" class="form-control" id="pickup_date" name="pickup_date" placeholder="Select pickup date" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="return_date" class="form-label">Return Date *</label>
                                    <input type="date" class="form-control" id="return_date" name="return_date" placeholder="Select return date" required>
                                </div>
                            </div>
                            @elseif($deal->type == 'hotel')
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="check_in" class="form-label">Check-in *</label>
                                    <input type="date" class="form-control" id="check_in" name="check_in" placeholder="Select check-in date" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="check_out" class="form-label">Check-out *</label>
                                    <input type="date" class="form-control" id="check_out" name="check_out" placeholder="Select check-out date" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="guests" class="form-label">Guests *</label>
                                    <select class="form-control" id="guests" name="guests" required>
                                        <option value="">Select number of guests</option>
                                        @for($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'Guest' : 'Guests' }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="rooms" class="form-label">Rooms</label>
                                    <select class="form-control" id="rooms" name="rooms">
                                        <option value="1">1 Room</option>
                                        <option value="2">2 Rooms</option>
                                        <option value="3">3 Rooms</option>
                                    </select>
                                </div>
                            </div>
                            @elseif($deal->type == 'apartment')
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="check_in" class="form-label">Check-in *</label>
                                    <input type="date" class="form-control" id="check_in" name="check_in" placeholder="Select check-in date" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="check_out" class="form-label">Check-out *</label>
                                    <input type="date" class="form-control" id="check_out" name="check_out" placeholder="Select check-out date" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="guests" class="form-label">Guests *</label>
                                    <select class="form-control" id="guests" name="guests" required>
                                        <option value="">Select number of guests</option>
                                        @for($i = 1; $i <= 8; $i++)
                                            <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'Guest' : 'Guests' }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Personal Information -->
                        <div class="border-bottom pb-3 mb-4">
                            <h6 class="mb-3">
                                <i class="fas fa-user mr-2"></i>Personal Information
                            </h6>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="first_name" class="form-label">First Name *</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter your first name" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="last_name" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter your last name" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="phone" class="form-label">Phone *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" required>
                                </div>
                            </div>
                        </div>

                        <!-- Payment & Submit -->
                        <div>
                            <h6 class="mb-3">
                                <i class="fas fa-credit-card mr-2"></i>Payment Method
                            </h6>
                            <div class="row mb-4">
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" value="pesapal" id="pesapal" checked>
                                        <label class="form-check-label" for="pesapal">
                                            <i class="fas fa-mobile-alt mr-2"></i>Pay Online
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" value="offline" id="offline">
                                        <label class="form-check-label" for="offline">
                                            <i class="fas fa-handshake mr-2"></i>Pay on Arrival
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Terms & Submit -->
                            <div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="agree_terms" name="agree_terms" required>
                                    <label class="form-check-label" for="agree_terms">
                                        I agree to the <a href="#" class="text-primary">Terms and Conditions</a>
                                    </label>
                                </div>
                                
                                <div class="bg-light border rounded p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <div class="text-muted mb-1">Total Amount</div>
                                            <div>
                                                <span class="text-muted">USD</span>
                                                <span class="h4 text-success font-weight-bold">{{ number_format($deal->base_price, 0) }}</span>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-lg" id="bookBtn">
                                            <i class="fas fa-calendar-check mr-2"></i>
                                            Complete Booking
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Payment method selection
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const bookBtn = document.getElementById('bookBtn');
    
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'pesapal') {
                bookBtn.innerHTML = '<i class="fal fa-credit-card mr-2"></i>Complete Booking & Pay';
            } else {
                bookBtn.innerHTML = '<i class="fal fa-handshake mr-2"></i>Complete Booking';
            }
        });
    });
    
    // Form validation
    const form = document.getElementById('bookingForm');
    const requiredFields = form.querySelectorAll('[required]');
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.style.borderColor = '#dc3545';
            } else {
                field.style.borderColor = '#e9ecef';
            }
        });
        
        const termsCheckbox = document.getElementById('agree_terms');
        if (!termsCheckbox.checked) {
            isValid = false;
            alert('Please agree to the Terms and Conditions');
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields');
        }
    });
    
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.setAttribute('min', today);
    });
});
</script>
@endsection
