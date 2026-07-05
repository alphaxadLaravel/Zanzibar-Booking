@props(['stats', 'tour', 'compact' => false])

@if($stats && $tour?->is_group_package)
<div class="group-package-progress mb-4 p-3 rounded border" style="border-color: #218080 !important; background: #f8fcfc;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
        <div>
            <span class="badge text-white" style="background:#218080;">Group Package</span>
            @if($tour->group_departure_date)
                <small class="text-muted ms-2">
                    Departure: {{ $tour->group_departure_date->format('M d, Y') }}
                </small>
            @endif
        </div>
        <strong style="color:#218080;">
            {{ $stats['booked'] }} / {{ $stats['capacity'] }} booked
        </strong>
    </div>

    <div class="progress mb-2" style="height: 12px;">
        <div class="progress-bar" role="progressbar"
            style="width: {{ $stats['percent'] }}%; background-color: #218080;"
            aria-valuenow="{{ $stats['percent'] }}" aria-valuemin="0" aria-valuemax="100">
        </div>
    </div>

    <div class="d-flex justify-content-between flex-wrap gap-2 small text-muted">
        <span>{{ $stats['remaining'] }} spot(s) remaining</span>
        @if($tour->group_booking_deadline)
            <span>Book by {{ $tour->group_booking_deadline->format('M d, Y') }}</span>
        @endif
    </div>

    @if(!$compact)
        @if($stats['is_full'])
            <div class="alert alert-warning mt-3 mb-0 py-2">This group package is fully booked.</div>
        @elseif($stats['deadline_passed'])
            <div class="alert alert-warning mt-3 mb-0 py-2">The booking deadline for this group package has passed.</div>
        @else
            <div class="alert alert-info mt-3 mb-0 py-2">Online payment is required. Only paid bookings count toward group capacity.</div>
        @endif
    @endif
</div>
@endif
