@extends('admin.layouts.app')

@section('title', 'Flight Analytics')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-3">
        <div class="col d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h4 class="page-title mb-0">Flight Analytics</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Flight Analytics</li>
                </ol>
            </nav>
        </div>
    </div>

    @include('admin.layouts.alerts')

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.flights.analytics') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Global Search</label>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="Route, user, country...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Country</label>
                    <select name="country" class="form-select">
                        <option value="">All</option>
                        @foreach($countries as $country)
                            <option value="{{ $country }}" @selected(($filters['country'] ?? '') === $country)>{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label">Origin</label>
                    <input type="text" name="origin" value="{{ $filters['origin'] ?? '' }}" class="form-control text-uppercase" maxlength="3">
                </div>
                <div class="col-md-1">
                    <label class="form-label">Destination</label>
                    <input type="text" name="destination" value="{{ $filters['destination'] ?? '' }}" class="form-control text-uppercase" maxlength="3">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Airline</label>
                    <select name="airline" class="form-select">
                        <option value="">All</option>
                        @foreach($airlines as $airline)
                            <option value="{{ $airline }}" @selected(($filters['airline'] ?? '') === $airline)>{{ $airline }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">User Type</label>
                    <select name="user_type" class="form-select">
                        <option value="">All</option>
                        <option value="authenticated" @selected(($filters['user_type'] ?? '') === 'authenticated')>Logged In</option>
                        <option value="guest" @selected(($filters['user_type'] ?? '') === 'guest')>Guests</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        @foreach([
            ['Total Searches', $stats['total_searches']],
            ['Today\'s Searches', $stats['today_searches']],
            ['Total Booking Clicks', $stats['total_clicks']],
            ['Today\'s Booking Clicks', $stats['today_clicks']],
            ['Conversion Rate', $stats['conversion_rate'] . '%'],
            ['Top Destination', $stats['top_destination']],
            ['Top Origin', $stats['top_origin']],
            ['Top Airline', $stats['top_airline']],
        ] as [$label, $value])
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">{{ $label }}</div>
                    <div class="fs-4 fw-semibold">{{ $value }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header"><h5 class="mb-0">Daily Searches (30 days)</h5></div>
                <div class="card-body"><canvas id="dailySearchesChart" height="120"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header"><h5 class="mb-0">Daily Booking Clicks (30 days)</h5></div>
                <div class="card-body"><canvas id="dailyClicksChart" height="120"></canvas></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header"><h5 class="mb-0">Top Airlines</h5></div>
                <div class="card-body"><canvas id="topAirlinesChart" height="180"></canvas></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header"><h5 class="mb-0">Top Destinations</h5></div>
                <div class="card-body"><canvas id="topDestinationsChart" height="180"></canvas></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header"><h5 class="mb-0">Top Origins</h5></div>
                <div class="card-body"><canvas id="topOriginsChart" height="180"></canvas></div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card h-100">
                <div class="card-header"><h5 class="mb-0">Monthly Search Trends</h5></div>
                <div class="card-body"><canvas id="monthlyTrendsChart" height="100"></canvas></div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Search History</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.flights.export', ['type' => 'csv', 'dataset' => 'searches'] + request()->query()) }}" class="btn btn-sm btn-outline-secondary">CSV</a>
                <a href="{{ route('admin.flights.export', ['type' => 'excel', 'dataset' => 'searches'] + request()->query()) }}" class="btn btn-sm btn-outline-secondary">Excel</a>
                <a href="{{ route('admin.flights.export', ['type' => 'pdf', 'dataset' => 'searches'] + request()->query()) }}" class="btn btn-sm btn-outline-secondary" target="_blank">PDF</a>
            </div>
        </div>
        <div class="card-body">
            @permission('flights.manage')
            <form method="POST" action="{{ route('admin.flights.searches.bulk-delete') }}" id="bulk-delete-searches-form" class="mb-3">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete selected search records?')">Bulk Delete Selected</button>
            </form>
            @endpermission
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            @permission('flights.manage')<th><input type="checkbox" id="select-all-searches"></th>@endpermission
                            <th>Destination</th>
                            <th>Origin</th>
                            <th>User</th>
                            <th>Date</th>
                            <th>Device</th>
                            <th>Country</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($searches as $search)
                        <tr>
                            @permission('flights.manage')
                            <td><input type="checkbox" name="ids[]" value="{{ $search->id }}" form="bulk-delete-searches-form"></td>
                            @endpermission
                            <td>{{ $search->destination_code }}</td>
                            <td>{{ $search->origin_code }}</td>
                            <td>{{ $search->user?->email ?? 'Guest' }}</td>
                            <td>{{ $search->created_at->format('M d, Y H:i') }}</td>
                            <td>{{ $search->device ?? 'N/A' }}</td>
                            <td>{{ $search->country ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.flights.searches.show', $search) }}" class="btn btn-sm btn-outline-info">Search Details</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No search records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $searches->links() }}
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Booking Clicks</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.flights.export', ['type' => 'csv', 'dataset' => 'clicks'] + request()->query()) }}" class="btn btn-sm btn-outline-secondary">CSV</a>
                <a href="{{ route('admin.flights.export', ['type' => 'excel', 'dataset' => 'clicks'] + request()->query()) }}" class="btn btn-sm btn-outline-secondary">Excel</a>
                <a href="{{ route('admin.flights.export', ['type' => 'pdf', 'dataset' => 'clicks'] + request()->query()) }}" class="btn btn-sm btn-outline-secondary" target="_blank">PDF</a>
            </div>
        </div>
        <div class="card-body">
            @permission('flights.manage')
            <form method="POST" action="{{ route('admin.flights.clicks.bulk-delete') }}" id="bulk-delete-clicks-form" class="mb-3">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete selected click records?')">Bulk Delete Selected</button>
            </form>
            @endpermission
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            @permission('flights.manage')<th><input type="checkbox" id="select-all-clicks"></th>@endpermission
                            <th>Airline</th>
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Price</th>
                            <th>Affiliate</th>
                            <th>User</th>
                            <th>Clicked Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clickRows as $click)
                        <tr>
                            @permission('flights.manage')
                            <td><input type="checkbox" name="ids[]" value="{{ $click->id }}" form="bulk-delete-clicks-form"></td>
                            @endpermission
                            <td>{{ $click->airline ?? 'N/A' }}<br><small class="text-muted">{{ $click->flight_number }}</small></td>
                            <td>{{ $click->origin }}</td>
                            <td>{{ $click->destination }}</td>
                            <td>{{ $click->currency }} {{ number_format((float) $click->price, 2) }}</td>
                            <td>{{ $click->affiliate_name }}</td>
                            <td>{{ $click->user?->email ?? 'Guest' }}</td>
                            <td>{{ $click->clicked_at?->format('M d, Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No booking clicks found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $clickRows->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.getElementById('select-all-searches')?.addEventListener('change', function () {
    document.querySelectorAll('input[form="bulk-delete-searches-form"]').forEach(cb => cb.checked = this.checked);
});
document.getElementById('select-all-clicks')?.addEventListener('change', function () {
    document.querySelectorAll('input[form="bulk-delete-clicks-form"]').forEach(cb => cb.checked = this.checked);
});

const dailySearchLabels = @json($dailySearches->keys());
const dailySearchValues = @json($dailySearches->values());
const dailyClickLabels = @json($dailyClicks->keys());
const dailyClickValues = @json($dailyClicks->values());
const topAirlineLabels = @json($topAirlines->pluck('airline'));
const topAirlineValues = @json($topAirlines->pluck('total'));
const topDestinationLabels = @json($topDestinations->pluck('destination_code'));
const topDestinationValues = @json($topDestinations->pluck('total'));
const topOriginLabels = @json($topOrigins->pluck('origin_code'));
const topOriginValues = @json($topOrigins->pluck('total'));
const monthlyLabels = @json($monthlyTrends->pluck('month'));
const monthlyValues = @json($monthlyTrends->pluck('searches'));

new Chart(document.getElementById('dailySearchesChart'), {
    type: 'line',
    data: { labels: dailySearchLabels, datasets: [{ label: 'Searches', data: dailySearchValues, borderColor: '#0d6efd', tension: 0.3 }] },
    options: { responsive: true, maintainAspectRatio: false }
});
new Chart(document.getElementById('dailyClicksChart'), {
    type: 'line',
    data: { labels: dailyClickLabels, datasets: [{ label: 'Clicks', data: dailyClickValues, borderColor: '#198754', tension: 0.3 }] },
    options: { responsive: true, maintainAspectRatio: false }
});
new Chart(document.getElementById('topAirlinesChart'), {
    type: 'bar',
    data: { labels: topAirlineLabels, datasets: [{ label: 'Clicks', data: topAirlineValues, backgroundColor: '#6610f2' }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
});
new Chart(document.getElementById('topDestinationsChart'), {
    type: 'bar',
    data: { labels: topDestinationLabels, datasets: [{ label: 'Searches', data: topDestinationValues, backgroundColor: '#fd7e14' }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
});
new Chart(document.getElementById('topOriginsChart'), {
    type: 'bar',
    data: { labels: topOriginLabels, datasets: [{ label: 'Searches', data: topOriginValues, backgroundColor: '#20c997' }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
});
new Chart(document.getElementById('monthlyTrendsChart'), {
    type: 'line',
    data: { labels: monthlyLabels, datasets: [{ label: 'Monthly Searches', data: monthlyValues, borderColor: '#dc3545', tension: 0.3 }] },
    options: { responsive: true, maintainAspectRatio: false }
});
</script>
@endpush
