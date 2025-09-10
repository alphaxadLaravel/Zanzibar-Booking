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

    /* Tour item styles */
    .tour-item {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 20px;
        padding: 15px;
        background: #fff;
        transition: all 0.3s ease;
    }

    .tour-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .tour-item__thumbnail {
        position: relative;
        overflow: hidden;
        border-radius: 6px;
    }

    .tour-item__thumbnail img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .tour-item__label {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #28a745;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .tour-item__type {
        position: absolute;
        bottom: 10px;
        left: 10px;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        text-decoration: none;
    }

    .tour-item__title a {
        color: #333;
        text-decoration: none;
        font-weight: 600;
    }

    .tour-item__title a:hover {
        color: #007bff;
    }

    .tour-item__location {
        color: #666;
        font-size: 14px;
        margin: 8px 0;
    }

    .tour-item__price ._retail {
        font-size: 18px;
        font-weight: 700;
        color: #28a745;
    }

    .tour-item__price ._unit {
        font-size: 14px;
        color: #666;
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
      " alt="tour search banner" class="hero-bg-image"
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
                <form action="https://www.zanzibarbookings.com/tour-search" method="GET"
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
                        Find Your Perfect Tour in Zanzibar
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
                            <select class="form-control flex-grow-1" id="search_category" name="tour_type"
                                style="height: 45px;">
                                <option value="">All Tour Types</option>
                                <option value="city">City Tours</option>
                                <option value="beach">Beach Tours</option>
                                <option value="cultural">Cultural Tours</option>
                                <option value="adventure">Adventure Tours</option>
                                <option value="wildlife">Wildlife Tours</option>
                                <option value="food">Food Tours</option>
                                <option value="diving">Diving Tours</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3 d-flex flex-column" style="min-width: 0;">
                            <input type="text" class="form-control flex-grow-1" id="search_name" name="name"
                                placeholder="Tour name..." style="height: 45px;">
                        </div>
                        <div class="col-12 col-md-3 d-flex flex-column justify-content-end" style="min-width: 0;">
                            <button type="submit" class="btn btn-primary w-100" style="
                                        background: #003580;
                                        border: none;
                                        font-weight: 600;
                                        font-size: 16px;
                                        height: 45px;
                                    ">
                                <i class="fas fa-search mr-2"></i>Search Tours
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
        <!-- Tour List -->
        <div class="col-lg-7 my-5">
            <div class="list-tour h-100 d-flex flex-column">
                <div class="list-tour__content flex-grow-1" data-plugin="nicescroll" tabindex="1"
                style="overflow: hidden; outline: none; touch-action: none;">
                <div class="results-count d-flex align-items-center justify-content-between">
                    <div>
                        Found <b>45 Tours</b>
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

                    @for ($i = 0; $i < 20; $i++)
                    <div class="tour-item tour-item--list" data-id="357"
                        data-lat="-6.142857" data-lng="39.494472">
                    <div class="row">
                        <div class="col-4">
                            <div class="tour-item__thumbnail">
                                <div class="add-wishlist-wrapper">
                                    <a href="#gmz-login-popup" class="add-wishlist gmz-box-popup"
                                        data-effect="mfp-zoom-in"><i class="fal fa-heart"></i></a>
                                </div>
                                <a href="https://www.zanzibarbookings.com/tour/stone-town-cultural-tour">
                                    <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=360&h=240&fit=crop&crop=center"
                                        alt="Stone Town Cultural Tour"
                                        onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=360&amp;h=240&amp;fit=crop&amp;crop=center';"
                                        class="loaded">
                                </a>
                                <span class="tour-item__label">Popular</span>
                                <a class="tour-item__type"
                                    href="https://www.zanzibarbookings.com/tour-search?tour_type=cultural">
                                    Cultural Tours
                                </a>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="tour-item__details">
                                <div class="star-rating">
                                    <div class="star-rating"><i class="fa fa-star"></i><i class="fa fa-star"></i><i
                                            class="fa fa-star"></i></div>
                                </div>
                                <h3 class="tour-item__title">
                                    <a href="https://www.zanzibarbookings.com/tour/stone-town-cultural-tour">Stone Town
                                        Cultural Walking Tour</a>
                                </h3>
                                <p class="tour-item__location">
                                    <i class="fal fa-map-marker-alt mr-2"></i>
                                    Stone Town, Zanzibar
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <div class="tour-item__price">
                                        <span class="_retail">USD 45.00</span>
                                        <span class="_unit">per person</span>
                                    </div>
                                    <a class="btn btn-primary"
                                        href="https://www.zanzibarbookings.com/tour/stone-town-cultural-tour">View
                                        Details </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    @endfor

                <nav>
                    <ul class="pagination">
                        <li class="page-item disabled" aria-disabled="true" aria-label="« Previous">
                            <span class="page-link" aria-hidden="true">‹</span>
                        </li>
                        <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/tour-search?page=2">2</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/tour-search?page=3">3</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/tour-search?page=4">4</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/tour-search?page=5">5</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/tour-search?page=6">6</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/tour-search?page=7">7</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/tour-search?page=8">8</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/tour-search?page=9">9</a></li>
                        <li class="page-item"><a class="page-link"
                                href="https://www.zanzibarbookings.com/tour-search?page=10">10</a></li>
                        <li class="page-item">
                            <a class="page-link" href="https://www.zanzibarbookings.com/tour-search?page=2" rel="next"
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

        // Add markers for different tour locations in Zanzibar
        const locations = [
            { lat: -6.1639, lng: 39.1892, title: "Stone Town", info: "Cultural Walking Tours" },
            { lat: -5.7403, lng: 39.2926, title: "Nungwi", info: "Snorkeling & Beach Tours" },
            { lat: -6.1649, lng: 39.4359, title: "Paje", info: "Kite Surfing Tours" },
            { lat: -5.7549, lng: 39.2880, title: "Kendwa", info: "Sunset Dhow Tours" },
            { lat: -6.1429, lng: 39.4945, title: "Jambiani", info: "Seaweed Farm Tours" },
            { lat: -6.1649, lng: 39.4359, title: "Michamvi", info: "Spice Plantation Tours" },
            { lat: -5.9412, lng: 39.3623, title: "Matemwe", info: "Diving & Snorkeling" },
            { lat: -5.7237, lng: 39.3027, title: "Kiwengwa", info: "Beach & Water Sports" },
            { lat: -6.1649, lng: 39.4359, title: "Bwejuu", info: "Cultural Village Tours" },
            { lat: -6.1649, lng: 39.4359, title: "Pingwe", info: "The Rock Restaurant Tours" }
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

<script async defer 
    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap">
</script>

@endsection