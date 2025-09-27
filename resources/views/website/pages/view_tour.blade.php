@extends('website.layouts.app')

@include('website.components.deal_seo', ['deal' => $tour])

@section('pages')
@include('website.components.deal_gallery', ['deal' => $tour])

<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="{{ route('index') }}">Home</a></li>
            <li><a href="{{ route('tours') }}">Tours</a></li>
            <li><span>{{ $tour->title }}</span></li>
        </ul>
    </div>
</div>

<div class="container">
    <div class="row">
        {{-- ############## MAIN ############################# --}}
        <div class="col-lg-8 pb-5">
            <div class="hotel-star">
                @include('website.components.star_rating', ['rating' => $tour->star_rating ?? 5])
            </div>
            <div class="d-flex align-items-center" style="gap: 16px;">
                <h2 class="post-title bold">
                    {{ $tour->title }}
                </h2>
            </div>
            @if ($tour->location)
            <p class="location">
                <i class="fal fa-map-marker-alt"></i> {{ $tour->location }}
            </p>
            @endif

            @include('website.components.deal_meta_info', ['deal' => $tour, 'type' => 'tour'])
            <hr>
            @include('website.components.deal_description', ['deal' => $tour, 'title' => 'Activity Overview'])
            <hr>
            @include('website.components.deal_itinerary', ['deal' => $tour])




            <hr>
            @include('website.components.deal_features', ['deal' => $tour, 'type' => 'tour_include', 'title' => 'Tour Includes'])
            <hr>
            @include('website.components.deal_features', ['deal' => $tour, 'type' => 'tour_exclude', 'title' => 'Tour Excludes'])
            <hr>

            @include('website.components.deal_policies', ['deal' => $tour])
            <hr>

            @include('website.components.deal_video', ['deal' => $tour])
            <hr>

            @include('website.components.deal_map', ['deal' => $tour, 'title' => 'Tour Location On Map'])
            <hr>

            @include('website.components.deal_reviews', ['deal' => $tour, 'paginatedReviews' => $paginatedReviews ?? collect(), 'reviewTitle' => 'Reviews for this Tour'])
        </div>

        {{-- ################ BOOKING SECTION ################ --}}
        <div class="col-lg-4">
            <div class="siderbar-single">
                <h4 class="post-title my-3 fw-bold ">
                    Reserve Your Spot
                </h4>
            
                {{-- booking card --}}
                <div class="card booking-card border rounded">
                    <!-- Header -->
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-calendar-check me-2"></i>Reservation Details
                        </h5>
                    </div>
            
                    <!-- Body -->
                    <div class="card-body p-4">
                        <!-- Price + button -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="fw-bold text-dark" style="font-size: 2rem;">
                                USD {{ number_format($tour->base_price, 2) }}
                                <small class="text-muted" style="font-size: 1rem;">/person</small>
                            </div>
                        </div>
            
                        <!-- Button -->
                        <div class="d-grid">
                            <a href="{{ route('confirm-booking', ['deal_id' => $tour->id]) }}"
                               class="btn btn-primary btn-lg w-100 text-center fw-bold d-flex align-items-center justify-content-between">
                                <span class="d-flex align-items-center">
                                    <i class="fas fa-calendar-check me-2"></i>
                                    Reserve Now
                                </span>
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

@if(isset($nearbyTours) && $nearbyTours->isNotEmpty())
<section class="list-tour list-tour--grid py-40 bg-gray-100 mb-0 nearby">
    <div class="container">
        <h4 class="section-title mb-20">Nearby Tours</h4>
        <div class="row">
            @foreach($nearbyTours as $nearbyTour)
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="tour-item tour-item--grid" data-plugin="matchHeight">
                    <div class="tour-item__thumbnail position-relative">
                        @if($nearbyTour->is_featured)
                        <span class="tour-item__label position-absolute"
                            style="top: 12px; left: 12px; z-index: 2; background: #ff5722; color: #fff; padding: 4px 12px; border-radius: 6px; font-size: 14px;">Featured</span>
                        @endif
                        <a href="{{route('view-tour', ['id' => $hashids->encode($nearbyTour->id)])}}"
                            style="display:block;">
                            <img src="{{ $nearbyTour->cover_photo ? asset('storage/' . $nearbyTour->cover_photo) : 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=360&h=240&fit=crop&crop=center' }}"
                                alt="{{ $nearbyTour->title }}" loading="eager" width="360" height="240"
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
                            @include('website.components.star_rating', ['rating' => $tour->star_rating ?? 5, 'size' => 'small'])
                        </div>
                        <h3 class="tour-item__title" style="font-size:1.25rem;font-weight:600;">
                            <a href="{{route('view-tour', ['id' => $hashids->encode($nearbyTour->id)])}}"
                                style="color:#222;text-decoration:none;">{{ $nearbyTour->title }}</a>
                        </h3>
                        <div class="tour-item__meta" style="margin:18px 0 12px 0;">
                            <div class="i-meta d-flex align-items-center" style="font-size:15px;color:#888;">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $nearbyTour->location }}
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center" style="margin-top:18px;">
                            <div class="tour-item__price">
                                <span class="_retail" style="color:#2e8b57;font-size:1.3rem;font-weight:600;">USD
                                    {{ number_format($nearbyTour->base_price, 2) }}</span>
                                <span class="_unit" style="color:#2e8b57;font-size:1rem;">/person</span>
                            </div>
                            <a class="btn btn-primary btn-sm tour-item__view-detail"
                                href="{{route('view-tour', ['id' => $hashids->encode($nearbyTour->id)])}}"
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

<style>
    /* Fix modal z-index to appear above header */
    .white-popup.gmz-popup-form {
        z-index: 2147483647 !important;
    }

    .mfp-bg {
        z-index: 2147483646 !important;
    }

    .mfp-wrap {
        z-index: 2147483646 !important;
    }

    .mfp-container {
        z-index: 2147483646 !important;
    }

    /* Ensure modal content is properly positioned */
    .mfp-content {
        z-index: 2147483647 !important;
    }

    /* Fix for Magnific Popup modal positioning */
    .mfp-ready .mfp-bg {
        opacity: 0.8;
    }

    .mfp-ready .mfp-wrap {
        opacity: 1;
    }

    /* Ensure modal is centered and visible */
    .white-popup {
        position: relative;
        background: white;
        padding: 0;
        width: auto;
        max-width: 500px;
        margin: 0 auto;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .popup-inner {
        padding: 15px;
    }

    .popup-title {
        margin-bottom: 15px;
        font-size: 20px;
        font-weight: 600;
        color: #333;
        text-align: center;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Fix modal z-index issues
    function fixModalZIndex() {
        // Ensure modals are above header
        const modals = document.querySelectorAll('.white-popup.gmz-popup-form');
        modals.forEach(modal => {
            modal.style.zIndex = '2147483647';
        });
        
        // Fix Magnific Popup z-index
        if (typeof $.magnificPopup !== 'undefined') {
            $.magnificPopup.instance = null;
        }
    }
    
    // Call fix on load
    fixModalZIndex();
    
    // Re-apply fix when modals are opened
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('gmz-box-popup')) {
            setTimeout(fixModalZIndex, 100);
        }
    });
});

// Simple star rating display
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('rating');
    const starDisplay = document.getElementById('star-display');
    if (select && starDisplay) {
        select.addEventListener('change', function () {
            let val = parseInt(this.value);
            if (!val) {
                starDisplay.innerHTML = '';
                return;
            }
            let stars = '';
            for (let i = 0; i < val; i++) {
                stars += '★';
            }
            starDisplay.textContent = stars;
        });
    }
});
</script>

@endsection