<div>
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
                <img src="https://www.zanzibarbookings.com/storage/2025/02/19/zanzibarbookingscom1-1681820030-1920x768-large-1739955733-1920x768.jpg"
                    style="
            object-fit: cover;
            width: 100%;
            height: 160px;
            background-repeat: no-repeat;
          "  class="hero-bg-image"
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
                    <div class="search-card p-3 rounded shadow" style="
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
                            @switch($dealType)
                                @case('hotel')
                                    Find Your Perfect Hotel in Zanzibar
                                    @break
                                @case('apartment')
                                    Find Your Perfect Apartment in Zanzibar
                                    @break
                                @case('tour')
                                    Discover Amazing Tours in Zanzibar
                                    @break
                                @case('car')
                                    Rent Your Perfect Car in Zanzibar
                                    @break
                                @default
                                    Find Your Perfect {{ ucfirst($dealType) }} in Zanzibar
                            @endswitch
                        </p>
                        <div class="row g-3" style="width: 100%; margin: 0;">
                            <div class="col-12 col-md-3 d-flex flex-column" style="min-width: 0;">
                                <select class="form-control flex-grow-1" id="search_location" wire:model.live="searchLocation"
                                    style="height: 45px;">
                                    <option value="">All Locations</option>
                                    @foreach($locations as $location)
                                    <option value="{{ $location }}">{{ $location }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-3 d-flex flex-column" style="min-width: 0;">
                                <select class="form-control flex-grow-1" id="search_category" wire:model.live="searchCategory"
                                    style="height: 45px;">
                                    <option value="">All {{ ucfirst($dealType) }} Types</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-3 d-flex flex-column" style="min-width: 0;">
                                <input type="text" class="form-control flex-grow-1" id="search_name" wire:model.live.debounce.300ms="searchName"
                                    placeholder="{{ ucfirst($dealType) }} name..." style="height: 45px;">
                            </div>
                            <div class="col-12 col-md-3 d-flex flex-column justify-content-end" style="min-width: 0;">
                                <button type="button" class="btn btn-primary w-100" wire:click="resetFilters" style="
                                            background: #003580;
                                            border: none;
                                            font-weight: 600;
                                            font-size: 16px;
                                            height: 45px;
                                        ">
                                    <i class="fas fa-refresh mr-2"></i>Reset Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container-fluid">
        <div class="row">
            <!-- Hotel List -->
            <div class="col-lg-7 my-5">
                <div class="list-hotel h-100 d-flex flex-column">
                    <div class="list-hotel__content flex-grow-1" data-plugin="nicescroll" tabindex="1"
                        style="overflow: hidden; outline: none; touch-action: none;">
                        <div class="results-count d-flex align-items-center justify-content-between">
                            <div>
                                Found <b>{{ $deals->total() }} {{ ucfirst($dealType) }}s</b>
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
                                                    <input class="service-sort" type="radio" name="sort"
                                                        wire:click="updateSort('new')" value="new" {{ $sortBy == 'new' ? 'checked' : '' }}>
                                                    New
                                                </label>
                                            </div>
                                            <div class="sort-item">
                                                <span class="title">Price</span>
                                                <label>
                                                    <input class="service-sort" type="radio" name="sort"
                                                        wire:click="updateSort('price_asc')" value="price_asc" {{ $sortBy == 'price_asc' ? 'checked' : '' }}>
                                                    Low to High
                                                </label>
                                                <label>
                                                    <input class="service-sort" type="radio" name="sort"
                                                        wire:click="updateSort('price_desc')" value="price_desc" {{ $sortBy == 'price_desc' ? 'checked' : '' }}>
                                                    High to Low
                                                </label>
                                            </div>
                                            <div class="sort-item">
                                                <span class="title">Name</span>
                                                <label>
                                                    <input class="service-sort" type="radio" name="sort"
                                                        wire:click="updateSort('name_a_z')" value="name_a_z" {{ $sortBy == 'name_a_z' ? 'checked' : '' }}>
                                                    A - Z
                                                </label>
                                                <label>
                                                    <input class="service-sort" type="radio" name="sort"
                                                        wire:click="updateSort('name_z_a')" value="name_z_a" {{ $sortBy == 'name_z_a' ? 'checked' : '' }}>
                                                    Z - A
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            @forelse($deals as $deal)
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                                <div class="tour-item tour-item--grid rounded-4" data-plugin="matchHeight"
                                    data-id="{{ $deal->id }}" data-lat="{{ $deal->lat }}"
                                    data-lng="{{ $deal->long }}">
                                    <div class="hotel-item__thumbnail position-relative">
                                        @if($deal->is_featured)
                                        <span class="hotel-item__label position-absolute"
                                            style="top: 12px; left: 12px; z-index: 2; background: #ff5722; color: #fff; padding: 4px 12px; border-radius: 6px; font-size: 14px;">Featured</span>
                                        @endif
                                        <a href="{{ $deal->view_route }}"
                                            style="display:block;">
                                            <img src="{{ $deal->cover_photo ? asset('storage/' . $deal->cover_photo) : $deal->default_image }}"
                                                alt="{{ $deal->title }}" loading="eager" width="360" height="160"
                                                style="width:100%;height:140px;object-fit:cover;border-radius:8px;" />
                                        </a>
                                        @if($deal->category)
                                        <a class="hotel-item__type" href="#"
                                            style="position:absolute;left:12px;bottom:12px;z-index:2;background:#2e8b57;color:#fff;padding:4px 10px;border-radius:5px;font-size:13px;">
                                            {{ $deal->category->category }}
                                        </a>
                                        @endif
                                        <div class="add-wishlist-wrapper"
                                            style="position:absolute;top:12px;right:12px;z-index:2;">
                                            <a href="#gmz-login-popup" class="add-wishlist gmz-box-popup"
                                                data-effect="mfp-zoom-in">
                                                <i class="fal fa-heart"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="hotel-item__details" style="padding-top:18px;">
                                        <div class="star-rating mb-2">
                                            <div class="star-rating">
                                                <i class="fa fa-star text-warning"></i>
                                                <i class="fa fa-star text-warning"></i>
                                                <i class="fa fa-star text-warning"></i>
                                                <i class="fa fa-star text-warning"></i>
                                                <i class="fa fa-star text-warning"></i>
                                            </div>
                                        </div>
                                        <h3 class="hotel-item__title" style="font-size:1.25rem;font-weight:600;">
                                            <a href="{{ $deal->view_route }}"
                                                style="color:#222;text-decoration:none;">{{ $deal->title }}</a>
                                        </h3>
                                        <div class="hotel-item__meta" style="margin:18px 0 12px 0;">
                                            <div class="i-meta d-flex align-items-center"
                                                style="font-size:15px;color:#888;">
                                                <i class="fal fa-map-marker-alt" style="margin-right:6px;"></i>
                                                <span>{{ $deal->location }}</span>
                                            </div>
                                        </div>
                                        <div class="hotel-item__price mb-3">
                                            <span class="_retail"
                                                style="color:#2e8b57;font-size:1.3rem;font-weight:600;">USD {{
                                                number_format($deal->base_price, 2) }}</span>
                                            <span class="_unit" style="color:#2e8b57;font-size:1rem;">{{ $priceUnit }}</span>
                                        </div>
                                        <a class="btn btn-primary btn-sm hotel-item__view-detail w-100 d-flex justify-content-center align-items-center"
                                            href="{{ $deal->view_route }}"
                                            style="font-size:1rem;padding:8px 0;border-radius:7px;text-align:center;">
                                            View Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                    <h4>No {{ ucfirst($dealType) }}s Found</h4>
                                    <p class="text-muted">
                                        @if($searchLocation || $searchCategory || $searchName)
                                            No {{ $dealType }}s match your current filters. Try adjusting your search criteria.
                                        @else
                                            No {{ $dealType }}s are currently available.
                                        @endif
                                    </p>
                                    @if($searchLocation || $searchCategory || $searchName)
                                        <button class="btn btn-outline-primary" wire:click="resetFilters">
                                            <i class="fas fa-refresh mr-2"></i>Clear Filters
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @endforelse
                        </div>

                        <nav>
                            {{ $deals->links() }}
                        </nav>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 my-5 d-flex align-items-stretch">
                <div class="w-100"
                    style="min-height: 600px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); overflow: hidden;">
                    <div wire:ignore id="interactive-map" style="width: 100%; height: 100%; border-radius: 12px;"></div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        let map = null;
        let markers = [];
        let infoWindows = [];
        let currentDeals = [];
        
        // Initialize map when Livewire loads
        document.addEventListener('livewire:load', function () {
            initMap();
        });
        
        // Update markers when Livewire updates (pagination changes)
        document.addEventListener('livewire:update', function () {
            updateMapMarkers();
        });

        function initMap() {
            console.log('initMap called');
            // Check if map container exists
            const mapElement = document.getElementById("interactive-map");
            console.log('Map element:', mapElement);
            if (!mapElement || map) {
                console.log('Map element not found or map already exists');
                return;
            }
            
            // Zanzibar coordinates
            const zanzibar = { lat: -6.1659, lng: 39.2026 };
            
            try {
                // Create the map
                map = new google.maps.Map(mapElement, {
                    zoom: 8,
                    center: zanzibar,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    styles: [
                        {
                            featureType: "poi",
                            elementType: "labels",
                            stylers: [{ visibility: "off" }]
                        }
                    ]
                });
                
                console.log('Map created successfully');
                
                // Add initial markers
                updateMapMarkers();
            } catch (error) {
                console.error('Error creating map:', error);
            }
        }
        
        function updateMapMarkers() {
            if (!map) return;
            
            // Clear existing markers and info windows
            markers.forEach(marker => marker.setMap(null));
            infoWindows.forEach(infoWindow => infoWindow.close());
            markers = [];
            infoWindows = [];
            
            // Get current deals data from Livewire (only current page items)
            const deals = @json($deals->items());
            currentDeals = deals;
            
            // Filter deals that have coordinates
            const dealsWithCoordinates = deals.filter(deal => 
                deal.lat && deal.long && 
                parseFloat(deal.lat) !== 0 && 
                parseFloat(deal.long) !== 0
            );
            
            console.log(`Found ${dealsWithCoordinates.length} deals with coordinates out of ${deals.length} total deals`);
            
            if (dealsWithCoordinates.length === 0) {
                // Show message when no deals have coordinates
                const mapElement = document.getElementById("interactive-map");
                if (mapElement) {
                    mapElement.innerHTML = `
                        <div style="display: flex; align-items: center; justify-content: center; height: 100%; background: #f8f9fa; border-radius: 12px; flex-direction: column;">
                            <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Location Data</h5>
                            <p class="text-muted text-center">None of the current {{ ucfirst($dealType) }}s have location coordinates.</p>
                        </div>
                    `;
                }
                return;
            }
            
            // Add markers for each deal with coordinates
            dealsWithCoordinates.forEach(deal => {
                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(deal.lat), lng: parseFloat(deal.long) },
                    map: map,
                    title: deal.title,
                    icon: {
                        url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                        scaledSize: new google.maps.Size(30, 30)
                    }
                });
                
                markers.push(marker);
    
                // Add info window
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="padding: 8px; max-width: 220px;">
                            <div style="display: flex; align-items: flex-start; gap: 8px;">
                                <a href="${deal.view_route}" style="text-decoration: none;">
                                    <img src="${deal.cover_photo ? 'storage/' + deal.cover_photo : deal.default_image}" 
                                         alt="${deal.title}" 
                                         style="width: 35px; height: 35px; object-fit: cover; border-radius: 4px; flex-shrink: 0; cursor: pointer;">
                                </a>
                                <div style="flex: 1;">
                                    <a href="${deal.view_route}" style="text-decoration: none; color: inherit;">
                                        <h6 style="margin: 0 0 4px 0; font-weight: 600; color: #333; font-size: 13px; cursor: pointer;">${deal.title}</h6>
                                    </a>
                                    <p style="margin: 0 0 4px 0; color: #666; font-size: 11px;">
                                        <i class="fas fa-map-marker-alt"></i> ${deal.location}
                                    </p>
                                    <p style="margin: 0 0 6px 0; color: #2e8b57; font-weight: 600; font-size: 12px;">
                                        USD ${parseFloat(deal.base_price).toFixed(2)}{{ $priceUnit }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    `
                });
                
                infoWindows.push(infoWindow);
    
                // Add click listener to marker
                marker.addListener('click', () => {
                    // Close all other info windows
                    infoWindows.forEach(iw => iw.close());
                    infoWindow.open(map, marker);
                });
                
                // Add click listener to marker to navigate to deal
                marker.addListener('dblclick', () => {
                    window.location.href = deal.view_route;
                });
            });
            
            // Add click listeners to deal cards to center map on marker
            dealsWithCoordinates.forEach(deal => {
                const dealCard = document.querySelector(`[data-id="${deal.id}"]`);
                if (dealCard) {
                    // Remove existing listeners to prevent duplicates
                    dealCard.removeEventListener('click', handleCardClick);
                    dealCard.addEventListener('click', handleCardClick);
                }
            });
            
            function handleCardClick(event) {
                const dealId = event.currentTarget.getAttribute('data-id');
                const deal = dealsWithCoordinates.find(d => d.id == dealId);
                if (deal) {
                    const marker = markers.find(m => m.getTitle() === deal.title);
                    if (marker) {
                        map.setCenter(marker.getPosition());
                        map.setZoom(14);
                        // Close all info windows first
                        infoWindows.forEach(iw => iw.close());
                        // Find and open the corresponding info window
                        const infoWindow = infoWindows[markers.indexOf(marker)];
                        if (infoWindow) {
                            infoWindow.open(map, marker);
                        }
                    }
                }
            }
        }
    </script>

    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY', 'AIzaSyBvOkBwJcJjJjJjJjJjJjJjJjJjJjJjJjJj') }}&callback=initMap">
    </script>
    
    <!-- Fallback for when Google Maps fails to load -->
    <script>
        // Fallback if Google Maps doesn't load
        setTimeout(function() {
            if (typeof google === 'undefined' || !google.maps) {
                console.log('Google Maps failed to load, showing fallback');
                const mapElement = document.getElementById("interactive-map");
                if (mapElement) {
                    mapElement.innerHTML = `
                        <div style="display: flex; align-items: center; justify-content: center; height: 100%; background: #f8f9fa; border-radius: 12px; flex-direction: column;">
                            <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Interactive Map</h5>
                            <p class="text-muted text-center">Map will be displayed here when Google Maps API is configured.</p>
                            <small class="text-muted">Configure GOOGLE_MAPS_API_KEY in your .env file</small>
                        </div>
                    `;
                }
            }
        }, 5000);
    </script>
    @endpush
</div>