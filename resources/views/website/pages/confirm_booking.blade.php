@extends('website.layouts.app')

@section('title', 'Book Now - Zanzibar Bookings')
@section('meta')
<meta name="description" content="Book your Zanzibar adventure - tours, hotels, cars and more">
@endsection

@section('pages')

<div class="container py-5">
    @if($cartItems->count() > 0)
    <div class="row">
        <div class="col-lg-8 my-3">
            <div class="card border rounded-1">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold"><i class="mdi mdi-calendar-check me-2"></i>Complete Your
                        Booking</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('process-booking') }}" method="POST">
                        @csrf
                        <input type="hidden" name="selected_items" value="{{ $cartItems->map(function($item) use ($hashids) { return $hashids->encode($item->id); })->implode(',') }}">

                        <h6 class="mb-3 fw-semibold"><i class="mdi mdi-account me-2"></i> Personal Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6 my-2">
                                <label class="form-label">Full Name *</label>
                                <input type="text" class="form-control" name="fullname" required
                                    placeholder="Enter your full name"
                                    value="{{ Auth::check() ? Auth::user()->firstname . ' ' . Auth::user()->lastname : '' }}">
                            </div>
                            <div class="col-md-6 my-2">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" required
                                    placeholder="Enter your email address"
                                    value="{{ Auth::check() ? Auth::user()->email : '' }}">
                            </div>
                            <div class="col-md-6 my-2">
                                <label class="form-label">Phone *</label>
                                <input type="tel" class="form-control" name="phone" required
                                    placeholder="Enter your phone number"
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
                                    'Belize', 'Benin', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil',
                                    'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada',
                                    'Cape Verde', 'Central African Republic', 'Chad', 'Chile', 'China', 'Colombia',
                                    'Comoros', 'Congo', 'Costa Rica', 'Croatia', 'Cuba', 'Cyprus', 'Czech Republic',
                                    'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'East Timor', 'Ecuador',
                                    'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia',
                                    'Fiji', 'Finland', 'France', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana',
                                    'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti',
                                    'Honduras', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland',
                                    'Israel', 'Italy', 'Ivory Coast', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan',
                                    'Kenya', 'Kiribati', 'North Korea', 'South Korea', 'Kuwait', 'Kyrgyzstan', 'Laos',
                                    'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania',
                                    'Luxembourg', 'Macedonia', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali',
                                    'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico', 'Micronesia',
                                    'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Morocco', 'Mozambique', 'Myanmar',
                                    'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger',
                                    'Nigeria', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Panama', 'Papua New Guinea',
                                    'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal', 'Qatar', 'Romania',
                                    'Russia', 'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines',
                                    'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia',
                                    'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands',
                                    'Somalia', 'South Africa', 'South Sudan', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname',
                                    'Swaziland', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan', 'Tanzania',
                                    'Thailand', 'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan',
                                    'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States',
                                    'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City', 'Venezuela', 'Vietnam', 'Yemen',
                                    'Zambia', 'Zimbabwe'
                                    ];
                                    @endphp
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                    <option value="{{ $country }}" 
                                        {{ Auth::check() && Auth::user()->country == $country ? 'selected' : '' }}>
                                        {{ $country }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <h6 class="mb-3 fw-semibold mt-4"><i class="mdi mdi-credit-card me-2"></i> Payment Method</h6>
                        <div class="row g-3">
                            <div class="col-md-6 my-2">
                                <label class="form-label">Payment Method *</label>
                                <select class="form-control" name="payment_method" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="pesapal">Pesapal (Online Payment)</option>
                                    <option value="pay_offline">Pay Offline</option>
                                </select>
                                        </div>
                                    </div>

                        <h6 class="mb-3 fw-semibold mt-4"><i class="mdi mdi-comment-text me-2"></i> Special Requests</h6>
                        <div class="row g-3">
                            <div class="col-12 my-2">
                                <label class="form-label">Additional Notes (Optional)</label>
                                <textarea class="form-control" name="special_requests" rows="4"
                                    placeholder="Any special requests or additional information..."></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
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
                    <!-- Items Being Booked -->
                    <div class="p-3 border-bottom">
                        <h6 class="mb-3 fw-semibold">Items Being Booked</h6>
                        @foreach($cartItems as $item)
                        <div class="d-flex align-items-center mb-3" style="gap: 15px;">
                            @php
                            $room = $item->room;
                            $imageSrc = null;

                            if ($room && $room->photos && $room->photos->count() > 0) {
                                $imageSrc = asset('storage/' . $room->photos->first()->photo);
                            } elseif ($room && $room->cover_photo) {
                                $imageSrc = asset('storage/' . $room->cover_photo);
                            } elseif ($item->deal->photos && $item->deal->photos->count() > 0) {
                                $imageSrc = asset('storage/' . $item->deal->photos->first()->photo);
                            } elseif ($item->deal->cover_photo) {
                                $imageSrc = asset('storage/' . $item->deal->cover_photo);
                            }
                            @endphp

                            @if($imageSrc)
                            <img src="{{ $imageSrc }}" alt="{{ $room ? $room->title : $item->deal->title }}"
                                class="rounded" style="width: 60px; height: 60px; object-fit: cover; flex-shrink: 0;">
                            @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px; flex-shrink: 0;">
                                <i class="mdi mdi-image-outline text-muted"></i>
                            </div>
                            @endif
                            
                            <div style="flex: 1; min-width: 0;">
                                <h6 class="mb-1 fw-bold text-truncate" style="font-size: 14px;">{{ $item->deal->title }}</h6>
                                <div class="small text-muted">
                                    @if($room)
                                        <div>{{ $room->title }}</div>
                                    @elseif($item->deal->location)
                                        <div><i class="mdi mdi-map-marker me-1"></i>{{ $item->deal->location }}</div>
                                    @endif
                                    
                                    @if($item->check_in)
                                        <div>
                                            @if($item->type === 'car')
                                                <i class="mdi mdi-calendar-start me-1"></i>Pickup: {{ \Carbon\Carbon::parse($item->check_in)->format('M d, Y') }}
                                            @elseif($item->type === 'package' || $item->type === 'activity')
                                                <i class="mdi mdi-calendar-start me-1"></i>{{ ucfirst($item->type) }}: {{ \Carbon\Carbon::parse($item->check_in)->format('M d, Y') }}
                                            @elseif($item->type === 'apartment')
                                                <i class="mdi mdi-calendar-start me-1"></i>Check-in: {{ \Carbon\Carbon::parse($item->check_in)->format('M d, Y') }}
                                            @else
                                                <i class="mdi mdi-calendar-start me-1"></i>Check-in: {{ \Carbon\Carbon::parse($item->check_in)->format('M d, Y') }}
                                            @endif
                                        </div>
                                    @endif
                                    
                                    @if($item->check_out)
                                        <div>
                                            @if($item->type === 'car')
                                                <i class="mdi mdi-calendar-end me-1"></i>Return: {{ \Carbon\Carbon::parse($item->check_out)->format('M d, Y') }}
                                            @elseif($item->type === 'apartment')
                                                <i class="mdi mdi-calendar-end me-1"></i>Check-out: {{ \Carbon\Carbon::parse($item->check_out)->format('M d, Y') }}
                                            @else
                                                <i class="mdi mdi-calendar-end me-1"></i>Check-out: {{ \Carbon\Carbon::parse($item->check_out)->format('M d, Y') }}
                                            @endif
                                        </div>
                                    @endif
                                    
                                    @if(($item->type === 'package' || $item->type === 'activity' || $item->type === 'apartment') && ($item->adults || $item->children))
                                        <div>
                                            <i class="mdi mdi-account-group me-1"></i>
                                            {{ $item->adults }} Adult{{ $item->adults > 1 ? 's' : '' }}
                                            @if($item->children > 0)
                                                , {{ $item->children }} Child{{ $item->children > 1 ? 'ren' : '' }}
                                            @endif
                                        </div>
                                    @endif
                                    
                                    @if(($item->type === 'car' || $item->type === 'apartment') && $item->check_in && $item->check_out)
                                        <div>
                                            @php
                                                $days = \Carbon\Carbon::parse($item->check_in)->diffInDays(\Carbon\Carbon::parse($item->check_out));
                                            @endphp
                                            <i class="mdi mdi-clock me-1"></i>
                                            @if($item->type === 'car')
                                                {{ $days }} Day{{ $days > 1 ? 's' : '' }}
                                            @else
                                                {{ $days }} Night{{ $days > 1 ? 's' : '' }}
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="text-end">
                                <div class="fw-bold text-success">{{ priceForUser($item->total_price, 2) }}</div>
                        </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Price Breakdown -->
                    <div class="p-3 border-bottom">
                        <h6 class="mb-3 fw-semibold">Price Breakdown</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Items ({{ $cartItems->count() }}):</span>
                            <span class="fw-bold">{{ priceForUser($totalAmount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Service Fee:</span>
                            <span class="text-success">FREE</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total:</span>
                            <span class="fw-bold text-success fs-5">{{ priceForUser($totalAmount, 2) }}</span>
                        </div>
                    </div>

                    <!-- Booking Reference -->
                    <div class="p-3">
                        <div class="text-center">
                            <small class="text-muted">Booking Reference</small>
                            <div class="fw-bold text-primary">Will be generated</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- No Items Selected -->
    <div class="text-center py-5">
        <div class="empty-cart-icon mb-4">
            <i class="mdi mdi-cart-outline text-muted" style="font-size: 5rem;"></i>
        </div>
        <h3 class="empty-cart-title mb-3">No items selected</h3>
        <p class="empty-cart-text text-muted mb-4">
            You need to select items from your cart to proceed with booking.
        </p>
        <div class="empty-cart-actions">
            <a href="{{ route('cart') }}" class="btn btn-primary">
                <i class="mdi mdi-cart me-2"></i>View Cart
            </a>
        </div>
    </div>
    @endif
</div>

@endsection