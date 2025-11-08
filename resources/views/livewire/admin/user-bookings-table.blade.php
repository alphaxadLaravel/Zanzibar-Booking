<div>
    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Search bookings..." wire:model.live="search">
        </div>
        <div class="col-md-3 mt-2 mt-md-0">
            <select class="form-select" wire:model.live="status">
                <option value="all">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
                <option value="paid">Paid</option>
            </select>
        </div>
    </div>

    @if($bookings->count())
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Booking Code</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                        <tr>
                            <td><strong>{{ $booking->booking_code }}</strong></td>
                            <td><span class="badge bg-primary">{{ ucfirst($booking->status) }}</span></td>
                            <td>${{ number_format($booking->total_amount, 2) }}</td>
                            <td>{{ $booking->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <a href="{{ route('admin.bookings.view', $hashids->encode($booking->id)) }}"
                                   class="btn btn-sm btn-outline-info">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $bookings->links() }}
        </div>
    @else
        <p class="text-muted mb-0">No bookings found for this user.</p>
    @endif
</div>

