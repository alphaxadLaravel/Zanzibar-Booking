@extends('website.layouts.app')

@include('website.components.deal_seo', ['deal' => $activity])

@section('pages')
@include('website.components.deal_gallery', ['deal' => $activity])

<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="{{ route('index') }}">Home</a></li>
            <li><a href="#">Activities</a></li>
            <li><span>{{ $activity->title }}</span></li>
        </ul>
    </div>
</div>

<div class="container">
    <div class="row">
        {{-- ############## MAIN ############################# --}}
        <div class="col-lg-8 pb-5">
            <div class="card">
                <div class="card-body">
                    <div class="hotel-star">
                        @include('website.components.star_rating', ['rating' => $activity->star_rating ?? 5])
                    </div>
                    <div class="d-flex align-items-center" style="gap: 16px;">
                        <h2 class="post-title bold">
                            {{ $activity->title }}
                        </h2>
                    </div>
                    @if ($activity->location)
                    <p class="location">
                        <i class="fal fa-map-marker-alt"></i> {{ $activity->location }}
                    </p>
                    @endif

                    @include('website.components.deal_meta_info', ['deal' => $activity, 'type' => 'activity'])
                    <hr>
                    @include('website.components.deal_description', ['deal' => $activity, 'title' => 'Activity Overview'])
                    <hr>
                    @include('website.components.deal_itinerary', ['deal' => $activity])
                    <hr>
                    @include('website.components.deal_features', ['deal' => $activity, 'type' => 'tour_include', 'title' => 'Activity Includes'])
                    <hr>
                    @include('website.components.deal_features', ['deal' => $activity, 'type' => 'tour_exclude', 'title' => 'Activity Excludes'])
                    <hr>

                    @include('website.components.deal_policies', ['deal' => $activity])
                    <hr>

                    @include('website.components.deal_video', ['deal' => $activity])
                    <hr>

                    @include('website.components.deal_map', ['deal' => $activity, 'title' => 'Tour Location On Map'])

                </div>
            </div>
            <hr>

            @include('website.components.deal_reviews', ['deal' => $activity, 'paginatedReviews' => $paginatedReviews ?? collect(), 'reviewTitle' => 'Reviews for this Tour'])
        </div>

        {{-- ################ BOOKING SECTION ################ --}}
        <div class="col-lg-4">
            <div class="siderbar-single">
                <h4 class="post-title my-2 bold">
                    Book This Activity
                </h4>

                <div class="card mb-4 booking-card rounded"
                    style="overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                    <div class="card-header" style="background: #f8f9fa; padding: 15px;">
                        <h5 class="mb-0" style="font-size: 1.2rem; font-weight: 600; color: #333;">
                            <i class="mdi mdi-currency-usd me-2"></i>
                            Adult: <span style="color: #218080;">${{ number_format($activity->tours->adult_price ?? 0,
                                2) }}</span>
                            &nbsp;|&nbsp;
                            Child: <span style="color: #218080;">${{ number_format($activity->tours->child_price ?? 0,
                                2) }}</span>
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('book-deal') }}" method="POST" class="activity-booking-form">
                            @csrf
                            <input type="hidden" name="deal_id" value="{{ $activity->id }}">
                            <input type="hidden" name="type" value="activity">

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="check_in" class="form-label">Activity Date</label>
                                    <input type="date" class="form-control" id="check_in" name="check_in" required
                                        min="{{ date('Y-m-d') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="adults" class="form-label">Adults</label>
                                    <select class="form-control" id="adults" name="adults" required
                                        onchange="calculateActivityPrice()">
                                        @for($i = 1; $i <= ($activity->tours->max_people ?? 8); $i++)
                                            <option value="{{ $i }}" {{ $i===2 ? 'selected' : '' }}>{{ $i }} Adult{{ $i
                                                > 1 ? 's' : '' }}</option>
                                            @endfor
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="children" class="form-label">Children</label>
                                    <select class="form-control" id="children" name="children"
                                        onchange="calculateActivityPrice()">
                                        <option value="0">No Children</option>
                                        @for($i = 1; $i <= 4; $i++) <option value="{{ $i }}">{{ $i }} Child{{ $i > 1 ?
                                            'ren' : '' }}</option>
                                            @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="booking-summary mt-4 p-3"
                                style="background: #f8f9fa; border-radius: 8px; border-left: 4px solid #ff5722;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 style="color: #333; font-weight: 600; margin-bottom: 5px;">Total Price</h6>
                                        <p class="mb-1" style="font-size: 0.9rem; color: #666;">
                                            <span id="activity_adults">2</span> adult(s) × ${{
                                            number_format($activity->tours->adult_price ?? 0, 2) }}<br>
                                            <span id="activity_children">0</span> child(ren) × ${{
                                            number_format($activity->tours->child_price ?? 0, 2) }}
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <p class="mb-0" style="font-size: 1.5rem; font-weight: 700; color: #ff5722;">
                                            $<span id="activity_total_price">{{
                                                number_format(($activity->tours->adult_price ?? 0) * 2, 2) }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" name="book_now" class="btn btn-primary btn-lg text-uppercase fw-semibold w-100">
                                    Book Now
                                </button>
                                <button type="submit" name="add_cart" class="btn btn-outline-secondary btn-lg text-uppercase fw-semibold w-100 my-3" style="font-size: 13px;">
                                    <i class="mdi mdi-cart-plus me-1"></i> ADD TO CART
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <script>
                    function calculateActivityPrice() {
                        const adultPrice = {{ $activity->tours->adult_price ?? 0 }};
                        const childPrice = {{ $activity->tours->child_price ?? 0 }};
                        const adults = parseInt(document.getElementById('adults').value) || 0;
                        const children = parseInt(document.getElementById('children').value) || 0;
                        const totalPrice = (adults * adultPrice) + (children * childPrice);

                        document.getElementById('activity_adults').textContent = adults;
                        document.getElementById('activity_children').textContent = children;
                        document.getElementById('activity_total_price').textContent = totalPrice.toFixed(2);
                    }
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('adults').addEventListener('change', calculateActivityPrice);
                        document.getElementById('children').addEventListener('change', calculateActivityPrice);
                        calculateActivityPrice();
                    });
                </script>

                {{-- Contact Information --}}
                @include('website.components.contact_card')

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
                            @include('website.components.star_rating', ['rating' => $activity->star_rating ?? 5, 'size' => 'small'])
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle booking form submission errors - open login modal if user is not authenticated
        @if($errors->any() && !auth()->check())
            @if(str_contains($errors->first('error') ?? '', 'logged in') || str_contains($errors->first('error') ?? '', 'login'))
                const loginModalEl = document.getElementById('exampleModal');
                if (loginModalEl) {
                    const loginModal = bootstrap.Modal.getOrCreateInstance(loginModalEl);
                    loginModal.show();
                }
            @endif
        @endif
        
        // Don't prevent form submission for logged-in users
        @guest
            const bookingForm = document.querySelector('.activity-booking-form');
            if (bookingForm) {
                bookingForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const loginModalEl = document.getElementById('exampleModal');
                    if (loginModalEl) {
                        const loginModal = bootstrap.Modal.getOrCreateInstance(loginModalEl);
                        loginModal.show();
                    }
                    return false;
                });
            }
        @endguest
    });
</script>

@endsection