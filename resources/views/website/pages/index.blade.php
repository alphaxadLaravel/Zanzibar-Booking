@extends('website.layouts.app')

@section('pages')
<section class="hero-slider" style="height: 450px; position: relative;">
    <div class="container-fluid no-gutters p-0">
        <div class="single-hero-image" style="position: relative; height: 450px; overflow: hidden;">
            <video autoplay muted loop playsinline class="hero-bg-image"
                poster="https://www.zanzibarbookings.com/storage/2025/02/19/zanzibarbookingscom1-1681820030-1920x768-large-1739955733-1920x768.jpg"
                style="object-fit: cover; width: 100%; height: 450px; min-height: 450px; max-height: 450px; background-repeat: no-repeat; display: block;">
                <source src="{{ asset('images/zanzibar.mp4') }}" type="video/mp4">
            </video>
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
                <form action="#" method="GET" class="search-card p-3 rounded shadow" style="
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
                            <select class="form-control flex-grow-1" id="search_location" name="location"
                                style="height: 45px;">
                                <option value="">All Locations</option>
                                @php
                                $locations =
                                \App\Models\Deal::active()->distinct()->pluck('location')->filter()->sort();
                                @endphp
                                @foreach($locations as $location)
                                <option value="{{ $location }}">{{ $location }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3 d-flex flex-column" style="min-width: 0;">
                            <select class="form-control flex-grow-1" id="search_category" name="category"
                                style="height: 45px;">
                                <option value="">All Categories</option>
                                @foreach($propertyCategories as $category)
                                <option value="{{ $category->type }}">{{ $category->category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3 d-flex flex-column" style="min-width: 0;">
                            <input type="text" class="form-control flex-grow-1" id="search_name" name="name"
                                placeholder="Hotel, Apartment, Tour..." style="height: 45px;">
                        </div>
                        <div class="col-12 col-md-3 d-flex flex-column justify-content-end" style="min-width: 0;">
                            <button type="submit" class="btn btn-primary w-100" style="
                                        background: #003580;
                                        border: none;
                                        font-weight: 600;
                                        font-size: 16px;
                                        height: 45px;
                                    ">
                                <i class="fas fa-search mr-2"></i>Quick Search
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

        .form-control,
        .btn {
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

        .form-control,
        .btn {
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
        <div class="d-flex justify-content-between align-items-center mb-20">
            <h2 class="section-title mb-0">Property Types</h2>
        </div>
        <div class="row">
            @forelse($propertyCategories as $category)
            <div class="col-lg-2 col-md-4 col-6">
                <div class="hotel-type__item rounded" data-plugin="matchHeight">
                    <div class="hotel-type__thumbnail">
                        <a href="#">
                            <img class="rounded" style="width:100%; height:120px; object-fit:cover;"
                                src="{{ $category->image ? asset('storage/' . $category->image) : 'https://www.zanzibarbookings.com/storage/2023/09/12/dji-0973-hdr-sky5-3-1694521316-360x240.jpg' }}"
                                alt="{{ $category->category }}" />
                        </a>
                    </div>
                    <div class="hotel-type__info">
                        <h3 class="hotel-type__name">
                            <a href="#">{{ $category->category }}</a>
                        </h3>
                        <div class="hotel-type__description"></div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-center">No property types available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
<section class="list-hotel list-hotel--grid py-40 bg-gray-100">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-20">
            <h2 class="section-title mb-0">List Of Hotels</h2>
            <a href="#" class="btn btn-primary">View All</a>
        </div>
        <div class="row">
            @forelse($featuredDeals as $deal)
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="tour-item tour-item--grid" data-plugin="matchHeight">
                    <div class="tour-item__thumbnail position-relative">
                        <span class="tour-item__label position-absolute"
                            style="top: 12px; left: 12px; z-index: 2; background: #ff5722; color: #fff; padding: 4px 12px; border-radius: 6px; font-size: 14px;">Featured</span>
                        <a href="{{ $deal->type === 'apartment' ? route('view-apartment', ['id' => $hashids->encode($deal->id)]) : route('view-hotel', ['id' => $hashids->encode($deal->id)]) }}" style="display:block;">
                            <img src="{{ $deal->cover_photo ? asset('storage/' . $deal->cover_photo) : 'https://www.zanzibarbookings.com/storage/2023/09/12/dji-0973-hdr-sky5-3-1694521316-360x240.jpg' }}"
                                alt="{{ $deal->title }}" loading="eager" width="360" height="240"
                                style="width:100%;height:220px;object-fit:cover;border-radius:12px;" />
                        </a>

                        <div class="add-wishlist-wrapper" style="position:absolute;top:12px;right:12px;z-index:2;">
                            <a href="#gmz-login-popup" class="add-wishlist gmz-box-popup" data-effect="mfp-zoom-in">
                                <i class="fal fa-heart"></i>
                            </a>
                        </div>
                    </div>
                    <div class="tour-item__details" style="padding-top:18px;">
                        <div class="star-rating mb-2">
                            <div class="star-rating">
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                            </div>
                        </div>
                        <h3 class="car-item__title" style="font-size:1.25rem;font-weight:600;">
                            <a href="{{ $deal->type === 'apartment' ? route('view-apartment', ['id' => $hashids->encode($deal->id)]) : route('view-hotel', ['id' => $hashids->encode($deal->id)]) }}" style="color:#222;text-decoration:none;">{{ $deal->title
                                }}</a>
                        </h3>
                        <div class="tour-item__meta" style="margin:18px 0 12px 0;">
                            <div class="i-meta d-flex align-items-center" style="font-size:15px;color:#888;">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $deal->location }}
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center" style="margin-top:18px;">
                            <div class="tour-item__price">
                                <span class="_retail" style="color:#2e8b57;font-size:1.3rem;font-weight:600;">USD
                                    {{ number_format($deal->base_price, 2) }}</span>
                                <span class="_unit" style="color:#2e8b57;font-size:1rem;">/Night</span>
                            </div>
                            <a class="btn btn-primary btn-sm tour-item__view-detail" href="{{ $deal->type === 'apartment' ? route('view-apartment', ['id' => $hashids->encode($deal->id)]) : route('view-hotel', ['id' => $hashids->encode($deal->id)]) }}"
                                style="font-size:1rem;padding:8px 22px;border-radius:7px;">
                                View Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-center">No featured hotels available at the moment.</p>
            </div>
            @endforelse

            <style>
                .bg-orange {
                    background-color: #ff5722 !important;
                    color: #fff !important;
                }

                .card .badge.bg-success {
                    background: #2e8b57 !important;
                }
            </style>


        </div>
    </div>
</section>
<section class="tour-type">
    <div class="container py-40">
        <div class="d-flex justify-content-between align-items-center mb-20">
            <h2 class="section-title mb-0">Tour Types</h2>
        </div>
        <div class="row">
            @forelse($tourCategories as $category)
            <div class="col-lg-2 col-md-4 col-6">
                <div class="tour-type__item" data-plugin="matchHeight">
                    <div class="tour-type__thumbnail" style="width:170px; height:204px; overflow:hidden; margin:auto;">
                        <a href="#">
                            <img class="_image-tour"
                                src="{{ $category->image ? asset('storage/' . $category->image) : 'https://images.unsplash.com/photo-1523906834658-6e24ef2386f9?w=360&h=240&fit=crop&crop=center' }}"
                                alt="{{ $category->category }}"
                                style="width:170px; height:204px; object-fit:cover; display:block;" />
                        </a>
                    </div>
                    <div class="tour-type__info">
                        <h3 class="tour-type__name">
                            <a href="#">{{ $category->category }}</a>
                        </h3>
                        <div class="tour-type__description">Click here</div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-center">No tour types available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
<section class="list-tour list-tour--grid py-40 bg-gray-100">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-20">
            <h2 class="section-title mb-0">List Of Tours</h2>
            <a href="#" class="btn btn-primary">View All</a>
        </div>
        <div class="row">
            @forelse($featuredTours as $tour)
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="tour-item tour-item--grid" data-plugin="matchHeight">
                    <div class="tour-item__thumbnail position-relative">
                        <span class="tour-item__label position-absolute"
                            style="top: 12px; left: 12px; z-index: 2; background: #ff5722; color: #fff; padding: 4px 12px; border-radius: 6px; font-size: 14px;">Featured</span>
                        <a href="{{route('view-tour', ['id' => $hashids->encode($tour->id)])}}" style="display:block;">
                            <img src="{{ $tour->cover_photo ? asset('storage/' . $tour->cover_photo) : 'https://images.unsplash.com/photo-1523906834658-6e24ef2386f9?w=360&h=240&fit=crop&crop=center' }}"
                                alt="{{ $tour->title }}" loading="eager" width="360" height="240"
                                style="width:100%;height:220px;object-fit:cover;border-radius:12px;" />
                        </a>
                        @if($tour->category)
                        <a class="tour-item__type" href="#"
                            style="position:absolute;left:12px;bottom:12px;z-index:2;background:#2e8b57;color:#fff;padding:4px 10px;border-radius:5px;font-size:13px;">
                            {{ $tour->category->category }}
                        </a>
                        @endif
                        <div class="add-wishlist-wrapper" style="position:absolute;top:12px;right:12px;z-index:2;">
                            <a href="#gmz-login-popup" class="add-wishlist gmz-box-popup" data-effect="mfp-zoom-in">
                                <i class="fal fa-heart"></i>
                            </a>
                        </div>
                    </div>
                    <div class="tour-item__details" style="padding-top:18px;">
                        <h3 class="tour-item__title" style="font-size:1.25rem;font-weight:600;">
                            <a href="{{route('view-tour', ['id' => $hashids->encode($tour->id)])}}" style="color:#222;text-decoration:none;">{{ $tour->title
                                }}</a>
                        </h3>
                        <div class="tour-item__meta" style="margin:18px 0 12px 0;">
                            <div class="i-meta d-flex align-items-center" style="font-size:15px;color:#888;">
                                <i class="fal fa-calendar-alt" style="margin-right:6px;"></i>
                                <span>{{ $tour->tours ? $tour->tours->max_people : 'N/A' }} people</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center" style="margin-top:18px;">
                            <div class="tour-item__price">
                                <span class="_retail" style="color:#2e8b57;font-size:1.3rem;font-weight:600;">USD
                                    {{ $tour->tours ? number_format($tour->tours->adult_price, 2) :
                                    number_format($tour->base_price, 2) }}</span>
                                <span class="_unit" style="color:#2e8b57;font-size:1rem;">/person</span>
                            </div>
                            <a class="btn btn-primary btn-sm tour-item__view-detail" href="{{route('view-tour', ['id' => $hashids->encode($tour->id)])}}"
                                style="font-size:1rem;padding:8px 22px;border-radius:7px;">
                                View Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-center">No featured tours available at the moment.</p>
            </div>
            @endforelse


        </div>
    </div>
</section>
<section class="car-type">
    <div class="container py-40">
        <div class="d-flex justify-content-between align-items-center mb-20">
            <h2 class="section-title mb-0">Car Types</h2>
        </div>
        <div class="row">
            @forelse($carCategories as $category)
            <div class="col-lg-4 col-md-6">
                <div class="car-type__item" data-plugin="matchHeight">
                    <div class="car-type__left">
                        <h3 class="car-type__name">
                            <a href="#">{{ $category->category }}</a>
                        </h3>
                        <div class="car-type__description">Click here for details</div>
                        <a href="#" class="btn btn-primary car-type__detail">View Detail</a>
                    </div>
                    <div class="car-type__right">
                        <a href="#">
                            <img class="rounded" style="width:120px; height:120px; object-fit:cover;"
                                src="{{ $category->image ? asset('storage/' . $category->image) : 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=360&h=240&fit=crop&crop=center' }}"
                                alt="{{ $category->category }}" />
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-center">No car types available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
<section class="list-car list-car--grid py-40 bg-gray-100">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-20">
            <h2 class="section-title mb-0">List Of Cars</h2>
            <a href="#" class="btn btn-primary">View All</a>
        </div>
        <div class="row">
            @forelse($featuredCars as $car)
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="tour-item tour-item--grid" data-plugin="matchHeight">
                    <div class="tour-item__thumbnail position-relative">
                        <span class="tour-item__label position-absolute"
                            style="top: 12px; left: 12px; z-index: 2; background: #ff5722; color: #fff; padding: 4px 12px; border-radius: 6px; font-size: 14px;">Featured</span>
                        <a href="{{route('view-car', ['id' => $hashids->encode($car->id)])}}" style="display:block;">
                            <img src="{{ $car->cover_photo ? asset('storage/' . $car->cover_photo) : asset('html/assets/image/page/become-a-partner.jpg') }}"
                                alt="{{ $car->title }}" loading="eager" width="360" height="240"
                                style="width:100%;height:220px;object-fit:cover;border-radius:12px;" />
                        </a>

                        <div class="add-wishlist-wrapper" style="position:absolute;top:12px;right:12px;z-index:2;">
                            <a href="#gmz-login-popup" class="add-wishlist gmz-box-popup" data-effect="mfp-zoom-in">
                                <i class="fal fa-heart"></i>
                            </a>
                        </div>
                    </div>
                    <div class="tour-item__details" style="padding-top:18px;">
                        <div class="star-rating mb-2">
                            <div class="star-rating">
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                            </div>
                        </div>
                        <h3 class="car-item__title" style="font-size:1.25rem;font-weight:600;">
                            <a href="{{route('view-car', ['id' => $hashids->encode($car->id)])}}" style="color:#222;text-decoration:none;">{{ $car->title
                                }}</a>
                        </h3>
                        <div class="tour-item__meta" style="margin:18px 0 12px 0;">
                            <div class="i-meta d-flex align-items-center" style="font-size:15px;color:#888;">
                                <i class="fas fa-users" style="margin-right:6px;"></i>
                                <span>{{ $car->car ? $car->car->capacity : 'N/A' }} Passengers</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center" style="margin-top:18px;">
                            <div class="tour-item__price">
                                <span class="_retail" style="color:#2e8b57;font-size:1.3rem;font-weight:600;">USD
                                    {{ number_format($car->base_price, 2) }}</span>
                                <span class="_unit" style="color:#2e8b57;font-size:1rem;">/DAY</span>
                            </div>
                            <a class="btn btn-primary btn-sm tour-item__view-detail" href="{{route('view-car', ['id' => $hashids->encode($car->id)])}}"
                                style="font-size:1rem;padding:8px 22px;border-radius:7px;">
                                View Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-center">No featured cars available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

@endsection