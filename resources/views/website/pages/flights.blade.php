@extends('website.layouts.app')

@section('pages')
<style>
    /* Banner and Search Styles */
    .hero-slider {
        min-height: 160px;
        position: relative;
    }

    .single-hero-image {
        position: relative;
    }

    .hero-bg-image {
        object-fit: cover;
        width: 100%;
        height: 160px;
        background-repeat: no-repeat;
    }

    .search-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        padding: 0 15px;
        z-index: 10;
    }

    .search-card .form-control::placeholder {
        color: #6c757d;
        opacity: 0.7;
    }

    .search-center__title--desktop {
        font-size: clamp(20px, 4vw, 30px);
        font-weight: 600;
        color: #fff;
        text-align: center;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }

    .search-center__title--mobile {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        text-align: center;
        margin-bottom: 15px;
        display: none;
    }

        /* Mobile responsive styles */
        @media (max-width: 768px) {
            .hero-bg-image {
                height: 320px !important;
                min-height: 320px;
            }

            .search-center {
                position: absolute !important;
                top: 50% !important;
                left: 50% !important;
                transform: translate(-50%, -50%) !important;
                width: 100% !important;
                padding: 0 10px !important;
            }

            .search-card {
                padding: 15px !important;
            }

            .search-center__title--desktop {
                display: none !important;
            }

            .search-center__title--mobile {
                display: block !important;
            }

            .form-control,
            .btn {
                height: 40px !important;
                font-size: 14px !important;
            }

            .col-12 {
                margin-bottom: 10px !important;
            }

            .col-12:last-child {
                margin-bottom: 0 !important;
            }
        }

        @media (max-width: 576px) {
            .hero-bg-image {
                height: 300px !important;
                min-height: 300px;
            }

            .search-center__title--mobile {
                font-size: 16px !important;
                margin-bottom: 12px !important;
            }

            .search-card {
                padding: 10px !important;
            }

            .form-control,
            .btn {
                height: 38px !important;
                font-size: 13px !important;
            }
        }

        /* Large screens - maintain current design */
        @media (min-width: 769px) {
            .hero-bg-image {
                height: 160px !important;
            }

            .search-center {
                min-height: 100px !important;
            }
        }

        /* Laptop screens - reduced banner size */
        @media (min-width: 1024px) and (max-width: 1366px) {
            .hero-bg-image {
                height: 140px !important;
            }

            .search-center {
                min-height: 80px !important;
            }
        }
</style>

<section class="hero-slider" style="min-height: 160px; position: relative;">
    <div class="container-fluid no-gutters p-0">
        <div class="single-hero-image" style="position: relative;">
            <img src="{{ asset('images/banner.jpg') }}"
                style="
                    object-fit: cover;
                    width: 100%;
                    height: 160px;
                    background-repeat: no-repeat;
                " class="hero-bg-image"
            />
        </div>
        <div class="search-center" style="
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            padding: 0 15px;
            z-index: 10;
        ">
            <div class="container" style="max-width: 1350px; margin: 0 auto;">
                <form action="{{ route('flights.index') }}" method="GET" class="search-card p-3 rounded shadow" id="flight-search-form" style="
                    background: rgba(255,255,255,0.97);
                    width: 100%;
                    max-width: 1280px;
                    margin-left: auto;
                    margin-right: auto;
                ">
                    <p class="search-center__title search-center__title--mobile" style="
                        font-size: 18px;
                        font-weight: 600;
                        color: #333;
                        text-align: center;
                        margin-bottom: 15px;
                        display: none;
                    ">
                        Find Your Perfect Flight in Tanzania
                    </p>
                    
                    @if(isset($error) && $error)
                    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ $error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif
                    
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <div class="row g-3" style="width: 100%; margin: 0;">
                        <div class="col-12 col-md-3 d-flex flex-column" style="min-width: 0;">
                            <div class="position-relative">
                                <input type="text" 
                                       class="form-control airport-autocomplete flex-grow-1" 
                                       id="origin-input" 
                                       placeholder="From (Airport/City)"
                                       autocomplete="off"
                                       value="{{ request('origin') && isset($destinations[request('origin')]) ? request('origin') . ' - ' . $destinations[request('origin')] : (request('origin', 'ZNZ') . ' - ' . ($destinations[request('origin', 'ZNZ')] ?? 'Zanzibar')) }}"
                                       style="height: 45px;"
                                       required>
                                <input type="hidden" name="origin" id="origin-code" value="{{ request('origin', 'ZNZ') }}">
                                <div class="autocomplete-dropdown" id="origin-dropdown"></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 d-flex flex-column" style="min-width: 0;">
                            <div class="position-relative">
                                <input type="text" 
                                       class="form-control airport-autocomplete flex-grow-1" 
                                       id="destination-input" 
                                       placeholder="To (Airport/City)"
                                       autocomplete="off"
                                       value="{{ request('destination') && isset($destinations[request('destination')]) ? request('destination') . ' - ' . $destinations[request('destination')] : '' }}"
                                       style="height: 45px;"
                                       required>
                                <input type="hidden" name="destination" id="destination-code" value="{{ request('destination', '') }}">
                                <div class="autocomplete-dropdown" id="destination-dropdown"></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-2 d-flex flex-column" style="min-width: 0;">
                            <input type="date" class="form-control flex-grow-1" name="departureDate" 
                                value="{{ request('departureDate', date('Y-m-d', strtotime('+1 day'))) }}" 
                                min="{{ date('Y-m-d') }}" 
                                style="height: 45px;"
                                required>
                        </div>
                        <div class="col-12 col-md-2 d-flex flex-column" style="min-width: 0;">
                            <input type="date" class="form-control flex-grow-1" name="returnDate" 
                                value="{{ request('returnDate') }}" 
                                min="{{ request('departureDate', date('Y-m-d', strtotime('+1 day'))) }}"
                                placeholder="Return (Optional)"
                                style="height: 45px;">
                        </div>
                        <div class="col-12 col-md-2 d-flex flex-column justify-content-end" style="min-width: 0;">
                            <button type="submit" class="btn btn-primary w-100" id="search-btn" style="
                                background: #003580;
                                border: none;
                                font-weight: 600;
                                font-size: 16px;
                                height: 45px;
                            ">
                                <i class="fas fa-search me-2"></i>Search Flights
                            </button>
                        </div>
                    </div>
                    
                    <!-- Hidden fields for default values -->
                    <input type="hidden" name="adults" value="1">
                    <input type="hidden" name="children" value="0">
                    <input type="hidden" name="infants" value="0">
                    <input type="hidden" name="travelClass" value="ECONOMY">
                    <input type="hidden" name="nonStop" value="false">
                </form>
            </div>
        </div>
    </div>
</section>

<section class="list-tour list-tour--grid py-40 bg-gray-100">
    <div class="container">

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

    /* Autocomplete Styles */
    .position-relative {
        position: relative;
    }

    .autocomplete-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ddd;
        border-top: none;
        border-radius: 0 0 8px 8px;
        max-height: 300px;
        overflow-y: auto;
        z-index: 9999;
        display: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .autocomplete-dropdown.show {
        display: block;
    }

    .autocomplete-item {
        padding: 12px 15px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }

    .autocomplete-item:hover,
    .autocomplete-item.active {
        background-color: #f8f9fa;
    }

    .autocomplete-item:last-child {
        border-bottom: none;
    }

    .autocomplete-item .location-code {
        font-weight: 600;
        color: #007bff;
        margin-right: 8px;
    }

    .autocomplete-item .location-name {
        color: #333;
        font-size: 0.95rem;
    }

    .autocomplete-item .location-details {
        font-size: 0.85rem;
        color: #666;
        margin-top: 4px;
    }

    .autocomplete-item .location-type {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        margin-left: 8px;
    }

    .autocomplete-item .location-type.airport {
        background-color: #e3f2fd;
        color: #1976d2;
    }

    .autocomplete-item .location-type.city {
        background-color: #f3e5f5;
        color: #7b1fa2;
    }

    .autocomplete-loading {
        padding: 15px;
        text-align: center;
        color: #666;
    }

    .autocomplete-no-results {
        padding: 15px;
        text-align: center;
        color: #999;
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
    // Airport/City Autocomplete functionality
    let searchTimeout;
    let activeDropdown = null;
    let selectedIndex = -1;

    function initAutocomplete(inputId, codeInputId, dropdownId) {
        const input = document.getElementById(inputId);
        const codeInput = document.getElementById(codeInputId);
        const dropdown = document.getElementById(dropdownId);

        if (!input || !codeInput || !dropdown) return;

        input.addEventListener('input', function() {
            const keyword = this.value.trim();
            
            if (keyword.length < 2) {
                dropdown.classList.remove('show');
                codeInput.value = '';
                return;
            }

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchLocations(keyword, dropdown, codeInput, input);
            }, 300);
        });

        input.addEventListener('focus', function() {
            if (this.value.trim().length >= 2) {
                searchLocations(this.value.trim(), dropdown, codeInput, input);
            }
        });

        input.addEventListener('keydown', function(e) {
            const items = dropdown.querySelectorAll('.autocomplete-item');
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                updateSelection(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                updateSelection(items);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (selectedIndex >= 0 && items[selectedIndex]) {
                    items[selectedIndex].click();
                }
            } else if (e.key === 'Escape') {
                dropdown.classList.remove('show');
                selectedIndex = -1;
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
                selectedIndex = -1;
            }
        });
    }

    function searchLocations(keyword, dropdown, codeInput, input) {
        dropdown.innerHTML = '<div class="autocomplete-loading"><i class="fas fa-spinner fa-spin"></i> Searching...</div>';
        dropdown.classList.add('show');
        selectedIndex = -1;

        // Filter by Tanzania (TZ) only
        fetch('{{ route("flights.search-locations") }}?keyword=' + encodeURIComponent(keyword) + '&countryCode=TZ&subTypes[]=AIRPORT&subTypes[]=CITY&limit=10', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                dropdown.innerHTML = '';
                data.data.forEach((location, index) => {
                    const item = document.createElement('div');
                    item.className = 'autocomplete-item';
                    item.setAttribute('data-index', index);
                    
                    const typeClass = location.subType === 'CITY' ? 'city' : 'airport';
                    const typeLabel = location.subType === 'CITY' ? 'City' : 'Airport';
                    
                    item.innerHTML = `
                        <div>
                            <span class="location-code">${location.iataCode || ''}</span>
                            <span class="location-name">${location.name}</span>
                            <span class="location-type ${typeClass}">${typeLabel}</span>
                        </div>
                        <div class="location-details">
                            ${location.cityName ? location.cityName + ', ' : ''}${location.countryName}
                        </div>
                    `;
                    
                    item.addEventListener('click', function() {
                        input.value = location.displayName || `${location.iataCode} - ${location.name}`;
                        codeInput.value = location.iataCode || '';
                        dropdown.classList.remove('show');
                        selectedIndex = -1;
                    });
                    
                    dropdown.appendChild(item);
                });
            } else {
                dropdown.innerHTML = '<div class="autocomplete-no-results">No locations found</div>';
            }
        })
        .catch(error => {
            console.error('Location search error:', error);
            dropdown.innerHTML = '<div class="autocomplete-no-results">Error searching locations</div>';
        });
    }

    function updateSelection(items) {
        items.forEach((item, index) => {
            if (index === selectedIndex) {
                item.classList.add('active');
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.classList.remove('active');
            }
        });
    }

    // Initialize autocomplete for origin and destination
    initAutocomplete('origin-input', 'origin-code', 'origin-dropdown');
    initAutocomplete('destination-input', 'destination-code', 'destination-dropdown');

    // Add loading state to search button
    const searchForm = document.getElementById('flight-search-form');
    const searchBtn = document.getElementById('search-btn');
    
    if (searchForm && searchBtn) {
        searchForm.addEventListener('submit', function(e) {
            // Validate that codes are selected
            const originCode = document.getElementById('origin-code').value;
            const destinationCode = document.getElementById('destination-code').value;
            
            if (!originCode || !destinationCode) {
                e.preventDefault();
                alert('Please select valid origin and destination airports/cities.');
                return;
            }
            
            searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Searching...';
            searchBtn.disabled = true;
        });
    }
    
    // Swap airports functionality
    const swapBtn = document.getElementById('swap-airports');
    const originInput = document.getElementById('origin-input');
    const destinationInput = document.getElementById('destination-input');
    const originCodeInput = document.getElementById('origin-code');
    const destinationCodeInput = document.getElementById('destination-code');
    
    if (swapBtn && originInput && destinationInput) {
        swapBtn.addEventListener('click', function() {
            const originValue = originInput.value;
            const destinationValue = destinationInput.value;
            const originCode = originCodeInput.value;
            const destinationCode = destinationCodeInput.value;
            
            // Swap the values
            originInput.value = destinationValue;
            destinationInput.value = originValue;
            originCodeInput.value = destinationCode;
            destinationCodeInput.value = originCode;
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