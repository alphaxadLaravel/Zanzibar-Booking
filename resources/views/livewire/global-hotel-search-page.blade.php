<div>
    <style>
        .gh-hero-media { aspect-ratio: 4/1; max-height: 220px; overflow: hidden; background: #0b2d5c; }
        .gh-hero-media img { width: 100%; height: 100%; object-fit: cover; }
        .gh-search-block { margin-top: -24px; position: relative; z-index: 2; }
        .gh-card { background: #fff; border: 1px solid #e9ecef; border-radius: 6px; }
        .gh-hotel-row { border: 1px solid #e9ecef; border-radius: 6px; padding: 14px 16px; margin-bottom: 10px; background: #fff; }
        .gh-hotel-row:hover { border-color: #003580; }
        .gh-badge { font-size: 12px; background: #eef4ff; color: #003580; padding: 2px 8px; border-radius: 999px; }
        .btn-gh { background: #003580; color: #fff; border: none; }
        .btn-gh:hover { background: #002a66; color: #fff; }
    </style>

    <section class="gh-hero-media">
        <img src="{{ asset('assets/img/hotel-banner.jpg') }}" alt="Partner hotels" onerror="this.style.display='none'">
    </section>

    <div class="container gh-search-block pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="gh-card p-3 mb-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                        <div>
                            <h1 class="h4 mb-1">Partner Hotels — Live Availability</h1>
                            <p class="text-muted small mb-0">Real-time rates across Zanzibar & Tanzania via our wholesaler network.</p>
                        </div>
                        <a href="{{ route('hotels') }}" class="btn btn-sm btn-outline-secondary">Browse local hotels</a>
                    </div>

                    <form wire:submit.prevent="searchHotels" class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small mb-1">Destination</label>
                            <select wire:model="destination" class="form-select">
                                @foreach($destinationOptions as $group => $options)
                                    <optgroup label="{{ $group }}">
                                        @foreach($options as $code => $label)
                                            <option value="{{ $code }}">{{ $label }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small mb-1">Check-in</label>
                            <input type="date" wire:model="checkIn" class="form-control" min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small mb-1">Check-out</label>
                            <input type="date" wire:model="checkOut" class="form-control" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label small mb-1">Rooms</label>
                            <input type="number" wire:model="rooms" min="1" max="5" class="form-control">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label small mb-1">Adults</label>
                            <input type="number" wire:model="adults" min="1" max="9" class="form-control">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label small mb-1">Children</label>
                            <input type="number" wire:model="children" min="0" max="6" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-gh w-100" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="searchHotels">Search</span>
                                <span wire:loading wire:target="searchHotels">Searching…</span>
                            </button>
                        </div>
                    </form>
                </div>

                @if($error)
                    <div class="alert alert-danger">{{ $error }}</div>
                @endif

                @if($searched && ! $loading)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-muted small">{{ count($hotels) }} hotel(s) found</div>
                        <select wire:model.live="sortBy" class="form-select form-select-sm" style="width:auto;">
                            <option value="price_asc">Price: low to high</option>
                            <option value="price_desc">Price: high to low</option>
                            <option value="name_asc">Name A–Z</option>
                        </select>
                    </div>
                @endif

                @if($loading)
                    <div class="text-center py-5 text-muted">Searching partner hotels…</div>
                @elseif($searched && empty($hotels) && ! $error)
                    <div class="alert alert-info">No availability for these dates. Try different dates or destination.</div>
                @else
                    @foreach($hotels as $hotel)
                        @php
                            $stars = \App\Support\HotelOfferMapper::categoryStars($hotel['category_code'] ?? null);
                        @endphp
                        <div class="gh-hotel-row d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <div class="fw-semibold">{{ $hotel['hotel_name'] }}</div>
                                <div class="text-muted small">
                                    {{ $hotel['destination_name'] ?? '' }}
                                    @if($stars)
                                        · {{ str_repeat('★', $stars) }}
                                    @endif
                                    @if(($hotel['rates_count'] ?? 1) > 1)
                                        · <span class="gh-badge">{{ $hotel['rates_count'] }} room options</span>
                                    @endif
                                </div>
                                <div class="small mt-1">{{ $hotel['room_name'] ?? '' }} @if(!empty($hotel['board_name'])) · {{ $hotel['board_name'] }} @endif</div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-primary">{{ $hotel['currency'] ?? 'USD' }} {{ \App\Support\FlightOfferMapper::formatPrice((float) $hotel['price']) }}</div>
                                <div class="text-muted small">total stay · incl. taxes</div>
                                <a href="{{ route('hotels.global.show', ['hotelCode' => $hotel['hotel_code']]) }}" class="btn btn-sm btn-gh mt-2">View rooms</a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
