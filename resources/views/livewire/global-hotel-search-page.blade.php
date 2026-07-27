<div>
    <style>
        .pagination { margin: 0; padding: 0; display: flex; list-style: none; justify-content: center; }
        .pagination li { margin-right: 5px; }
        .pagination .page-link {
            position: relative; display: block; padding: 0.5rem 0.75rem; line-height: 1.25;
            color: var(--primary, #003580); background-color: #fff; border: 1px solid #dee2e6;
            border-radius: 3px; min-width: 38px; height: 38px; text-align: center;
            font-size: 14px; font-weight: 500; text-decoration: none;
        }
        .pagination .page-item.active .page-link {
            background-color: var(--primary, #003580); border-color: var(--primary, #003580); color: #fff;
        }
        .list-hotel__content { overflow-y: auto !important; overflow-x: hidden !important; }
        @media (max-width: 991.98px) {
            .list-hotel { height: auto !important; }
            .list-hotel__content { max-height: none !important; }
        }
        @media (max-width: 768px) {
            .hero-bg-image { height: 120px !important; min-height: 120px; }
            .hb-search-block { margin-top: -18px; }
        }
        @media (min-width: 769px) {
            .hero-bg-image { height: 120px !important; }
            .hb-search-block { margin-top: -22px; }
        }
        .hb-search-card .form-control,
        .hb-search-card .btn { height: 40px !important; font-size: 14px !important; }
        .hb-search-card { padding: 10px 12px !important; }
        .hb-filter-toolbar .form-control { height: 38px; font-size: 14px; max-width: 220px; }
    </style>

    <section class="hero-slider hb-hero" style="min-height: 120px; position: relative; margin-bottom: 0;">
        <div class="container-fluid no-gutters p-0">
            <div class="single-hero-image" style="position: relative;">
                <img src="{{ asset('images/banner.jpg') }}" class="hero-bg-image"
                    style="object-fit: cover; width: 100%; height: 120px;" alt="Hotel & Beds" />
            </div>
        </div>
    </section>

    <div class="container hb-search-block" style="position: relative; z-index: 10; max-width: 1280px;">
        <div class="search-card hb-search-card rounded shadow" style="background: #fff; border: 1px solid #e9ecef;">
            <form wire:submit.prevent="searchHotels">
                <div class="row g-2 align-items-center">
                    <div class="col-6 col-md-2">
                        <select wire:model="destination" class="form-control" title="Destination">
                            @foreach($destinationOptions as $group => $options)
                                <optgroup label="{{ $group }}">
                                    @foreach($options as $code => $label)
                                        <option value="{{ $code }}">{{ $label }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <input type="date" wire:model="checkIn" class="form-control" min="{{ date('Y-m-d') }}" title="Check-in">
                    </div>
                    <div class="col-6 col-md-2">
                        <input type="date" wire:model="checkOut" class="form-control" min="{{ date('Y-m-d', strtotime('+1 day')) }}" title="Check-out">
                    </div>
                    <div class="col-4 col-md-1">
                        <input type="number" wire:model="rooms" min="1" max="5" class="form-control" placeholder="Rooms" title="Rooms">
                    </div>
                    <div class="col-4 col-md-1">
                        <input type="number" wire:model="adults" min="1" max="9" class="form-control" placeholder="Adults" title="Adults">
                    </div>
                    <div class="col-4 col-md-1">
                        <input type="number" wire:model="children" min="0" max="6" class="form-control" placeholder="Kids" title="Children">
                    </div>
                    <div class="col-12 col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1" style="background: #003580; border: none; font-weight: 600;" wire:loading.attr="disabled" wire:target="searchHotels">
                            <span wire:loading.remove wire:target="searchHotels"><i class="fas fa-search me-1"></i> Search</span>
                            <span wire:loading wire:target="searchHotels">Searching…</span>
                        </button>
                        <button type="button" class="btn btn-outline-secondary px-3" wire:click="resetFilters" title="Reset filters">
                            <i class="fas fa-refresh"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <section class="container-fluid">
        <div class="row">
            <div class="col-lg-7 mt-4 mb-5">
                <div class="list-hotel h-100 d-flex flex-column">
                    <div class="list-hotel__content flex-grow-1" tabindex="1">
                        @if($error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endif

                        @if($loading)
                            <div class="text-center py-5 text-muted">Loading hotels from Hotelbeds…</div>
                        @else
                            <div class="results-count d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 hb-filter-toolbar">
                                <div>
                                    Found <b>{{ $hotels->total() }} Hotels</b>
                                    @if($searched)
                                        <span class="text-muted small">· {{ $checkIn }} to {{ $checkOut }}</span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <input type="text" wire:model.live.debounce.300ms="searchName" class="form-control form-control-sm" placeholder="Filter by name…">
                                    <select wire:model.live="sortBy" class="form-select form-select-sm" style="width:auto; min-width:140px;">
                                        <option value="price_asc">Price: low to high</option>
                                        <option value="price_desc">Price: high to low</option>
                                        <option value="name_asc">Name A–Z</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                @forelse($hotels as $hotel)
                                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                                        <div class="tour-item tour-item--grid rounded-4" data-id="{{ $hotel['id'] }}"
                                            data-lat="{{ $hotel['lat'] }}" data-lng="{{ $hotel['long'] }}">
                                            <div class="hotel-item__thumbnail position-relative">
                                                <span class="hotel-item__label position-absolute"
                                                    style="top: 12px; left: 12px; z-index: 2; background: #003580; color: #fff; padding: 4px 12px; border-radius: 6px; font-size: 13px;">
                                                    Hotelbeds
                                                </span>
                                                <a href="{{ $hotel['view_route'] }}" style="display:block;">
                                                    <img src="{{ $hotel['image_url'] }}" alt="{{ $hotel['title'] }}"
                                                        loading="lazy" width="360" height="200"
                                                        style="width:100%;height:200px;object-fit:cover;border-radius:8px;" />
                                                </a>
                                                @if(!empty($hotel['board_name']))
                                                    <span style="position:absolute;left:12px;bottom:12px;z-index:2;background:#2e8b57;color:#fff;padding:4px 10px;border-radius:5px;font-size:13px;">
                                                        {{ $hotel['board_name'] }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="hotel-item__details" style="padding-top:18px;">
                                                <div class="star-rating mb-2">
                                                    @include('website.components.star_rating', ['rating' => $hotel['star_rating'], 'size' => 'small'])
                                                </div>
                                                <h3 class="hotel-item__title" style="font-size:1.25rem;font-weight:600;">
                                                    <a href="{{ $hotel['view_route'] }}" style="color:#222;text-decoration:none;">{{ $hotel['title'] }}</a>
                                                </h3>
                                                <div class="hotel-item__meta" style="margin:18px 0 12px 0;">
                                                    <div class="i-meta d-flex align-items-center" style="font-size:15px;color:#888;">
                                                        <i class="fal fa-map-marker-alt" style="margin-right:6px;"></i>
                                                        <span>{{ $hotel['location'] }}</span>
                                                    </div>
                                                </div>
                                                <div class="hotel-item__price mb-3">
                                                    <span class="_retail" style="color:#2e8b57;font-size:1.3rem;font-weight:600;">{{ $hotel['display_price'] }}</span>
                                                    <span class="_unit" style="color:#2e8b57;font-size:1rem;">/ stay</span>
                                                </div>
                                                @if(($hotel['rates_count'] ?? 1) > 1)
                                                    <p class="small text-muted mb-2">{{ $hotel['rates_count'] }} room options available</p>
                                                @endif
                                                <a class="btn btn-primary btn-sm hotel-item__view-detail w-100 d-flex justify-content-center align-items-center"
                                                    href="{{ $hotel['view_route'] }}"
                                                    style="font-size:1rem;padding:8px 0;border-radius:7px;">
                                                    View Detail
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-5">
                                            <i class="fas fa-hotel fa-3x text-muted mb-3"></i>
                                            <h4>No Hotels Found</h4>
                                            <p class="text-muted">Try different dates or another destination.</p>
                                            <button class="btn btn-outline-primary" wire:click="resetFilters">Reset filters</button>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            @if($hotels->hasPages())
                                @php
                                    $currentPage = $hotels->currentPage();
                                    $lastPage = $hotels->lastPage();
                                    $pageStart = max(1, $currentPage - 2);
                                    $pageEnd = min($lastPage, $currentPage + 2);
                                @endphp
                                <nav aria-label="Page navigation" class="mt-4">
                                    <ul class="pagination justify-content-center mb-0 flex-wrap">
                                        <li class="page-item {{ $hotels->onFirstPage() ? 'disabled' : '' }}">
                                            <button type="button" class="page-link" wire:click="previousPage" @disabled($hotels->onFirstPage())>‹</button>
                                        </li>
                                        @if($pageStart > 1)
                                            <li class="page-item">
                                                <button type="button" class="page-link" wire:click="gotoPage(1)">1</button>
                                            </li>
                                            @if($pageStart > 2)
                                                <li class="page-item disabled"><span class="page-link">…</span></li>
                                            @endif
                                        @endif
                                        @for($page = $pageStart; $page <= $pageEnd; $page++)
                                            <li class="page-item {{ $page === $currentPage ? 'active' : '' }}">
                                                <button type="button" class="page-link" wire:click="gotoPage({{ $page }})">{{ $page }}</button>
                                            </li>
                                        @endfor
                                        @if($pageEnd < $lastPage)
                                            @if($pageEnd < $lastPage - 1)
                                                <li class="page-item disabled"><span class="page-link">…</span></li>
                                            @endif
                                            <li class="page-item">
                                                <button type="button" class="page-link" wire:click="gotoPage({{ $lastPage }})">{{ $lastPage }}</button>
                                            </li>
                                        @endif
                                        <li class="page-item {{ $hotels->hasMorePages() ? '' : 'disabled' }}">
                                            <button type="button" class="page-link" wire:click="nextPage" @disabled(! $hotels->hasMorePages())>›</button>
                                        </li>
                                    </ul>
                                </nav>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-5 mt-4 mb-5 d-flex align-items-stretch">
                <div class="w-100" style="min-height: 600px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); overflow: hidden;">
                    <div wire:ignore id="interactive-map-hotelbeds" style="width: 100%; height: 100%; border-radius: 12px;"></div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        let hbMap = null;
        let hbMarkers = [];
        let hbInfoWindows = [];

        document.addEventListener('livewire:load', initHbMap);
        document.addEventListener('livewire:update', updateHbMapMarkers);

        function initHbMap() {
            const el = document.getElementById('interactive-map-hotelbeds');
            if (!el || hbMap || typeof google === 'undefined' || !google.maps) return;

            hbMap = new google.maps.Map(el, {
                zoom: 8,
                center: { lat: -6.1659, lng: 39.2026 },
                mapTypeId: google.maps.MapTypeId.ROADMAP,
            });

            updateHbMapMarkers();
        }

        function updateHbMapMarkers() {
            if (!hbMap) return;

            hbMarkers.forEach(m => m.setMap(null));
            hbInfoWindows.forEach(iw => iw.close());
            hbMarkers = [];
            hbInfoWindows = [];

            const hotels = @json($hotels->items());

            hotels.filter(h => h.lat && h.long && parseFloat(h.lat) !== 0).forEach(hotel => {
                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(hotel.lat), lng: parseFloat(hotel.long) },
                    map: hbMap,
                    title: hotel.title,
                });

                hbMarkers.push(marker);

                const infoWindow = new google.maps.InfoWindow({
                    content: `<div style="padding:8px;max-width:220px;">
                        <a href="${hotel.view_route}"><img src="${hotel.image_url}" style="width:100%;height:80px;object-fit:cover;border-radius:4px;margin-bottom:6px;" alt=""></a>
                        <strong>${hotel.title}</strong><br>
                        <span style="color:#2e8b57;font-weight:600;">${hotel.display_price}</span>
                    </div>`,
                });

                hbInfoWindows.push(infoWindow);
                marker.addListener('click', () => {
                    hbInfoWindows.forEach(iw => iw.close());
                    infoWindow.open(hbMap, marker);
                });
            });
        }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initHbMap"></script>
    @endpush
</div>
