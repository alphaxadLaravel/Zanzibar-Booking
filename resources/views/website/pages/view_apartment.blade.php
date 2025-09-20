@extends('website.layouts.app')

@php
use Illuminate\Support\Str;
@endphp

@section('pages')
<section class="gallery">
    <div class="gmz-carousel-with-lightbox" data-count="{{ $apartment->photos->count() }}">
        @forelse($apartment->photos as $photo)
        <a href="{{ asset('storage/' . $photo->photo) }}">
            <img src="{{ asset('storage/' . $photo->photo) }}" alt="{{ $apartment->title }}"
                style="width: 100%; height: 400px; object-fit: cover; display: block;" loading="lazy" />
        </a>
        @empty
        <a
            href="{{ $apartment->cover_photo ? asset('storage/' . $apartment->cover_photo) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop&crop=center' }}">
            <img src="{{ $apartment->cover_photo ? asset('storage/' . $apartment->cover_photo) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop&crop=center' }}"
                alt="{{ $apartment->title }}" style="width: 100%; height: 400px; object-fit: cover; display: block;"
                loading="lazy" />
        </a>
        @endforelse
    </div>
</section>
<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="{{ route('index') }}">Home</a></li>
            <li><span>{{ $apartment->title }}</span></li>
        </ul>
    </div>
</div>

<div class="container">
    <div class="row">
        {{-- ############## MAIN ############################# --}}
        <div class="col-lg-8 pb-5">
            <div class="hotel-star">
                <div class="star-rating">
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                </div>
            </div>
            <div class="d-flex align-items-center mb-3" style="gap: 16px;">
                <h2 class="post-title my-2 bold">
                    {{ $apartment->title }}
                </h2>
            </div>
            <p class="location">
                <i class="fal fa-map-marker-alt"></i> {{ $apartment->location }}
            </p>

            <div class="meta">
                <ul class="row gx-3 gy-2" style="list-style:none;padding:0;margin:0;">
                    <li class="col-6 col-md-4 mb-2">
                        <div class="d-flex flex-column">
                            <span class="label text-muted" style="font-size:13px;">Type</span>
                            <span class="value fw-semibold" style="font-size:15px;">{{ $apartment->category ?
                                $apartment->category->category : 'Apartment' }}</span>
                        </div>
                    </li>
                    <li class="col-6 col-md-4 mb-2">
                        <div class="d-flex flex-column">
                            <span class="label text-muted" style="font-size:13px;">Price</span>
                            <span class="value fw-semibold" style="font-size:15px;">USD {{
                                number_format($apartment->base_price, 2) }}/night</span>
                        </div>
                    </li>
                    <li class="col-6 col-md-4 mb-2">
                        <div class="d-flex flex-column">
                            <span class="label text-muted" style="font-size:13px;">Rating</span>
                            <span class="value fw-semibold" style="font-size:15px;">{{ $apartment->ratings ?
                                number_format($apartment->ratings, 1) : '5.0' }}/5</span>
                        </div>
                    </li>
                </ul>
            </div>
            <hr>
            <section class="description">
                <h4 class="section-title">Detail</h4>
                <div class="section-content">
                    <p>
                        {!! $apartment->description !!}
                    </p>
                </div>
            </section>
            <hr>
            <section class="feature">
                <h4 class="section-title">Facilities</h4>
                <div class="section-content">
                    <div class="d-flex flex-wrap" style="gap: 10px;">
                        @forelse($apartment->features as $feature)
                        <div class="facility-card d-flex align-items-center px-3 py-2 mb-2"
                            style="background: #fff; border-radius: 6px; border: 1px solid #e0e0e0; min-height: 38px; flex: 0 0 auto; min-width: 140px; max-width: 220px;">
                            @if($feature->icon)
                            <i class="mdi {{ $feature->icon }} me-2"
                                style="font-size: 1.2rem; color: #2e8b57; width: 20px; text-align: center;"></i>
                            @else
                            <i class="mdi mdi-check-circle me-2"
                                style="font-size: 1.2rem; color: #2e8b57; width: 20px; text-align: center;"></i>
                            @endif
                            <span style="font-size: 13px; font-weight: 500; color: #333; line-height: 1.3;">{{ $feature->name }}</span>
                        </div>
                        @empty
                        <div class="text-muted" style="font-size: 14px;">No facilities listed.</div>
                        @endforelse
                    </div>
                </div>
            </section>
            <hr>
            <section class="feature">
                <h2 class="section-title">Apartment Services</h2>
                <div class="section-content">
                    <div class="d-flex flex-wrap">
                        <div class="service-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-home me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Self Catering</span>
                        </div>
                        <div class="service-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-wifi me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Free WiFi</span>
                        </div>
                        <div class="service-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-car me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Parking Space</span>
                        </div>
                        <div class="service-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-tv me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Smart TV</span>
                        </div>
                        <div class="service-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-utensils me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Fully Equipped Kitchen</span>
                        </div>
                        <div class="service-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-swimming-pool me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Swimming Pool</span>
                        </div>
                        <div class="service-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-tshirt me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Laundry Facilities</span>
                        </div>
                        <div class="service-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-phone me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">24/7 Support</span>
                        </div>
                    </div>
                </div>
            </section>
            </section>

            <hr>
            <section class="map">
                <h2 class="section-title">Apartment Location On Map</h2>
                <div id="address-map-container" style="width: 100%; height: 400px">
                    @if($apartment->lat && $apartment->long)
                    <iframe width="100%" height="100%" frameborder="0" style="border:0; border-radius: 8px;"
                        src="https://www.google.com/maps?q={{ $apartment->lat }},{{ $apartment->long }}&output=embed"
                        allowfullscreen aria-hidden="false" tabindex="0" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                    @else
                    <iframe width="100%" height="100%" frameborder="0" style="border:0; border-radius: 8px;"
                        src="https://www.google.com/maps?q={{ $apartment->location }}&output=embed" allowfullscreen
                        aria-hidden="false" tabindex="0" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                    @endif
                </div>
            </section>
            <hr>

            <div class="reviews-section mt-4" id="review-section">
                <h3 class="comment-count mb-4">Reviews for this Apartment</h3>

                <!-- Sample Reviews -->
                <div class="reviews-list">
                    <!-- Review 1 -->
                    <div class="review-item d-flex mb-4 p-3"
                        style="background: #f8f9fa; border-radius: 12px; border: 1px solid #e9ecef;">
                        <div class="review-avatar me-3" style="flex-shrink: 0;">
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=60&h=60&fit=crop&crop=face"
                                alt="John Doe"
                                style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; display: block;">
                        </div>
                        <div class="review-content flex-grow-1">
                            <div class="review-header d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="reviewer-name mb-1"
                                        style="font-size: 16px; font-weight: 600; color: #333;">John Doe</h5>
                                    <div class="review-rating mb-1">
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                    </div>
                                </div>
                                <small class="text-muted">2 days ago</small>
                            </div>
                            <h6 class="review-title mb-2" style="font-size: 14px; font-weight: 500; color: #555;">
                                Perfect home away from home!</h6>
                            <p class="review-text mb-0" style="font-size: 14px; color: #666; line-height: 1.5;">
                                This apartment exceeded our expectations. It was clean, comfortable, and had everything we needed for a perfect stay.
                                The kitchen was fully equipped, the WiFi was fast, and the location was ideal. Highly recommended for families!
                            </p>
                        </div>
                    </div>

                    <!-- Review 2 -->
                    <div class="review-item d-flex mb-4 p-3"
                        style="background: #f8f9fa; border-radius: 12px; border: 1px solid #e9ecef;">
                        <div class="review-avatar me-3" style="flex-shrink: 0;">
                            <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=60&h=60&fit=crop&crop=face"
                                alt="Sarah Wilson"
                                style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; display: block;">
                        </div>
                        <div class="review-content flex-grow-1">
                            <div class="review-header d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="reviewer-name mb-1"
                                        style="font-size: 16px; font-weight: 600; color: #333;">Sarah Wilson</h5>
                                    <div class="review-rating mb-1">
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                    </div>
                                </div>
                                <small class="text-muted">1 week ago</small>
                            </div>
                            <h6 class="review-title mb-2" style="font-size: 14px; font-weight: 500; color: #555;">
                                Excellent location and amenities</h6>
                            <p class="review-text mb-0" style="font-size: 14px; color: #666; line-height: 1.5;">
                                We had a wonderful time at this apartment. The location is perfect - close to all attractions and the beach.
                                The apartment had everything we needed including a great kitchen and comfortable living space.
                                The balcony view was amazing!
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="post-comment parent-form" id="gmz-comment-section">
                <div class="comment-form-wrapper">
                    <form action="https://www.zanzibarbookings.com/add-comment"
                        class="comment-form form-sm gmz-form-action form-add-post-comment" method="post"
                        data-reload-time="1000">
                        <h3 class="comment-title mb-4">Leave a Review</h3>
                        <p class="notice mb-4 text-muted">
                            Your email address will not be published. Required fields are marked *
                        </p>

                        <div class="gmz-loader">
                            <div class="loader-inner">
                                <div class="spinner-grow text-info align-self-center loader-lg"></div>
                            </div>
                        </div>

                        <input type="hidden" name="post_id" value="{{ $apartment->id }}" />
                        <input type="hidden" name="comment_id" value="0" />
                        <input type="hidden" name="comment_type" value="apartment" />

                        <div class="row g-3">
                            <div class="col-12">
                                <div class="review-select-rate mb-3">
                                    <label class="form-label fw-semibold">Your rating *</label>
                                    <div class="fas-star mt-2">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </div>
                                    <input type="hidden" name="review_star" value="5" class="review_star" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comment-name" class="form-label fw-semibold">Your Name *</label>
                                    <input id="comment-name" type="text" name="comment_name"
                                        class="form-control gmz-validation" placeholder="Enter your full name"
                                        data-validation="required" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comment-email" class="form-label fw-semibold">Your Email *</label>
                                    <input id="comment-email" type="email" name="comment_email"
                                        class="form-control gmz-validation" placeholder="Enter your email address"
                                        data-validation="required" />
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="comment-title" class="form-label fw-semibold">Review Title *</label>
                                    <input id="comment-title" type="text" name="comment_title"
                                        class="form-control gmz-validation" placeholder="Give your review a title"
                                        data-validation="required" />
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="comment-content" class="form-label fw-semibold">Your Review *</label>
                                    <textarea id="comment-content" name="comment_content"
                                        placeholder="Share your experience with this apartment..."
                                        class="form-control gmz-validation" data-validation="required"
                                        rows="5"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="gmz-message mt-3"></div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg text-uppercase fw-semibold">
                                Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        {{-- ################ BOOKING SECTION ################ --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Book This Apartment</h5>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-baseline mb-1">
                            <h3 class="text-primary mb-0 me-2" style="font-size:2rem;">USD {{ number_format($apartment->base_price, 0) }}</h3>
                        </div>
                        <p class="text-muted mb-0" style="font-size:1rem;">per night</p>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('confirm-booking', ['deal_id' => $apartment->id]) }}" 
                           class="btn btn-primary btn-lg w-100 text-uppercase fw-semibold"
                           style="padding: 14px 0; text-align: center;">
                            Book This Apartment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@if($nearbyHotels->isNotEmpty())
<section class="list-hotel list-hotel--grid py-40 bg-gray-100 mb-0 nearby">
    <div class="container">
        <h4 class="section-title mb-20">Nearby Hotels & Apartments</h4>
        <div class="row">
            @foreach($nearbyHotels as $nearbyHotel)
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="tour-item tour-item--grid" data-plugin="matchHeight">
                    <div class="tour-item__thumbnail position-relative">
                        @if($nearbyHotel->is_featured)
                        <span class="tour-item__label position-absolute"
                            style="top: 12px; left: 12px; z-index: 2; background: #ff5722; color: #fff; padding: 4px 12px; border-radius: 6px; font-size: 14px;">Featured</span>
                        @endif
                        <a href="{{ $nearbyHotel->type === 'apartment' ? route('view-apartment', ['id' => $hashids->encode($nearbyHotel->id)]) : route('view-hotel', ['id' => $hashids->encode($nearbyHotel->id)]) }}" style="display:block;">
                            <img src="{{ $nearbyHotel->cover_photo ? asset('storage/' . $nearbyHotel->cover_photo) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=360&h=240&fit=crop&crop=center' }}"
                                alt="{{ $nearbyHotel->title }}" loading="eager" width="360" height="240"
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
                            <a href="{{ $nearbyHotel->type === 'apartment' ? route('view-apartment', ['id' => $hashids->encode($nearbyHotel->id)]) : route('view-hotel', ['id' => $hashids->encode($nearbyHotel->id)]) }}"
                                style="color:#222;text-decoration:none;">{{ $nearbyHotel->title }}</a>
                        </h3>
                        <div class="tour-item__meta" style="margin:18px 0 12px 0;">
                            <div class="i-meta d-flex align-items-center" style="font-size:15px;color:#888;">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $nearbyHotel->location }}
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center" style="margin-top:18px;">
                            <div class="tour-item__price">
                                <span class="_retail" style="color:#2e8b57;font-size:1.3rem;font-weight:600;">USD
                                    {{ number_format($nearbyHotel->base_price, 2) }}</span>
                                <span class="_unit" style="color:#2e8b57;font-size:1rem;">/Night</span>
                            </div>
                            <a class="btn btn-primary btn-sm tour-item__view-detail"
                                href="{{ $nearbyHotel->type === 'apartment' ? route('view-apartment', ['id' => $hashids->encode($nearbyHotel->id)]) : route('view-hotel', ['id' => $hashids->encode($nearbyHotel->id)]) }}"
                                style="font-size:1rem;padding:8px 22px;border-radius:7px;">
                                View Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($nearbyTours->isNotEmpty())
<section class="list-tour list-tour--grid py-40 bg-white mb-0 nearby-activities">
    <div class="container">
        <h4 class="section-title mb-20">Nearby Activities</h4>
        <div class="row">
            @foreach($nearbyTours as $tour)
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="tour-item tour-item--grid" data-plugin="matchHeight">
                    <div class="tour-item__thumbnail position-relative">
                        @if($tour->is_featured)
                        <span class="tour-item__label position-absolute"
                            style="top: 12px; left: 12px; z-index: 2; background: #ff5722; color: #fff; padding: 4px 12px; border-radius: 6px; font-size: 14px;">Featured</span>
                        @endif
                        <a href="{{route('view-tour', ['id' => $hashids->encode($tour->id)])}}" style="display:block;">
                            <img src="{{ $tour->cover_photo ? asset('storage/' . $tour->cover_photo) : 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=360&h=240&fit=crop&crop=center' }}"
                                alt="{{ $tour->title }}" loading="eager" width="360" height="240"
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
                        <h3 class="tour-item__title" style="font-size:1.25rem;font-weight:600;">
                            <a href="{{route('view-tour', ['id' => $hashids->encode($tour->id)])}}"
                                style="color:#222;text-decoration:none;">{{ $tour->title }}</a>
                        </h3>
                        <div class="tour-item__meta" style="margin:18px 0 12px 0;">
                            <div class="i-meta d-flex align-items-center" style="font-size:15px;color:#888;">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $tour->location }}
                            </div>
                        </div>
                        @if($tour->tours)
                        <div class="tour-duration mb-2" style="font-size:14px;color:#666;">
                            <i class="fas fa-clock me-2"></i>{{ $tour->tours->duration ?? 'Full Day' }}
                        </div>
                        @endif
                        <div class="d-flex justify-content-between align-items-center" style="margin-top:18px;">
                            <div class="tour-item__price">
                                <span class="_retail" style="color:#2e8b57;font-size:1.3rem;font-weight:600;">USD
                                    {{ number_format($tour->base_price, 2) }}</span>
                                <span class="_unit" style="color:#2e8b57;font-size:1rem;">/person</span>
                            </div>
                            <a class="btn btn-primary btn-sm tour-item__view-detail"
                                href="{{route('view-tour', ['id' => $hashids->encode($tour->id)])}}"
                                style="font-size:1rem;padding:8px 22px;border-radius:7px;">
                                View Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
