<div wire:init="loadFeaturedFlights">
    <style>
        .flights-hero {
            position: relative;
            width: 100%;
        }
        .flights-hero-media {
            position: relative;
            width: 100%;
            aspect-ratio: 4 / 1;
            max-height: 256px;
            overflow: hidden;
            background: #1a0a2e;
        }
        .flights-hero-bg {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: left center;
            display: block;
        }
        .flights-hero-media .hero-bg-image {
            width: 100% !important;
            height: 100% !important;
            min-height: 100% !important;
        }
        .flights-hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                180deg,
                rgba(0, 21, 56, 0.05) 0%,
                rgba(0, 21, 56, 0.25) 100%
            );
            pointer-events: none;
        }
        .flights-search-block {
            position: relative;
            z-index: 2;
            margin-top: -26px;
            margin-bottom: 0;
        }
        .flights-options-bar {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: 10px 0 12px;
            margin-top: 0;
        }
        .flights-page-shell {
            width: 100%;
            max-width: 920px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 20px;
            padding-right: 20px;
        }
        .flights-search-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            box-shadow: none;
            width: 100%;
            max-width: 920px;
            margin: 0 auto;
            padding: 14px 16px;
        }
        .flights-search-card .form-control,
        .flights-search-card .btn-search-flights {
            height: 48px;
            font-size: 15px;
            border-radius: 6px;
        }
        .flights-search-card .form-control {
            border-color: #d7dee8;
            color: #1a2b42;
        }
        .flights-search-card .form-control:focus {
            border-color: #003580;
            box-shadow: 0 0 0 0.15rem rgba(0, 53, 128, 0.12);
        }
        .flights-search-card .btn-search-flights {
            background: #003580;
            border: none;
            font-weight: 600;
            font-size: 16px;
        }
        .flights-search-card .btn-search-flights:hover {
            background: #002a66;
        }
        .flights-options-bar .form-control {
            height: 38px;
            font-size: 14px;
            border-radius: 6px;
            border-color: #d7dee8;
            background: #fff;
        }
        .flights-options-bar .btn-reset {
            height: 38px;
            font-size: 14px;
            border-radius: 6px;
            white-space: nowrap;
        }
        .flights-options-divider {
            width: 1px;
            height: 28px;
            background: #d7dee8;
            margin: 0 12px;
            flex-shrink: 0;
        }
        .flight-list-toolbar {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 8px 12px;
        }
        .flight-row {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 8px 10px;
            margin-bottom: 8px;
            transition: box-shadow 0.15s ease;
        }
        .flight-row:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .flight-row__logo { max-height: 22px; max-width: 64px; }
        .flight-row__time { font-size: 14px; font-weight: 600; line-height: 1.2; }
        .flight-row__code { font-size: 11px; color: #6c757d; }
        .flight-row__mid {
            font-size: 10px;
            color: #868e96;
            min-width: 58px;
            text-align: center;
        }
        .flight-row__price { font-size: 15px; font-weight: 700; color: #003580; white-space: nowrap; }
        .flight-row__airline-name { font-size: 11px; font-weight: 600; max-width: 76px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .flight-row__actions .btn { font-size: 12px; padding: 4px 10px; }
        .flight-availability-badge {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.02em;
        }
        .flight-detail-route {
            background: #f8fafc;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 16px;
        }
        .flight-detail-route__leg {
            flex: 1 1 140px;
            min-width: 120px;
        }
        .flight-detail-route__time {
            font-size: 20px;
            font-weight: 700;
            color: #1a2b42;
            line-height: 1.2;
        }
        .flight-detail-route__place {
            font-size: 13px;
            color: #495057;
            margin-top: 2px;
        }
        .flight-detail-route__date {
            font-size: 12px;
            color: #6c757d;
            margin-top: 2px;
        }
        .flight-detail-route__mid {
            text-align: center;
            padding: 0 12px;
            min-width: 110px;
        }
        .flight-detail-route__line {
            height: 2px;
            background: #d7dee8;
            margin: 6px 0;
            position: relative;
        }
        .flight-detail-route__line::after {
            content: '';
            position: absolute;
            right: 0;
            top: -3px;
            border: 4px solid transparent;
            border-left-color: #d7dee8;
        }
        .flight-detail-route__duration {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 4px;
        }
        .flight-detail-info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }
        @media (min-width: 576px) {
            .flight-detail-info-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        .flight-detail-info-item {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 10px 12px;
            min-height: 68px;
        }
        .flight-detail-info-item__label {
            font-size: 11px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-bottom: 4px;
        }
        .flight-detail-info-item__value {
            font-size: 14px;
            color: #1a2b42;
            font-weight: 500;
            line-height: 1.35;
        }
        @media (max-width: 768px) {
            .flights-hero-media { max-height: 180px; }
            .flights-hero-bg { object-position: 20% center; }
            .flights-search-block { margin-top: -20px; }
            .flights-page-shell { padding-left: 14px; padding-right: 14px; }
            .flights-search-card { padding: 14px; }
            .flights-search-card .form-control,
            .flights-search-card .btn-search-flights { height: 44px; font-size: 14px; }
            .flights-options-divider { display: none; }
            .flight-row__book { width: 100%; margin-top: 6px; }
            .flight-detail-route__inner {
                flex-direction: column;
                gap: 12px;
            }
            .flight-detail-route__leg { text-align: center !important; flex: none; width: 100%; }
            .flight-detail-route__mid { width: 100%; max-width: 200px; }
        }
        @media (min-width: 769px) and (max-width: 1023px) {
            .flights-hero-media { max-height: 220px; }
        }
        @media (min-width: 1024px) {
            .flights-hero-media { max-height: 256px; }
        }
    </style>

    <div class="flights-hero">
        <div class="flights-hero-media">
            <img src="{{ asset('images/banner/flights-hero.png') }}"
                 onerror="this.src='{{ asset('images/banner.jpg') }}'"
                 alt="Search flights to Zanzibar"
                 class="flights-hero-bg">
            <div class="flights-hero-overlay"></div>
        </div>
    </div>

    <div class="flights-search-block">
        <div class="flights-page-shell">
            <div class="flights-search-card">
                        @if (session('success'))
                            <div class="alert alert-success py-1 px-2 mb-2 small">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger py-1 px-2 mb-2 small">{{ session('error') }}</div>
                        @endif

                        <form wire:submit.prevent="searchFlights">
                            <div class="row g-2 align-items-stretch" style="margin: 0;">
                                <div class="col-12 col-md-3 mb-2 mb-md-0 d-flex" style="min-width: 0;">
                                    <select wire:model="origin" class="form-control flex-grow-1" required>
                                        @foreach($airportOptions as $group => $airports)
                                            <optgroup label="{{ $group }}">
                                                @foreach($airports as $code => $label)
                                                    <option value="{{ $code }}">{{ $label }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-3 mb-2 mb-md-0 d-flex" style="min-width: 0;">
                                    <select wire:model="destination" class="form-control flex-grow-1" required>
                                        @foreach($airportOptions as $group => $airports)
                                            <optgroup label="{{ $group }}">
                                                @foreach($airports as $code => $label)
                                                    <option value="{{ $code }}">{{ $label }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 col-md-{{ $tripType === 'round_trip' ? '2' : '3' }} mb-2 mb-md-0 d-flex" style="min-width: 0;">
                                    <input type="date" wire:model="departureDate" class="form-control flex-grow-1" required>
                                </div>
                                @if($tripType === 'round_trip')
                                <div class="col-6 col-md-2 mb-2 mb-md-0 d-flex" style="min-width: 0;">
                                    <input type="date" wire:model="returnDate" class="form-control flex-grow-1" required>
                                </div>
                                @endif
                                <div class="col-12 col-md-{{ $tripType === 'round_trip' ? '2' : '3' }} d-flex" style="min-width: 0;">
                                    <button type="submit" class="btn btn-primary btn-search-flights w-100"
                                            wire:loading.attr="disabled" wire:target="searchFlights">
                                        <span wire:loading.remove wire:target="searchFlights">
                                            <i class="fas fa-search mr-2"></i>{{ $tripType === 'round_trip' ? 'Search only' : 'Search Flight' }}
                                        </span>
                                        <span wire:loading wire:target="searchFlights">
                                            <span class="spinner-border spinner-border-sm"></span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
            </div>
        </div>
    </div>

    <section class="flights-options-bar">
        <div class="flights-page-shell">
            <div class="d-flex flex-wrap align-items-center">
                <div class="d-flex flex-wrap align-items-center mb-2 mb-md-0">
                    <select wire:model.live="tripType" class="form-control mr-2 mb-2 mb-md-0" style="width: auto; min-width: 110px;">
                        <option value="one_way">One Way</option>
                        <option value="round_trip">Round Trip</option>
                    </select>
                    <span class="flights-options-divider d-none d-md-block"></span>
                    <select wire:model="adults" class="form-control mr-2 mb-2 mb-md-0" style="width: auto; min-width: 88px;" title="Adults">
                        @for($i = 1; $i <= 9; $i++)
                            <option value="{{ $i }}">{{ $i }} Adult{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                    <select wire:model="children" class="form-control mr-2 mb-2 mb-md-0" style="width: auto; min-width: 100px;" title="Children">
                        @for($i = 0; $i <= 9; $i++)
                            <option value="{{ $i }}">{{ $i }} Child{{ $i !== 1 ? 'ren' : '' }}</option>
                        @endfor
                    </select>
                    <select wire:model="infants" class="form-control mr-2 mb-2 mb-md-0" style="width: auto; min-width: 92px;" title="Infants">
                        @for($i = 0; $i <= 9; $i++)
                            <option value="{{ $i }}">{{ $i }} Infant{{ $i !== 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                    <select wire:model="travelClass" class="form-control mr-2 mb-2 mb-md-0" style="width: auto; min-width: 120px;">
                        <option value="ECONOMY">Economy</option>
                        <option value="PREMIUM_ECONOMY">Premium Economy</option>
                        <option value="BUSINESS">Business</option>
                        <option value="FIRST">First Class</option>
                    </select>
                    <div class="custom-control custom-checkbox mb-2 mb-md-0 mr-2">
                        <input type="checkbox" class="custom-control-input" wire:model="nonStop" id="nonStop">
                        <label class="custom-control-label" for="nonStop">Non-stop only</label>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-secondary btn-reset ml-md-auto mb-2 mb-md-0" wire:click="resetSearch">
                    <i class="fas fa-undo mr-1"></i> Reset
                </button>
            </div>
        </div>
    </section>

    <section class="py-3">
        <div class="flights-page-shell">
                @if($error)
                    <div class="alert alert-danger py-2 small mb-2">{{ $error }}</div>
                @endif

                @if(!$searched && !$loading && $browseMode)
                    <div class="text-center py-3 text-muted small">Loading popular routes...</div>
                @endif

                @if($loading)
                    <div class="text-center py-4">
                        <div class="spinner-border spinner-border-sm text-primary"></div>
                        <span class="text-muted small ml-2">{{ $browseMode ? 'Loading flights...' : 'Searching...' }}</span>
                    </div>
                @endif

                @if($searched && !$loading && empty($displayFlights) && !$error)
                    @if(!empty($flights) && ($hasActiveFilters ?? false))
                        <div class="alert alert-light border text-center py-3 small mb-0">
                            <strong>No flights match your filters.</strong><br>
                            Clear the airline{{ $browseMode ? ' or route' : '' }} filter to see {{ count($flights) }} available flight(s).
                        </div>
                    @elseif($browseMode)
                        <div class="alert alert-warning border text-center py-3 small mb-0">
                            <strong>No featured flights loaded for this date.</strong><br>
                            Try another date, or search a specific route using the form above.
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="loadFeaturedFlights">Retry</button>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning border text-center py-3 small mb-0">
                            <strong>No flights found for {{ $origin }} → {{ $destination }} on {{ \Carbon\Carbon::parse($departureDate)->format('d M Y') }}.</strong><br>
                            Try another date or nearby airports, or adjust passengers and cabin class.
                        </div>
                    @endif
                @endif

                @if(!empty($displayFlights) && !$loading)
                    <div class="flight-list-toolbar d-flex flex-wrap align-items-center justify-content-between mb-2">
                        <span class="small mb-1 mb-md-0">
                            <strong>{{ count($displayFlights) }}</strong> flights available
                            @if($browseMode)
                                <span class="text-muted">· Tanzania, Africa &amp; international</span>
                            @else
                                <span class="text-muted">· {{ $origin }} → {{ $destination }} on {{ \Carbon\Carbon::parse($departureDate)->format('d M Y') }}</span>
                            @endif
                        </span>
                        <div class="d-flex flex-wrap">
                            @if($browseMode && !empty($routeOptions))
                            <select wire:model.live="filterRoute" class="form-control form-control-sm mr-1 mb-1" style="width:auto;min-width:110px;">
                                <option value="">All routes</option>
                                @foreach($routeOptions as $route)
                                    <option value="{{ $route }}">{{ $route }}</option>
                                @endforeach
                            </select>
                            @endif
                            <select wire:model.live="filterAirline" class="form-control form-control-sm mr-1 mb-1" style="width:auto;min-width:110px;">
                                <option value="">All airlines</option>
                                @foreach($airlineOptions as $airline)
                                    <option value="{{ $airline }}">{{ $airline }}</option>
                                @endforeach
                            </select>
                            <select wire:model.live="sortBy" class="form-control form-control-sm mb-1" style="width:auto;min-width:120px;">
                                <option value="price_asc">Cheapest</option>
                                <option value="price_desc">Highest</option>
                                <option value="duration_asc">Shortest</option>
                                <option value="stops_asc">Fewest stops</option>
                            </select>
                        </div>
                    </div>

                    @foreach($displayFlights as $flight)
                        <div class="flight-row">
                            <div class="row align-items-center no-gutters">
                                <div class="col-md-3 col-4 d-flex align-items-center mb-2 mb-md-0 pr-md-1">
                                    <img src="{{ $flight['airline_logo'] ?? 'https://pics.avs.io/72/24/' . ($flight['airline_code'] ?? 'XX') . '.png' }}"
                                         alt="" class="flight-row__logo mr-2">
                                    <div class="min-width-0">
                                        <div class="flight-row__airline-name" title="{{ $flight['airline'] }}">{{ $flight['airline'] }}</div>
                                        <div class="flight-row__code">{{ $flight['flight_number'] }}</div>
                                    </div>
                                </div>

                                <div class="col-md-5 col-8 mb-2 mb-md-0 px-md-1">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <div class="flight-row__time">{{ $flight['departure']['time'] }}</div>
                                            <div class="flight-row__code">{{ $flight['departure']['airport'] }}</div>
                                        </div>
                                        <div class="flight-row__mid px-1">
                                            <div>{{ $flight['duration'] }}</div>
                                            <span class="badge badge-{{ $flight['stops'] === 0 ? 'success' : 'light' }} badge-pill" style="font-size:9px;">
                                                {{ $flight['stops'] === 0 ? 'Direct' : $flight['stops'] . ' stop' }}
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <div class="flight-row__time">{{ $flight['arrival']['time'] }}</div>
                                            <div class="flight-row__code">{{ $flight['arrival']['airport'] }}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap align-items-center mt-1" style="gap:4px;">
                                        <span class="badge badge-success flight-availability-badge">Available</span>
                                        @if($browseMode && !empty($flight['route_label']))
                                            <span class="badge badge-light border" style="font-size:9px;">{{ $flight['route_label'] }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4 col-12 d-flex flex-wrap align-items-center justify-content-md-end flight-row__book flight-row__actions">
                                    <div class="flight-row__price mr-2 mb-2 mb-md-0">{{ $flight['currency'] }} {{ number_format($flight['price'], 0) }}</div>
                                    <button type="button" class="btn btn-outline-primary btn-sm mr-1 mb-2 mb-md-0"
                                            wire:click="showFlightDetails('{{ $flight['id'] }}')">
                                        Details
                                    </button>
                                    <a href="{{ route('flights.checkout', ['offerId' => $flight['id']]) }}"
                                       class="btn btn-success btn-sm mb-2 mb-md-0">
                                        Book
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
        </div>
    </section>

    @if($selectedFlight)
        @php
            $raw = $selectedFlight['offer_data'] ?? [];
            $cabinLabel = str_replace('_', ' ', ucwords(strtolower($selectedFlight['cabin_class'] ?? 'Economy')));
            $departDate = $selectedFlight['departure']['date'] ?? null;
            $arriveDate = $selectedFlight['arrival']['date'] ?? null;
            $priceExpiresLabel = !empty($selectedFlight['price_expires_at'])
                ? \App\Support\FlightOfferMapper::formatAvailabilityExpiry($selectedFlight['price_expires_at'])
                : null;
            if ($departDate) {
                $departDate = \Carbon\Carbon::parse($departDate)->format('D, d M Y');
            }
            if ($arriveDate) {
                $arriveDate = \Carbon\Carbon::parse($arriveDate)->format('D, d M Y');
            }
        @endphp
        <div class="modal fade show d-block" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="flightDetailsModalLabel">
            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <div>
                            <h5 class="modal-title font-weight-bold mb-1" id="flightDetailsModalLabel">Flight Details</h5>
                            @if(!empty($selectedFlight['route_label']))
                                <span class="badge badge-light border">{{ $selectedFlight['route_label'] }}</span>
                            @endif
                        </div>
                        <button type="button" class="close" wire:click="closeFlightDetails" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-3">
                        <div class="d-flex align-items-center mb-4 p-3 rounded" style="background:#f4f7fb;">
                            <img src="{{ $selectedFlight['airline_logo'] ?? 'https://pics.avs.io/99/36/' . ($selectedFlight['airline_code'] ?? 'XX') . '.png' }}"
                                 alt="{{ $selectedFlight['airline'] }}" class="mr-3" style="max-height:40px;">
                            <div>
                                <div class="font-weight-bold h6 mb-1">{{ $selectedFlight['airline'] }}</div>
                                <div class="text-muted small">Flight {{ $selectedFlight['flight_number'] }} · {{ $selectedFlight['airline_code'] ?? '' }}</div>
                            </div>
                            <div class="ml-auto text-right">
                                <span class="badge badge-success mb-2">{{ $selectedFlight['availability_label'] ?? 'Available for this date' }}</span>
                                <div class="h4 text-primary mb-0">{{ $selectedFlight['currency'] }} {{ number_format($selectedFlight['price'], 0) }}</div>
                                <div class="small text-muted">per passenger</div>
                            </div>
                        </div>

                        <div class="flight-detail-route">
                            <div class="d-flex align-items-center justify-content-between flex-wrap flight-detail-route__inner">
                                <div class="flight-detail-route__leg mb-2 mb-md-0">
                                    <div class="flight-detail-route__time">{{ $selectedFlight['departure']['time'] ?? '--:--' }}</div>
                                    <div class="flight-detail-route__place">
                                        {{ $selectedFlight['departure']['city'] ?? '' }}
                                        <span class="font-weight-bold">({{ $selectedFlight['departure']['airport'] ?? '' }})</span>
                                    </div>
                                    @if($departDate)
                                        <div class="flight-detail-route__date">{{ $departDate }}</div>
                                    @endif
                                </div>
                                <div class="flight-detail-route__mid mb-2 mb-md-0">
                                    <div class="flight-detail-route__duration">
                                        <i class="fas fa-clock mr-1"></i>{{ $selectedFlight['duration'] ?? 'N/A' }}
                                    </div>
                                    <div class="flight-detail-route__line"></div>
                                    <span class="badge badge-{{ ($selectedFlight['stops'] ?? 0) === 0 ? 'success' : 'secondary' }}">
                                        {{ ($selectedFlight['stops'] ?? 0) === 0 ? 'Direct' : ($selectedFlight['stops'] . ' stop(s)') }}
                                    </span>
                                </div>
                                <div class="flight-detail-route__leg text-md-right">
                                    <div class="flight-detail-route__time">{{ $selectedFlight['arrival']['time'] ?? '--:--' }}</div>
                                    <div class="flight-detail-route__place">
                                        {{ $selectedFlight['arrival']['city'] ?? '' }}
                                        <span class="font-weight-bold">({{ $selectedFlight['arrival']['airport'] ?? '' }})</span>
                                    </div>
                                    @if($arriveDate)
                                        <div class="flight-detail-route__date">{{ $arriveDate }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <h6 class="font-weight-bold mb-2">Flight information</h6>
                        <div class="flight-detail-info-grid mb-3">
                            <div class="flight-detail-info-item">
                                <div class="flight-detail-info-item__label">Cabin class</div>
                                <div class="flight-detail-info-item__value">{{ $cabinLabel }}</div>
                            </div>
                            <div class="flight-detail-info-item">
                                <div class="flight-detail-info-item__label">Baggage</div>
                                <div class="flight-detail-info-item__value">{{ $selectedFlight['baggage'] ?? 'Check airline policy' }}</div>
                            </div>
                            <div class="flight-detail-info-item">
                                <div class="flight-detail-info-item__label">Ticket policy</div>
                                <div class="flight-detail-info-item__value">{{ $selectedFlight['refundable'] ?? 'Varies by fare' }}</div>
                            </div>
                            <div class="flight-detail-info-item">
                                <div class="flight-detail-info-item__label">Stops</div>
                                <div class="flight-detail-info-item__value">
                                    {{ ($selectedFlight['stops'] ?? 0) === 0 ? 'Non-stop' : $selectedFlight['stops'] . ' stop(s)' }}
                                </div>
                            </div>
                            <div class="flight-detail-info-item">
                                <div class="flight-detail-info-item__label">Book via</div>
                                <div class="flight-detail-info-item__value">{{ $selectedFlight['affiliate_name'] ?? 'Duffel' }}</div>
                            </div>
                            <div class="flight-detail-info-item">
                                <div class="flight-detail-info-item__label">Fare validity</div>
                                <div class="flight-detail-info-item__value">
                                    @if($priceExpiresLabel)
                                        Until {{ $priceExpiresLabel }}
                                    @else
                                        Confirmed at checkout
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if(!empty($raw))
                            <h6 class="font-weight-bold mb-2">Additional details</h6>
                            <div class="flight-detail-info-grid mb-3">
                                @if(!empty($raw['id']))
                                    <div class="flight-detail-info-item">
                                        <div class="flight-detail-info-item__label">Offer ID</div>
                                        <div class="flight-detail-info-item__value">{{ $raw['id'] }}</div>
                                    </div>
                                @endif
                                @if(!empty($raw['owner']['name']))
                                    <div class="flight-detail-info-item">
                                        <div class="flight-detail-info-item__label">Seller</div>
                                        <div class="flight-detail-info-item__value">{{ $raw['owner']['name'] }}</div>
                                    </div>
                                @endif
                                @if(isset($raw['tax_amount']))
                                    <div class="flight-detail-info-item">
                                        <div class="flight-detail-info-item__label">Taxes</div>
                                        <div class="flight-detail-info-item__value">{{ $raw['total_currency'] ?? '' }} {{ $raw['tax_amount'] }}</div>
                                    </div>
                                @endif
                                @if(!empty($raw['expires_at']))
                                    <div class="flight-detail-info-item">
                                        <div class="flight-detail-info-item__label">Offer expires</div>
                                        <div class="flight-detail-info-item__value">{{ $raw['expires_at'] }}</div>
                                    </div>
                                @endif
                                @if(!empty($raw['total_emissions_kg']))
                                    <div class="flight-detail-info-item">
                                        <div class="flight-detail-info-item__label">CO₂ estimate</div>
                                        <div class="flight-detail-info-item__value">{{ $raw['total_emissions_kg'] }} kg</div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="alert alert-light border small mt-3 mb-0">
                            Live fares from Duffel. Prices are held for a limited time — continue to checkout to confirm availability and complete your booking.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeFlightDetails">Close</button>
                        <a href="{{ route('flights.checkout', ['offerId' => $selectedFlight['id']]) }}" class="btn btn-success">
                            Continue to Book
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show" wire:click="closeFlightDetails"></div>
    @endif
</div>
