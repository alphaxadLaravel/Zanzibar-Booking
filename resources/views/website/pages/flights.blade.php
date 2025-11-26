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
                        
                        @if(isset($error) && $error)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ $error }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif
                        
                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <!-- Main Search Fields -->
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">From <span class="text-danger">*</span></label>
                                    <select class="form-control" name="origin" id="origin-input" required>
                                        <option value="">Select Origin</option>
                                        @foreach($destinations as $code => $name)
                                        <option value="{{ $code }}" {{ request('origin', 'ZNZ') == $code ? 'selected' : '' }}>
                                            {{ $name }} ({{ $code }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1 text-center">
                                <button type="button" class="btn btn-outline-secondary btn-sm mt-4" id="swap-airports" title="Swap airports">
                                    <i class="fas fa-exchange-alt"></i>
                                </button>
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
                                    <label class="form-label fw-semibold">Return</label>
                                    <input type="date" class="form-control" name="returnDate" 
                                        value="{{ request('returnDate') }}" 
                                        min="{{ request('departureDate', date('Y-m-d', strtotime('+1 day'))) }}"
                                        placeholder="Optional">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">Passengers</label>
                                    <select class="form-control" name="adults" id="passengers-select">
                                        @for($i = 1; $i <= 9; $i++)
                                        <option value="{{ $i }}" {{ request('adults', 1) == $i ? 'selected' : '' }}>
                                            {{ $i }} {{ $i == 1 ? 'Adult' : 'Adults' }}
                                        </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Advanced Options (Collapsible) -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="button" class="btn btn-link p-0 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#advancedOptions" aria-expanded="false">
                                    <i class="fas fa-cog me-2"></i>Advanced Options
                                    <i class="fas fa-chevron-down ms-2"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="collapse mt-2" id="advancedOptions">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold">Children (2-11)</label>
                                        <select class="form-control" name="children">
                                            <option value="0" {{ request('children', 0) == 0 ? 'selected' : '' }}>0 Children</option>
                                            @for($i = 1; $i <= 9; $i++)
                                            <option value="{{ $i }}" {{ request('children', 0) == $i ? 'selected' : '' }}>
                                                {{ $i }} {{ $i == 1 ? 'Child' : 'Children' }}
                                            </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold">Infants (Under 2)</label>
                                        <select class="form-control" name="infants">
                                            <option value="0" {{ request('infants', 0) == 0 ? 'selected' : '' }}>0 Infants</option>
                                            @for($i = 1; $i <= 9; $i++)
                                            <option value="{{ $i }}" {{ request('infants', 0) == $i ? 'selected' : '' }}>
                                                {{ $i }} {{ $i == 1 ? 'Infant' : 'Infants' }}
                                            </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold">Travel Class</label>
                                        <select class="form-control" name="travelClass">
                                            <option value="ECONOMY" {{ request('travelClass', 'ECONOMY') == 'ECONOMY' ? 'selected' : '' }}>Economy</option>
                                            <option value="PREMIUM_ECONOMY" {{ request('travelClass') == 'PREMIUM_ECONOMY' ? 'selected' : '' }}>Premium Economy</option>
                                            <option value="BUSINESS" {{ request('travelClass') == 'BUSINESS' ? 'selected' : '' }}>Business</option>
                                            <option value="FIRST" {{ request('travelClass') == 'FIRST' ? 'selected' : '' }}>First Class</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold">Flight Type</label>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="nonStop" value="true" 
                                                id="nonStopCheck" {{ request('nonStop') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="nonStopCheck">
                                                Non-stop flights only
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Search Button -->
                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5" id="search-btn">
                                    <i class="fas fa-search me-2"></i>Search Flights
                                </button>
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
                                @if(is_array($airlines))
                                    @foreach($airlines as $code => $airline)
                                    <option value="{{ $airline }}">{{ $airline }}</option>
                                    @endforeach
                                @else
                                    @foreach($airlines as $airline)
                                    <option value="{{ $airline }}">{{ $airline }}</option>
                                    @endforeach
                                @endif
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
                        <h4 class="mb-0">Found <b>{{ count($flights) }} Flights</b></h4>
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
                        <div class="flight-card mb-3" data-airline="{{ $flight['airline'] ?? 'Unknown' }}"
                            data-destination="{{ $flight['arrival']['city'] ?? 'Unknown' }}" 
                            data-price="{{ $flight['price'] ?? 0 }}"
                            data-duration="{{ $flight['duration'] ?? 'N/A' }}"
                            data-departure="{{ $flight['departure']['time'] ?? '' }}">
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
                                                    $flight['airline'] ?? 'Unknown Airline' }}</h6>
                                                <small class="text-muted">{{ $flight['flight_number'] ?? 'N/A' }}</small>
                                            </div>
                                        </div>

                                        <!-- Flight Route -->
                                        <div class="col-md-4">
                                            <div class="flight-route">
                                                <div class="route-info">
                                                    <div class="departure">
                                                <div class="time"
                                                    style="font-size: 1.5rem; font-weight: 700; color: #333;">{{
                                                    $flight['departure']['time'] ?? 'N/A' }}</div>
                                                <div class="airport" style="font-size: 0.9rem; color: #666;">{{
                                                    $flight['departure']['airport'] ?? 'N/A' }}</div>
                                                <div class="city" style="font-size: 0.8rem; color: #888;">{{
                                                    $flight['departure']['city'] ?? 'N/A' }}</div>
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
                                                            $flight['arrival']['time'] ?? 'N/A' }}</div>
                                                        <div class="airport" style="font-size: 0.9rem; color: #666;">{{
                                                            $flight['arrival']['airport'] ?? 'N/A' }}</div>
                                                        <div class="city" style="font-size: 0.8rem; color: #888;">{{
                                                            $flight['arrival']['city'] ?? 'N/A' }}</div>
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
                                                        $flight['aircraft'] ?? 'N/A' }}</span>
                                                </div>
                                                <div class="detail-item mb-2">
                                                    <span class="label"
                                                        style="font-size: 0.8rem; color: #666;">Stops:</span>
                                                    <span class="value" style="font-size: 0.9rem; font-weight: 500;">
                                                        @if(isset($flight['stops']) && $flight['stops'] !== null)
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
                                                        class="value status-{{ strtolower(str_replace(' ', '-', $flight['status'] ?? 'On Time')) }}"
                                                        style="font-size: 0.9rem; font-weight: 500;">
                                                        {{ $flight['status'] ?? 'On Time' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Price & Book -->
                                        <div class="col-md-3">
                                            <div class="price-section text-end">
                                                <div class="price mb-2">
                                                    @if(isset($flight['price']) && $flight['price'] !== null)
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
                                                @if(isset($flight['price']) && $flight['price'] !== null)
                                                <div class="per-person mb-3" style="font-size: 0.8rem; color: #888;">per
                                                    person</div>
                                                @endif
                                                @if(isset($flight['id']))
                                                <a href="{{ route('flights.booking.form', $flight['id']) }}" 
                                                    class="btn btn-primary btn-sm w-100"
                                                    style="border-radius: 8px;">
                                                    <i class="fas fa-ticket-alt me-2"></i>Book Flight
                                                </a>
                                                @else
                                                <button class="btn btn-secondary btn-sm w-100" disabled
                                                    style="border-radius: 8px;">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>Booking Unavailable
                                                </button>
                                                @endif
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
                            <h5 class="mb-2" style="color: #888;">No flights found</h5>
                            <p class="mb-0" style="color: #aaa;">
                                @if(request()->has('destination'))
                                    No flights available for your search criteria. Please try different dates or destinations.
                                @else
                                    Please search for flights using the form above.
                                @endif
                            </p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Grid View (Hidden by default) -->
                    <div id="flight-grid-view" style="display: none;">
                        <div class="row">
                            @forelse($flights as $flight)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="flight-card-grid" data-airline="{{ $flight['airline'] ?? 'Unknown' }}"
                                    data-destination="{{ $flight['arrival']['city'] ?? 'Unknown' }}"
                                    data-price="{{ $flight['price'] ?? 0 }}" 
                                    data-duration="{{ $flight['duration'] ?? 'N/A' }}"
                                    data-departure="{{ $flight['departure']['time'] ?? '' }}">
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
                                                            $flight['airline'] ?? 'Unknown Airline' }}</h6>
                                                        <small class="text-muted">{{ $flight['flight_number'] ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="route-info mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="departure">
                                                        <div class="time" style="font-size: 1.2rem; font-weight: 600;">
                                                            {{ $flight['departure']['time'] ?? 'N/A' }}</div>
                                                        <div class="airport" style="font-size: 0.8rem; color: #666;">{{
                                                            $flight['departure']['airport'] ?? 'N/A' }}</div>
                                                    </div>
                                                    <div class="text-center">
                                                        <div class="duration" style="font-size: 0.7rem; color: #666;">{{
                                                            $flight['duration'] ?? 'N/A' }}</div>
                                                        <i class="fas fa-plane"
                                                            style="color: #007bff; font-size: 1rem;"></i>
                                                    </div>
                                                    <div class="arrival text-end">
                                                        <div class="time" style="font-size: 1.2rem; font-weight: 600;">
                                                            {{ $flight['arrival']['time'] ?? 'N/A' }}</div>
                                                        <div class="airport" style="font-size: 0.8rem; color: #666;">{{
                                                            $flight['arrival']['airport'] ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <small class="text-muted">{{ $flight['departure']['city'] ?? 'N/A' }} → {{
                                                        $flight['arrival']['city'] ?? 'N/A' }}</small>
                                                </div>
                                            </div>

                                            <div class="flight-details mb-3">
                                                <div class="row text-center">
                                                    <div class="col-4">
                                                        <div class="detail">
                                                            <div class="value"
                                                                style="font-size: 0.9rem; font-weight: 600;">{{
                                                                $flight['aircraft'] ?? 'N/A' }}</div>
                                                            <div class="label" style="font-size: 0.7rem; color: #666;">
                                                                Aircraft</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="detail">
                                                            <div class="value"
                                                                style="font-size: 0.9rem; font-weight: 600;">
                                                                @if(isset($flight['stops']) && $flight['stops'] !== null)
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
                                                            <div class="value status-{{ strtolower(str_replace(' ', '-', $flight['status'] ?? 'On Time')) }}"
                                                                style="font-size: 0.9rem; font-weight: 600;">{{
                                                                $flight['status'] ?? 'On Time' }}</div>
                                                            <div class="label" style="font-size: 0.7rem; color: #666;">
                                                                Status</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="price-section">
                                                <div class="price text-center mb-2">
                                                    @if(isset($flight['price']) && $flight['price'] !== null)
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
                                                @if(isset($flight['id']))
                                                <a href="{{ route('flights.booking.form', $flight['id']) }}" 
                                                    class="btn btn-primary btn-sm w-100"
                                                    style="border-radius: 8px;">
                                                    <i class="fas fa-ticket-alt me-2"></i>Book Flight
                                                </a>
                                                @else
                                                <button class="btn btn-secondary btn-sm w-100" disabled
                                                    style="border-radius: 8px;">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>Booking Unavailable
                                                </button>
                                                @endif
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
                                    <h5 class="mb-2" style="color: #888;">No flights found</h5>
                                    <p class="mb-0" style="color: #aaa;">
                                        @if(request()->has('destination'))
                                            No flights available for your search criteria. Please try different dates or destinations.
                                        @else
                                            Please search for flights using the form above.
                                        @endif
                                    </p>
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

    #swap-airports {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        transition: all 0.3s ease;
    }
    
    #swap-airports:hover {
        background-color: #007bff;
        color: white;
        transform: rotate(180deg);
    }
    
    @media (max-width: 768px) {
        .flight-route .route-info {
            text-align: center;
        }

        .flight-route .departure,
        .flight-route .arrival {
            margin-bottom: 10px;
        }
        
        #swap-airports {
            margin: 10px auto;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Add loading state to search button
    const searchForm = document.getElementById('flight-search-form');
    const searchBtn = document.getElementById('search-btn');
    
    if (searchForm && searchBtn) {
        searchForm.addEventListener('submit', function() {
            searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Searching...';
            searchBtn.disabled = true;
        });
    }
    
    // Swap airports functionality
    const swapBtn = document.getElementById('swap-airports');
    const originSelect = document.getElementById('origin-input');
    const destinationSelect = document.getElementById('destination-input');
    
    if (swapBtn && originSelect && destinationSelect) {
        swapBtn.addEventListener('click', function() {
            const originValue = originSelect.value;
            const destinationValue = destinationSelect.value;
            
            // Swap the values
            originSelect.value = destinationValue;
            destinationSelect.value = originValue;
            
            // Trigger change event to update any dependent fields
            originSelect.dispatchEvent(new Event('change'));
            destinationSelect.dispatchEvent(new Event('change'));
        });
    }
    
    // Update return date minimum when departure date changes
    const departureDateInput = document.querySelector('input[name="departureDate"]');
    const returnDateInput = document.querySelector('input[name="returnDate"]');
    
    if (departureDateInput && returnDateInput) {
        departureDateInput.addEventListener('change', function() {
            const departureDate = this.value;
            if (departureDate) {
                // Set minimum return date to departure date
                returnDateInput.min = departureDate;
                
                // If return date is before new departure date, clear it
                if (returnDateInput.value && returnDateInput.value < departureDate) {
                    returnDateInput.value = '';
                }
            }
        });
    }
    
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
    const airlineFilter = document.getElementById('airline-filter');
    const sortFilter = document.getElementById('sort-filter');

    if (airlineFilter && sortFilter) {
        function filterFlights() {
            const airline = airlineFilter.value.toLowerCase();
            const sortBy = sortFilter.value;

            const flightCards = document.querySelectorAll('.flight-card, .flight-card-grid');
            
            flightCards.forEach(card => {
                const cardAirline = (card.dataset.airline || '').toLowerCase();
                
                const showCard = !airline || cardAirline.includes(airline);
                
                card.style.display = showCard ? 'block' : 'none';
            });

            // Sort visible cards
            const visibleCards = Array.from(flightCards).filter(card => card.style.display !== 'none');
            
            visibleCards.sort((a, b) => {
                switch(sortBy) {
                    case 'price':
                        return parseFloat(a.dataset.price || 0) - parseFloat(b.dataset.price || 0);
                    case 'duration':
                        const durationA = parseInt((a.dataset.duration || '0').replace(/[^\d]/g, '')) || 0;
                        const durationB = parseInt((b.dataset.duration || '0').replace(/[^\d]/g, '')) || 0;
                        return durationA - durationB;
                    case 'departure':
                        return (a.dataset.departure || '').localeCompare(b.dataset.departure || '');
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

        airlineFilter.addEventListener('change', filterFlights);
        sortFilter.addEventListener('change', filterFlights);
    }
});
</script>

@endsection