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
      " alt="hotel search banner" class="hero-bg-image"
                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1920&h=350&fit=crop&crop=center';" />
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
                <form action="https://www.zanzibarbookings.com/hotel-search" method="GET"
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
                        Find Your Perfect Hotel in Zanzibar
                    </p>
                    <div class="row g-3" style="width: 100%; margin: 0;">
                        <div class="col-12 col-md-3 d-flex flex-column" style="min-width: 0;">
                            <select class="form-control flex-grow-1" id="search_location" name="location"
                                style="height: 45px;">
                                <option value="">All Locations</option>
                                <option value="Nungwi">Nungwi</option>
                                <option value="Stone Town">Stone Town</option>
                                <option value="Paje">Paje</option>
                                <option value="Kendwa">Kendwa</option>
                                <option value="Jambiani">Jambiani</option>
                                <option value="Michamvi">Michamvi</option>
                                <option value="Matemwe">Matemwe</option>
                                <option value="Kiwengwa">Kiwengwa</option>
                                <option value="Bwejuu">Bwejuu</option>
                                <option value="Pingwe">Pingwe</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3 d-flex flex-column" style="min-width: 0;">
                            <select class="form-control flex-grow-1" id="search_category" name="property_type"
                                style="height: 45px;">
                                <option value="">All Hotel Types</option>
                                <option value="68">Budget Hotels</option>
                                <option value="67">Mid Range Hotels</option>
                                <option value="66">Luxury Beach Resorts</option>
                                <option value="65">Stone Town Hotels</option>
                                <option value="64">Villas & Apartments</option>
                                <option value="63">Homestay</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3 d-flex flex-column" style="min-width: 0;">
                            <input type="text" class="form-control flex-grow-1" id="search_name" name="name"
                                placeholder="Hotel name..." style="height: 45px;">
                        </div>
                        <div class="col-12 col-md-3 d-flex flex-column justify-content-end" style="min-width: 0;">
                            <button type="submit" class="btn btn-primary w-100" style="
                                        background: #003580;
                                        border: none;
                                        font-weight: 600;
                                        font-size: 16px;
                                        height: 45px;
                                    ">
                                <i class="fas fa-search mr-2"></i>Search Hotels
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
        <!-- Hotel List -->
        <div class="col-lg-7 my-5">
            <div class="list-hotel h-100 d-flex flex-column">
                <div class="list-hotel__content flex-grow-1" data-plugin="nicescroll" tabindex="1"
                style="overflow: hidden; outline: none; touch-action: none;">
                <div class="results-count d-flex align-items-center justify-content-between">
                    <div>
                        Found <b>60 Hotels</b>
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
                    @for ($i = 0; $i < 18; $i++)
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                        <div class="tour-item tour-item--grid rounded-4" data-plugin="matchHeight" data-id="357"
                            data-lat="-6.142857" data-lng="39.494472">
                            <div class="hotel-item__thumbnail position-relative">
                                <span class="hotel-item__label position-absolute" style="top: 12px; left: 12px; z-index: 2; background: #ff5722; color: #fff; padding: 4px 12px; border-radius: 6px; font-size: 14px;">Featured</span>
                                <a href="https://www.zanzibarbookings.com/hotel/emerald-dreams-boutique-hotel" style="display:block;">
                                    <img 
                                        src="https://www.zanzibarbookings.com/storage/2024/02/28/emarald-michamvi-10-1709067414-360x240.jpg"
                                        alt="Emerald Dreams Boutique Hotel"
                                        loading="eager"
                                        width="360"
                                        height="240"
                                        style="width:100%;height:220px;object-fit:cover;border-radius:12px;"
                                    />
                                </a>
                                <a class="hotel-item__type" href="https://www.zanzibarbookings.com/hotel-search?property_type=67" style="position:absolute;left:12px;bottom:12px;z-index:2;background:#2e8b57;color:#fff;padding:4px 10px;border-radius:5px;font-size:13px;">
                                    Mid range Hotels
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
                                        <i class="fa fa-star text-muted"></i>
                                    </div>
                                </div>
                                <h3 class="hotel-item__title" style="font-size:1.25rem;font-weight:600;">
                                    <a href="https://www.zanzibarbookings.com/hotel/emerald-dreams-boutique-hotel" style="color:#222;text-decoration:none;">Emerald Dreams Boutique Hotel</a>
                                </h3>
                                <div class="hotel-item__meta" style="margin:18px 0 12px 0;">
                                    <div class="i-meta d-flex align-items-center" style="font-size:15px;color:#888;">
                                        <i class="fal fa-map-marker-alt" style="margin-right:6px;"></i>
                                        <span>Michamvi, Zanzibar</span>
                                    </div>
                                </div>
                                <div class="hotel-item__price mb-3">
                                    <span class="_retail" style="color:#2e8b57;font-size:1.3rem;font-weight:600;">USD 150.00</span>
                                    <span class="_unit" style="color:#2e8b57;font-size:1rem;">/night</span>
                                </div>
                                <a class="btn btn-primary btn-sm hotel-item__view-detail w-100 d-flex justify-content-center align-items-center"
                                    href="https://www.zanzibarbookings.com/hotel/emerald-dreams-boutique-hotel"
                                    style="font-size:1rem;padding:8px 0;border-radius:7px;text-align:center;">
                                    View Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>

                <nav>
                    <ul class="pagination">
                        <li class="page-item disabled" aria-disabled="true" aria-label="« Previous">
                            <span class="page-link" aria-hidden="true">‹</span>
                        </li>
                        <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/hotel-search?page=2">2</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/hotel-search?page=3">3</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/hotel-search?page=4">4</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/hotel-search?page=5">5</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/hotel-search?page=6">6</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/hotel-search?page=7">7</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/hotel-search?page=8">8</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/hotel-search?page=9">9</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/hotel-search?page=10">10</a></li>
                        <li class="page-item">
                            <a class="page-link" href="https://www.zanzibarbookings.com/hotel-search?page=2" rel="next"
                                aria-label="Next »">›</a>
                        </li>
                    </ul>
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

        // Add markers for different areas in Zanzibar
        const locations = [
            { lat: -5.7237, lng: 39.3027, title: "Stone Town", info: "Historic Stone Town" },
            { lat: -5.7403, lng: 39.2926, title: "Nungwi", info: "Nungwi Beach" },
            { lat: -6.1649, lng: 39.4359, title: "Paje", info: "Paje Beach" },
            { lat: -5.7549, lng: 39.2880, title: "Kendwa", info: "Kendwa Beach" },
            { lat: -6.1429, lng: 39.4945, title: "Jambiani", info: "Jambiani Beach" },
            { lat: -6.1649, lng: 39.4359, title: "Michamvi", info: "Michamvi Peninsula" },
            { lat: -5.9412, lng: 39.3623, title: "Matemwe", info: "Matemwe Beach" },
            { lat: -5.7237, lng: 39.3027, title: "Kiwengwa", info: "Kiwengwa Beach" },
            { lat: -6.1649, lng: 39.4359, title: "Bwejuu", info: "Bwejuu Beach" },
            { lat: -6.1649, lng: 39.4359, title: "Pingwe", info: "Pingwe Beach" }
        ];

        // Add markers for each location
        locations.forEach(location => {
            const marker = new google.maps.Marker({
                position: { lat: location.lat, lng: location.lng },
                map: map,
                title: location.title,
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
                    scaledSize: new google.maps.Size(20, 20)
                }
            });

            // Add info window
            const infoWindow = new google.maps.InfoWindow({
                content: `<div style="padding: 5px;"><strong>${location.title}</strong><br>${location.info}</div>`
            });

            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });
        });
    }
</script>

<!-- Load Google Maps API -->
<script async defer 
    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap">
</script>




@endsection