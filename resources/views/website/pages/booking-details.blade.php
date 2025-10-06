@extends('website.layouts.app')

@section('title', 'Booking Details - Zanzibar Bookings')

@section('meta')
<meta name="description" content="View your booking details and status">
@endsection

@section('pages')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="card border rounded-1 mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="mdi mdi-calendar-check me-2"></i>Booking Details
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Booking Information -->
        <div class="col-lg-8">
            <div class="card border rounded-1 mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="mdi mdi-ticket-confirmation me-2"></i>Booking Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Booking Code:</label>
                            <div class="fw-bold text-primary">{{ $booking->booking_code }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status:</label>
                            <div>
                                @if($booking->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="badge bg-info">Confirmed</span>
                                @elseif($booking->status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name:</label>
                            <div>{{ $booking->fullname }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email:</label>
                            <div>{{ $booking->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone:</label>
                            <div>{{ $booking->phone }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Country:</label>
                            <div>{{ $booking->country }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Total Amount:</label>
                            <div class="fw-bold text-success fs-5">${{ number_format($booking->total_amount, 2) }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Payment Method:</label>
                            <div>{{ ucfirst(str_replace('_', ' ', $booking->payment_method)) }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Booking Date:</label>
                            <div>{{ $booking->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Booking Time:</label>
                            <div>{{ $booking->created_at->format('h:i A') }}</div>
                        </div>
                    </div>

                    @if($booking->additional_notes)
                    <div class="row mt-4">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Special Requests:</label>
                            <div class="bg-light p-3 rounded border-start border-primary border-3">
                                {{ $booking->additional_notes }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Booking Items -->
            @if($bookingItems && count($bookingItems) > 0)
            <div class="card border rounded-1">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="mdi mdi-package-variant me-2"></i>Booking Items
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($bookingItems as $index => $item)
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="mb-0 fw-bold">
                                    @if($item['deal'])
                                        {{ $item['deal']->title }}
                                    @else
                                        Item #{{ $index + 1 }}
                                    @endif
                                </h6>
                                @if($item['deal'] && $item['deal']->location)
                                <small class="text-muted">
                                    <i class="mdi mdi-map-marker me-1"></i>{{ $item['deal']->location }}
                                </small>
                                @endif
                            </div>
                            <div class="fw-bold text-success">${{ number_format($item['item_data']['total_price'], 2) }}</div>
                        </div>
                        
                        <div class="row g-2">
                            <!-- Deal Type -->
                            <div class="col-md-6">
                                <small class="text-muted">Type:</small>
                                <div class="fw-semibold">{{ ucfirst($item['item_data']['type'] ?? 'Unknown') }}</div>
                            </div>

                            <!-- Dates based on deal type -->
                            @if($item['item_data']['check_in'])
                            <div class="col-md-6">
                                <small class="text-muted">
                                    @if($item['item_data']['type'] === 'car')
                                        Pickup Date:
                                    @elseif($item['item_data']['type'] === 'package' || $item['item_data']['type'] === 'activity')
                                        {{ ucfirst($item['item_data']['type']) }} Date:
                                    @elseif($item['item_data']['type'] === 'apartment')
                                        Check-in:
                                    @else
                                        Check-in:
                                    @endif
                                </small>
                                <div class="fw-semibold">{{ \Carbon\Carbon::parse($item['item_data']['check_in'])->format('M d, Y') }}</div>
                            </div>
                            @endif

                            @if($item['item_data']['check_out'])
                            <div class="col-md-6">
                                <small class="text-muted">
                                    @if($item['item_data']['type'] === 'car')
                                        Return Date:
                                    @elseif($item['item_data']['type'] === 'apartment')
                                        Check-out:
                                    @else
                                        Check-out:
                                    @endif
                                </small>
                                <div class="fw-semibold">{{ \Carbon\Carbon::parse($item['item_data']['check_out'])->format('M d, Y') }}</div>
                            </div>
                            @endif

                            <!-- Guests for packages, activities, and apartments -->
                            @if(($item['item_data']['type'] === 'package' || $item['item_data']['type'] === 'activity' || $item['item_data']['type'] === 'apartment') && (($item['item_data']['adults'] ?? 0) > 0 || ($item['item_data']['children'] ?? 0) > 0))
                            <div class="col-md-6">
                                <small class="text-muted">Guests:</small>
                                <div class="fw-semibold">
                                    {{ $item['item_data']['adults'] ?? 0 }} Adult{{ ($item['item_data']['adults'] ?? 0) > 1 ? 's' : '' }}
                                    @if(($item['item_data']['children'] ?? 0) > 0)
                                        , {{ $item['item_data']['children'] }} Child{{ $item['item_data']['children'] > 1 ? 'ren' : '' }}
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- Duration for cars and apartments -->
                            @if(($item['item_data']['type'] === 'car' || $item['item_data']['type'] === 'apartment') && $item['item_data']['check_in'] && $item['item_data']['check_out'])
                            <div class="col-md-6">
                                <small class="text-muted">
                                    @if($item['item_data']['type'] === 'car')
                                        Duration:
                                    @else
                                        Nights:
                                    @endif
                                </small>
                                <div class="fw-semibold">
                                    @php
                                        $days = \Carbon\Carbon::parse($item['item_data']['check_in'])->diffInDays(\Carbon\Carbon::parse($item['item_data']['check_out']));
                                    @endphp
                                    {{ $days }} 
                                    @if($item['item_data']['type'] === 'car')
                                        Day{{ $days > 1 ? 's' : '' }}
                                    @else
                                        Night{{ $days > 1 ? 's' : '' }}
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- Room details for hotels only -->
                            @if(($item['item_data']['type'] === 'hotel') && $item['item_data']['number_rooms'])
                            <div class="col-md-6">
                                <small class="text-muted">Rooms:</small>
                                <div class="fw-semibold">{{ $item['item_data']['number_rooms'] }} Room{{ $item['item_data']['number_rooms'] > 1 ? 's' : '' }}</div>
                            </div>
                            @endif

                            @if(($item['item_data']['type'] === 'hotel') && $item['room'])
                            <div class="col-md-6">
                                <small class="text-muted">Room Type:</small>
                                <div class="fw-semibold">{{ $item['room']->name }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Actions Sidebar -->
        <div class="col-lg-4">
            <div class="card border rounded-1 mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="mdi mdi-cog me-2"></i>Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('booking.lookup') }}" class="btn btn-outline-primary">
                            <i class="mdi mdi-magnify me-2"></i>Look Up Another Booking
                        </a>
                        
                        <a href="mailto:support@zanzibarbookings.com?subject=Booking Inquiry - {{ $booking->booking_code }}" class="btn btn-outline-info">
                            <i class="mdi mdi-email me-2"></i>Contact Support
                        </a>
                        
                        <a href="https://wa.me/message/JMDWFIGBWX5TI1" target="_blank" class="btn btn-outline-success">
                            <i class="mdi mdi-whatsapp me-2"></i>WhatsApp Support
                        </a>
                    </div>
                </div>
            </div>

            <!-- Booking Status Info -->
            <div class="card border rounded-1">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="mdi mdi-information me-2"></i>Status Information
                    </h6>
                </div>
                <div class="card-body">
                    @if($booking->status === 'pending')
                        <div class="alert alert-warning">
                            <i class="mdi mdi-clock me-2"></i>
                            <strong>Pending:</strong> Your booking is being processed. You will receive a confirmation email soon.
                        </div>
                    @elseif($booking->status === 'confirmed')
                        <div class="alert alert-info">
                            <i class="mdi mdi-check-circle me-2"></i>
                            <strong>Confirmed:</strong> Your booking has been confirmed. Please check your email for further details.
                        </div>
                    @elseif($booking->status === 'paid')
                        <div class="alert alert-success">
                            <i class="mdi mdi-credit-card me-2"></i>
                            <strong>Paid:</strong> Payment received. Your booking is confirmed and ready.
                        </div>
                    @elseif($booking->status === 'cancelled')
                        <div class="alert alert-danger">
                            <i class="mdi mdi-cancel me-2"></i>
                            <strong>Cancelled:</strong> This booking has been cancelled. Contact support for more information.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
