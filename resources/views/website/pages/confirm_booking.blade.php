@extends('website.layouts.app')

@section('title', 'Book Now - Zanzibar Bookings')
@section('meta')
<meta name="description" content="Book your Zanzibar adventure - tours, hotels, cars and more">
@endsection

@section('pages')

<div class="container py-5">
    <div class="row">

        <div class="col-lg-3 mb-4">
            <div class="card border rounded-1">
                <img src="/images/placeholder.jpg" class="card-img-top" alt="Deal Image">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">Hotel / Tour / Car / Apartment</h5>
                    <p class="text-muted mb-3"><i class="fas fa-map-marker-alt me-2"></i>Zanzibar, Tanzania</p>
                    <div class="border rounded-1 p-3 bg-light">
                        <div class="h4 mb-0 text-success">$120</div>
                        <small class="text-muted">per night / per person</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-9">
            <div class="card border rounded-1">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-check me-2 text-success"></i>Complete Your Booking</h5>
                </div>
                <div class="card-body">

                    <form>
                        <!-- Booking Details -->
                        <div class="card mb-4 border rounded-1 p-3">
                            <h6 class="mb-3 fw-semibold">Booking Details</h6>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Adults *</label>
                                    <select class="form-select" required>
                                        <option selected>1 Adult</option>
                                        <option>2 Adults</option>
                                        <option>3 Adults</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Children</label>
                                    <select class="form-select">
                                        <option selected>0 Children</option>
                                        <option>1 Child</option>
                                        <option>2 Children</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Check-in *</label>
                                    <input type="date" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Check-out *</label>
                                    <input type="date" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Info Card -->
                        <div class="card mb-4 border rounded-1 p-3">
                            <h6 class="mb-3 fw-semibold">Personal Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Phone *</label>
                                    <input type="tel" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Country *</label>
                                    <select class="form-select" required>
                                        <option selected disabled>Select your country</option>
                                        <option>Tanzania</option>
                                        <option>Kenya</option>
                                        <option>Uganda</option>
                                        <option>USA</option>
                                        <option>UK</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Methods Card -->
                        <div class="card mb-4 border rounded-1 p-3">
                            <h6 class="mb-3 fw-semibold">Payment Method</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check border rounded-1 p-2 d-flex align-items-center">
                                        <input type="radio" class="form-check-input me-2" name="payment" checked>
                                        <label class="form-check-label d-flex align-items-center w-100">
                                            <img src="https://www.pesapal.com/assets/images/logo.png" alt="Pesapal" width="80" class="me-2">
                                            Pay Online
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check border rounded-1 p-2 d-flex align-items-center">
                                        <input type="radio" class="form-check-input me-2" name="payment">
                                        <label class="form-check-label d-flex align-items-center w-100">
                                            <img src="https://img.icons8.com/ios-filled/50/000000/cash-in-hand.png" alt="Offline" width="40" class="me-2">
                                            Pay on Arrival
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Terms & Submit -->
                        <div class="form-check mb-4">
                            <input type="checkbox" class="form-check-input" required>
                            <label class="form-check-label">
                                I agree to the <a href="#" class="text-success">Terms and Conditions</a>
                            </label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center p-3 border rounded-1 bg-light">
                            <div>
                                <small class="text-muted">Total Amount</small>
                                <div class="h4 text-success mb-0">$120</div>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg fw-bold">
                                <i class="fas fa-calendar-check me-2"></i>Complete Booking
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
