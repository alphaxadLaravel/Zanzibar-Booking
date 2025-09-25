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
                        
                                <!-- Price Display -->
                                <div class="bg-light border rounded p-3 text-center" id="price-display">
                                    <div class="mb-1">
                                        <span class="text-muted">USD</span>
                                        <span class="h4 text-primary font-weight-bold" id="display-price">
                                            @if($room && $room->price > 0)
                                                {{ number_format($room->price, 0) }}
                                            @else
                                                {{ number_format($deal->base_price, 0) }}
                                            @endif
                                        </span>
                                    </div>
                                    <small class="text-muted" id="price-unit">
                                        @if($deal->type == 'tour')
                                            per person
                                        @elseif($deal->type == 'car')
                                            per day
                                        @elseif(in_array($deal->type, ['hotel', 'apartment']))
                                            @if($room && $room->price > 0)
                                                {{ $room->title }} - per night
                                            @else
                                                per night
                                            @endif
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
                    <!-- Display validation errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6 class="alert-heading">Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Booking Form -->
                    <form id="bookingForm" method="POST" action="{{ route('process.booking') }}">
                        @csrf
                        <input type="hidden" name="deal_id" value="{{ $deal->id }}">
                        @if($room)
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        @endif
                        
                        <!-- Booking Details -->
                        <div class="border-bottom pb-3 mb-4">
                            <h6 class="mb-3">
                                <i class="fas fa-calendar mr-2"></i>Booking Details
                            </h6>
                            @if($deal->type == 'tour')
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="pickup_location" class="form-label">Pickup Location *</label>
                                    <input type="text" class="form-control" id="pickup_location" name="pickup_location" placeholder="Enter pickup location" value="{{ old('pickup_location') }}" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="pickup_time" class="form-label">Pickup Time *</label>
                                    <input type="time" class="form-control" id="pickup_time" name="pickup_time" value="{{ old('pickup_time') }}" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="adult" class="form-label">Adults *</label>
                                    <select class="form-control" id="adult" name="adult" required>
                                        <option value="">Select adults</option>
                                        @for($i = 1; $i <= 8; $i++)
                                            <option value="{{ $i }}" {{ old('adult') == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'Adult' : 'Adults' }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="children" class="form-label">Children</label>
                                    <select class="form-control" id="children" name="children">
                                        <option value="0" {{ old('children', 0) == 0 ? 'selected' : '' }}>0 Children</option>
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ old('children') == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'Child' : 'Children' }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            @elseif($deal->type == 'car')
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="pickup_location" class="form-label">Pickup Location *</label>
                                    <input type="text" class="form-control" id="pickup_location" name="pickup_location" placeholder="Enter pickup location" value="{{ old('pickup_location') }}" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="return_location" class="form-label">Return Location *</label>
                                    <input type="text" class="form-control" id="return_location" name="return_location" placeholder="Enter return location" value="{{ old('return_location') }}" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="pickup_time" class="form-label">Pickup Time *</label>
                                    <input type="time" class="form-control" id="pickup_time" name="pickup_time" value="{{ old('pickup_time') }}" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="return_time" class="form-label">Return Time *</label>
                                    <input type="time" class="form-control" id="return_time" name="return_time" value="{{ old('return_time') }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="need_driver" id="need_driver" value="1" {{ old('need_driver') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="need_driver">
                                            I need a driver
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @elseif($deal->type == 'hotel')
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="check_in" class="form-label">Check-in *</label>
                                    <input type="date" class="form-control" id="check_in" name="check_in" placeholder="Select check-in date" value="{{ old('check_in') }}" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="check_out" class="form-label">Check-out *</label>
                                    <input type="date" class="form-control" id="check_out" name="check_out" placeholder="Select check-out date" value="{{ old('check_out') }}" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="adult" class="form-label">Adults *</label>
                                    <select class="form-control" id="adult" name="adult" required>
                                        <option value="">Select adults</option>
                                        @for($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}" {{ old('adult') == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'Adult' : 'Adults' }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="children" class="form-label">Children</label>
                                    <select class="form-control" id="children" name="children">
                                        <option value="0" {{ old('children', 0) == 0 ? 'selected' : '' }}>0 Children</option>
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ old('children') == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'Child' : 'Children' }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="number_rooms" class="form-label">Number of Rooms *</label>
                                    <select class="form-control" id="number_rooms" name="number_rooms" required>
                                        <option value="1" {{ old('number_rooms', 1) == 1 ? 'selected' : '' }}>1 Room</option>
                                        <option value="2" {{ old('number_rooms') == 2 ? 'selected' : '' }}>2 Rooms</option>
                                        <option value="3" {{ old('number_rooms') == 3 ? 'selected' : '' }}>3 Rooms</option>
                                        <option value="4" {{ old('number_rooms') == 4 ? 'selected' : '' }}>4 Rooms</option>
                                    </select>
                                </div>
                            </div>
                            @elseif($deal->type == 'apartment')
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="check_in" class="form-label">Check-in *</label>
                                    <input type="date" class="form-control" id="check_in" name="check_in" placeholder="Select check-in date" value="{{ old('check_in') }}" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="check_out" class="form-label">Check-out *</label>
                                    <input type="date" class="form-control" id="check_out" name="check_out" placeholder="Select check-out date" value="{{ old('check_out') }}" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="adult" class="form-label">Adults *</label>
                                    <select class="form-control" id="adult" name="adult" required>
                                        <option value="">Select adults</option>
                                        @for($i = 1; $i <= 8; $i++)
                                            <option value="{{ $i }}" {{ old('adult') == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'Adult' : 'Adults' }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="children" class="form-label">Children</label>
                                    <select class="form-control" id="children" name="children">
                                        <option value="0" {{ old('children', 0) == 0 ? 'selected' : '' }}>0 Children</option>
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ old('children') == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'Child' : 'Children' }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="number_rooms" class="form-label">Number of Rooms *</label>
                                    <select class="form-control" id="number_rooms" name="number_rooms" required>
                                        <option value="1" {{ old('number_rooms', 1) == 1 ? 'selected' : '' }}>1 Room</option>
                                        <option value="2" {{ old('number_rooms') == 2 ? 'selected' : '' }}>2 Rooms</option>
                                        <option value="3" {{ old('number_rooms') == 3 ? 'selected' : '' }}>3 Rooms</option>
                                        <option value="4" {{ old('number_rooms') == 4 ? 'selected' : '' }}>4 Rooms</option>
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
                                <div class="col-md-6 mb-3">
                                    <label for="fullname" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter your full name" value="{{ old('fullname') }}" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" value="{{ old('email') }}" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="phone" class="form-label">Phone *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" value="{{ old('phone') }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="country" class="form-label">Country *</label>
                                    <input type="text" class="form-control" id="country" name="country" placeholder="Enter your country" value="{{ old('country') }}" required>
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
                                        <input class="form-check-input" type="radio" name="payment_method" value="pesapal" id="pesapal" {{ old('payment_method', 'pesapal') == 'pesapal' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="pesapal">
                                            <i class="fas fa-mobile-alt mr-2"></i>Pay Online
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" value="offline" id="offline" {{ old('payment_method') == 'offline' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="offline">
                                            <i class="fas fa-handshake mr-2"></i>Pay on Arrival
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Terms & Submit -->
                            <div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="agree_terms" name="agree_terms" {{ old('agree_terms') ? 'checked' : '' }} required>
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
                                                <span class="h4 text-success font-weight-bold" id="total-amount">
                                                    @if($room && $room->price > 0)
                                                        {{ number_format($room->price, 0) }}
                                                    @else
                                                        {{ number_format($deal->base_price, 0) }}
                                                    @endif
                                                </span>
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
    // Deal data for price calculation
    const dealType = '{{ $deal->type }}';
    const basePrice = {{ $deal->base_price }};
    const roomPrice = {{ $room ? $room->price : 0 }};
    const adultPrice = {{ $deal->tour ? $deal->tour->adult_price : 0 }};
    const childPrice = {{ $deal->tour ? $deal->tour->child_price : 0 }};
    
    // Set initial price display
    const initialPrice = roomPrice > 0 ? roomPrice : basePrice;
    
    // Payment method selection
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const bookBtn = document.getElementById('bookBtn');
    
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'pesapal') {
                bookBtn.innerHTML = '<i class="fas fa-credit-card mr-2"></i>Complete Booking & Pay';
            } else {
                bookBtn.innerHTML = '<i class="fas fa-handshake mr-2"></i>Complete Booking';
            }
        });
    });
    
    // Live price calculation
    function calculateTotalPrice() {
        let total = 0;
        
        if (dealType === 'hotel' || dealType === 'apartment') {
            const checkIn = document.getElementById('check_in')?.value;
            const checkOut = document.getElementById('check_out')?.value;
            const rooms = parseInt(document.getElementById('number_rooms')?.value || 1);
            
            if (checkIn && checkOut) {
                const nights = Math.max(1, Math.ceil((new Date(checkOut) - new Date(checkIn)) / (1000 * 60 * 60 * 24)));
                // Use room price if available, otherwise use hotel base price
                const pricePerNight = roomPrice > 0 ? roomPrice : basePrice;
                total = pricePerNight * rooms * nights;
            } else {
                // Show room price or hotel base price when no dates selected
                total = roomPrice > 0 ? roomPrice : basePrice;
            }
        } else if (dealType === 'tour') {
            const adults = parseInt(document.getElementById('adult')?.value || 1);
            const children = parseInt(document.getElementById('children')?.value || 0);
            
            // Calculate base price per person
            total = (adults * adultPrice) + (children * childPrice);
            
            // Multiply by tour duration if available
            const tourPeriod = {{ $deal->tour ? $deal->tour->period : 1 }};
            if (tourPeriod > 1) {
                total = total * tourPeriod;
            }
        } else if (dealType === 'car') {
            // For cars, calculate based on pickup and return times
            const pickupTime = document.getElementById('pickup_time')?.value;
            const returnTime = document.getElementById('return_time')?.value;
            
            if (pickupTime && returnTime) {
                // For simplicity, assume 1 day rental
                // You can enhance this to calculate actual duration
                total = basePrice;
            } else {
                total = basePrice;
            }
        } else {
            total = basePrice;
        }
        
        return total;
    }
    
    function updatePriceDisplay() {
        const total = calculateTotalPrice();
        const displayPriceElement = document.getElementById('display-price');
        const priceDisplayElement = document.getElementById('price-display');
        const priceUnitElement = document.getElementById('price-unit');
        const totalAmountElement = document.getElementById('total-amount');
        
        if (displayPriceElement) {
            displayPriceElement.textContent = total.toLocaleString();
        }
        
        if (totalAmountElement) {
            totalAmountElement.textContent = total.toLocaleString();
        }
        
        if (priceDisplayElement) {
            // Change to green background when showing calculated price
            if (total > basePrice) {
                priceDisplayElement.className = 'bg-success text-white border rounded p-3 text-center';
                displayPriceElement.className = 'h4 text-white font-weight-bold';
                if (priceUnitElement) {
                    priceUnitElement.className = 'text-white';
                    priceUnitElement.textContent = 'Total calculated price';
                }
            } else {
                priceDisplayElement.className = 'bg-light border rounded p-3 text-center';
                displayPriceElement.className = 'h4 text-primary font-weight-bold';
                if (priceUnitElement) {
                    priceUnitElement.className = 'text-muted';
                    // Reset to original unit text
                    if (dealType === 'tour') {
                        priceUnitElement.textContent = 'per person';
                    } else if (dealType === 'car') {
                        priceUnitElement.textContent = 'per day';
                    } else if (dealType === 'hotel' || dealType === 'apartment') {
                        priceUnitElement.textContent = 'per night';
                    }
                }
            }
        }
    }
    
    // Add event listeners for price calculation
    const priceInputs = document.querySelectorAll('#check_in, #check_out, #number_rooms, #adult, #children');
    priceInputs.forEach(input => {
        if (input) {
            input.addEventListener('change', updatePriceDisplay);
            input.addEventListener('input', updatePriceDisplay);
        }
    });
    
    // Initial price calculation
    updatePriceDisplay();
    
    // Update price display when page loads with old values
    setTimeout(updatePriceDisplay, 100);
    
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

