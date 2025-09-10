@extends('website.layouts.app')

@section('pages')
<section class="hero-slider" style="min-height: 350px; position: relative;">
    <div class="container-fluid no-gutters p-0">
        <div class="single-hero-image" style="position: relative;">
            <img src="storage/2025/02/19/zanzibarbookingscom1-1681820030-1920x768-large-1739955733-1920x768.jpg" style="
        object-fit: cover;
        width: 100%;
        height: 769px;
        background-repeat: no-repeat;
      " alt="home slider" class="hero-bg-image"
                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1920&amp;h=768&amp;fit=crop&amp;crop=center';" />
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
                <p class="search-center__title search-center__title--desktop" style="
                    font-size: clamp(20px, 4vw, 30px);
                    font-weight: 600;
                    color: #fff;
                    text-align: center;
                    margin-bottom: 20px;
                    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
                ">
                    Find a great ride with Zanzibar Bookings
                </p>
                <form action="#" method="GET" class="search-card p-3 rounded shadow"
                      style="
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
                        Find a great ride with Zanzibar Bookings
                    </p>
                    <div class="row g-3" style="width: 100%; margin: 0;">
                        <div class="col-12 col-md-3 d-flex flex-column" style="min-width: 0;">
                            <select class="form-control flex-grow-1" id="search_location" name="location" style="height: 45px;">
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
                                <!-- Add more locations as needed -->
                            </select>
                        </div>
                        <div class="col-12 col-md-3 d-flex flex-column" style="min-width: 0;">
                            <select class="form-control flex-grow-1" id="search_category" name="category" style="height: 45px;">
                                <option value="">All Categories</option>
                                <option value="hotel">Hotel</option>
                                <option value="apartment">Apartment</option>
                                <option value="tour">Tour</option>
                                <option value="resort">Resort</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3 d-flex flex-column" style="min-width: 0;">
                            <input type="text" class="form-control flex-grow-1" id="search_name" name="name" 
                                   placeholder="Hotel, Apartment, Tour..." style="height: 45px;">
                        </div>
                        <div class="col-12 col-md-3 d-flex flex-column justify-content-end" style="min-width: 0;">
                            <button type="submit" class="btn btn-primary w-100"
                                    style="
                                        background: #003580;
                                        border: none;
                                        font-weight: 600;
                                        font-size: 16px;
                                        height: 45px;
                                    ">
                                <i class="fas fa-search mr-2"></i>Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
/* Mobile responsive styles */
@media (max-width: 768px) {
    .hero-bg-image {
        height: 400px !important;
        min-height: 400px;
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
        margin-top: 0 !important;
    }
    
    .search-center__title--desktop {
        display: none !important;
    }
    
    .search-center__title--mobile {
        display: block !important;
        font-size: 18px !important;
        margin-bottom: 15px !important;
    }
    
    .form-control, .btn {
        height: 40px !important;
        font-size: 14px !important;
        margin-bottom: 10px !important;
    }
    
    .btn {
        margin-top: 0 !important;
        margin-bottom: 0 !important;
    }
}

@media (max-width: 576px) {
    .hero-bg-image {
        height: 350px !important;
        min-height: 350px;
    }
    
    .search-center__title--mobile {
        font-size: 16px !important;
        margin-bottom: 12px !important;
    }
    
    .search-card {
        padding: 10px !important;
    }
    
    .form-control, .btn {
        height: 38px !important;
        font-size: 13px !important;
        margin-bottom: 8px !important;
    }
    
    .btn {
        margin-bottom: 0 !important;
    }
}

/* Large screens - maintain current design */
@media (min-width: 769px) {
    .hero-bg-image {
        height: 769px !important;
    }
    
    .search-center {
        min-height: 260px !important;
    }
}
</style>



<section class="hotel-type">
    <div class="container py-40">
        <h2 class="section-title mb-20">Property Types</h2>
        <div class="row">
            <div class="col-lg-2 col-md-4 col-6">
                <div class="hotel-type__item" data-plugin="matchHeight">
                    <div class="hotel-type__thumbnail">
                        <a href="hotel-search9234.html?property_type=68">
                            <img class="_image-hotel"
                                src="storage/2023/04/08/shanuo-beach-bungalows-23-1680964040-250x150.jpg"
                                alt="Budget Hotels"
                                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=250&amp;h=150&amp;fit=crop&amp;crop=center';" />
                        </a>
                    </div>
                    <div class="hotel-type__info">
                        <h3 class="hotel-type__name">
                            <a href="hotel-search9234.html?property_type=68">Budget Hotels</a>
                        </h3>
                        <div class="hotel-type__description"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="hotel-type__item" data-plugin="matchHeight">
                    <div class="hotel-type__thumbnail">
                        <a href="hotel-searcha761.html?property_type=67">
                            <img class="_image-hotel" src="storage/2023/04/08/beach-lodge-1680965632-250x150.jpg"
                                alt="Mid range Hotels"
                                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=250&amp;h=150&amp;fit=crop&amp;crop=center';" />
                        </a>
                    </div>
                    <div class="hotel-type__info">
                        <h3 class="hotel-type__name">
                            <a href="hotel-searcha761.html?property_type=67">Mid range Hotels</a>
                        </h3>
                        <div class="hotel-type__description"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="hotel-type__item" data-plugin="matchHeight">
                    <div class="hotel-type__thumbnail">
                        <a href="hotel-search86a4.html?property_type=66">
                            <img class="_image-hotel"
                                src="storage/2023/04/08/hotel-riu-palace-zanzibar-72-1680967382-250x150.jpg"
                                alt="Luxury Beach Resorts"
                                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=250&amp;h=150&amp;fit=crop&amp;crop=center';" />
                        </a>
                    </div>
                    <div class="hotel-type__info">
                        <h3 class="hotel-type__name">
                            <a href="hotel-search86a4.html?property_type=66">Luxury Beach Resorts</a>
                        </h3>
                        <div class="hotel-type__description"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="hotel-type__item" data-plugin="matchHeight">
                    <div class="hotel-type__thumbnail">
                        <a href="hotel-search5021.html?property_type=65">
                            <img class="_image-hotel"
                                src="storage/2023/04/08/park-exterior-hyatt-1680967642-250x150.jpg"
                                alt="Stone Town Hotels"
                                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=250&amp;h=150&amp;fit=crop&amp;crop=center';" />
                        </a>
                    </div>
                    <div class="hotel-type__info">
                        <h3 class="hotel-type__name">
                            <a href="hotel-search5021.html?property_type=65">Stone Town Hotels</a>
                        </h3>
                        <div class="hotel-type__description"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="hotel-type__item" data-plugin="matchHeight">
                    <div class="hotel-type__thumbnail">
                        <a href="hotel-searchfe2d.html?property_type=64">
                            <img class="_image-hotel"
                                src="storage/2023/04/08/apartment-in-zanzibar-1-1680965251-250x150.jpg"
                                alt="Villas &amp; Apartments"
                                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=250&amp;h=150&amp;fit=crop&amp;crop=center';" />
                        </a>
                    </div>
                    <div class="hotel-type__info">
                        <h3 class="hotel-type__name">
                            <a href="hotel-searchfe2d.html?property_type=64">Villas &amp; Apartments</a>
                        </h3>
                        <div class="hotel-type__description"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="hotel-type__item" data-plugin="matchHeight">
                    <div class="hotel-type__thumbnail">
                        <a href="hotel-search864d.html?property_type=63">
                            <img class="_image-hotel" src="storage/2023/04/08/living-room-1680972056-250x150.jpg"
                                alt="Homestay"
                                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=250&amp;h=150&amp;fit=crop&amp;crop=center';" />
                        </a>
                    </div>
                    <div class="hotel-type__info">
                        <h3 class="hotel-type__name">
                            <a href="hotel-search864d.html?property_type=63">Homestay</a>
                        </h3>
                        <div class="hotel-type__description"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="list-hotel list-hotel--grid py-40 bg-gray-100">
    <div class="container">
        <h2 class="section-title mb-20">List Of Hotels</h2>
        <div class="row">

            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="hotel-item hotel-item--grid" data-plugin="matchHeight">
                    <div class="hotel-item__thumbnail">
                        <div class="add-wishlist-wrapper">
                            <a href="#gmz-login-popup" class="add-wishlist gmz-box-popup" data-effect="mfp-zoom-in"><i
                                    class="fal fa-heart"></i></a>
                        </div>

                        <a href="hotel/emerald-dreams-boutique-hotel.html">
                            <img src="storage/2024/02/28/emarald-michamvi-10-1709067414-360x240.jpg"
                                alt="Emerald Dreams Boutique Hotel"
                                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=360&amp;h=240&amp;fit=crop&amp;crop=center';" />
                        </a>
                        <a class="hotel-item__type" href="hotel-searcha761.html?property_type=67">
                            Mid range Hotels
                        </a>
                    </div>
                    <div class="hotel-item__details">
                        <span class="hotel-item__label">Featured</span>
                        <div class="hotel-item__rating">
                            <div class="star-rating">
                                <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                            </div>
                        </div>
                        <h3 class="hotel-item__title">
                            <a href="hotel/emerald-dreams-boutique-hotel.html">Emerald Dreams Boutique Hotel</a>
                        </h3>
                        <p class="hotel-item__location">
                            <i class="fas fa-map-marker-alt mr-2"></i>Emerald Dreams
                            Boutique Hotel
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="hotel-item__price">
                                <span class="">USD 150.00</span>
                                <span class="_unit">night</span>
                            </div>
                            <a class="hotel-item__view-detail" href="hotel/emerald-dreams-boutique-hotel.html">View
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="hotel-item hotel-item--grid" data-plugin="matchHeight">
                    <div class="hotel-item__thumbnail">
                        <div class="add-wishlist-wrapper">
                            <a href="#gmz-login-popup" class="add-wishlist gmz-box-popup" data-effect="mfp-zoom-in"><i
                                    class="fal fa-heart"></i></a>
                        </div>

                        <a href="hotel/opera-hotel-1.html">
                            <img src="storage/2024/01/19/opera-hotel-31-1705659734-360x240.jpg" alt="Opera Hotel"
                                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=360&amp;h=240&amp;fit=crop&amp;crop=center';" />
                        </a>
                        <a class="hotel-item__type" href="hotel-search86a4.html?property_type=66">
                            Luxury Beach Resorts
                        </a>
                    </div>
                    <div class="hotel-item__details">
                        <span class="hotel-item__label">Featured</span>
                        <div class="hotel-item__rating">
                            <div class="star-rating">
                                <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
                                    class="fa fa-star"></i><i class="fa fa-star"></i>
                            </div>
                        </div>
                        <h3 class="hotel-item__title">
                            <a href="hotel/opera-hotel-1.html">Opera Hotel</a>
                        </h3>
                        <p class="hotel-item__location">
                            <i class="fas fa-map-marker-alt mr-2"></i>Opera Hotel
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="hotel-item__price">
                                <span class="">USD 100.00</span>
                                <span class="_unit">night</span>
                            </div>
                            <a class="hotel-item__view-detail" href="hotel/opera-hotel-1.html">View Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="hotel-item hotel-item--grid" data-plugin="matchHeight">
                    <div class="hotel-item__thumbnail">
                        <div class="add-wishlist-wrapper">
                            <a href="#gmz-login-popup" class="add-wishlist gmz-box-popup" data-effect="mfp-zoom-in"><i
                                    class="fal fa-heart"></i></a>
                        </div>

                        <a href="hotel/maisha-nungwi.html">
                            <img src="storage/2024/01/11/maisha-nungwi-22-1704970316-360x240.jpg" alt="Maisha Nungwi"
                                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=360&amp;h=240&amp;fit=crop&amp;crop=center';" />
                        </a>
                        <a class="hotel-item__type" href="hotel-search9234.html?property_type=68">
                            Budget Hotels
                        </a>
                    </div>
                    <div class="hotel-item__details">
                        <span class="hotel-item__label">Featured</span>
                        <div class="hotel-item__rating">
                            <div class="star-rating">
                                <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                            </div>
                        </div>
                        <h3 class="hotel-item__title">
                            <a href="hotel/maisha-nungwi.html">Maisha Nungwi</a>
                        </h3>
                        <p class="hotel-item__location">
                            <i class="fas fa-map-marker-alt mr-2"></i>Maisha Nungwi
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="hotel-item__price">
                                <span class="">USD 100.00</span>
                                <span class="_unit">night</span>
                            </div>
                            <a class="hotel-item__view-detail" href="hotel/maisha-nungwi.html">View Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
</section>
<section class="tour-type">
    <div class="container py-40">
        <h2 class="section-title mb-20">Tour Types</h2>
        <div class="row">
            <div class="col-lg-2 col-md-4 col-6">
                <div class="tour-type__item" data-plugin="matchHeight">
                    <div class="tour-type__thumbnail">
                        <a href="tour-searchde47.html?tour_type=153">
                            <img class="_image-tour" src="storage/2023/04/10/sandbank-picnic-1681082906-250x300.jpg"
                                alt="Adventure tours" />
                        </a>
                    </div>
                    <div class="tour-type__info">
                        <h3 class="tour-type__name">
                            <a href="tour-searchde47.html?tour_type=153">Adventure tours</a>
                        </h3>
                        <div class="tour-type__description">Click here</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="tour-type__item" data-plugin="matchHeight">
                    <div class="tour-type__thumbnail">
                        <a href="tour-searchfa11.html?tour_type=120">
                            <img class="_image-tour"
                                src="storage/2023/04/10/stonetownslavemarket-1681080714-250x300.jpg"
                                alt="Historical tours" />
                        </a>
                    </div>
                    <div class="tour-type__info">
                        <h3 class="tour-type__name">
                            <a href="tour-searchfa11.html?tour_type=120">Historical tours</a>
                        </h3>
                        <div class="tour-type__description">Click here</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="tour-type__item" data-plugin="matchHeight">
                    <div class="tour-type__thumbnail">
                        <a href="tour-searchea36.html?tour_type=119">
                            <img class="_image-tour"
                                src="storage/2023/04/10/zanzibar-sea-weeds-womens-1681081575-250x300.jpg"
                                alt="Cultural tours" />
                        </a>
                    </div>
                    <div class="tour-type__info">
                        <h3 class="tour-type__name">
                            <a href="tour-searchea36.html?tour_type=119">Cultural tours</a>
                        </h3>
                        <div class="tour-type__description">Click here</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="tour-type__item" data-plugin="matchHeight">
                    <div class="tour-type__thumbnail">
                        <a href="tour-search2ff7.html?tour_type=117">
                            <img class="_image-tour" src="storage/2023/04/10/nature-monkeys-1681081799-250x300.jpg"
                                alt="Nature tours" />
                        </a>
                    </div>
                    <div class="tour-type__info">
                        <h3 class="tour-type__name">
                            <a href="tour-search2ff7.html?tour_type=117">Nature tours</a>
                        </h3>
                        <div class="tour-type__description">Click here</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="tour-type__item" data-plugin="matchHeight">
                    <div class="tour-type__thumbnail">
                        <a href="tour-search1079.html?tour_type=116">
                            <img class="_image-tour"
                                src="storage/2023/04/15/jambotrips-africa-tours-1681511410-250x300.jpg"
                                alt="Zanzibar Packages" />
                        </a>
                    </div>
                    <div class="tour-type__info">
                        <h3 class="tour-type__name">
                            <a href="tour-search1079.html?tour_type=116">Zanzibar Packages</a>
                        </h3>
                        <div class="tour-type__description">Click here</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="tour-type__item" data-plugin="matchHeight">
                    <div class="tour-type__thumbnail">
                        <a href="tour-search81b9.html?tour_type=115">
                            <img class="_image-tour"
                                src="storage/2023/07/24/tarangire-safari-tour-1690133266-250x300.jpg"
                                alt="Wildlife Safaris" />
                        </a>
                    </div>
                    <div class="tour-type__info">
                        <h3 class="tour-type__name">
                            <a href="tour-search81b9.html?tour_type=115">Wildlife Safaris</a>
                        </h3>
                        <div class="tour-type__description">Click here</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="list-tour list-tour--grid py-40 bg-gray-100">
    <div class="container">
        <h2 class="section-title mb-20">List Of Tours</h2>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="tour-item tour-item--grid" data-plugin="matchHeight">
                    <div class="tour-item__thumbnail">
                        <div class="add-wishlist-wrapper">
                            <a href="#gmz-login-popup" class="add-wishlist gmz-box-popup" data-effect="mfp-zoom-in"><i
                                    class="fal fa-heart"></i></a>
                        </div>
                        <a href="tour/zanzibar-historical-experience.html">
                            <img src="storage/2025/08/22/mbweni-ruins-6-1687402542-2-1755874154-360x240.jpg"
                                alt="Zanzibar Slave Route &amp; Heritage Tour"
                                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1523906834658-6e24ef2386f9?w=360&amp;h=240&amp;fit=crop&amp;crop=center';" />
                        </a>
                        <a class="tour-item__type" href="tour-searchfa11.html?tour_type=120">
                            Historical tours
                        </a>
                    </div>
                    <div class="tour-item__details">
                        <span class="tour-item__label">Featured</span>
                        <h3 class="tour-item__title">
                            <a href="tour/zanzibar-historical-experience.html">Zanzibar Slave Route &amp;
                                Heritage Tour</a>
                        </h3>

                        <div class="tour-item__meta">
                            <div class="i-meta">
                                <div class="i-meta__icon">
                                    <i class="fal fa-calendar-alt"></i>
                                </div>
                                <div class="i-meta__figure">10</div>
                            </div>
                            <div class="i-meta">
                                <div class="i-meta__icon">
                                    <i class="fal fa-users"></i>
                                </div>
                                <div class="i-meta__figure">25 people</div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="tour-item__price">
                                <span class="_retail">USD 120.00</span>
                                <span class="_unit">person</span>
                            </div>
                            <a class="btn btn-primary btn-sm tour-item__view-detail"
                                href="tour/zanzibar-historical-experience.html">View Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="tour-item tour-item--grid" data-plugin="matchHeight">
                    <div class="tour-item__thumbnail">
                        <div class="add-wishlist-wrapper">
                            <a href="#gmz-login-popup" class="add-wishlist gmz-box-popup" data-effect="mfp-zoom-in"><i
                                    class="fal fa-heart"></i></a>
                        </div>
                        <a href="tour/zanzibar-cultural-tour.html">
                            <img src="storage/2025/08/19/local-cooking-class-2-1683768360-1755607526-360x240.jpg"
                                alt="Jozani Forest &amp; Cultural Cooking Class– A Journey into Nature and Flavor"
                                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1523906834658-6e24ef2386f9?w=360&amp;h=240&amp;fit=crop&amp;crop=center';" />
                        </a>
                        <a class="tour-item__type" href="tour-searchea36.html?tour_type=119">
                            Cultural tours
                        </a>
                    </div>
                    <div class="tour-item__details">
                        <span class="tour-item__label">Featured</span>
                        <h3 class="tour-item__title">
                            <a href="tour/zanzibar-cultural-tour.html">Jozani Forest &amp; Cultural Cooking
                                Class– A Journey
                                into Nature and Flavor</a>
                        </h3>

                        <div class="tour-item__meta">
                            <div class="i-meta">
                                <div class="i-meta__icon">
                                    <i class="fal fa-calendar-alt"></i>
                                </div>
                                <div class="i-meta__figure">6</div>
                            </div>
                            <div class="i-meta">
                                <div class="i-meta__icon">
                                    <i class="fal fa-users"></i>
                                </div>
                                <div class="i-meta__figure">25 people</div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="tour-item__price">
                                <span class="_retail">USD 120.00</span>
                                <span class="_unit">person</span>
                            </div>
                            <a class="btn btn-primary btn-sm tour-item__view-detail"
                                href="tour/zanzibar-cultural-tour.html">View Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="tour-item tour-item--grid" data-plugin="matchHeight">
                    <div class="tour-item__thumbnail">
                        <div class="add-wishlist-wrapper">
                            <a href="#gmz-login-popup" class="add-wishlist gmz-box-popup" data-effect="mfp-zoom-in"><i
                                    class="fal fa-heart"></i></a>
                        </div>
                        <a href="tour/zanzibar-easter-packages.html">
                            <img src="storage/2025/04/04/zenj-topattractions-1743700222-360x240.jpg"
                                alt="3-Day Zanzibar Package"
                                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1523906834658-6e24ef2386f9?w=360&amp;h=240&amp;fit=crop&amp;crop=center';" />
                        </a>
                        <a class="tour-item__type" href="tour-search1079.html?tour_type=116">
                            Zanzibar Packages
                        </a>
                    </div>
                    <div class="tour-item__details">
                        <span class="tour-item__label">Featured</span>
                        <h3 class="tour-item__title">
                            <a href="tour/zanzibar-easter-packages.html">3-Day Zanzibar Package</a>
                        </h3>

                        <div class="tour-item__meta">
                            <div class="i-meta">
                                <div class="i-meta__icon">
                                    <i class="fal fa-calendar-alt"></i>
                                </div>
                                <div class="i-meta__figure">72</div>
                            </div>
                            <div class="i-meta">
                                <div class="i-meta__icon">
                                    <i class="fal fa-users"></i>
                                </div>
                                <div class="i-meta__figure">15 people</div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="tour-item__price">
                                <span class="_retail">USD 490.00</span>
                                <span class="_unit">person</span>
                            </div>
                            <a class="btn btn-primary btn-sm tour-item__view-detail"
                                href="tour/zanzibar-easter-packages.html">View Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>
<section class="car-type">
    <div class="container py-40">
        <h2 class="section-title mb-20">Car Types</h2>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="car-type__item" data-plugin="matchHeight">
                    <div class="car-type__left">
                        <h3 class="car-type__name">
                            <a href="car-searche38c.html?car_type=155">Sedan</a>
                        </h3>
                        <div class="car-type__description">5 Adults, 3 Bags</div>
                        <a href="car-searche38c.html?car_type=155" class="btn btn-primary car-type__detail">View
                            Detail</a>
                    </div>
                    <div class="car-type__right">
                        <a href="car-searche38c.html?car_type=155">
                            <img class="_image-car" src="storage/2023/05/04/toyata-ist-zanzibar-1683154503-300x200.jpg"
                                alt="Sedan" />
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="car-type__item" data-plugin="matchHeight">
                    <div class="car-type__left">
                        <h3 class="car-type__name">
                            <a href="car-search053c.html?car_type=13">Passenger Van</a>
                        </h3>
                        <div class="car-type__description">5 Adults, 5 Bags</div>
                        <a href="car-search053c.html?car_type=13" class="btn btn-primary car-type__detail">View
                            Detail</a>
                    </div>
                    <div class="car-type__right">
                        <a href="car-search053c.html?car_type=13">
                            <img class="_image-car" src="storage/2023/04/10/alphardtoyota-1681083559-300x200.jpg"
                                alt="Passenger Van" />
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="car-type__item" data-plugin="matchHeight">
                    <div class="car-type__left">
                        <h3 class="car-type__name">
                            <a href="car-search6807.html?car_type=12">Intermediate</a>
                        </h3>
                        <div class="car-type__description">5 Adults, 3 Bags</div>
                        <a href="car-search6807.html?car_type=12" class="btn btn-primary car-type__detail">View
                            Detail</a>
                    </div>
                    <div class="car-type__right">
                        <a href="car-search6807.html?car_type=12">
                            <img class="_image-car"
                                src="storage/2023/04/10/toyota-rav-4-zanzibar-1681085523-300x200.jpg"
                                alt="Intermediate" />
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="car-type__item" data-plugin="matchHeight">
                    <div class="car-type__left">
                        <h3 class="car-type__name">
                            <a href="car-searchee18.html?car_type=10">Luxury</a>
                        </h3>
                        <div class="car-type__description">5 Adults, 5 Bags</div>
                        <a href="car-searchee18.html?car_type=10" class="btn btn-primary car-type__detail">View
                            Detail</a>
                    </div>
                    <div class="car-type__right">
                        <a href="car-searchee18.html?car_type=10">
                            <img class="_image-car"
                                src="storage/2023/04/10/luxury-prado-zanzibar-1681085705-300x200.png" alt="Luxury" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="list-car list-car--grid py-40 bg-gray-100">
    <div class="container">
        <h2 class="section-title mb-20">List Of Cars</h2>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="car-item car-item--grid" data-plugin="matchHeight">
                    <div class="car-item__thumbnail">
                        <div class="add-wishlist-wrapper">
                            <a href="#gmz-login-popup" class="add-wishlist gmz-box-popup" data-effect="mfp-zoom-in"><i
                                    class="fal fa-heart"></i></a>
                        </div>
                        <a href="car/zanzibar-bookings-car-rental-now.html">
                            <img src="storage/2023/05/29/landcruiser-prado-car-hire-zanzibar-7-1685320444-360x240.jpg"
                                alt="Landcruiser Prado"
                                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?w=360&amp;h=240&amp;fit=crop&amp;crop=center';" />
                        </a>
                        <a class="car-item__type" href="car-searchee18.html?car_type=10">
                            Luxury
                        </a>
                    </div>
                    <div class="car-item__details">
                        <span class="car-item__label">Featured</span>
                        <h3 class="car-item__title">
                            <a href="car/zanzibar-bookings-car-rental-now.html">Landcruiser Prado</a>
                        </h3>
                        <div class="car-item__meta">
                            <div class="i-meta" data-toggle="tooltip" title="Passenger">
                                <span class="i-meta__icon"><i class="gmz-icon"><svg width="24px" height="24px"
                                            viewBox="0 0 20 20" version="1.1">
                                            <desc>Created with Sketch.</desc>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <g transform="translate(-155.000000, -2016.000000)" stroke="#1A2B50"
                                                    stroke-width="0.5">
                                                    <g transform="translate(135.000000, 1603.000000)">
                                                        <g transform="translate(0.000000, 86.000000)">
                                                            <g>
                                                                <g transform="translate(20.000000, 327.000000)">
                                                                    <g>
                                                                        <circle cx="10" cy="3.125" r="2.5">
                                                                        </circle>
                                                                        <path
                                                                            d="M13.125,11.875 L13.125,10 C13.125,8.27411016 11.7258898,6.875 10,6.875 C8.27411016,6.875 6.875,8.27411016 6.875,10 L6.875,11.875 L8.125,11.875 L8.75,16.875 L11.25,16.875 L11.875,11.875 L13.125,11.875 Z">
                                                                        </path>
                                                                        <circle cx="3.75" cy="5" r="1.875">
                                                                        </circle>
                                                                        <path
                                                                            d="M4.75,8.28916667 C3.79624791,7.9682092 2.74632008,8.12578507 1.92880562,8.71257878 C1.11129116,9.29937249 0.626070499,10.2436921 0.625,11.25 L0.625,11.875 L1.875,11.875 L2.5,15.625 L5,15.625 L5.31333333,13.745">
                                                                        </path>
                                                                        <circle cx="16.25" cy="5" r="1.875">
                                                                        </circle>
                                                                        <path
                                                                            d="M15.25,8.28916667 C16.2037521,7.9682092 17.2536799,8.12578507 18.0711944,8.71257878 C18.8887088,9.29937249 19.3739295,10.2436921 19.375,11.25 L19.375,11.875 L18.125,11.875 L17.5,15.625 L15,15.625 L14.6866667,13.745">
                                                                        </path>
                                                                        <path
                                                                            d="M19.375,17.5 C19.375,18.5358333 15.1775,19.375 10,19.375 C4.8225,19.375 0.625,18.5358333 0.625,17.5">
                                                                        </path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg></i></span>
                                <span class="i-meta__figure">7</span>
                            </div>
                            <div class="i-meta" data-toggle="tooltip" title="Doors">
                                <span class="i-meta__icon"><i class="gmz-icon"><svg width="24px" height="24px"
                                            viewBox="0 0 20 22" version="1.1">
                                            <desc>Created with Sketch.</desc>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-309.000000, -2015.000000)" fill="#1A2B50"
                                                    fill-rule="nonzero" stroke="#FFFFFF">
                                                    <g transform="translate(135.000000, 1603.000000)">
                                                        <g transform="translate(0.000000, 86.000000)">
                                                            <g>
                                                                <g transform="translate(125.000000, 326.000000)">
                                                                    <g transform="translate(6.000000, 0.042132)">
                                                                        <g>
                                                                            <g
                                                                                transform="translate(44.000000, 0.957868)">
                                                                                <path
                                                                                    d="M14.4049515,11.1208738 L14.4049515,8.15165049 L17.2397087,10.9865049 C17.3819417,11.1287379 17.5682524,11.1998058 17.7546602,11.1998058 C17.9409709,11.1998058 18.1273786,11.1287379 18.2696117,10.9865049 C18.5539806,10.7021359 18.5539806,10.241068 18.2696117,9.95679612 L14.4049515,6.09203883 L14.4049515,5.65330097 L14.4049515,2.16990291 C14.4049515,0.973398058 13.3596117,0 12.0748544,0 L6.49135922,0 C5.20660194,0 4.16126214,0.973398058 4.16126214,2.16990291 L4.16126214,5.65330097 L4.16126214,6.09203883 L0.296601942,9.95679612 C0.0122330097,10.241165 0.0122330097,10.702233 0.296601942,10.9865049 C0.438834951,11.1286408 0.625242718,11.1998058 0.811553398,11.1998058 C0.997864078,11.1998058 1.18427184,11.1287379 1.32650485,10.9865049 L4.16126214,8.15165049 L4.16126214,11.1209709 L0.296601942,14.9858252 C0.0122330097,15.2701942 0.0122330097,15.7312621 0.296699029,16.0156311 C0.438834951,16.1578641 0.625242718,16.228932 0.811553398,16.228932 C0.997864078,16.228932 1.18427184,16.1578641 1.32650485,16.0156311 L4.16126214,13.1806796 L4.16126214,15.6059223 L4.16126214,17.7557282 C4.16126214,18.952233 5.20660194,19.9256311 6.49135922,19.9256311 L12.0748544,19.9256311 C13.3596117,19.9256311 14.4049515,18.952233 14.4049515,17.7557282 L14.4049515,15.6058252 L14.4049515,13.1805825 L17.2397087,16.015534 C17.3819417,16.157767 17.5682524,16.228835 17.7546602,16.228835 C17.9409709,16.228835 18.1273786,16.157767 18.2695146,16.015534 C18.5539806,15.731165 18.5539806,15.2701942 18.2696117,14.9857282 L14.4049515,11.1208738 Z M12.9486408,11.4228155 L12.9486408,14.8776699 L5.61757282,14.8776699 L5.61757282,11.4228155 C5.61757282,11.4226214 5.61757282,11.4225243 5.61757282,11.4223301 L5.61757282,6.39417476 C5.61757282,6.3938835 5.61757282,6.39349515 5.61757282,6.39320388 L5.61757282,6.38145631 L12.9486408,6.38145631 L12.9486408,6.39320388 C12.9486408,6.39349515 12.9486408,6.3938835 12.9486408,6.39417476 L12.9486408,11.4223301 C12.9486408,11.4224272 12.9486408,11.4226214 12.9486408,11.4228155 Z M6.49135922,1.45631068 L12.0748544,1.45631068 C12.5485437,1.45631068 12.9486408,1.7831068 12.9486408,2.16990291 L12.9486408,4.92514563 L5.61757282,4.92514563 L5.61757282,2.16990291 C5.61757282,1.7831068 6.0176699,1.45631068 6.49135922,1.45631068 Z M12.0748544,18.4691262 L6.49135922,18.4691262 C6.0176699,18.4691262 5.61757282,18.1423301 5.61757282,17.755534 L5.61757282,16.3339806 L12.9486408,16.3339806 L12.9486408,17.7556311 C12.9486408,18.1424272 12.5485437,18.4691262 12.0748544,18.4691262 Z">
                                                                                </path>
                                                                            </g>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg></i></span>
                                <span class="i-meta__figure">5</span>
                            </div>
                            <div class="i-meta" data-toggle="tooltip" title="Baggage">
                                <span class="i-meta__icon"><i class="gmz-icon"><svg width="24px" height="24px"
                                            viewBox="0 0 21 22" version="1.1">
                                            <desc>Created with Sketch.</desc>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-259.000000, -2015.000000)" fill="#1A2B50"
                                                    fill-rule="nonzero" stroke="#FFFFFF" stroke-width="0.2">
                                                    <g transform="translate(135.000000, 1603.000000)">
                                                        <g transform="translate(0.000000, 86.000000)">
                                                            <g>
                                                                <g transform="translate(125.000000, 326.000000)">
                                                                    <g transform="translate(0.000000, 1.042132)">
                                                                        <path
                                                                            d="M19.4092827,14.3460535 L17.8059072,14.3460535 C18.1347914,13.9961395 18.3161389,13.5329174 18.3122363,13.0527202 L18.3122363,4.57107461 C18.3122363,3.503986 17.4504219,2.61609571 16.3833333,2.61609571 L12.9535865,2.61609571 L12.9535865,1.22748811 C12.9537382,0.899854993 12.8228435,0.585770667 12.5900649,0.355211302 C12.3572864,0.124651936 12.0419626,-0.00322816269 11.714346,6.19501863e-05 L8.03248945,6.19501863e-05 C7.70487282,-0.00322816269 7.38954907,0.124651936 7.1567705,0.355211302 C6.92399193,0.585770667 6.79309722,0.899854993 6.79324895,1.22748811 L6.79324895,2.61609571 L3.36350211,2.61609571 C2.2964135,2.61609571 1.43459916,3.503986 1.43459916,4.57107461 L1.43459916,13.0527202 C1.43069655,13.5329174 1.61204409,13.9961395 1.94092827,14.3460535 L0.337552743,14.3460535 C0.151139241,14.3460535 0,14.5142392 0,14.7004839 L0,16.7679944 C0,16.9544501 0.151139241,17.0886695 0.337552743,17.0886695 L2.03135021,17.0886695 C1.53379016,17.5396946 1.36504995,18.2503908 1.60676195,18.8769408 C1.84847395,19.5034908 2.45080526,19.9167212 3.12236287,19.9167212 C3.79392048,19.9167212 4.39625179,19.5034908 4.63796379,18.8769408 C4.87967579,18.2503908 4.71093558,17.5396946 4.21337553,17.0886695 L15.5334599,17.0886695 C15.0358999,17.5396946 14.8671597,18.2503908 15.1088717,18.8769408 C15.3505837,19.5034908 15.952915,19.9167212 16.6244726,19.9167212 C17.2960302,19.9167212 17.8983615,19.5034908 18.1400735,18.8769408 C18.3817855,18.2503908 18.2130453,17.5396946 17.7154852,17.0886695 L19.4092827,17.0886695 C19.5956962,17.0886695 19.7468354,16.9546189 19.7468354,16.7679944 L19.7468354,14.7004839 C19.7468354,14.5142392 19.5956962,14.3460535 19.4092827,14.3460535 Z M3.12236287,19.2574459 C2.5980419,19.2574459 2.17299578,18.8323998 2.17299578,18.3080788 C2.17299578,17.7837579 2.5980419,17.3587117 3.12236287,17.3587117 C3.64668383,17.3587117 4.07172996,17.7837579 4.07172996,18.3080788 C4.07112541,18.8321492 3.64643322,19.2568414 3.12236287,19.2574459 L3.12236287,19.2574459 Z M16.6244726,19.2574459 C16.1001516,19.2574459 15.6751055,18.8323998 15.6751055,18.3080788 C15.6751055,17.7837579 16.1001516,17.3587117 16.6244726,17.3587117 C17.1487935,17.3587117 17.5738397,17.7837579 17.5738397,18.3080788 C17.5732351,18.8321492 17.1485429,19.2568414 16.6244726,19.2574459 Z M14.8523207,3.29120119 L14.8523207,14.3038594 L4.89451477,14.3038594 L4.89451477,3.29120119 L14.8523207,3.29120119 Z M17.6371308,4.57107461 L17.6371308,13.0527202 C17.6378305,13.3852181 17.5058736,13.7042605 17.2705122,13.939123 C17.0351508,14.1739854 16.715829,14.3052648 16.3833333,14.3038594 L15.5274262,14.3038706 L15.5274262,3.29120119 L16.3833333,3.29120119 C17.0781857,3.29120119 17.6371308,3.87622229 17.6371308,4.57107461 Z M7.46835443,1.22748811 C7.46800611,1.07883593 7.52771647,0.936345366 7.63393609,0.832350243 C7.7401557,0.72835512 7.88387793,0.67167347 8.03248945,0.675167435 L11.714346,0.675167435 C11.8629575,0.67167347 12.0066797,0.72835512 12.1128994,0.832350243 C12.219119,0.936345366 12.2788293,1.07883593 12.278481,1.22748811 L12.2784825,2.61609571 L7.46835291,2.61609571 L7.46835443,1.22748811 Z M2.10970464,4.57107461 C2.10970464,3.87622229 2.66864979,3.29120119 3.36350211,3.29120119 L4.21940928,3.29120119 L4.21940928,14.3038706 L3.36350211,14.3038594 C3.03100643,14.3052648 2.71168464,14.1739854 2.47632322,13.939123 C2.2409618,13.7042605 2.10900499,13.3852181 2.10970464,13.0527202 L2.10970464,4.57107461 Z M19.07173,16.4135641 L0.675105485,16.4135641 L0.675105485,15.021159 L19.07173,15.021159 L19.07173,16.4135641 Z">
                                                                        </path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg></i></span>
                                <span class="i-meta__figure">8</span>
                            </div>
                            <div class="i-meta" data-toggle="tooltip" title="Gear Shift">
                                <span class="i-meta__icon"><i class="gmz-icon"><svg width="24px" height="24px"
                                            viewBox="0 0 23 20" version="1.1">
                                            <desc>Created with Sketch.</desc>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-206.000000, -2016.000000)" fill="#1A2B50"
                                                    fill-rule="nonzero" stroke="#FFFFFF" stroke-width="0.1">
                                                    <g transform="translate(135.000000, 1603.000000)">
                                                        <g transform="translate(0.000000, 86.000000)">
                                                            <g>
                                                                <g transform="translate(70.000000, 327.000000)">
                                                                    <g transform="translate(1.000000, 0.000000)">
                                                                        <path
                                                                            d="M19.7745569,0.0513671801 L19.7745392,0.0514139099 C18.3074667,0.0512294327 17.0910913,1.18771017 16.9917569,2.65141594 C16.8924225,4.11512172 17.9440975,5.40552369 19.4409941,5.65309615 L19.3909941,9.66473167 L11.6307137,9.61473167 L11.6741302,5.60479315 C13.1303141,5.41137358 14.1868843,4.12166512 14.0899634,2.65589263 C13.9930424,1.19012015 12.7758553,0.0507588812 11.306882,0.0507588812 C9.83790863,0.0507588812 8.62072156,1.19012015 8.52380058,2.65589263 C8.4268796,4.12166512 9.4834498,5.41137358 10.9830502,5.65435784 L10.9330502,9.66473167 L3.17276982,9.61473167 L3.216023,5.60355344 C4.67176554,5.40530795 5.72422287,4.11193704 5.6224451,2.64628739 C5.52066733,1.18063775 4.2995449,0.0451489602 2.83037373,0.0500155897 C1.36120257,0.0548822192 0.147629469,1.19843593 0.055563751,2.66472769 C-0.0365019666,4.13101944 1.02450076,5.41738951 2.52510626,5.65557279 L2.48152666,14.3711402 C1.02497044,14.5597349 -0.0356560051,15.845726 0.056409469,17.3115528 C0.148474943,18.7773795 1.3616723,19.9205566 2.83037933,19.9254217 C4.29908636,19.9302868 5.51983056,18.7951721 5.62160501,17.3299875 C5.72337946,15.8648028 4.67129589,14.5718133 3.17276982,14.3240307 L3.22276982,10.3123952 L10.9830502,10.3623952 L10.9396337,14.3723337 C9.4834498,14.5657533 8.4268796,15.8554618 8.52380058,17.3212343 C8.62072156,18.7870067 9.83790863,19.926368 11.306882,19.926368 C12.7758553,19.926368 13.9930424,18.7870067 14.0899634,17.3212343 C14.1868843,15.8554618 13.1303141,14.5657533 11.6307137,14.3227691 L11.6807137,10.3123952 L19.7648259,10.3123952 C19.9436732,10.3123952 20.0886577,10.1674108 20.0886577,9.98856344 L20.1322559,5.60598431 C21.5910187,5.41766076 22.6527359,4.12876319 22.5582126,2.66093483 C22.4636894,1.19310648 21.2454256,0.0510321461 19.7745569,0.0513671801 Z M9.16538667,2.84062966 L9.16538664,2.84057279 C9.16538664,1.65785758 10.1241668,0.699077462 11.306882,0.699077462 C12.4895972,0.699077462 13.4483773,1.65785758 13.4483773,2.84057279 C13.4483773,4.023288 12.4895972,4.98206812 11.3068251,4.98206808 C10.1246987,4.98072339 9.16673136,4.02275601 9.16538667,2.84062966 Z M0.697723119,2.84062966 L0.697723087,2.84057279 C0.697723087,1.65785758 1.6565032,0.699077462 2.83921841,0.699077462 C4.02193362,0.699077462 4.98071374,1.65785758 4.98071374,2.84057279 C4.98071374,4.023288 4.02193362,4.98206812 2.83916154,4.98206808 C1.65703519,4.98072339 0.699067809,4.02275601 0.697723119,2.84062966 Z M4.98066698,17.1364972 L4.98066701,17.1365541 C4.98066701,18.0027125 4.45890325,18.7835824 3.65867542,19.1150424 C2.85844758,19.4465024 1.93734851,19.2632763 1.3248889,18.650803 C0.712429292,18.0383298 0.529223852,17.1172266 0.860701716,16.3170062 C1.19217958,15.5167857 1.97306117,14.9950394 2.83927641,14.9950588 C4.02138407,14.9964299 4.97932234,15.9543895 4.98066698,17.1364972 Z M13.4483773,17.1364972 L13.4483773,17.1365541 C13.4483773,18.3192693 12.4895972,19.2780494 11.306882,19.2780494 C10.1241668,19.2780494 9.16538664,18.3192693 9.16538664,17.1365541 C9.16538664,15.9538389 10.1241668,14.9950588 11.3069388,14.9950588 C12.4890652,14.9964035 13.4470326,15.9543709 13.4483773,17.1364972 Z M19.7746002,4.98206809 L19.7745444,4.98206812 C18.5918354,4.98204169 17.6330793,4.02324987 17.6330969,2.84054089 C17.6331145,1.65783191 18.5918992,0.699068654 19.7746082,0.699077462 C20.9573172,0.69908627 21.9160876,1.65786381 21.9160875,2.84062855 C21.9147691,4.02278508 20.9567567,4.98077607 19.7746002,4.98206809 Z">
                                                                        </path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg></i></span>
                                <span class="i-meta__figure">Auto</span>
                            </div>
                        </div>
                        <p class="car-item__location">
                            <i class="fas fa-map-marker-alt mr-2"></i>Zanzibar. Town
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="car-item__price">
                                <span class="_retail">USD 135.00</span><span class="_unit">per day</span>
                            </div>
                            <a class="btn btn-primary car-item__view-detail"
                                href="car/zanzibar-bookings-car-rental-now.html">View Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="car-item car-item--grid" data-plugin="matchHeight">
                    <div class="car-item__thumbnail">
                        <div class="add-wishlist-wrapper">
                            <a href="#gmz-login-popup" class="add-wishlist gmz-box-popup" data-effect="mfp-zoom-in"><i
                                    class="fal fa-heart"></i></a>
                        </div>
                        <a href="car/rent-a-car-suzuki-escudo.html">
                            <img src="storage/2023/05/29/suzuki-escudo-car-hire-zanzibar-7-1685321566-360x240.jpg"
                                alt="Suzuki Escudo 3 &amp; 5Doors"
                                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?w=360&amp;h=240&amp;fit=crop&amp;crop=center';" />
                        </a>
                        <a class="car-item__type" href="car-search6807.html?car_type=12">
                            Intermediate
                        </a>
                    </div>
                    <div class="car-item__details">
                        <span class="car-item__label">Featured</span>
                        <h3 class="car-item__title">
                            <a href="car/rent-a-car-suzuki-escudo.html">Suzuki Escudo 3 &amp; 5Doors</a>
                        </h3>
                        <div class="car-item__meta">
                            <div class="i-meta" data-toggle="tooltip" title="Passenger">
                                <span class="i-meta__icon"><i class="gmz-icon"><svg width="24px" height="24px"
                                            viewBox="0 0 20 20" version="1.1">
                                            <desc>Created with Sketch.</desc>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <g transform="translate(-155.000000, -2016.000000)" stroke="#1A2B50"
                                                    stroke-width="0.5">
                                                    <g transform="translate(135.000000, 1603.000000)">
                                                        <g transform="translate(0.000000, 86.000000)">
                                                            <g>
                                                                <g transform="translate(20.000000, 327.000000)">
                                                                    <g>
                                                                        <circle cx="10" cy="3.125" r="2.5">
                                                                        </circle>
                                                                        <path
                                                                            d="M13.125,11.875 L13.125,10 C13.125,8.27411016 11.7258898,6.875 10,6.875 C8.27411016,6.875 6.875,8.27411016 6.875,10 L6.875,11.875 L8.125,11.875 L8.75,16.875 L11.25,16.875 L11.875,11.875 L13.125,11.875 Z">
                                                                        </path>
                                                                        <circle cx="3.75" cy="5" r="1.875">
                                                                        </circle>
                                                                        <path
                                                                            d="M4.75,8.28916667 C3.79624791,7.9682092 2.74632008,8.12578507 1.92880562,8.71257878 C1.11129116,9.29937249 0.626070499,10.2436921 0.625,11.25 L0.625,11.875 L1.875,11.875 L2.5,15.625 L5,15.625 L5.31333333,13.745">
                                                                        </path>
                                                                        <circle cx="16.25" cy="5" r="1.875">
                                                                        </circle>
                                                                        <path
                                                                            d="M15.25,8.28916667 C16.2037521,7.9682092 17.2536799,8.12578507 18.0711944,8.71257878 C18.8887088,9.29937249 19.3739295,10.2436921 19.375,11.25 L19.375,11.875 L18.125,11.875 L17.5,15.625 L15,15.625 L14.6866667,13.745">
                                                                        </path>
                                                                        <path
                                                                            d="M19.375,17.5 C19.375,18.5358333 15.1775,19.375 10,19.375 C4.8225,19.375 0.625,18.5358333 0.625,17.5">
                                                                        </path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg></i></span>
                                <span class="i-meta__figure">5</span>
                            </div>
                            <div class="i-meta" data-toggle="tooltip" title="Doors">
                                <span class="i-meta__icon"><i class="gmz-icon"><svg width="24px" height="24px"
                                            viewBox="0 0 20 22" version="1.1">
                                            <desc>Created with Sketch.</desc>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-309.000000, -2015.000000)" fill="#1A2B50"
                                                    fill-rule="nonzero" stroke="#FFFFFF">
                                                    <g transform="translate(135.000000, 1603.000000)">
                                                        <g transform="translate(0.000000, 86.000000)">
                                                            <g>
                                                                <g transform="translate(125.000000, 326.000000)">
                                                                    <g transform="translate(6.000000, 0.042132)">
                                                                        <g>
                                                                            <g
                                                                                transform="translate(44.000000, 0.957868)">
                                                                                <path
                                                                                    d="M14.4049515,11.1208738 L14.4049515,8.15165049 L17.2397087,10.9865049 C17.3819417,11.1287379 17.5682524,11.1998058 17.7546602,11.1998058 C17.9409709,11.1998058 18.1273786,11.1287379 18.2696117,10.9865049 C18.5539806,10.7021359 18.5539806,10.241068 18.2696117,9.95679612 L14.4049515,6.09203883 L14.4049515,5.65330097 L14.4049515,2.16990291 C14.4049515,0.973398058 13.3596117,0 12.0748544,0 L6.49135922,0 C5.20660194,0 4.16126214,0.973398058 4.16126214,2.16990291 L4.16126214,5.65330097 L4.16126214,6.09203883 L0.296601942,9.95679612 C0.0122330097,10.241165 0.0122330097,10.702233 0.296601942,10.9865049 C0.438834951,11.1286408 0.625242718,11.1998058 0.811553398,11.1998058 C0.997864078,11.1998058 1.18427184,11.1287379 1.32650485,10.9865049 L4.16126214,8.15165049 L4.16126214,11.1209709 L0.296601942,14.9858252 C0.0122330097,15.2701942 0.0122330097,15.7312621 0.296699029,16.0156311 C0.438834951,16.1578641 0.625242718,16.228932 0.811553398,16.228932 C0.997864078,16.228932 1.18427184,16.1578641 1.32650485,16.0156311 L4.16126214,13.1806796 L4.16126214,15.6059223 L4.16126214,17.7557282 C4.16126214,18.952233 5.20660194,19.9256311 6.49135922,19.9256311 L12.0748544,19.9256311 C13.3596117,19.9256311 14.4049515,18.952233 14.4049515,17.7557282 L14.4049515,15.6058252 L14.4049515,13.1805825 L17.2397087,16.015534 C17.3819417,16.157767 17.5682524,16.228835 17.7546602,16.228835 C17.9409709,16.228835 18.1273786,16.157767 18.2695146,16.015534 C18.5539806,15.731165 18.5539806,15.2701942 18.2696117,14.9857282 L14.4049515,11.1208738 Z M12.9486408,11.4228155 L12.9486408,14.8776699 L5.61757282,14.8776699 L5.61757282,11.4228155 C5.61757282,11.4226214 5.61757282,11.4225243 5.61757282,11.4223301 L5.61757282,6.39417476 C5.61757282,6.3938835 5.61757282,6.39349515 5.61757282,6.39320388 L5.61757282,6.38145631 L12.9486408,6.38145631 L12.9486408,6.39320388 C12.9486408,6.39349515 12.9486408,6.3938835 12.9486408,6.39417476 L12.9486408,11.4223301 C12.9486408,11.4224272 12.9486408,11.4226214 12.9486408,11.4228155 Z M6.49135922,1.45631068 L12.0748544,1.45631068 C12.5485437,1.45631068 12.9486408,1.7831068 12.9486408,2.16990291 L12.9486408,4.92514563 L5.61757282,4.92514563 L5.61757282,2.16990291 C5.61757282,1.7831068 6.0176699,1.45631068 6.49135922,1.45631068 Z M12.0748544,18.4691262 L6.49135922,18.4691262 C6.0176699,18.4691262 5.61757282,18.1423301 5.61757282,17.755534 L5.61757282,16.3339806 L12.9486408,16.3339806 L12.9486408,17.7556311 C12.9486408,18.1424272 12.5485437,18.4691262 12.0748544,18.4691262 Z">
                                                                                </path>
                                                                            </g>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg></i></span>
                                <span class="i-meta__figure">3</span>
                            </div>
                            <div class="i-meta" data-toggle="tooltip" title="Baggage">
                                <span class="i-meta__icon"><i class="gmz-icon"><svg width="24px" height="24px"
                                            viewBox="0 0 21 22" version="1.1">
                                            <desc>Created with Sketch.</desc>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-259.000000, -2015.000000)" fill="#1A2B50"
                                                    fill-rule="nonzero" stroke="#FFFFFF" stroke-width="0.2">
                                                    <g transform="translate(135.000000, 1603.000000)">
                                                        <g transform="translate(0.000000, 86.000000)">
                                                            <g>
                                                                <g transform="translate(125.000000, 326.000000)">
                                                                    <g transform="translate(0.000000, 1.042132)">
                                                                        <path
                                                                            d="M19.4092827,14.3460535 L17.8059072,14.3460535 C18.1347914,13.9961395 18.3161389,13.5329174 18.3122363,13.0527202 L18.3122363,4.57107461 C18.3122363,3.503986 17.4504219,2.61609571 16.3833333,2.61609571 L12.9535865,2.61609571 L12.9535865,1.22748811 C12.9537382,0.899854993 12.8228435,0.585770667 12.5900649,0.355211302 C12.3572864,0.124651936 12.0419626,-0.00322816269 11.714346,6.19501863e-05 L8.03248945,6.19501863e-05 C7.70487282,-0.00322816269 7.38954907,0.124651936 7.1567705,0.355211302 C6.92399193,0.585770667 6.79309722,0.899854993 6.79324895,1.22748811 L6.79324895,2.61609571 L3.36350211,2.61609571 C2.2964135,2.61609571 1.43459916,3.503986 1.43459916,4.57107461 L1.43459916,13.0527202 C1.43069655,13.5329174 1.61204409,13.9961395 1.94092827,14.3460535 L0.337552743,14.3460535 C0.151139241,14.3460535 0,14.5142392 0,14.7004839 L0,16.7679944 C0,16.9544501 0.151139241,17.0886695 0.337552743,17.0886695 L2.03135021,17.0886695 C1.53379016,17.5396946 1.36504995,18.2503908 1.60676195,18.8769408 C1.84847395,19.5034908 2.45080526,19.9167212 3.12236287,19.9167212 C3.79392048,19.9167212 4.39625179,19.5034908 4.63796379,18.8769408 C4.87967579,18.2503908 4.71093558,17.5396946 4.21337553,17.0886695 L15.5334599,17.0886695 C15.0358999,17.5396946 14.8671597,18.2503908 15.1088717,18.8769408 C15.3505837,19.5034908 15.952915,19.9167212 16.6244726,19.9167212 C17.2960302,19.9167212 17.8983615,19.5034908 18.1400735,18.8769408 C18.3817855,18.2503908 18.2130453,17.5396946 17.7154852,17.0886695 L19.4092827,17.0886695 C19.5956962,17.0886695 19.7468354,16.9546189 19.7468354,16.7679944 L19.7468354,14.7004839 C19.7468354,14.5142392 19.5956962,14.3460535 19.4092827,14.3460535 Z M3.12236287,19.2574459 C2.5980419,19.2574459 2.17299578,18.8323998 2.17299578,18.3080788 C2.17299578,17.7837579 2.5980419,17.3587117 3.12236287,17.3587117 C3.64668383,17.3587117 4.07172996,17.7837579 4.07172996,18.3080788 C4.07112541,18.8321492 3.64643322,19.2568414 3.12236287,19.2574459 L3.12236287,19.2574459 Z M16.6244726,19.2574459 C16.1001516,19.2574459 15.6751055,18.8323998 15.6751055,18.3080788 C15.6751055,17.7837579 16.1001516,17.3587117 16.6244726,17.3587117 C17.1487935,17.3587117 17.5738397,17.7837579 17.5738397,18.3080788 C17.5732351,18.8321492 17.1485429,19.2568414 16.6244726,19.2574459 Z M14.8523207,3.29120119 L14.8523207,14.3038594 L4.89451477,14.3038594 L4.89451477,3.29120119 L14.8523207,3.29120119 Z M17.6371308,4.57107461 L17.6371308,13.0527202 C17.6378305,13.3852181 17.5058736,13.7042605 17.2705122,13.939123 C17.0351508,14.1739854 16.715829,14.3052648 16.3833333,14.3038594 L15.5274262,14.3038706 L15.5274262,3.29120119 L16.3833333,3.29120119 C17.0781857,3.29120119 17.6371308,3.87622229 17.6371308,4.57107461 Z M7.46835443,1.22748811 C7.46800611,1.07883593 7.52771647,0.936345366 7.63393609,0.832350243 C7.7401557,0.72835512 7.88387793,0.67167347 8.03248945,0.675167435 L11.714346,0.675167435 C11.8629575,0.67167347 12.0066797,0.72835512 12.1128994,0.832350243 C12.219119,0.936345366 12.2788293,1.07883593 12.278481,1.22748811 L12.2784825,2.61609571 L7.46835291,2.61609571 L7.46835443,1.22748811 Z M2.10970464,4.57107461 C2.10970464,3.87622229 2.66864979,3.29120119 3.36350211,3.29120119 L4.21940928,3.29120119 L4.21940928,14.3038706 L3.36350211,14.3038594 C3.03100643,14.3052648 2.71168464,14.1739854 2.47632322,13.939123 C2.2409618,13.7042605 2.10900499,13.3852181 2.10970464,13.0527202 L2.10970464,4.57107461 Z M19.07173,16.4135641 L0.675105485,16.4135641 L0.675105485,15.021159 L19.07173,15.021159 L19.07173,16.4135641 Z">
                                                                        </path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg></i></span>
                                <span class="i-meta__figure">4</span>
                            </div>
                            <div class="i-meta" data-toggle="tooltip" title="Gear Shift">
                                <span class="i-meta__icon"><i class="gmz-icon"><svg width="24px" height="24px"
                                            viewBox="0 0 23 20" version="1.1">
                                            <desc>Created with Sketch.</desc>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-206.000000, -2016.000000)" fill="#1A2B50"
                                                    fill-rule="nonzero" stroke="#FFFFFF" stroke-width="0.1">
                                                    <g transform="translate(135.000000, 1603.000000)">
                                                        <g transform="translate(0.000000, 86.000000)">
                                                            <g>
                                                                <g transform="translate(70.000000, 327.000000)">
                                                                    <g transform="translate(1.000000, 0.000000)">
                                                                        <path
                                                                            d="M19.7745569,0.0513671801 L19.7745392,0.0514139099 C18.3074667,0.0512294327 17.0910913,1.18771017 16.9917569,2.65141594 C16.8924225,4.11512172 17.9440975,5.40552369 19.4409941,5.65309615 L19.3909941,9.66473167 L11.6307137,9.61473167 L11.6741302,5.60479315 C13.1303141,5.41137358 14.1868843,4.12166512 14.0899634,2.65589263 C13.9930424,1.19012015 12.7758553,0.0507588812 11.306882,0.0507588812 C9.83790863,0.0507588812 8.62072156,1.19012015 8.52380058,2.65589263 C8.4268796,4.12166512 9.4834498,5.41137358 10.9830502,5.65435784 L10.9330502,9.66473167 L3.17276982,9.61473167 L3.216023,5.60355344 C4.67176554,5.40530795 5.72422287,4.11193704 5.6224451,2.64628739 C5.52066733,1.18063775 4.2995449,0.0451489602 2.83037373,0.0500155897 C1.36120257,0.0548822192 0.147629469,1.19843593 0.055563751,2.66472769 C-0.0365019666,4.13101944 1.02450076,5.41738951 2.52510626,5.65557279 L2.48152666,14.3711402 C1.02497044,14.5597349 -0.0356560051,15.845726 0.056409469,17.3115528 C0.148474943,18.7773795 1.3616723,19.9205566 2.83037933,19.9254217 C4.29908636,19.9302868 5.51983056,18.7951721 5.62160501,17.3299875 C5.72337946,15.8648028 4.67129589,14.5718133 3.17276982,14.3240307 L3.22276982,10.3123952 L10.9830502,10.3623952 L10.9396337,14.3723337 C9.4834498,14.5657533 8.4268796,15.8554618 8.52380058,17.3212343 C8.62072156,18.7870067 9.83790863,19.926368 11.306882,19.926368 C12.7758553,19.926368 13.9930424,18.7870067 14.0899634,17.3212343 C14.1868843,15.8554618 13.1303141,14.5657533 11.6307137,14.3227691 L11.6807137,10.3123952 L19.7648259,10.3123952 C19.9436732,10.3123952 20.0886577,10.1674108 20.0886577,9.98856344 L20.1322559,5.60598431 C21.5910187,5.41766076 22.6527359,4.12876319 22.5582126,2.66093483 C22.4636894,1.19310648 21.2454256,0.0510321461 19.7745569,0.0513671801 Z M9.16538667,2.84062966 L9.16538664,2.84057279 C9.16538664,1.65785758 10.1241668,0.699077462 11.306882,0.699077462 C12.4895972,0.699077462 13.4483773,1.65785758 13.4483773,2.84057279 C13.4483773,4.023288 12.4895972,4.98206812 11.3068251,4.98206808 C10.1246987,4.98072339 9.16673136,4.02275601 9.16538667,2.84062966 Z M0.697723119,2.84062966 L0.697723087,2.84057279 C0.697723087,1.65785758 1.6565032,0.699077462 2.83921841,0.699077462 C4.02193362,0.699077462 4.98071374,1.65785758 4.98071374,2.84057279 C4.98071374,4.023288 4.02193362,4.98206812 2.83916154,4.98206808 C1.65703519,4.98072339 0.699067809,4.02275601 0.697723119,2.84062966 Z M4.98066698,17.1364972 L4.98066701,17.1365541 C4.98066701,18.0027125 4.45890325,18.7835824 3.65867542,19.1150424 C2.85844758,19.4465024 1.93734851,19.2632763 1.3248889,18.650803 C0.712429292,18.0383298 0.529223852,17.1172266 0.860701716,16.3170062 C1.19217958,15.5167857 1.97306117,14.9950394 2.83927641,14.9950588 C4.02138407,14.9964299 4.97932234,15.9543895 4.98066698,17.1364972 Z M13.4483773,17.1364972 L13.4483773,17.1365541 C13.4483773,18.3192693 12.4895972,19.2780494 11.306882,19.2780494 C10.1241668,19.2780494 9.16538664,18.3192693 9.16538664,17.1365541 C9.16538664,15.9538389 10.1241668,14.9950588 11.3069388,14.9950588 C12.4890652,14.9964035 13.4470326,15.9543709 13.4483773,17.1364972 Z M19.7746002,4.98206809 L19.7745444,4.98206812 C18.5918354,4.98204169 17.6330793,4.02324987 17.6330969,2.84054089 C17.6331145,1.65783191 18.5918992,0.699068654 19.7746082,0.699077462 C20.9573172,0.69908627 21.9160876,1.65786381 21.9160875,2.84062855 C21.9147691,4.02278508 20.9567567,4.98077607 19.7746002,4.98206809 Z">
                                                                        </path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg></i></span>
                                <span class="i-meta__figure">Auto</span>
                            </div>
                        </div>
                        <p class="car-item__location">
                            <i class="fas fa-map-marker-alt mr-2"></i>Zanzibar Town
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="car-item__price">
                                <span class="_retail">USD 35.00</span><span class="_unit">per day</span>
                            </div>
                            <a class="btn btn-primary car-item__view-detail"
                                href="car/rent-a-car-suzuki-escudo.html">View Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="car-item car-item--grid" data-plugin="matchHeight">
                    <div class="car-item__thumbnail">
                        <div class="add-wishlist-wrapper">
                            <a href="#gmz-login-popup" class="add-wishlist gmz-box-popup" data-effect="mfp-zoom-in"><i
                                    class="fal fa-heart"></i></a>
                        </div>
                        <a href="car/car-rental-suzuki-escudo-book-today.html">
                            <img src="storage/2023/05/29/rav4-car-hire-zanzibar-2-1685320782-360x240.jpg"
                                alt="Toyota RAV4- 3Doors"
                                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?w=360&amp;h=240&amp;fit=crop&amp;crop=center';" />
                        </a>
                        <a class="car-item__type" href="car-search6807.html?car_type=12">
                            Intermediate
                        </a>
                    </div>
                    <div class="car-item__details">
                        <span class="car-item__label">Featured</span>
                        <h3 class="car-item__title">
                            <a href="car/car-rental-suzuki-escudo-book-today.html">Toyota RAV4- 3Doors</a>
                        </h3>
                        <div class="car-item__meta">
                            <div class="i-meta" data-toggle="tooltip" title="Passenger">
                                <span class="i-meta__icon"><i class="gmz-icon"><svg width="24px" height="24px"
                                            viewBox="0 0 20 20" version="1.1">
                                            <desc>Created with Sketch.</desc>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <g transform="translate(-155.000000, -2016.000000)" stroke="#1A2B50"
                                                    stroke-width="0.5">
                                                    <g transform="translate(135.000000, 1603.000000)">
                                                        <g transform="translate(0.000000, 86.000000)">
                                                            <g>
                                                                <g transform="translate(20.000000, 327.000000)">
                                                                    <g>
                                                                        <circle cx="10" cy="3.125" r="2.5">
                                                                        </circle>
                                                                        <path
                                                                            d="M13.125,11.875 L13.125,10 C13.125,8.27411016 11.7258898,6.875 10,6.875 C8.27411016,6.875 6.875,8.27411016 6.875,10 L6.875,11.875 L8.125,11.875 L8.75,16.875 L11.25,16.875 L11.875,11.875 L13.125,11.875 Z">
                                                                        </path>
                                                                        <circle cx="3.75" cy="5" r="1.875">
                                                                        </circle>
                                                                        <path
                                                                            d="M4.75,8.28916667 C3.79624791,7.9682092 2.74632008,8.12578507 1.92880562,8.71257878 C1.11129116,9.29937249 0.626070499,10.2436921 0.625,11.25 L0.625,11.875 L1.875,11.875 L2.5,15.625 L5,15.625 L5.31333333,13.745">
                                                                        </path>
                                                                        <circle cx="16.25" cy="5" r="1.875">
                                                                        </circle>
                                                                        <path
                                                                            d="M15.25,8.28916667 C16.2037521,7.9682092 17.2536799,8.12578507 18.0711944,8.71257878 C18.8887088,9.29937249 19.3739295,10.2436921 19.375,11.25 L19.375,11.875 L18.125,11.875 L17.5,15.625 L15,15.625 L14.6866667,13.745">
                                                                        </path>
                                                                        <path
                                                                            d="M19.375,17.5 C19.375,18.5358333 15.1775,19.375 10,19.375 C4.8225,19.375 0.625,18.5358333 0.625,17.5">
                                                                        </path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg></i></span>
                                <span class="i-meta__figure">4</span>
                            </div>
                            <div class="i-meta" data-toggle="tooltip" title="Doors">
                                <span class="i-meta__icon"><i class="gmz-icon"><svg width="24px" height="24px"
                                            viewBox="0 0 20 22" version="1.1">
                                            <desc>Created with Sketch.</desc>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-309.000000, -2015.000000)" fill="#1A2B50"
                                                    fill-rule="nonzero" stroke="#FFFFFF">
                                                    <g transform="translate(135.000000, 1603.000000)">
                                                        <g transform="translate(0.000000, 86.000000)">
                                                            <g>
                                                                <g transform="translate(125.000000, 326.000000)">
                                                                    <g transform="translate(6.000000, 0.042132)">
                                                                        <g>
                                                                            <g
                                                                                transform="translate(44.000000, 0.957868)">
                                                                                <path
                                                                                    d="M14.4049515,11.1208738 L14.4049515,8.15165049 L17.2397087,10.9865049 C17.3819417,11.1287379 17.5682524,11.1998058 17.7546602,11.1998058 C17.9409709,11.1998058 18.1273786,11.1287379 18.2696117,10.9865049 C18.5539806,10.7021359 18.5539806,10.241068 18.2696117,9.95679612 L14.4049515,6.09203883 L14.4049515,5.65330097 L14.4049515,2.16990291 C14.4049515,0.973398058 13.3596117,0 12.0748544,0 L6.49135922,0 C5.20660194,0 4.16126214,0.973398058 4.16126214,2.16990291 L4.16126214,5.65330097 L4.16126214,6.09203883 L0.296601942,9.95679612 C0.0122330097,10.241165 0.0122330097,10.702233 0.296601942,10.9865049 C0.438834951,11.1286408 0.625242718,11.1998058 0.811553398,11.1998058 C0.997864078,11.1998058 1.18427184,11.1287379 1.32650485,10.9865049 L4.16126214,8.15165049 L4.16126214,11.1209709 L0.296601942,14.9858252 C0.0122330097,15.2701942 0.0122330097,15.7312621 0.296699029,16.0156311 C0.438834951,16.1578641 0.625242718,16.228932 0.811553398,16.228932 C0.997864078,16.228932 1.18427184,16.1578641 1.32650485,16.0156311 L4.16126214,13.1806796 L4.16126214,15.6059223 L4.16126214,17.7557282 C4.16126214,18.952233 5.20660194,19.9256311 6.49135922,19.9256311 L12.0748544,19.9256311 C13.3596117,19.9256311 14.4049515,18.952233 14.4049515,17.7557282 L14.4049515,15.6058252 L14.4049515,13.1805825 L17.2397087,16.015534 C17.3819417,16.157767 17.5682524,16.228835 17.7546602,16.228835 C17.9409709,16.228835 18.1273786,16.157767 18.2695146,16.015534 C18.5539806,15.731165 18.5539806,15.2701942 18.2696117,14.9857282 L14.4049515,11.1208738 Z M12.9486408,11.4228155 L12.9486408,14.8776699 L5.61757282,14.8776699 L5.61757282,11.4228155 C5.61757282,11.4226214 5.61757282,11.4225243 5.61757282,11.4223301 L5.61757282,6.39417476 C5.61757282,6.3938835 5.61757282,6.39349515 5.61757282,6.39320388 L5.61757282,6.38145631 L12.9486408,6.38145631 L12.9486408,6.39320388 C12.9486408,6.39349515 12.9486408,6.3938835 12.9486408,6.39417476 L12.9486408,11.4223301 C12.9486408,11.4224272 12.9486408,11.4226214 12.9486408,11.4228155 Z M6.49135922,1.45631068 L12.0748544,1.45631068 C12.5485437,1.45631068 12.9486408,1.7831068 12.9486408,2.16990291 L12.9486408,4.92514563 L5.61757282,4.92514563 L5.61757282,2.16990291 C5.61757282,1.7831068 6.0176699,1.45631068 6.49135922,1.45631068 Z M12.0748544,18.4691262 L6.49135922,18.4691262 C6.0176699,18.4691262 5.61757282,18.1423301 5.61757282,17.755534 L5.61757282,16.3339806 L12.9486408,16.3339806 L12.9486408,17.7556311 C12.9486408,18.1424272 12.5485437,18.4691262 12.0748544,18.4691262 Z">
                                                                                </path>
                                                                            </g>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg></i></span>
                                <span class="i-meta__figure">3</span>
                            </div>
                            <div class="i-meta" data-toggle="tooltip" title="Baggage">
                                <span class="i-meta__icon"><i class="gmz-icon"><svg width="24px" height="24px"
                                            viewBox="0 0 21 22" version="1.1">
                                            <desc>Created with Sketch.</desc>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-259.000000, -2015.000000)" fill="#1A2B50"
                                                    fill-rule="nonzero" stroke="#FFFFFF" stroke-width="0.2">
                                                    <g transform="translate(135.000000, 1603.000000)">
                                                        <g transform="translate(0.000000, 86.000000)">
                                                            <g>
                                                                <g transform="translate(125.000000, 326.000000)">
                                                                    <g transform="translate(0.000000, 1.042132)">
                                                                        <path
                                                                            d="M19.4092827,14.3460535 L17.8059072,14.3460535 C18.1347914,13.9961395 18.3161389,13.5329174 18.3122363,13.0527202 L18.3122363,4.57107461 C18.3122363,3.503986 17.4504219,2.61609571 16.3833333,2.61609571 L12.9535865,2.61609571 L12.9535865,1.22748811 C12.9537382,0.899854993 12.8228435,0.585770667 12.5900649,0.355211302 C12.3572864,0.124651936 12.0419626,-0.00322816269 11.714346,6.19501863e-05 L8.03248945,6.19501863e-05 C7.70487282,-0.00322816269 7.38954907,0.124651936 7.1567705,0.355211302 C6.92399193,0.585770667 6.79309722,0.899854993 6.79324895,1.22748811 L6.79324895,2.61609571 L3.36350211,2.61609571 C2.2964135,2.61609571 1.43459916,3.503986 1.43459916,4.57107461 L1.43459916,13.0527202 C1.43069655,13.5329174 1.61204409,13.9961395 1.94092827,14.3460535 L0.337552743,14.3460535 C0.151139241,14.3460535 0,14.5142392 0,14.7004839 L0,16.7679944 C0,16.9544501 0.151139241,17.0886695 0.337552743,17.0886695 L2.03135021,17.0886695 C1.53379016,17.5396946 1.36504995,18.2503908 1.60676195,18.8769408 C1.84847395,19.5034908 2.45080526,19.9167212 3.12236287,19.9167212 C3.79392048,19.9167212 4.39625179,19.5034908 4.63796379,18.8769408 C4.87967579,18.2503908 4.71093558,17.5396946 4.21337553,17.0886695 L15.5334599,17.0886695 C15.0358999,17.5396946 14.8671597,18.2503908 15.1088717,18.8769408 C15.3505837,19.5034908 15.952915,19.9167212 16.6244726,19.9167212 C17.2960302,19.9167212 17.8983615,19.5034908 18.1400735,18.8769408 C18.3817855,18.2503908 18.2130453,17.5396946 17.7154852,17.0886695 L19.4092827,17.0886695 C19.5956962,17.0886695 19.7468354,16.9546189 19.7468354,16.7679944 L19.7468354,14.7004839 C19.7468354,14.5142392 19.5956962,14.3460535 19.4092827,14.3460535 Z M3.12236287,19.2574459 C2.5980419,19.2574459 2.17299578,18.8323998 2.17299578,18.3080788 C2.17299578,17.7837579 2.5980419,17.3587117 3.12236287,17.3587117 C3.64668383,17.3587117 4.07172996,17.7837579 4.07172996,18.3080788 C4.07112541,18.8321492 3.64643322,19.2568414 3.12236287,19.2574459 L3.12236287,19.2574459 Z M16.6244726,19.2574459 C16.1001516,19.2574459 15.6751055,18.8323998 15.6751055,18.3080788 C15.6751055,17.7837579 16.1001516,17.3587117 16.6244726,17.3587117 C17.1487935,17.3587117 17.5738397,17.7837579 17.5738397,18.3080788 C17.5732351,18.8321492 17.1485429,19.2568414 16.6244726,19.2574459 Z M14.8523207,3.29120119 L14.8523207,14.3038594 L4.89451477,14.3038594 L4.89451477,3.29120119 L14.8523207,3.29120119 Z M17.6371308,4.57107461 L17.6371308,13.0527202 C17.6378305,13.3852181 17.5058736,13.7042605 17.2705122,13.939123 C17.0351508,14.1739854 16.715829,14.3052648 16.3833333,14.3038594 L15.5274262,14.3038706 L15.5274262,3.29120119 L16.3833333,3.29120119 C17.0781857,3.29120119 17.6371308,3.87622229 17.6371308,4.57107461 Z M7.46835443,1.22748811 C7.46800611,1.07883593 7.52771647,0.936345366 7.63393609,0.832350243 C7.7401557,0.72835512 7.88387793,0.67167347 8.03248945,0.675167435 L11.714346,0.675167435 C11.8629575,0.67167347 12.0066797,0.72835512 12.1128994,0.832350243 C12.219119,0.936345366 12.2788293,1.07883593 12.278481,1.22748811 L12.2784825,2.61609571 L7.46835291,2.61609571 L7.46835443,1.22748811 Z M2.10970464,4.57107461 C2.10970464,3.87622229 2.66864979,3.29120119 3.36350211,3.29120119 L4.21940928,3.29120119 L4.21940928,14.3038706 L3.36350211,14.3038594 C3.03100643,14.3052648 2.71168464,14.1739854 2.47632322,13.939123 C2.2409618,13.7042605 2.10900499,13.3852181 2.10970464,13.0527202 L2.10970464,4.57107461 Z M19.07173,16.4135641 L0.675105485,16.4135641 L0.675105485,15.021159 L19.07173,15.021159 L19.07173,16.4135641 Z">
                                                                        </path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg></i></span>
                                <span class="i-meta__figure">2</span>
                            </div>
                            <div class="i-meta" data-toggle="tooltip" title="Gear Shift">
                                <span class="i-meta__icon"><i class="gmz-icon"><svg width="24px" height="24px"
                                            viewBox="0 0 23 20" version="1.1">
                                            <desc>Created with Sketch.</desc>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-206.000000, -2016.000000)" fill="#1A2B50"
                                                    fill-rule="nonzero" stroke="#FFFFFF" stroke-width="0.1">
                                                    <g transform="translate(135.000000, 1603.000000)">
                                                        <g transform="translate(0.000000, 86.000000)">
                                                            <g>
                                                                <g transform="translate(70.000000, 327.000000)">
                                                                    <g transform="translate(1.000000, 0.000000)">
                                                                        <path
                                                                            d="M19.7745569,0.0513671801 L19.7745392,0.0514139099 C18.3074667,0.0512294327 17.0910913,1.18771017 16.9917569,2.65141594 C16.8924225,4.11512172 17.9440975,5.40552369 19.4409941,5.65309615 L19.3909941,9.66473167 L11.6307137,9.61473167 L11.6741302,5.60479315 C13.1303141,5.41137358 14.1868843,4.12166512 14.0899634,2.65589263 C13.9930424,1.19012015 12.7758553,0.0507588812 11.306882,0.0507588812 C9.83790863,0.0507588812 8.62072156,1.19012015 8.52380058,2.65589263 C8.4268796,4.12166512 9.4834498,5.41137358 10.9830502,5.65435784 L10.9330502,9.66473167 L3.17276982,9.61473167 L3.216023,5.60355344 C4.67176554,5.40530795 5.72422287,4.11193704 5.6224451,2.64628739 C5.52066733,1.18063775 4.2995449,0.0451489602 2.83037373,0.0500155897 C1.36120257,0.0548822192 0.147629469,1.19843593 0.055563751,2.66472769 C-0.0365019666,4.13101944 1.02450076,5.41738951 2.52510626,5.65557279 L2.48152666,14.3711402 C1.02497044,14.5597349 -0.0356560051,15.845726 0.056409469,17.3115528 C0.148474943,18.7773795 1.3616723,19.9205566 2.83037933,19.9254217 C4.29908636,19.9302868 5.51983056,18.7951721 5.62160501,17.3299875 C5.72337946,15.8648028 4.67129589,14.5718133 3.17276982,14.3240307 L3.22276982,10.3123952 L10.9830502,10.3623952 L10.9396337,14.3723337 C9.4834498,14.5657533 8.4268796,15.8554618 8.52380058,17.3212343 C8.62072156,18.7870067 9.83790863,19.926368 11.306882,19.926368 C12.7758553,19.926368 13.9930424,18.7870067 14.0899634,17.3212343 C14.1868843,15.8554618 13.1303141,14.5657533 11.6307137,14.3227691 L11.6807137,10.3123952 L19.7648259,10.3123952 C19.9436732,10.3123952 20.0886577,10.1674108 20.0886577,9.98856344 L20.1322559,5.60598431 C21.5910187,5.41766076 22.6527359,4.12876319 22.5582126,2.66093483 C22.4636894,1.19310648 21.2454256,0.0510321461 19.7745569,0.0513671801 Z M9.16538667,2.84062966 L9.16538664,2.84057279 C9.16538664,1.65785758 10.1241668,0.699077462 11.306882,0.699077462 C12.4895972,0.699077462 13.4483773,1.65785758 13.4483773,2.84057279 C13.4483773,4.023288 12.4895972,4.98206812 11.3068251,4.98206808 C10.1246987,4.98072339 9.16673136,4.02275601 9.16538667,2.84062966 Z M0.697723119,2.84062966 L0.697723087,2.84057279 C0.697723087,1.65785758 1.6565032,0.699077462 2.83921841,0.699077462 C4.02193362,0.699077462 4.98071374,1.65785758 4.98071374,2.84057279 C4.98071374,4.023288 4.02193362,4.98206812 2.83916154,4.98206808 C1.65703519,4.98072339 0.699067809,4.02275601 0.697723119,2.84062966 Z M4.98066698,17.1364972 L4.98066701,17.1365541 C4.98066701,18.0027125 4.45890325,18.7835824 3.65867542,19.1150424 C2.85844758,19.4465024 1.93734851,19.2632763 1.3248889,18.650803 C0.712429292,18.0383298 0.529223852,17.1172266 0.860701716,16.3170062 C1.19217958,15.5167857 1.97306117,14.9950394 2.83927641,14.9950588 C4.02138407,14.9964299 4.97932234,15.9543895 4.98066698,17.1364972 Z M13.4483773,17.1364972 L13.4483773,17.1365541 C13.4483773,18.3192693 12.4895972,19.2780494 11.306882,19.2780494 C10.1241668,19.2780494 9.16538664,18.3192693 9.16538664,17.1365541 C9.16538664,15.9538389 10.1241668,14.9950588 11.3069388,14.9950588 C12.4890652,14.9964035 13.4470326,15.9543709 13.4483773,17.1364972 Z M19.7746002,4.98206809 L19.7745444,4.98206812 C18.5918354,4.98204169 17.6330793,4.02324987 17.6330969,2.84054089 C17.6331145,1.65783191 18.5918992,0.699068654 19.7746082,0.699077462 C20.9573172,0.69908627 21.9160876,1.65786381 21.9160875,2.84062855 C21.9147691,4.02278508 20.9567567,4.98077607 19.7746002,4.98206809 Z">
                                                                        </path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg></i></span>
                                <span class="i-meta__figure">Auto</span>
                            </div>
                        </div>
                        <p class="car-item__location">
                            <i class="fas fa-map-marker-alt mr-2"></i>Zanzibar, Town
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="car-item__price">
                                <span class="_retail">USD 40.00</span><span class="_unit">per day</span>
                            </div>
                            <a class="btn btn-primary car-item__view-detail"
                                href="car/car-rental-suzuki-escudo-book-today.html">View Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection