@extends('admin.layouts.app')

@section('title', 'View Booking - ' . $booking->booking_code)


@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.bookings') }}">Bookings</a></li>
                        <li class="breadcrumb-item active">View Booking</li>
                    </ol>
                </div>
                <h4 class="page-title">Booking Details - {{ $booking->booking_code }}</h4>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Page Content -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Booking Information</h5>
                </div>
                <div class="card-body">
                    @php
                        $bookingItems = $booking->getBookingItems();
                        $firstItem = !empty($bookingItems) ? $bookingItems[0] : null;
                    @endphp
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="ti ti-receipt text-primary"></i> Booking Code</h6>
                            <p class="text-muted fs-5 fw-bold">{{ $booking->booking_code }}</p>
                            
                            <h6><i class="ti ti-user text-primary"></i> Customer Name</h6>
                            <p class="text-muted">{{ $booking->fullname }}</p>
                            
                            <h6><i class="ti ti-mail text-primary"></i> Email</h6>
                            <p class="text-muted">{{ $booking->email }}</p>
                            
                            <h6><i class="ti ti-phone text-primary"></i> Phone</h6>
                            <p class="text-muted">{{ $booking->phone }}</p>
                            
                            <h6><i class="ti ti-world text-primary"></i> Country</h6>
                            <p class="text-muted">{{ $booking->country }}</p>
                        </div>
                        <div class="col-md-6">
                            @if($firstItem)
                                <h6><i class="ti ti-building text-primary"></i> Service Type</h6>
                                <p class="text-muted">
                                    <span class="badge bg-info">{{ ucfirst($firstItem['type'] ?? 'N/A') }}</span>
                                </p>
                                
                                @if(isset($firstItem['check_in']))
                                    <h6><i class="ti ti-calendar-event text-primary"></i> Check-in Date</h6>
                                    <p class="text-muted">{{ \Carbon\Carbon::parse($firstItem['check_in'])->format('M d, Y') }}</p>
                                @endif
                                
                                @if(isset($firstItem['check_out']))
                                    <h6><i class="ti ti-calendar-event text-primary"></i> Check-out Date</h6>
                                    <p class="text-muted">{{ \Carbon\Carbon::parse($firstItem['check_out'])->format('M d, Y') }}</p>
                                @endif
                                
                                @if(isset($firstItem['adults']) || isset($firstItem['children']))
                                    <h6><i class="ti ti-users text-primary"></i> Guests</h6>
                                    <p class="text-muted">
                                        {{ ($firstItem['adults'] ?? 0) }} Adults
                                        @if(isset($firstItem['children']) && $firstItem['children'] > 0)
                                            , {{ $firstItem['children'] }} Children
                                        @endif
                                    </p>
                                @endif
                                
                                @if(isset($firstItem['number_rooms']))
                                    <h6><i class="ti ti-door text-primary"></i> Number of Rooms</h6>
                                    <p class="text-muted">{{ $firstItem['number_rooms'] }}</p>
                                @endif
                            @endif
                            
                            <h6><i class="ti ti-clock text-primary"></i> Booking Created</h6>
                            <p class="text-muted">
                                {{ $booking->created_at->format('M d, Y h:i A') }}
                            </p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="ti ti-currency-dollar text-primary"></i> Total Amount</h6>
                            <p class="h4 text-primary fw-bold">${{ number_format($booking->total_amount, 2) }}</p>
                            
                            <h6><i class="ti ti-credit-card text-primary"></i> Payment Method</h6>
                            <p class="text-muted">{{ ucfirst(str_replace('_', ' ', $booking->payment_method)) }}</p>
                            
                            @if($booking->additional_notes)
                                <h6><i class="ti ti-note text-primary"></i> Additional Notes</h6>
                                <p class="text-muted">{{ $booking->additional_notes }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6><i class="ti ti-info-circle text-primary"></i> Current Status</h6>
                            <p class="text-muted">
                                @switch($booking->status)
                                    @case('pending')
                                        <span class="badge bg-warning fs-6">Pending</span>
                                        @break
                                    @case('confirmed')
                                        <span class="badge bg-info fs-6">Confirmed</span>
                                        @break
                                    @case('paid')
                                        <span class="badge bg-success fs-6">Paid</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger fs-6">Cancelled</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-success fs-6">Completed</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary fs-6">{{ ucfirst($booking->status) }}</span>
                                @endswitch
                            </p>
                            
                            <h6><i class="ti ti-calendar text-primary"></i> Last Updated</h6>
                            <p class="text-muted">{{ $booking->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    
                    @if(count($bookingItems) > 0)
                        <hr>
                        <h5><i class="ti ti-list text-primary"></i> Booking Items</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Type</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Guests</th>
                                        <th>Rooms</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookingItems as $item)
                                        @php
                                            $deal = $deals->get($item['deal_id'] ?? null);
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $deal->title ?? 'Unknown Service' }}</strong>
                                                @if($deal->cover_photo)
                                                    <br><small class="text-muted">
                                                        <img src="{{ asset('storage/' . $deal->cover_photo) }}" 
                                                             alt="{{ $deal->title }}" 
                                                             style="width: 30px; height: 20px; object-fit: cover; border-radius: 2px;">
                                                    </small>
                                                @endif
                                            </td>
                                            <td><span class="badge bg-info">{{ ucfirst($item['type'] ?? 'N/A') }}</span></td>
                                            <td>{{ isset($item['check_in']) ? \Carbon\Carbon::parse($item['check_in'])->format('M d, Y') : 'N/A' }}</td>
                                            <td>{{ isset($item['check_out']) ? \Carbon\Carbon::parse($item['check_out'])->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                {{ ($item['adults'] ?? 0) }} Adults
                                                @if(isset($item['children']) && $item['children'] > 0)
                                                    <br><small class="text-muted">{{ $item['children'] }} Children</small>
                                                @endif
                                            </td>
                                            <td>{{ $item['number_rooms'] ?? 1 }}</td>
                                            <td><strong class="text-primary">${{ number_format($item['total_price'] ?? 0, 2) }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Booking Summary Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Booking Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6><i class="ti ti-receipt text-primary"></i> Booking Code</h6>
                        <p class="mb-2 fw-bold">{{ $booking->booking_code }}</p>
                        
                        <h6><i class="ti ti-currency-dollar text-primary"></i> Total Amount</h6>
                        <p class="h4 text-primary fw-bold mb-3">${{ number_format($booking->total_amount, 2) }}</p>
                        
                        <h6><i class="ti ti-credit-card text-primary"></i> Payment Method</h6>
                        <p class="mb-2">{{ ucfirst(str_replace('_', ' ', $booking->payment_method)) }}</p>
                        
                        <h6><i class="ti ti-info-circle text-primary"></i> Current Status</h6>
                        <p class="mb-2">
                            @switch($booking->status)
                                @case('pending')
                                    <span class="badge bg-warning fs-6">Pending</span>
                                    @break
                                @case('confirmed')
                                    <span class="badge bg-info fs-6">Confirmed</span>
                                    @break
                                @case('paid')
                                    <span class="badge bg-success fs-6">Paid</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger fs-6">Cancelled</span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-success fs-6">Completed</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary fs-6">{{ ucfirst($booking->status) }}</span>
                            @endswitch
                        </p>
                        
                        <h6><i class="ti ti-calendar text-primary"></i> Booking Date</h6>
                        <p class="mb-2">{{ $booking->created_at->format('M d, Y h:i A') }}</p>
                        
                        @if($booking->additional_notes)
                            <h6><i class="ti ti-note text-primary"></i> Notes</h6>
                            <p class="mb-2 text-muted">{{ $booking->additional_notes }}</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Actions Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" onclick="printBooking()">
                            <i class="ti ti-printer"></i> Print Booking
                        </button>
                        <button type="button" class="btn btn-outline-warning" onclick="updateBookingStatus()">
                            <i class="ti ti-edit"></i> Update Status
                        </button>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Booking Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="statusForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="sts" name="status" required>
                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="paid" {{ $booking->status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="Add any notes about this booking...">{{ $booking->additional_notes }}</textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="statusForm" class="btn btn-primary">Update Status</button>
            </div>
        </div>
    </div>
</div>

@endsection
