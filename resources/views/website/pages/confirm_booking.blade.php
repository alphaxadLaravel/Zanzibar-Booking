@extends('website.layouts.app')

@section('title', 'Confirm Booking - Zanzibar Bookings')
@section('meta')
<meta name="description" content="Confirm your booking and complete payment for your Zanzibar adventure">
@endsection

@section('pages')
<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="{{ route('index') }}">Home</a></li>
            <li><a href="{{ route('tours') }}">Booking</a></li>
            <li><span>Payment</span></li>
        </ul>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <!-- Booking Summary Card -->
        <div class="col-lg-4 mb-4">
            <div class="card booking-summary-card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fal fa-calendar-check mr-2"></i>
                        Your Booking
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Booking Image -->
                    <div class="booking-image mb-3">
                        <img src="{{ asset('https://www.zanzibarbookings.com/storage/2024/02/28/emarald-michamvi-34-1709067436.jpg') }}" 
                             alt="Dolphin Spotting" class="img-fluid rounded">
                    </div>
                    
                    <!-- Booking Number -->
                    <div class="booking-number mb-3">
                        <small class="text-muted">Booking Number:</small>
                        <span class="fw-bold text-primary">FORTUNE:7975</span>
                    </div>
                    
                    <!-- Activity Details -->
                    <div class="activity-details mb-3">
                        <h6 class="fw-bold mb-2">Dolphin Spotting</h6>
                        <p class="text-muted mb-1">
                            <i class="fal fa-map-marker-alt text-primary mr-1"></i>
                            Nungwi
                        </p>
                        <p class="text-muted mb-1">
                            <i class="fal fa-calendar text-primary mr-1"></i>
                            2025-09-13 - 2025-09-25
                        </p>
                        <p class="text-muted mb-1">
                            <i class="fal fa-users text-primary mr-1"></i>
                            4 Adults
                        </p>
                    </div>
                    
                    <!-- Price Breakdown -->
                    <div class="price-breakdown">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Base Price (4 adults)</span>
                            <span class="fw-semibold">USD 65</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Duration</span>
                            <span class="fw-semibold">12 day(s)</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Calculation</span>
                            <span class="fw-semibold">65 x 4 x 12</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total:</span>
                            <span class="fw-bold text-primary fs-5">USD 3,120</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Payment and Personal Details Form -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fal fa-credit-card mr-2"></i>
                        Payment Method & Personal Details
                    </h5>
                </div>
                <div class="card-body">
                    <form id="bookingForm" method="POST" action="{{ route('process.booking') }}">
                        @csrf
                        
                        <!-- Payment Method Selection -->
                        <div class="payment-method mb-4">
                            <h6 class="fw-bold mb-3">Payment Method</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="payment-option">
                                        <label class="payment-radio">
                                            <input type="radio" name="payment_method" value="pesapal" checked>
                                            <div class="payment-card">
                                                <div class="payment-icon">
                                                    <i class="fal fa-mobile-alt"></i>
                                                </div>
                                                <div class="payment-info">
                                                    <h6 class="mb-1">Pay via Pesapal</h6>
                                                    <small class="text-muted">Secure online payment</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="payment-option">
                                        <label class="payment-radio">
                                            <input type="radio" name="payment_method" value="offline">
                                            <div class="payment-card">
                                                <div class="payment-icon">
                                                    <i class="fal fa-handshake"></i>
                                                </div>
                                                <div class="payment-info">
                                                    <h6 class="mb-1">Pay Offline</h6>
                                                    <small class="text-muted">Pay on arrival</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Personal Details Form -->
                        <div class="personal-details">
                            <h6 class="fw-bold mb-3">Personal Details</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="field-wrapper input">
                                        <label for="first_name">FIRST NAME</label>
                                        <i class="fal fa-user-alt"></i>
                                        <input id="first_name" name="first_name" type="text" 
                                               class="form-control gmz-validation" 
                                               data-validation="required" 
                                               placeholder="First Name" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="field-wrapper input">
                                        <label for="last_name">LAST NAME</label>
                                        <i class="fal fa-user-alt"></i>
                                        <input id="last_name" name="last_name" type="text" 
                                               class="form-control gmz-validation" 
                                               data-validation="required" 
                                               placeholder="Last Name" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="field-wrapper input">
                                        <label for="email">EMAIL</label>
                                        <i class="fal fa-at"></i>
                                        <input id="email" name="email" type="email" 
                                               class="form-control gmz-validation" 
                                               data-validation="required" 
                                               placeholder="Email Address" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="field-wrapper input">
                                        <label for="phone">PHONE NUMBER</label>
                                        <i class="fal fa-phone"></i>
                                        <input id="phone" name="phone" type="tel" 
                                               class="form-control gmz-validation" 
                                               data-validation="required" 
                                               placeholder="Phone Number" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="field-wrapper input">
                                        <label for="nationality">NATIONALITY</label>
                                        <i class="fal fa-flag"></i>
                                        <select id="nationality" name="nationality" 
                                                class="form-control gmz-validation" 
                                                data-validation="required" required>
                                            <option value="">Select Nationality</option>
                                            <option value="Tanzania">Tanzania</option>
                                            <option value="Kenya">Kenya</option>
                                            <option value="Uganda">Uganda</option>
                                            <option value="USA">USA</option>
                                            <option value="UK">UK</option>
                                            <option value="Germany">Germany</option>
                                            <option value="France">France</option>
                                            <option value="Italy">Italy</option>
                                            <option value="Spain">Spain</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="field-wrapper input">
                                        <label for="passport_number">PASSPORT NUMBER</label>
                                        <i class="fal fa-id-card"></i>
                                        <input id="passport_number" name="passport_number" type="text" 
                                               class="form-control gmz-validation" 
                                               data-validation="required" 
                                               placeholder="Passport Number" required>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Special Requirements -->
                            <div class="mb-3">
                                <div class="field-wrapper input">
                                    <label for="special_requirements">SPECIAL REQUIREMENTS</label>
                                    <i class="fal fa-clipboard-list"></i>
                                    <textarea id="special_requirements" name="special_requirements" 
                                              class="form-control" rows="3" 
                                              placeholder="Any special dietary requirements, accessibility needs, or other requests..."></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <div class="terms-condition mb-4">
                            <div class="n-chk">
                                <label class="new-control new-checkbox checkbox-primary">
                                    <input type="checkbox" name="agree_terms" value="1" 
                                           id="agree-terms" class="new-control-input gmz-validation" 
                                           data-validation="required" required>
                                    <span class="new-control-indicator"></span>
                                    <span>I have read and agree to the 
                                        <a href="#" class="text-primary">Terms and Conditions</a>
                                    </span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Total Amount Display -->
                        <div class="total-amount mb-4 p-3 bg-light rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Total Amount:</span>
                                <span class="fw-bold text-primary fs-4">USD 3,120</span>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5" id="proceedBtn">
                                <i class="fal fa-calendar-check mr-2"></i>
                                Proceed and Pay
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.booking-summary-card {
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

.booking-summary-card .card-header {
    border-radius: 10px 10px 0 0;
    background: linear-gradient(135deg, #2c5aa0 0%, #1e3a8a 100%);
}

.booking-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
}

.payment-option {
    margin-bottom: 1rem;
}

.payment-radio {
    cursor: pointer;
    display: block;
    margin: 0;
}

.payment-radio input[type="radio"] {
    display: none;
}

.payment-card {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    background: #fff;
}

.payment-card:hover {
    border-color: #2c5aa0;
    box-shadow: 0 2px 8px rgba(44, 90, 160, 0.1);
}

.payment-radio input[type="radio"]:checked + .payment-card {
    border-color: #2c5aa0;
    background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
}

.payment-icon {
    width: 50px;
    height: 50px;
    background: #2c5aa0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: white;
    font-size: 1.2rem;
}

.payment-info h6 {
    margin: 0;
    color: #333;
}

.field-wrapper {
    position: relative;
    margin-bottom: 1rem;
}

.field-wrapper label {
    font-size: 12px;
    font-weight: 600;
    color: #666;
    margin-bottom: 5px;
    display: block;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.field-wrapper i {
    position: absolute;
    left: 15px;
    top: 35px;
    color: #999;
    z-index: 2;
}

.field-wrapper input,
.field-wrapper select,
.field-wrapper textarea {
    padding-left: 45px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: #fff;
}

.field-wrapper input:focus,
.field-wrapper select:focus,
.field-wrapper textarea:focus {
    border-color: #2c5aa0;
    box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
    outline: none;
}

.field-wrapper textarea {
    resize: vertical;
    min-height: 80px;
}

.total-amount {
    background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
    border: 2px solid #e8f2ff;
}

.btn-primary {
    background: linear-gradient(135deg, #2c5aa0 0%, #1e3a8a 100%);
    border: none;
    border-radius: 8px;
    padding: 12px 30px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(44, 90, 160, 0.3);
}

.n-chk .new-control {
    position: relative;
    padding-left: 30px;
    cursor: pointer;
}

.n-chk .new-control-input {
    position: absolute;
    left: 0;
    top: 0;
    width: 20px;
    height: 20px;
    opacity: 0;
}

.n-chk .new-control-indicator {
    position: absolute;
    left: 0;
    top: 0;
    width: 20px;
    height: 20px;
    border: 2px solid #ddd;
    border-radius: 4px;
    background: #fff;
    transition: all 0.3s ease;
}

.n-chk .new-control-input:checked + .new-control-indicator {
    background: #2c5aa0;
    border-color: #2c5aa0;
}

.n-chk .new-control-input:checked + .new-control-indicator::after {
    content: '✓';
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 12px;
    font-weight: bold;
}

@media (max-width: 768px) {
    .payment-card {
        flex-direction: column;
        text-align: center;
    }
    
    .payment-icon {
        margin-right: 0;
        margin-bottom: 0.5rem;
    }
    
    .btn-lg {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Payment method selection
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const proceedBtn = document.getElementById('proceedBtn');
    
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'pesapal') {
                proceedBtn.innerHTML = '<i class="fal fa-credit-card mr-2"></i>Proceed and Pay';
            } else {
                proceedBtn.innerHTML = '<i class="fal fa-handshake mr-2"></i>Confirm Booking';
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
        
        const termsCheckbox = document.getElementById('agree-terms');
        if (!termsCheckbox.checked) {
            isValid = false;
            alert('Please agree to the Terms and Conditions');
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields');
        }
    });
});
</script>
@endsection
