@extends('website.layouts.app')

@section('pages')
<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="{{ route('index') }}">Home</a></li>
            <li><span>Flights</span></li>
        </ul>
    </div>
</div>

<section class="list-tour list-tour--grid py-40 bg-gray-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title text-center mb-40">
                    <h2 class="title">Flight Schedules & Routes</h2>
                    <p class="subtitle">Find the best flights from Zanzibar to destinations worldwide</p>
                </div>
            </div>
        </div>

        <!-- Search Form -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <form action="{{ route('flights.index') }}" method="GET" id="flight-search-form">
                    <div class="flight-search-card p-4 mb-4"
                        style="background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        
                        @if($error)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ $error }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">From</label>
                                    <input type="text" class="form-control" name="origin" value="{{ request('origin', 'ZNZ') }}" readonly
                                        style="background: #f8f9fa;">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">To <span class="text-danger">*</span></label>
                                    <select class="form-control" name="destination" id="destination-input" required>
                                        <option value="">Select Destination</option>
                                        @foreach($destinations as $code => $name)
                                        <option value="{{ $code }}" {{ request('destination') == $code ? 'selected' : '' }}>
                                            {{ $name }} ({{ $code }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">Departure <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="departureDate" 
                                        value="{{ request('departureDate', date('Y-m-d', strtotime('+1 day'))) }}" 
                                        min="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">Return (Optional)</label>
                                    <input type="date" class="form-control" name="returnDate" 
                                        value="{{ request('returnDate') }}" 
                                        min="{{ request('departureDate', date('Y-m-d', strtotime('+1 day'))) }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">Passengers</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="adults" 
                                            value="{{ request('adults', 1) }}" min="1" max="9" required>
                                        <span class="input-group-text">Adults</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-3 mt-2">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">Children</label>
                                    <input type="number" class="form-control" name="children" 
                                        value="{{ request('children', 0) }}" min="0" max="9">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">Infants</label>
                                    <input type="number" class="form-control" name="infants" 
                                        value="{{ request('infants', 0) }}" min="0" max="9">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">Travel Class</label>
                                    <select class="form-control" name="travelClass">
                                        <option value="ECONOMY" {{ request('travelClass') == 'ECONOMY' ? 'selected' : '' }}>Economy</option>
                                        <option value="PREMIUM_ECONOMY" {{ request('travelClass') == 'PREMIUM_ECONOMY' ? 'selected' : '' }}>Premium Economy</option>
                                        <option value="BUSINESS" {{ request('travelClass') == 'BUSINESS' ? 'selected' : '' }}>Business</option>
                                        <option value="FIRST" {{ request('travelClass') == 'FIRST' ? 'selected' : '' }}>First Class</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">Options</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="nonStop" value="true" 
                                            id="nonStopCheck" {{ request('nonStop') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="nonStopCheck">
                                            Non-stop flights only
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary w-100" id="search-btn">
                                        <i class="fas fa-search me-2"></i>Search Flights
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Filter Section (Only show if we have results) -->
        @if(count($flights) > 0)
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center p-3" 
                    style="background: white; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
                    <div class="row g-3 flex-grow-1">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Filter by Airline</label>
                            <select class="form-control" id="airline-filter">
                                <option value="">All Airlines</option>
                                @foreach($airlines as $code => $airline)
                                <option value="{{ $airline }}">{{ $airline }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Sort By</label>
                            <select class="form-control" id="sort-filter">
                                <option value="price">Price (Low to High)</option>
                                <option value="duration">Duration (Shortest)</option>
                                <option value="departure">Departure Time</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Flight Results -->
        <div class="row">
            <div class="col-lg-12">
                <div class="flight-results">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Found <b>{{ $flights->count() }} Flights</b></h4>
                        <div class="view-toggle">
                            <button class="btn btn-outline-primary btn-sm active" id="list-view">
                                <i class="fas fa-list"></i> List View
                            </button>
                            <button class="btn btn-outline-primary btn-sm" id="grid-view">
                                <i class="fas fa-th"></i> Grid View
                            </button>
                        </div>
                    </div>

                    <!-- List View -->
                    <div id="flight-list-view">
                        @forelse($flights as $flight)
                        <div class="flight-card mb-3" data-airline="{{ $flight['airline'] }}"
                            data-destination="{{ $flight['arrival']['city'] }}" data-price="{{ $flight['price'] }}"
                            data-duration="{{ $flight['duration'] }}"
                            data-departure="{{ $flight['departure']['time'] }}">
                            <div class="card"
                                style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                <div class="card-body p-4">
                                    <div class="row align-items-center">
                                        <!-- Airline Logo & Info -->
                                        <div class="col-md-2">
                                            <div class="airline-info text-center">
                                                <div class="airline-logo mb-2"
                                                    style="width: 60px; height: 60px; background: #f8f9fa; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                                    <i class="fas fa-plane"
                                                        style="font-size: 1.5rem; color: #007bff;"></i>
                                                </div>
                                                <h6 class="mb-1" style="font-size: 0.9rem; font-weight: 600;">{{
                                                    $flight['airline'] }}</h6>
                                                <small class="text-muted">{{ $flight['flight_number'] }}</small>
                                            </div>
                                        </div>

                                        <!-- Flight Route -->
                                        <div class="col-md-4">
                                            <div class="flight-route">
                                                <div class="route-info">
                                                    <div class="departure">
                                                        <div class="time"
                                                            style="font-size: 1.5rem; font-weight: 700; color: #333;">{{
                                                            $flight['departure']['time'] }}</div>
                                                        <div class="airport" style="font-size: 0.9rem; color: #666;">{{
                                                            $flight['departure']['airport'] }}</div>
                                                        <div class="city" style="font-size: 0.8rem; color: #888;">{{
                                                            $flight['departure']['city'] }}</div>
                                                    </div>

                                                    <div class="route-line text-center my-2">
                                                        <div class="duration"
                                                            style="font-size: 0.8rem; color: #666; background: #f8f9fa; padding: 4px 8px; border-radius: 12px; display: inline-block;">
                                                            {{ $flight['duration'] }}</div>
                                                        <div class="flight-path mt-1">
                                                            <i class="fas fa-plane"
                                                                style="color: #007bff; font-size: 1.2rem;"></i>
                                                        </div>
                                                    </div>

                                                    <div class="arrival">
                                                        <div class="time"
                                                            style="font-size: 1.5rem; font-weight: 700; color: #333;">{{
                                                            $flight['arrival']['time'] }}</div>
                                                        <div class="airport" style="font-size: 0.9rem; color: #666;">{{
                                                            $flight['arrival']['airport'] }}</div>
                                                        <div class="city" style="font-size: 0.8rem; color: #888;">{{
                                                            $flight['arrival']['city'] }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Flight Details -->
                                        <div class="col-md-3">
                                            <div class="flight-details">
                                                <div class="detail-item mb-2">
                                                    <span class="label"
                                                        style="font-size: 0.8rem; color: #666;">Aircraft:</span>
                                                    <span class="value" style="font-size: 0.9rem; font-weight: 500;">{{
                                                        $flight['aircraft'] }}</span>
                                                </div>
                                                <div class="detail-item mb-2">
                                                    <span class="label"
                                                        style="font-size: 0.8rem; color: #666;">Stops:</span>
                                                    <span class="value" style="font-size: 0.9rem; font-weight: 500;">
                                                        @if($flight['stops'] !== null)
                                                        {{ $flight['stops'] == 0 ? 'Non-stop' : $flight['stops'] . '
                                                        stop' . ($flight['stops'] > 1 ? 's' : '') }}
                                                        @else
                                                        N/A
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="detail-item">
                                                    <span class="label"
                                                        style="font-size: 0.8rem; color: #666;">Status:</span>
                                                    <span
                                                        class="value status-{{ strtolower(str_replace(' ', '-', $flight['status'])) }}"
                                                        style="font-size: 0.9rem; font-weight: 500;">
                                                        {{ $flight['status'] }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Price & Book -->
                                        <div class="col-md-3">
                                            <div class="price-section text-end">
                                                <div class="price mb-2">
                                                    @if($flight['price'] !== null)
                                                    <span class="currency" style="font-size: 0.9rem; color: #666;">{{
                                                        $flight['currency'] ?? 'USD' }}</span>
                                                    <span class="amount"
                                                        style="font-size: 2rem; font-weight: 700; color: #2e8b57;">{{
                                                        number_format($flight['price'], 0) }}</span>
                                                    @else
                                                    <span class="amount"
                                                        style="font-size: 1.2rem; font-weight: 600; color: #888;">Price
                                                        on request</span>
                                                    @endif
                                                </div>
                                                @if($flight['price'] !== null)
                                                <div class="per-person mb-3" style="font-size: 0.8rem; color: #888;">per
                                                    person</div>
                                                @endif
                                                <a href="{{ route('flights.booking.form', $flight['id']) }}" 
                                                    class="btn btn-primary btn-sm w-100"
                                                    style="border-radius: 8px;">
                                                    <i class="fas fa-ticket-alt me-2"></i>Book Flight
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-plane" style="font-size: 3rem; color: #bdbdbd;"></i>
                            </div>
                            <h5 class="mb-2" style="color: #888;">No flights available</h5>
                            <p class="mb-0" style="color: #aaa;">No flights found from the API. Please check back later
                                or contact support.</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Grid View (Hidden by default) -->
                    <div id="flight-grid-view" style="display: none;">
                        <div class="row">
                            @forelse($flights as $flight)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="flight-card-grid" data-airline="{{ $flight['airline'] }}"
                                    data-destination="{{ $flight['arrival']['city'] }}"
                                    data-price="{{ $flight['price'] }}" data-duration="{{ $flight['duration'] }}"
                                    data-departure="{{ $flight['departure']['time'] }}">
                                    <div class="card h-100"
                                        style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                        <div class="card-body p-3">
                                            <div class="airline-header mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="airline-logo me-3"
                                                        style="width: 40px; height: 40px; background: #f8f9fa; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-plane" style="color: #007bff;"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1" style="font-size: 0.9rem; font-weight: 600;">{{
                                                            $flight['airline'] }}</h6>
                                                        <small class="text-muted">{{ $flight['flight_number'] }}</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="route-info mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="departure">
                                                        <div class="time" style="font-size: 1.2rem; font-weight: 600;">
                                                            {{ $flight['departure']['time'] }}</div>
                                                        <div class="airport" style="font-size: 0.8rem; color: #666;">{{
                                                            $flight['departure']['airport'] }}</div>
                                                    </div>
                                                    <div class="text-center">
                                                        <div class="duration" style="font-size: 0.7rem; color: #666;">{{
                                                            $flight['duration'] }}</div>
                                                        <i class="fas fa-plane"
                                                            style="color: #007bff; font-size: 1rem;"></i>
                                                    </div>
                                                    <div class="arrival text-end">
                                                        <div class="time" style="font-size: 1.2rem; font-weight: 600;">
                                                            {{ $flight['arrival']['time'] }}</div>
                                                        <div class="airport" style="font-size: 0.8rem; color: #666;">{{
                                                            $flight['arrival']['airport'] }}</div>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <small class="text-muted">{{ $flight['departure']['city'] }} → {{
                                                        $flight['arrival']['city'] }}</small>
                                                </div>
                                            </div>

                                            <div class="flight-details mb-3">
                                                <div class="row text-center">
                                                    <div class="col-4">
                                                        <div class="detail">
                                                            <div class="value"
                                                                style="font-size: 0.9rem; font-weight: 600;">{{
                                                                $flight['aircraft'] }}</div>
                                                            <div class="label" style="font-size: 0.7rem; color: #666;">
                                                                Aircraft</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="detail">
                                                            <div class="value"
                                                                style="font-size: 0.9rem; font-weight: 600;">
                                                                @if($flight['stops'] !== null)
                                                                {{ $flight['stops'] == 0 ? 'Non-stop' : $flight['stops']
                                                                }}
                                                                @else
                                                                N/A
                                                                @endif
                                                            </div>
                                                            <div class="label" style="font-size: 0.7rem; color: #666;">
                                                                Stops</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="detail">
                                                            <div class="value status-{{ strtolower(str_replace(' ', '-', $flight['status'])) }}"
                                                                style="font-size: 0.9rem; font-weight: 600;">{{
                                                                $flight['status'] }}</div>
                                                            <div class="label" style="font-size: 0.7rem; color: #666;">
                                                                Status</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="price-section">
                                                <div class="price text-center mb-2">
                                                    @if($flight['price'] !== null)
                                                    <span class="currency" style="font-size: 0.8rem; color: #666;">{{
                                                        $flight['currency'] ?? 'USD' }}</span>
                                                    <span class="amount"
                                                        style="font-size: 1.5rem; font-weight: 700; color: #2e8b57;">{{
                                                        number_format($flight['price'], 0) }}</span>
                                                    @else
                                                    <span class="amount"
                                                        style="font-size: 1rem; font-weight: 600; color: #888;">Price on
                                                        request</span>
                                                    @endif
                                                </div>
                                                <a href="{{ route('flights.booking.form', $flight['id']) }}" 
                                                    class="btn btn-primary btn-sm w-100"
                                                    style="border-radius: 8px;">
                                                    <i class="fas fa-ticket-alt me-2"></i>Book Flight
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="fas fa-plane" style="font-size: 3rem; color: #bdbdbd;"></i>
                                    </div>
                                    <h5 class="mb-2" style="color: #888;">No flights available</h5>
                                    <p class="mb-0" style="color: #aaa;">No flights found. Please try different search criteria.</p>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .status-on-time {
        color: #28a745 !important;
    }

    .status-delayed {
        color: #ffc107 !important;
    }

    .status-cancelled {
        color: #dc3545 !important;
    }

    .flight-card:hover {
        transform: translateY(-2px);
        transition: transform 0.3s ease;
    }

    .view-toggle .btn.active {
        background-color: #007bff;
        color: white;
    }

    .flight-search-card {
        border: 1px solid #e0e0e0;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    @media (max-width: 768px) {
        .flight-route .route-info {
            text-align: center;
        }

        .flight-route .departure,
        .flight-route .arrival {
            margin-bottom: 10px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // View toggle functionality
    const listViewBtn = document.getElementById('list-view');
    const gridViewBtn = document.getElementById('grid-view');
    const listView = document.getElementById('flight-list-view');
    const gridView = document.getElementById('flight-grid-view');

    listViewBtn.addEventListener('click', function() {
        listViewBtn.classList.add('active');
        gridViewBtn.classList.remove('active');
        listView.style.display = 'block';
        gridView.style.display = 'none';
    });

    gridViewBtn.addEventListener('click', function() {
        gridViewBtn.classList.add('active');
        listViewBtn.classList.remove('active');
        gridView.style.display = 'block';
        listView.style.display = 'none';
    });

    // Filter functionality
    const destinationFilter = document.getElementById('destination-filter');
    const airlineFilter = document.getElementById('airline-filter');
    const sortFilter = document.getElementById('sort-filter');

    function filterFlights() {
        const destination = destinationFilter.value.toLowerCase();
        const airline = airlineFilter.value.toLowerCase();
        const sortBy = sortFilter.value;

        const flightCards = document.querySelectorAll('.flight-card, .flight-card-grid');
        
        flightCards.forEach(card => {
            const cardDestination = card.dataset.destination.toLowerCase();
            const cardAirline = card.dataset.airline.toLowerCase();
            
            const showCard = (!destination || cardDestination.includes(destination)) &&
                           (!airline || cardAirline.includes(airline));
            
            card.style.display = showCard ? 'block' : 'none';
        });

        // Sort visible cards
        const visibleCards = Array.from(flightCards).filter(card => card.style.display !== 'none');
        
        visibleCards.sort((a, b) => {
            switch(sortBy) {
                case 'price':
                    return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                case 'duration':
                    const durationA = parseInt(a.dataset.duration.replace(/[^\d]/g, ''));
                    const durationB = parseInt(b.dataset.duration.replace(/[^\d]/g, ''));
                    return durationA - durationB;
                case 'departure':
                    return a.dataset.departure.localeCompare(b.dataset.departure);
                default:
                    return 0;
            }
        });

        // Re-append sorted cards
        const container = visibleCards[0]?.parentNode;
        if (container) {
            visibleCards.forEach(card => container.appendChild(card));
        }
    }

    destinationFilter.addEventListener('change', filterFlights);
    airlineFilter.addEventListener('change', filterFlights);
    sortFilter.addEventListener('change', filterFlights);
});
</script>

@endsection
@endsection