@extends('admin.layouts.app')

@section('title', 'Flight Search Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col d-flex justify-content-between align-items-center">
            <h4 class="page-title mb-0">Search Details #{{ $search->id }}</h4>
            <a href="{{ route('admin.flights.analytics') }}" class="btn btn-outline-secondary btn-sm">Back to Analytics</a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><strong>Route:</strong> {{ $search->origin_code }} → {{ $search->destination_code }}</div>
                <div class="col-md-3"><strong>Trip Type:</strong> {{ str_replace('_', ' ', ucfirst($search->trip_type ?? 'one_way')) }}</div>
                <div class="col-md-3"><strong>Departure:</strong> {{ $search->departure_date?->format('Y-m-d') }}</div>
                <div class="col-md-3"><strong>Return:</strong> {{ $search->return_date?->format('Y-m-d') ?? 'N/A' }}</div>
                <div class="col-md-3"><strong>Passengers:</strong> {{ $search->adults }}A / {{ $search->children }}C / {{ $search->infants }}I</div>
                <div class="col-md-3"><strong>Cabin:</strong> {{ $search->travel_class }}</div>
                <div class="col-md-3"><strong>Results:</strong> {{ $search->results_count }}</div>
                <div class="col-md-3"><strong>User:</strong> {{ $search->user?->email ?? 'Guest' }}</div>
                <div class="col-md-3"><strong>Session:</strong> <small>{{ $search->session_id }}</small></div>
                <div class="col-md-3"><strong>IP:</strong> {{ $search->ip_address ?? 'N/A' }}</div>
                <div class="col-md-3"><strong>Country:</strong> {{ $search->country ?? 'N/A' }}</div>
                <div class="col-md-3"><strong>Device:</strong> {{ $search->device ?? 'N/A' }}</div>
                <div class="col-md-3"><strong>Browser:</strong> {{ $search->browser ?? 'N/A' }}</div>
                <div class="col-md-3"><strong>OS:</strong> {{ $search->operating_system ?? 'N/A' }}</div>
                <div class="col-md-3"><strong>Searched At:</strong> {{ $search->created_at->format('M d, Y H:i') }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Related Booking Clicks</h5></div>
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Airline</th>
                        <th>Flight</th>
                        <th>Price</th>
                        <th>Affiliate</th>
                        <th>Clicked At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($search->clicks as $click)
                    <tr>
                        <td>{{ $click->airline }}</td>
                        <td>{{ $click->flight_number }}</td>
                        <td>{{ $click->currency }} {{ number_format((float) $click->price, 2) }}</td>
                        <td>{{ $click->affiliate_name }}</td>
                        <td>{{ $click->clicked_at?->format('M d, Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-muted text-center">No clicks recorded for this search.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
