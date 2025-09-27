@extends('website.layouts.app')

@section('title', 'Book Now - Zanzibar Bookings')
@section('meta')
<meta name="description" content="Book your Zanzibar adventure - tours, hotels, cars and more">
@endsection

@section('pages')

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 my-3">
            <div class="card border rounded-1">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold"><i class="mdi mdi-calendar-check me-2"></i>Complete Your
                        Booking</h5>
                </div>
                <div class="card-body">
                    <form action="#" method="POST">
                        @csrf

                        <h6 class="mb-3 fw-semibold"><i class="mdi mdi-account me-2"></i> Personal Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6 my-2">
                                <label class="form-label">Full Name *</label>
                                <input type="text" class="form-control" name="fullname" required
                                    value="{{ Auth::check() ? Auth::user()->firstname . ' ' . Auth::user()->lastname : '' }}">
                            </div>
                            <div class="col-md-6 my-2">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" required
                                    value="{{ Auth::check() ? Auth::user()->email : '' }}">
                            </div>
                            <div class="col-md-6 my-2">
                                <label class="form-label">Phone *</label>
                                <input type="tel" class="form-control" name="phone" required
                                    value="{{ Auth::check() ? Auth::user()->phone : '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Country *</label>
                                <select class="form-control" name="country" required>
                                    @php
                                    $countries = [
                                    'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda',
                                    'Argentina', 'Armenia', 'Australia', 'Austria',
                                    'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium',
                                    'Belize', 'Benin', 'Bhutan',
                                    'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei', 'Bulgaria',
                                    'Burkina Faso', 'Burundi', 'Cabo Verde', 'Cambodia',
                                    'Cameroon', 'Canada', 'Central African Republic', 'Chad', 'Chile', 'China',
                                    'Colombia', 'Comoros', 'Congo (Congo-Brazzaville)', 'Costa Rica',
                                    'Croatia', 'Cuba', 'Cyprus', 'Czechia (Czech Republic)', 'Democratic Republic of the
                                    Congo', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'Ecuador',
                                    'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Eswatini (fmr.
                                    "Swaziland")', 'Ethiopia', 'Fiji', 'Finland', 'France',
                                    'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Greece', 'Grenada', 'Guatemala',
                                    'Guinea', 'Guinea-Bissau',
                                    'Guyana', 'Haiti', 'Holy See', 'Honduras', 'Hungary', 'Iceland', 'India',
                                    'Indonesia', 'Iran', 'Iraq',
                                    'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya',
                                    'Kiribati', 'Kuwait',
                                    'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya',
                                    'Liechtenstein', 'Lithuania', 'Luxembourg',
                                    'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands',
                                    'Mauritania', 'Mauritius', 'Mexico',
                                    'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Morocco',
                                    'Mozambique', 'Myanmar (formerly Burma)', 'Namibia', 'Nauru',
                                    'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'North
                                    Korea', 'North Macedonia', 'Norway', 'Oman',
                                    'Pakistan', 'Palau', 'Palestine State', 'Panama', 'Papua New Guinea', 'Paraguay',
                                    'Peru', 'Philippines', 'Poland', 'Portugal',
                                    'Qatar', 'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia',
                                    'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe',
                                    'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore',
                                    'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia',
                                    'South Africa', 'South Korea', 'South Sudan', 'Spain', 'Sri Lanka', 'Sudan',
                                    'Suriname', 'Sweden', 'Switzerland', 'Syria',
                                    'Tajikistan', 'Tanzania', 'Thailand', 'Timor-Leste', 'Togo', 'Tonga', 'Trinidad and
                                    Tobago', 'Tunisia', 'Turkey', 'Turkmenistan',
                                    'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United
                                    States of America', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Venezuela',
                                    'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe'
                                    ];
                                    $selectedCountry = Auth::check() ? Auth::user()->country : '';
                                    @endphp
                                    <option value="" disabled {{ $selectedCountry ? '' : 'selected' }}>Select your
                                        country</option>
                                    @foreach($countries as $country)
                                    <option value="{{ $country }}" {{ $selectedCountry==$country ? 'selected' : '' }}>{{
                                        $country }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr>
                        <h6 class="mb-3 fw-semibold"><i class="mdi mdi-credit-card me-2"></i> Payment Method</h6>

                        <ul class="row gy-2 mb-4" style="list-style: none; padding: 0; margin: 0;">
                            <li class="col-12 col-md-6 d-flex align-items-stretch mb-3 mb-md-0">
                                <label
                                    class="d-flex flex-nowrap align-items-center w-100 border rounded bg-white px-3 py-2 h-100 payment-method-option"
                                    style="min-height:70px; border-color: #218080; cursor:pointer; transition: border-color 0.3s; position:relative;">
                                    <input type="radio" name="payment_method" value="pesapal" class="visually-hidden"
                                        autocomplete="off" checked>
                                    <span
                                        class="d-flex align-items-center justify-content-center rounded bg-light flex-shrink-0"
                                        style="width:32px; height:32px; background: #e6f4f1 !important; margin-right: 18px;">
                                        <i class="mdi mdi-credit-card" style="color: #218080; font-size: 1.2rem;"></i>
                                    </span>
                                    <div class="flex-grow-1" style="min-width:0;">
                                        <div class="fw-bold text-dark"
                                            style="font-size: 1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                            Pay Online
                                        </div>
                                        <div class="text-muted small" style="white-space:nowrap;">Secure payment with
                                            Pesapal</div>
                                    </div>
                                    <span class="selected-indicator"
                                        style="display:none; position:absolute; top:10px; right:10px; color:#0d6efd;">
                                        <i class="mdi mdi-check-circle" style="font-size:1.3rem;"></i>
                                    </span>
                                </label>
                            </li>
                            <li class="col-12 col-md-6 d-flex align-items-stretch mb-3 mb-md-0">
                                <label
                                    class="d-flex flex-nowrap align-items-center w-100 border rounded bg-white px-3 py-2 h-100 payment-method-option"
                                    style="min-height:70px; border-color: #218080; cursor:pointer; transition: border-color 0.3s; position:relative;">
                                    <input type="radio" name="payment_method" value="offline" class="visually-hidden"
                                        autocomplete="off">
                                    <span
                                        class="d-flex align-items-center justify-content-center rounded bg-light flex-shrink-0"
                                        style="width:32px; height:32px; background: #e6f4f1 !important; margin-right: 18px;">
                                        <i class="mdi mdi-cash-multiple" style="color: #ffb300; font-size: 1.2rem;"></i>
                                    </span>
                                    <div class="flex-grow-1" style="min-width:0;">
                                        <div class="fw-bold text-dark"
                                            style="font-size: 1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                            Pay on Arrival
                                        </div>
                                        <div class="text-muted small" style="white-space:nowrap;">Pay when you arrive
                                        </div>
                                    </div>
                                    <span class="selected-indicator"
                                        style="display:none; position:absolute; top:10px; right:10px; color:#0d6efd;">
                                        <i class="mdi mdi-check-circle" style="font-size:1.3rem;"></i>
                                    </span>
                                </label>
                            </li>
                        </ul>
                        <script>
                            // Payment method selection highlight
                            document.querySelectorAll('.payment-method-option input[type="radio"]').forEach(function(radio) {
                                radio.addEventListener('change', function() {
                                    document.querySelectorAll('.payment-method-option').forEach(function(label) {
                                        label.style.borderColor = '#218080';
                                        label.classList.remove('border-primary');
                                        label.querySelector('.selected-indicator').style.display = 'none';
                                        label.classList.remove('active');
                                    });
                                    if (this.checked) {
                                        this.closest('.payment-method-option').style.borderColor = '#0d6efd';
                                        this.closest('.payment-method-option').classList.add('border-primary');
                                        this.closest('.payment-method-option').querySelector('.selected-indicator').style.display = 'inline';
                                        this.closest('.payment-method-option').classList.add('active');
                                    }
                                });
                                // Initial state
                                if (radio.checked) {
                                    radio.closest('.payment-method-option').style.borderColor = '#0d6efd';
                                    radio.closest('.payment-method-option').classList.add('border-primary');
                                    radio.closest('.payment-method-option').querySelector('.selected-indicator').style.display = 'inline';
                                    radio.closest('.payment-method-option').classList.add('active');
                                }
                            });
                        </script>

                        <div class="form-check mb-4">
                            <input type="checkbox" class="form-check-input" name="terms" required>
                            <label class="form-check-label">
                                I agree to the <a href="#" class="text-success">Terms and Conditions</a> and <a href="#"
                                    class="text-success">Privacy Policy</a>
                            </label>
                        </div>

                        <div class="">
                            <button type="submit" class="btn btn-success fw-bold ">
                                <i class="mdi mdi-check me-2"></i>COMPLETE BOOKING
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Booking Summary Sidebar -->
        <div class="col-lg-4 my-3">
            <div class="card border rounded-1 sticky-top" style="top: 20px; z-index: 2;">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 fw-bold"><i class="mdi mdi-receipt me-2"></i>Booking Summary</h6>
                </div>
                <div class="card-body p-0">
                    <!-- Deal Information -->
                    <div class="p-3 border-bottom">
                        <div class="d-flex align-items-center mb-3" style="gap: 20px;">
                            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=80&h=80&fit=crop&crop=center"
                                alt="Hotel/Tour/Car" class="rounded"
                                style="width: 60px; height: 60px; object-fit: cover; flex-shrink: 0;">
                            <div style="flex: 1;">
                                <h6 class="mb-1 fw-bold">Hotel/Tour/Car</h6>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>Zanzibar, Tanzania
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div class="p-3 border-bottom">
                        <h6 class="mb-3 fw-semibold">Booking Details</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Check-in:</span>
                            <span class="fw-semibold">Dec 15, 2024</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Check-out:</span>
                            <span class="fw-semibold">Dec 18, 2024</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Guests:</span>
                            <span class="fw-semibold">2 Adults, 1 Child</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Rooms:</span>
                            <span class="fw-semibold">1 Room</span>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="p-3 border-bottom">
                        <h6 class="mb-3 fw-semibold">Price Breakdown</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Base Price:</span>
                            <span>$120.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">3 nights:</span>
                            <span>$360.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">1 room:</span>
                            <span>$360.00</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total:</span>
                            <span class="fw-bold text-success fs-5">$360.00</span>
                        </div>
                    </div>

                    <!-- Booking Code -->
                    <div class="p-3">
                        <div class="text-center">
                            <small class="text-muted">Booking Reference</small>
                            <div class="fw-bold text-primary">#BK12345678</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection