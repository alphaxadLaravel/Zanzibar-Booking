@extends('website.layouts.app')

@section('pages')
<style>
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

        /* Flight card mobile styles */
        .flight-item {
            padding: 15px !important;
            margin-bottom: 15px !important;
        }

        .flight-item .row {
            flex-direction: column !important;
        }

        .flight-item .col-md-2 {
            width: 100% !important;
            margin-bottom: 15px !important;
            text-align: center !important;
        }

        .flight-item .col-md-2:last-child {
            margin-bottom: 0 !important;
        }

        .airline-logo {
            width: 50px !important;
            height: 50px !important;
            margin-bottom: 8px !important;
        }

        .departure-info .time,
        .arrival-info .time {
            font-size: 24px !important;
            font-weight: 700 !important;
        }

        .departure-info .airport,
        .arrival-info .airport {
            font-size: 14px !important;
            font-weight: 600 !important;
        }

        .airline-name {
            font-size: 14px !important;
            font-weight: 700 !important;
            color: #003580 !important;
        }

        .flight-number {
            font-size: 12px !important;
            color: #666 !important;
        }

        .duration {
            font-size: 14px !important;
            font-weight: 600 !important;
            color: #333 !important;
        }

        .aircraft {
            font-size: 12px !important;
            color: #666 !important;
        }

        .price {
            font-size: 24px !important;
            font-weight: 700 !important;
        }

        .price-label {
            font-size: 12px !important;
        }

        .action-buttons .btn {
            width: 100% !important;
            margin-bottom: 8px !important;
            font-size: 14px !important;
            padding: 8px 16px !important;
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

        /* Extra small mobile styles */
        .flight-item {
            padding: 12px !important;
            margin-bottom: 12px !important;
        }

        .airline-logo {
            width: 40px !important;
            height: 40px !important;
        }

        .departure-info .time,
        .arrival-info .time {
            font-size: 20px !important;
        }

        .price {
            font-size: 20px !important;
        }

        .action-buttons .btn {
            font-size: 12px !important;
            padding: 6px 12px !important;
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
            <img src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1920&h=350&fit=crop&crop=center" style="
        object-fit: cover;
        width: 100%;
        height: 160px;
        background-repeat: no-repeat;
      " alt="flight search banner" class="hero-bg-image"
                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1920&h=350&fit=crop&crop=center';" />
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
                <form action="https://www.zanzibarbookings.com/flight-search" method="GET"
                    class="search-card p-3 rounded shadow" style="
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
                        Find Your Perfect Flight to Zanzibar
                    </p>
                    <div class="row g-3" style="width: 100%; margin: 0;">
                        <div class="col-12 col-md-2 d-flex flex-column" style="min-width: 0;">
                            <select class="form-control flex-grow-1" id="departure_airport" name="from"
                                style="height: 45px;">
                                <option value="">From</option>
                                <option value="DAR">Dar es Salaam (DAR)</option>
                                <option value="NBO">Nairobi (NBO)</option>
                                <option value="JRO">Kilimanjaro (JRO)</option>
                                <option value="MBA">Mombasa (MBA)</option>
                                <option value="EBB">Entebbe (EBB)</option>
                                <option value="KGL">Kigali (KGL)</option>
                                <option value="ADD">Addis Ababa (ADD)</option>
                                <option value="JNB">Johannesburg (JNB)</option>
                                <option value="CPT">Cape Town (CPT)</option>
                                <option value="DXB">Dubai (DXB)</option>
                                <option value="IST">Istanbul (IST)</option>
                                <option value="LHR">London (LHR)</option>
                                <option value="CDG">Paris (CDG)</option>
                                <option value="FRA">Frankfurt (FRA)</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2 d-flex flex-column" style="min-width: 0;">
                            <select class="form-control flex-grow-1" id="arrival_airport" name="to"
                                style="height: 45px;">
                                <option value="">To</option>
                                <option value="ZNZ">Zanzibar (ZNZ)</option>
                                <option value="DAR">Dar es Salaam (DAR)</option>
                                <option value="NBO">Nairobi (NBO)</option>
                                <option value="JRO">Kilimanjaro (JRO)</option>
                                <option value="MBA">Mombasa (MBA)</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2 d-flex flex-column" style="min-width: 0;">
                            <input type="date" class="form-control flex-grow-1" id="departure_date" name="departure_date"
                                style="height: 45px;">
                        </div>
                        <div class="col-12 col-md-2 d-flex flex-column" style="min-width: 0;">
                            <select class="form-control flex-grow-1" id="airline" name="airline"
                                style="height: 45px;">
                                <option value="">All Airlines</option>
                                <option value="Precision Air">Precision Air</option>
                                <option value="Air Tanzania">Air Tanzania</option>
                                <option value="Coastal Aviation">Coastal Aviation</option>
                                <option value="Auric Air">Auric Air</option>
                                <option value="Kenya Airways">Kenya Airways</option>
                                <option value="Ethiopian Airlines">Ethiopian Airlines</option>
                                <option value="Emirates">Emirates</option>
                                <option value="Turkish Airlines">Turkish Airlines</option>
                                <option value="British Airways">British Airways</option>
                                <option value="Air France">Air France</option>
                                <option value="Lufthansa">Lufthansa</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2 d-flex flex-column" style="min-width: 0;">
                            <select class="form-control flex-grow-1" id="passengers" name="passengers"
                                style="height: 45px;">
                                <option value="1">1 Passenger</option>
                                <option value="2">2 Passengers</option>
                                <option value="3">3 Passengers</option>
                                <option value="4">4 Passengers</option>
                                <option value="5">5 Passengers</option>
                                <option value="6">6 Passengers</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2 d-flex flex-column justify-content-end" style="min-width: 0;">
                            <button type="submit" class="btn btn-primary w-100" style="
                                        background: #003580;
                                        border: none;
                                        font-weight: 600;
                                        font-size: 16px;
                                        height: 45px;
                                    ">
                                <i class="fas fa-search mr-2"></i>Search Flights
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="container-fluid">
<div class="row">
        <!-- Flight List -->
        <div class="col-12 my-5">
            <div class="list-hotel h-100 d-flex flex-column" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
                <div class="list-hotel__content flex-grow-1" data-plugin="nicescroll" tabindex="1"
                style="overflow: hidden; outline: none; touch-action: none;">
                <div class="results-count d-flex align-items-center justify-content-between">
                    <div>
                        Found <b>24 Flights</b>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="sort">
                            <div class="dropdown">
                                <button class="btn btn-link dropdown" type="button" id="dropdownMenuSort"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Sort <i class="fal fa-angle-down arrow"></i>
                                </button>
                                <div class="dropdown-menu sort-menu dropdown-menu-right"
                                    aria-labelledby="dropdownMenuSort">
                                    <div class="sort-title">
                                        <h3>SORT BY</h3>
                                    </div>
                                    <div class="sort-item">
                                        <label>
                                            <input class="service-sort" type="radio" name="sort" data-value="departure_time"
                                                value="departure_time" checked="&quot;checked&quot;">
                                            Departure Time
                                        </label>
                                    </div>
                                    <div class="sort-item">
                                        <span class="title">Price</span>
                                        <label>
                                                <input class="service-sort" type="radio" name="sort"
                                                    data-value="price_asc" value="price_asc">
                                            Low to High
                                        </label>
                                        <label>
                                                <input class="service-sort" type="radio" name="sort"
                                                    data-value="price_desc" value="price_desc">
                                            High to Low
                                        </label>
                                    </div>
                                    <div class="sort-item">
                                        <span class="title">Duration</span>
                                        <label>
                                                <input class="service-sort" type="radio" name="sort"
                                                    data-value="duration_asc" value="duration_asc">
                                            Shortest
                                        </label>
                                        <label>
                                                <input class="service-sort" type="radio" name="sort"
                                                    data-value="duration_desc" value="duration_desc">
                                            Longest
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    @php
                        $flights = [
                            ['airline' => 'Precision Air', 'flight_no' => 'PW 101', 'from' => 'DAR', 'to' => 'ZNZ', 'departure' => '08:30', 'arrival' => '09:15', 'duration' => '45m', 'price' => 120, 'aircraft' => 'ATR 72', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8a/Precision_Air_logo.svg/200px-Precision_Air_logo.svg.png'],
                            ['airline' => 'Air Tanzania', 'flight_no' => 'TC 201', 'from' => 'DAR', 'to' => 'ZNZ', 'departure' => '10:45', 'arrival' => '11:30', 'duration' => '45m', 'price' => 135, 'aircraft' => 'Bombardier Q400', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Air_Tanzania_logo.svg/200px-Air_Tanzania_logo.svg.png'],
                            ['airline' => 'Coastal Aviation', 'flight_no' => 'CQ 301', 'from' => 'DAR', 'to' => 'ZNZ', 'departure' => '14:20', 'arrival' => '15:05', 'duration' => '45m', 'price' => 150, 'aircraft' => 'Cessna 208', 'logo' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=200&h=200&fit=crop&crop=center'],
                            ['airline' => 'Auric Air', 'flight_no' => 'UI 401', 'from' => 'DAR', 'to' => 'ZNZ', 'departure' => '16:10', 'arrival' => '16:55', 'duration' => '45m', 'price' => 140, 'aircraft' => 'Cessna 208', 'logo' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=200&h=200&fit=crop&crop=center'],
                            ['airline' => 'Precision Air', 'flight_no' => 'PW 102', 'from' => 'NBO', 'to' => 'ZNZ', 'departure' => '09:15', 'arrival' => '11:45', 'duration' => '2h 30m', 'price' => 280, 'aircraft' => 'ATR 72', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8a/Precision_Air_logo.svg/200px-Precision_Air_logo.svg.png'],
                            ['airline' => 'Kenya Airways', 'flight_no' => 'KQ 501', 'from' => 'NBO', 'to' => 'ZNZ', 'departure' => '12:30', 'arrival' => '15:00', 'duration' => '2h 30m', 'price' => 320, 'aircraft' => 'Boeing 737', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4a/Kenya_Airways_logo.svg/200px-Kenya_Airways_logo.svg.png'],
                            ['airline' => 'Ethiopian Airlines', 'flight_no' => 'ET 601', 'from' => 'ADD', 'to' => 'ZNZ', 'departure' => '06:45', 'arrival' => '12:20', 'duration' => '5h 35m', 'price' => 450, 'aircraft' => 'Boeing 737', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8e/Ethiopian_Airlines_logo.svg/200px-Ethiopian_Airlines_logo.svg.png'],
                            ['airline' => 'Emirates', 'flight_no' => 'EK 701', 'from' => 'DXB', 'to' => 'ZNZ', 'departure' => '02:15', 'arrival' => '10:30', 'duration' => '8h 15m', 'price' => 680, 'aircraft' => 'Boeing 777', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Emirates_logo.svg/200px-Emirates_logo.svg.png'],
                            ['airline' => 'Turkish Airlines', 'flight_no' => 'TK 801', 'from' => 'IST', 'to' => 'ZNZ', 'departure' => '23:30', 'arrival' => '08:45', 'duration' => '9h 15m', 'price' => 720, 'aircraft' => 'Airbus A330', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Turkish_Airlines_logo.svg/200px-Turkish_Airlines_logo.svg.png'],
                            ['airline' => 'British Airways', 'flight_no' => 'BA 901', 'from' => 'LHR', 'to' => 'ZNZ', 'departure' => '22:20', 'arrival' => '14:30', 'duration' => '12h 10m', 'price' => 890, 'aircraft' => 'Boeing 787', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5b/British_Airways_logo.svg/200px-British_Airways_logo.svg.png'],
                            ['airline' => 'Air France', 'flight_no' => 'AF 1001', 'from' => 'CDG', 'to' => 'ZNZ', 'departure' => '21:45', 'arrival' => '13:20', 'duration' => '11h 35m', 'price' => 850, 'aircraft' => 'Airbus A350', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/44/Air_France_logo.svg/200px-Air_France_logo.svg.png'],
                            ['airline' => 'Lufthansa', 'flight_no' => 'LH 1101', 'from' => 'FRA', 'to' => 'ZNZ', 'departure' => '20:10', 'arrival' => '11:55', 'duration' => '11h 45m', 'price' => 920, 'aircraft' => 'Airbus A340', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8e/Lufthansa_logo.svg/200px-Lufthansa_logo.svg.png'],
                        ];
                    @endphp

                    @foreach($flights as $flight)
                    <div class="flight-item mb-3" style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 15px; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <div class="airline-info text-center">
                                    <div class="airline-name" style="font-size: 14px; font-weight: 700; color: #003580; margin-bottom: 8px;">{{ $flight['airline'] }}</div>
                                    <div class="airline-logo" style="width: 60px; height: 60px; background: #f8f9fa; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin: 0 auto; overflow: hidden; border: 2px solid #e9ecef;">
                                        <img src="{{ $flight['logo'] }}" alt="{{ $flight['airline'] }}" style="width: 100%; height: 100%; object-fit: contain; border-radius: 8px;" 
                                             onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; background: #003580; color: white; font-size: 20px;">
                                            <i class="fas fa-plane"></i>
                                        </div>
                                    </div>
                                    <div class="flight-number" style="font-size: 10px; color: #999; margin-top: 4px;">{{ $flight['flight_no'] }}</div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="departure-info text-center">
                                    <div class="time" style="font-size: 18px; font-weight: 600; color: #333;">{{ $flight['departure'] }}</div>
                                    <div class="airport" style="font-size: 12px; color: #666;">{{ $flight['from'] }}</div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="flight-route text-center">
                                    <div class="duration" style="font-size: 12px; color: #666; margin-bottom: 5px;">{{ $flight['duration'] }}</div>
                                    <div class="route-line" style="height: 2px; background: #003580; position: relative;">
                                        <i class="fas fa-plane" style="position: absolute; right: -8px; top: -6px; color: #003580; font-size: 12px;"></i>
                                    </div>
                                    <div class="aircraft" style="font-size: 10px; color: #999; margin-top: 5px;">{{ $flight['aircraft'] }}</div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="arrival-info text-center">
                                    <div class="time" style="font-size: 18px; font-weight: 600; color: #333;">{{ $flight['arrival'] }}</div>
                                    <div class="airport" style="font-size: 12px; color: #666;">{{ $flight['to'] }}</div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="price-info text-center">
                                    <div class="price" style="font-size: 20px; font-weight: 700; color: #003580;">${{ $flight['price'] }}</div>
                                    <div class="price-label" style="font-size: 11px; color: #666;">per person</div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="action-buttons text-center">
                                    <a href="#" class="btn btn-success btn-sm" style="width: 100%; font-size: 12px;">
                                        <i class="fas fa-shopping-cart mr-1"></i>Book Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                <nav>
                    <ul class="pagination">
                        <li class="page-item disabled" aria-disabled="true" aria-label="« Previous">
                            <span class="page-link" aria-hidden="true">‹</span>
                        </li>
                        <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/car-search?page=2">2</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/car-search?page=3">3</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/car-search?page=4">4</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/car-search?page=5">5</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/car-search?page=6">6</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/car-search?page=7">7</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/car-search?page=8">8</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/car-search?page=9">9</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/car-search?page=10">10</a></li>
                        <li class="page-item">
                            <a class="page-link" href="https://www.zanzibarbookings.com/car-search?page=2" rel="next"
                                aria-label="Next »">›</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    </div>
</section>




@endsection