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
            <img src="storage/2025/02/19/zanzibarbookingscom1-1681820030-1920x768-large-1739955733-1920x768.jpg" style="
        object-fit: cover;
        width: 100%;
        height: 160px;
        background-repeat: no-repeat;
      " alt="car rental search banner" class="hero-bg-image"
                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1549317336-206569e8475c?w=1920&h=350&fit=crop&crop=center';" />
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
                <form action="https://www.zanzibarbookings.com/car-search" method="GET"
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
                        Find Your Perfect Car in Zanzibar
                    </p>
                    <div class="row g-3" style="width: 100%; margin: 0;">
                        <div class="col-12 col-md-3 d-flex flex-column" style="min-width: 0;">
                            <select class="form-control flex-grow-1" id="search_location" name="location"
                                style="height: 45px;">
                                <option value="">All Locations</option>
                                @foreach($locations as $location)
                                <option value="{{ $location }}">{{ $location }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3 d-flex flex-column" style="min-width: 0;">
                            <select class="form-control flex-grow-1" id="search_category" name="car_type"
                                style="height: 45px;">
                                <option value="">All Car Types</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3 d-flex flex-column" style="min-width: 0;">
                            <input type="text" class="form-control flex-grow-1" id="search_name" name="name"
                                placeholder="Car model or brand..." style="height: 45px;">
                        </div>
                        <div class="col-12 col-md-3 d-flex flex-column justify-content-end" style="min-width: 0;">
                            <button type="submit" class="btn btn-primary w-100" style="
                                        background: #003580;
                                        border: none;
                                        font-weight: 600;
                                        font-size: 16px;
                                        height: 45px;
                                    ">
                                <i class="fas fa-search mr-2"></i>Search Cars
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
        <!-- Car List -->
        <div class="col-lg-7 my-5">
            <div class="list-hotel h-100 d-flex flex-column">
                <div class="list-hotel__content flex-grow-1" data-plugin="nicescroll" tabindex="1"
                style="overflow: hidden; outline: none; touch-action: none;">
                <div class="results-count d-flex align-items-center justify-content-between">
                    <div>
                        Found <b>{{ $cars->total() }} Cars</b>
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
                                            <input class="service-sort" type="radio" name="sort" data-value="new"
                                                value="new" checked="&quot;checked&quot;">
                                            New
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
                                        <span class="title">Name</span>
                                        <label>
                                                <input class="service-sort" type="radio" name="sort"
                                                    data-value="name_a_z" value="name_a_z">
                                            A - Z
                                        </label>
                                        <label>
                                                <input class="service-sort" type="radio" name="sort"
                                                    data-value="name_z_a" value="name_z_a">
                                            Z - A
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="layout">
                            <a class="layout-item active" data-layout="list" href="javascript:void(0);"
                                data-toggle="tooltip" data-placement="top" title="" data-original-title="List View">
                                <i class="fal fa-list-alt"></i>
                            </a>
                                <a class="layout-item " data-layout="grid" href="javascript:void(0);"
                                    data-toggle="tooltip" data-placement="top" title="" data-original-title="Grid View">
                                <i class="fal fa-th"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @forelse($cars as $car)
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                        <div class="tour-item tour-item--grid rounded-4" data-plugin="matchHeight" data-id="{{ $car->id }}"
                            data-lat="{{ $car->lat }}" data-lng="{{ $car->long }}">
                            <div class="hotel-item__thumbnail position-relative">
                                @if($car->is_featured)
                                <span class="hotel-item__label position-absolute" style="top: 12px; left: 12px; z-index: 2; background: #28a745; color: #fff; padding: 4px 12px; border-radius: 6px; font-size: 14px;">Featured</span>
                                @else
                                <span class="hotel-item__label position-absolute" style="top: 12px; left: 12px; z-index: 2; background: #28a745; color: #fff; padding: 4px 12px; border-radius: 6px; font-size: 14px;">Available</span>
                                @endif
                                <a href="{{ route('view-car', ['id' => $hashids->encode($car->id)]) }}" style="display:block;">
                                    <img 
                                        src="{{ $car->cover_photo ? asset('storage/' . $car->cover_photo) : 'https://images.unsplash.com/photo-1549317336-206569e8475c?w=360&h=240&fit=crop&crop=center' }}"
                                        alt="{{ $car->title }}"
                                        onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1549317336-206569e8475c?w=360&h=240&fit=crop&crop=center';"
                                        loading="eager"
                                        width="360"
                                        height="240"
                                        style="width:100%;height:220px;object-fit:cover;border-radius:12px;"
                                    />
                                </a>
                                <a class="hotel-item__type" href="#" style="position:absolute;left:12px;bottom:12px;z-index:2;background:#007bff;color:#fff;padding:4px 10px;border-radius:5px;font-size:13px;">
                                    {{ $car->category->category }}
                                </a>
                                <div class="add-wishlist-wrapper" style="position:absolute;top:12px;right:12px;z-index:2;">
                                    <a href="#gmz-login-popup" class="add-wishlist gmz-box-popup" data-effect="mfp-zoom-in">
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
                                    <a href="{{ route('view-car', ['id' => $hashids->encode($car->id)]) }}" style="color:#222;text-decoration:none;">{{ $car->title }}</a>
                                </h3>
                                <div class="hotel-item__meta" style="margin:18px 0 12px 0;">
                                    <div class="i-meta d-flex align-items-center" style="font-size:15px;color:#888;">
                                        <i class="fal fa-map-marker-alt" style="margin-right:6px;"></i>
                                        <span>{{ $car->location }}</span>
                                    </div>
                                </div>
                                <div style="margin-top:18px;">
                                    <div class="tour-item__price mb-2" style="text-align:left;">
                                        <span class="_retail" style="color:#2e8b57;font-size:1.3rem;font-weight:600;">USD {{ number_format($car->base_price, 2) }}</span>
                                        <span class="_unit" style="color:#2e8b57;font-size:1rem;">/Day</span>
                                    </div>
                                    <a class="btn btn-primary btn-sm tour-item__view-detail"
                                        href="{{ route('view-car', ['id' => $hashids->encode($car->id)]) }}"
                                        style="width:100%;display:block;text-align:center;font-size:1rem;padding:8px 22px;border-radius:7px;">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <h3>No cars found</h3>
                            <p>Try adjusting your search criteria</p>
                        </div>
                    </div>
                    @endforelse
                </div>

                <nav>
                    {{ $cars->links() }}
                </nav>
            </div>
        </div>
    </div>

        <!-- Static Google Map Aside -->
        <div class="col-lg-5 my-5 d-flex align-items-stretch">
            <div class="w-100" style="min-height: 600px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); overflow: hidden;">
                <div id="interactive-map" style="width: 100%; height: 100%; border-radius: 12px;"></div>
            </div>
        </div>
    </div>
</section>
<!-- Interactive Google Map -->
<script>
    function initMap() {
        // Zanzibar coordinates
        const zanzibar = { lat: -6.1659, lng: 39.2026 };
        
        // Create the map
        const map = new google.maps.Map(document.getElementById("interactive-map"), {
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

        // Get cars data from Laravel
        const cars = @json($cars);

        // Add markers for each car
        cars.forEach(car => {
            if (car.lat && car.lng) {
                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(car.lat), lng: parseFloat(car.lng) },
                    map: map,
                    title: car.title,
                    icon: {
                        url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                        scaledSize: new google.maps.Size(20, 20)
                    }
                });

                // Add info window
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="padding: 10px; max-width: 250px;">
                            <h6 style="margin: 0 0 8px 0; font-weight: 600;">${car.title}</h6>
                            <p style="margin: 0 0 8px 0; color: #666; font-size: 14px;">
                                <i class="fas fa-map-marker-alt" style="margin-right: 5px;"></i>${car.location}
                            </p>
                            <p style="margin: 0 0 8px 0; color: #2e8b57; font-weight: 600;">
                                USD ${parseFloat(car.base_price).toFixed(2)} /day
                            </p>
                            <a href="/view-car/${car.id}" style="color: #007bff; text-decoration: none; font-size: 14px;">
                                View Details →
                            </a>
                        </div>
                    `
                });

                marker.addListener('click', () => {
                    infoWindow.open(map, marker);
                });
            }
        });

        // Add click listeners to car cards to center map on marker
        document.querySelectorAll('.tour-item').forEach(card => {
            card.addEventListener('click', function() {
                const lat = parseFloat(this.dataset.lat);
                const lng = parseFloat(this.dataset.lng);
                if (lat && lng) {
                    map.setCenter({ lat: lat, lng: lng });
                    map.setZoom(12);
                }
            });
        });
    }
</script>

<!-- Load Google Maps API -->
<script async defer 
    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap">
</script>




@endsection