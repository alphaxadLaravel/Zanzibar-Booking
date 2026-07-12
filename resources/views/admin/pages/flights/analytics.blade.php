@extends('admin.layouts.app')

@section('title', 'Flight Analytics')

@push('styles')
<style>
    .flight-stat-card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        background: #fff;
        padding: 18px 20px;
        height: 100%;
    }
    .flight-stat-card .label {
        color: #6c757d;
        font-size: 13px;
        margin-bottom: 6px;
    }
    .flight-stat-card .value {
        font-size: 28px;
        font-weight: 600;
        color: #1a2b42;
        line-height: 1.1;
    }
    .flight-chart-box {
        position: relative;
        height: 260px;
        max-height: 260px;
        overflow: hidden;
    }
    .flight-chart-box canvas {
        display: block;
        max-height: 260px !important;
    }
    .route-badge {
        font-weight: 600;
        letter-spacing: 0.02em;
    }
    .status-pill {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
    }
    .status-pill--confirmed { background: #e8f5e9; color: #2e7d32; }
    .status-pill--pending { background: #fff8e1; color: #f57c00; }
    .status-pill--cancelled { background: #ffebee; color: #c62828; }
    .money-split {
        font-size: 12px;
        line-height: 1.45;
        min-width: 160px;
    }
    .money-split .row-line {
        display: flex;
        justify-content: space-between;
        gap: 12px;
    }
    .money-split .margin {
        color: #2e7d32;
        font-weight: 600;
    }
    .money-split .duffel {
        color: #495057;
    }
    .revenue-card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        background: #fff;
        padding: 18px 20px;
        height: 100%;
    }
    .revenue-card .label { color: #6c757d; font-size: 13px; margin-bottom: 6px; }
    .revenue-card .value { font-size: 22px; font-weight: 600; color: #1a2b42; }
    .revenue-card .hint { font-size: 12px; color: #868e96; margin-top: 4px; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-3">
        <div class="col d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h4 class="page-title mb-0">Flight Analytics</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Flights</li>
                </ol>
            </nav>
        </div>
    </div>

    @include('admin.layouts.alerts')

    <div class="row g-3 mb-4">
        <div class="col-md-4 col-xl-2">
            <div class="flight-stat-card">
                <div class="label">Total searches</div>
                <div class="value">{{ number_format($stats['total_searches']) }}</div>
            </div>
        </div>
        <div class="col-md-4 col-xl-2">
            <div class="flight-stat-card">
                <div class="label">Searches today</div>
                <div class="value">{{ number_format($stats['today_searches']) }}</div>
            </div>
        </div>
        <div class="col-md-4 col-xl-2">
            <div class="flight-stat-card">
                <div class="label">Total bookings</div>
                <div class="value">{{ number_format($stats['total_bookings']) }}</div>
            </div>
        </div>
        <div class="col-md-4 col-xl-2">
            <div class="flight-stat-card">
                <div class="label">Confirmed</div>
                <div class="value text-success">{{ number_format($stats['confirmed_bookings']) }}</div>
            </div>
        </div>
        <div class="col-md-4 col-xl-2">
            <div class="flight-stat-card">
                <div class="label">Pending</div>
                <div class="value text-warning">{{ number_format($stats['pending_bookings']) }}</div>
            </div>
        </div>
        <div class="col-md-4 col-xl-2">
            <div class="flight-stat-card">
                <div class="label">Bookings today</div>
                <div class="value">{{ number_format($stats['today_bookings']) }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="revenue-card">
                <div class="label">Customer paid (confirmed)</div>
                <div class="value">USD {{ number_format($revenue['confirmed_customer_paid'], 2) }}</div>
                <div class="hint">Collected via Pesapal · all bookings USD {{ number_format($revenue['customer_paid'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="revenue-card">
                <div class="label">Paid to Duffel (confirmed)</div>
                <div class="value">USD {{ number_format($revenue['confirmed_duffel_cost'], 2) }}</div>
                <div class="hint">Airline fare from Duffel balance · all bookings USD {{ number_format($revenue['duffel_cost'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="revenue-card">
                <div class="label">Zanzibar Bookings margin</div>
                <div class="value text-success">USD {{ number_format($revenue['confirmed_margin'], 2) }}</div>
                <div class="hint">Your markup kept after Duffel settlement · all bookings USD {{ number_format($revenue['zanzibar_margin'], 2) }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Searches — last 30 days</h5>
                </div>
                <div class="card-body">
                    <div class="flight-chart-box">
                        <canvas id="dailySearchesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Top routes searched</h5>
                </div>
                <div class="card-body">
                    <div class="flight-chart-box">
                        <canvas id="topRoutesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Recent searches</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Route</th>
                            <th>Trip</th>
                            <th>Dates</th>
                            <th>Passengers</th>
                            <th>Class</th>
                            <th>Results</th>
                            <th>User</th>
                            <th>Searched</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($searches as $search)
                        <tr>
                            <td>
                                <span class="route-badge">{{ $search->origin_code }} → {{ $search->destination_code }}</span>
                                @if($search->origin_name || $search->destination_name)
                                    <div class="small text-muted">{{ $search->origin_name }} to {{ $search->destination_name }}</div>
                                @endif
                            </td>
                            <td>{{ str_replace('_', ' ', ucfirst($search->trip_type ?? 'one_way')) }}</td>
                            <td>
                                <div>{{ $search->departure_date?->format('d M Y') }}</div>
                                @if($search->return_date)
                                    <div class="small text-muted">Return {{ $search->return_date->format('d M Y') }}</div>
                                @endif
                            </td>
                            <td>{{ $search->adults }}A @if($search->children)+ {{ $search->children }}C @endif @if($search->infants)+ {{ $search->infants }}I @endif</td>
                            <td>{{ $search->travel_class }}</td>
                            <td>{{ $search->results_count }}</td>
                            <td>{{ $search->user?->email ?? 'Guest' }}</td>
                            <td>
                                <div>{{ $search->created_at->format('d M Y') }}</div>
                                <div class="small text-muted">{{ $search->created_at->format('H:i') }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No flight searches yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($searches->hasPages())
                <div class="card-footer">{{ $searches->links() }}</div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Flight bookings</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Reference</th>
                            <th>Route</th>
                            <th>Airline</th>
                            <th>Departure</th>
                            <th>Payment split</th>
                            <th>Status</th>
                            <th>Contact</th>
                            <th>Booked</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        @php
                            $statusClass = match($booking->status) {
                                'confirmed' => 'status-pill--confirmed',
                                'cancelled' => 'status-pill--cancelled',
                                default => 'status-pill--pending',
                            };
                            $split = $booking->paymentDistribution();
                            $currency = $split['currency'];
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $booking->booking_reference }}</strong>
                                @if($booking->payment)
                                    <div class="small text-muted">Pesapal: {{ $booking->payment->status }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="route-badge">{{ $booking->origin_code }} → {{ $booking->destination_code }}</span>
                            </td>
                            <td>
                                <div>{{ $booking->airline_name }}</div>
                                <div class="small text-muted">{{ $booking->flight_number }}</div>
                            </td>
                            <td>{{ $booking->departure_datetime?->format('d M Y H:i') ?? '—' }}</td>
                            <td>
                                <div class="money-split">
                                    <div class="row-line">
                                        <span>Customer paid</span>
                                        <strong>{{ $currency }} {{ number_format($split['customer_paid'], 2) }}</strong>
                                    </div>
                                    <div class="row-line duffel">
                                        <span>→ Duffel</span>
                                        <span>{{ $currency }} {{ number_format($split['duffel_cost'], 2) }}</span>
                                    </div>
                                    <div class="row-line margin">
                                        <span>→ Zanzibar</span>
                                        <span>{{ $currency }} {{ number_format($split['zanzibar_margin'], 2) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="status-pill {{ $statusClass }}">{{ $booking->status }}</span></td>
                            <td>
                                <div class="small">{{ $booking->contact_email }}</div>
                                <div class="small text-muted">{{ $booking->contact_phone }}</div>
                            </td>
                            <td>
                                <div>{{ $booking->created_at->format('d M Y') }}</div>
                                <div class="small text-muted">{{ $booking->created_at->format('H:i') }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No flight bookings yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($bookings->hasPages())
                <div class="card-footer">{{ $bookings->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { precision: 0 }
            }
        }
    };

    const dailyLabels = @json($dailySearches['labels']);
    const dailyValues = @json($dailySearches['values']);
    const routeLabels = @json($topRoutes['labels']);
    const routeValues = @json($topRoutes['values']);

    const dailyCtx = document.getElementById('dailySearchesChart');
    if (dailyCtx) {
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Searches',
                    data: dailyValues,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.08)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 2,
                }]
            },
            options: chartDefaults
        });
    }

    const routesCtx = document.getElementById('topRoutesChart');
    if (routesCtx) {
        new Chart(routesCtx, {
            type: 'bar',
            data: {
                labels: routeLabels,
                datasets: [{
                    label: 'Searches',
                    data: routeValues,
                    backgroundColor: '#20c997',
                    borderRadius: 4,
                }]
            },
            options: {
                ...chartDefaults,
                indexAxis: routeLabels.length > 5 ? 'y' : 'x',
            }
        });
    }
})();
</script>
@endpush
