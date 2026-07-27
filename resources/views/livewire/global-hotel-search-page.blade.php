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
            .hero-bg-image { height: 320px !important; min-height: 320px; }
            .hb-search-center {
                position: absolute !important;
                top: 50% !important;
                left: 50% !important;
                transform: translate(-50%, -50%) !important;
                width: 100% !important;
                padding: 0 10px !important;
            }
            .hb-search-card { padding: 15px !important; }
            .hb-search-title--mobile { display: block !important; }
        }
        @media (max-width: 576px) {
            .hero-bg-image { height: 300px !important; min-height: 300px; }
            .hb-search-card { padding: 10px !important; }
        }
        @media (min-width: 769px) {
            .hero-bg-image { height: 160px !important; }
            .hb-search-center { min-height: 100px !important; }
        }
        @media (min-width: 1024px) and (max-width: 1366px) {
            .hero-bg-image { height: 140px !important; }
            .hb-search-center { min-height: 80px !important; }
        }
        .hb-search-card .form-control,
        .hb-search-card .btn { height: 45px !important; font-size: 14px !important; }
        .hb-search-card {
            background: rgba(255,255,255,0.97);
            width: 100%;
            max-width: 1280px;
            margin-left: auto;
            margin-right: auto;
        }
        .hb-search-title--mobile {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            text-align: center;
            margin-bottom: 15px;
            display: none;
        }
        .hb-filter-toolbar .input-group { min-width: 200px; max-width: 260px; }
        .hb-filter-toolbar .input-group .form-control { height: 38px; font-size: 14px; border-left: 0; }
        .hb-filter-toolbar .input-group-text { height: 38px; font-size: 14px; background: #fff; }
        @media (max-width: 767.98px) {
            .hb-filter-toolbar { flex-wrap: wrap; gap: 12px; }
            .hb-filter-toolbar > .d-flex { width: 100%; justify-content: space-between; }
            .hb-filter-toolbar .input-group { flex: 1; min-width: 0; max-width: none; }
        }
    </style>

    <section class="hero-slider" style="min-height: 160px; position: relative; margin-bottom: 0;">
        <div class="container-fluid no-gutters p-0">
            <div class="single-hero-image" style="position: relative;">
                <img src="{{ asset('images/banner.jpg') }}" class="hero-bg-image"
                    style="object-fit: cover; width: 100%; height: 160px; background-repeat: no-repeat;" alt="Hotel & Beds" />
            </div>
            <div class="hb-search-center" style="
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 100%;
                padding: 0 15px;
                z-index: 10;
            ">
                <div class="container" style="max-width: 1350px; margin: 0 auto;">
                    <div class="search-card hb-search-card p-3 rounded shadow">
                        <p class="hb-search-title--mobile">Find Hotels in Tanzania &amp; Zanzibar</p>
                        <form wire:submit.prevent="searchHotels">
                            <div class="row g-3" style="width: 100%; margin: 0;">
                                <div class="col-12 col-md-3 d-flex flex-column" style="min-width: 0;">
                                    <select wire:model="destination" class="form-control flex-grow-1" title="Destination">
                                        <option value="">All destinations</option>
                                        @foreach($destinationOptions as $group => $options)
                                            <optgroup label="{{ $group }}">
                                                @foreach($options as $code => $label)
                                                    <option value="{{ $code }}">{{ $label }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 col-md-2 d-flex flex-column" style="min-width: 0;">
                                    <input type="date" wire:model="checkIn" class="form-control flex-grow-1" min="{{ date('Y-m-d') }}" title="Check-in (optional)">
                                </div>
                                <div class="col-6 col-md-2 d-flex flex-column" style="min-width: 0;">
                                    <input type="date" wire:model="checkOut" class="form-control flex-grow-1" min="{{ date('Y-m-d') }}" title="Check-out (optional)">
                                </div>
                                <div class="col-4 col-md-1 d-flex flex-column" style="min-width: 0;">
                                    <select wire:model="rooms" class="form-control flex-grow-1" title="Rooms">
                                        <option value="">Rooms</option>
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}">{{ $i }} Room{{ $i > 1 ? 's' : '' }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-4 col-md-1 d-flex flex-column" style="min-width: 0;">
                                    <select wire:model="adults" class="form-control flex-grow-1" title="Adults">
                                        <option value="">Adults</option>
                                        @for($i = 1; $i <= 9; $i++)
                                            <option value="{{ $i }}">{{ $i }} Adult{{ $i > 1 ? 's' : '' }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-4 col-md-1 d-flex flex-column" style="min-width: 0;">
                                    <select wire:model="children" class="form-control flex-grow-1" title="Children">
                                        <option value="">Children</option>
                                        @for($i = 0; $i <= 6; $i++)
                                            <option value="{{ $i }}">{{ $i === 0 ? 'No children' : $i . ' Child' . ($i > 1 ? 'ren' : '') }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-12 col-md-2 d-flex gap-2 align-items-stretch" style="min-width: 0;">
                                    <button type="submit" class="btn btn-primary flex-grow-1 w-100" style="background: #003580; border: none; font-weight: 600;" wire:loading.attr="disabled" wire:target="searchHotels">
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
            </div>
        </div>
    </section>

    <section class="container-fluid">
        <div class="row">
            <div class="col-lg-7 mt-4 mb-5">
                <div class="list-hotel h-100 d-flex flex-column">
                    <div class="list-hotel__content flex-grow-1" tabindex="1">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if($error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endif

                        @if($loading)
                            <div class="text-center py-5 text-muted">Loading hotels from Hotelbeds…</div>
                        @elseif(! $searched)
                            <div class="text-center py-5">
                                <i class="fas fa-hotel fa-3x text-muted mb-3"></i>
                                <h4>Browse Partner Hotels</h4>
                                <p class="text-muted mb-0">
                                    Choose filters above and click <strong>Search</strong>.<br>
                                    Leave dates empty to browse hotels, or add dates to see live rates.
                                </p>
                            </div>
                        @else
                            <div class="results-count d-flex align-items-center justify-content-between hb-filter-toolbar">
                                <div>
                                    Found <b>{{ $hotels->total() }} Hotels</b>
                                    @if($checkIn !== '' && $checkOut !== '')
                                        <span class="text-muted small">· {{ $checkIn }} to {{ $checkOut }}</span>
                                    @elseif($browseMode)
                                        <span class="text-muted small">· Directory browse</span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="input-group input-group-sm me-3">
                                        <span class="input-group-text"><i class="fal fa-search"></i></span>
                                        <input type="text" wire:model.live.debounce.300ms="searchName" class="form-control" placeholder="Filter by name…">
                                    </div>
                                    <div class="sort">
                                        <div class="dropdown">
                                            <button class="btn btn-link dropdown" type="button" id="dropdownMenuSortGlobal"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Sort <i class="fal fa-angle-down arrow"></i>
                                            </button>
                                            <div class="dropdown-menu sort-menu dropdown-menu-right" aria-labelledby="dropdownMenuSortGlobal">
                                                <div class="sort-title">
                                                    <h3>SORT BY</h3>
                                                </div>
                                                @if(! $browseMode)
                                                    <div class="sort-item">
                                                        <span class="title">Price</span>
                                                        <label>
                                                            <input class="service-sort" type="radio" name="sort"
                                                                wire:click="updateSort('price_asc')" value="price_asc" {{ $sortBy === 'price_asc' ? 'checked' : '' }}>
                                                            Low to High
                                                        </label>
                                                        <label>
                                                            <input class="service-sort" type="radio" name="sort"
                                                                wire:click="updateSort('price_desc')" value="price_desc" {{ $sortBy === 'price_desc' ? 'checked' : '' }}>
                                                            High to Low
                                                        </label>
                                                    </div>
                                                @endif
                                                <div class="sort-item">
                                                    <span class="title">Name</span>
                                                    <label>
                                                        <input class="service-sort" type="radio" name="sort"
                                                            wire:click="updateSort('name_asc')" value="name_asc" {{ $sortBy === 'name_asc' ? 'checked' : '' }}>
                                                        A - Z
                                                    </label>
                                                    <label>
                                                        <input class="service-sort" type="radio" name="sort"
                                                            wire:click="updateSort('name_desc')" value="name_desc" {{ $sortBy === 'name_desc' ? 'checked' : '' }}>
                                                        Z - A
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                                    @if(!empty($hotel['browse_only']) || empty($hotel['display_price']))
                                                        <span class="text-muted" style="font-size: 0.95rem;">Add dates to see rates</span>
                                                    @else
                                                        <span class="_retail" style="color:#2e8b57;font-size:1.3rem;font-weight:600;">{{ $hotel['display_price'] }}</span>
                                                        <span class="_unit" style="color:#2e8b57;font-size:1rem;">/ stay</span>
                                                    @endif
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
                                            <p class="text-muted">Try different filters or add stay dates for live availability.</p>
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
