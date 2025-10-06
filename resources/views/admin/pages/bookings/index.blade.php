@extends('admin.layouts.app')

@section('title', 'All Bookings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Bookings</li>
                    </ol>
                </div>
                <h4 class="page-title">All Bookings</h4>
            </div>
        </div>
    </div>

    <!-- Booking Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0 text-primary">{{ $bookings->total() }}</h4>
                            <p class="mb-0 text-muted">Total Bookings</p>
                        </div>
                        <div class="align-self-center">
                            <i class="ti ti-calendar text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0 text-success">{{ $bookings->where('status', 'paid')->count() }}</h4>
                            <p class="mb-0 text-muted">Paid Bookings</p>
                        </div>
                        <div class="align-self-center">
                            <i class="ti ti-check text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0 text-warning">{{ $bookings->where('status', 'pending')->count() }}</h4>
                            <p class="mb-0 text-muted">Pending Bookings</p>
                        </div>
                        <div class="align-self-center">
                            <i class="ti ti-clock text-warning" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0 text-info">${{ number_format($bookings->sum('total_amount'), 2) }}</h4>
                            <p class="mb-0 text-muted">Total Revenue</p>
                        </div>
                        <div class="align-self-center">
                            <i class="ti ti-currency-dollar text-info" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Bookings</h5>
                    <div class="card-tools">
                        <span class="badge bg-primary">{{ $bookings->total() }} Total</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($bookings->count() > 0)
                        <!-- Bookings Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Booking Code</th>
                                        <th>Customer</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Service Type</th>
                                        <th>Total Amount</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                        @php
                                            $bookingItems = $booking->getBookingItems();
                                            $serviceType = '';
                                            $checkIn = '';
                                            $checkOut = '';
                                            
                                            if (!empty($bookingItems)) {
                                                $firstItem = $bookingItems[0];
                                                $serviceType = $firstItem['type'] ?? 'N/A';
                                                $checkIn = $firstItem['check_in'] ?? '';
                                                $checkOut = $firstItem['check_out'] ?? '';
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $booking->booking_code }}</strong>
                                            </td>
                                            <td>{{ $booking->fullname }}</td>
                                            <td>{{ $booking->email }}</td>
                                            <td>{{ $booking->phone }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst($serviceType) }}</span>
                                                @if($checkIn && $checkOut)
                                                    <br><small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($checkIn)->format('M d') }} - 
                                                        {{ \Carbon\Carbon::parse($checkOut)->format('M d, Y') }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>${{ number_format($booking->total_amount, 2) }}</strong>
                                            </td>
                                            <td>{{ ucfirst($booking->payment_method) }}</td>
                                            <td>
                                                @switch($booking->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                        @break
                                                    @case('confirmed')
                                                        <span class="badge bg-info">Confirmed</span>
                                                        @break
                                                    @case('paid')
                                                        <span class="badge bg-success">Paid</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge bg-success">Completed</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <small>{{ $booking->created_at->format('M d, Y') }}</small><br>
                                                <small class="text-muted">{{ $booking->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.bookings.view', $hashids->encode($booking->id)) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="updateStatus('{{ $hashids->encode($booking->id) }}')" title="Update Status">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $bookings->links() }}
                        </div>
                    @else
                        <!-- No Bookings Message -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="ti ti-calendar-x" style="font-size: 4rem; color: #dee2e6;"></i>
                            </div>
                            <h4 class="text-muted">No Bookings Found</h4>
                            <p class="text-muted">There are no bookings in the system yet.</p>
                        </div>
                    @endif
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
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="paid">Paid</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="statusForm" class="btn btn-primary">Update Status</button>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(id) {
    document.getElementById('statusForm').action = '{{ route("admin.bookings.status", ":id") }}'.replace(':id', id);
    new bootstrap.Modal(document.getElementById('statusModal')).show();
}
</script>
@endsection
