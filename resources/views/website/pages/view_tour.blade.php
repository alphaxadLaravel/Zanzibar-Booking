@extends('website.layouts.app')

@php
use Illuminate\Support\Str;
@endphp

@section('title')
{{ $tour->seo_title ?: $tour->title }} - Zanzibar Bookings
@endsection

@section('meta')
<meta name="description" content="{{ $tour->seo_description ?: Str::limit(strip_tags($tour->description), 160) }}">
@if($tour->seo_keywords)
<meta name="keywords" content="{{ $tour->seo_keywords }}">
@endif

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ request()->url() }}">
<meta property="og:title" content="{{ $tour->seo_title ?: $tour->title }}">
<meta property="og:description" content="{{ $tour->seo_description ?: Str::limit(strip_tags($tour->description), 160) }}">
<meta property="og:image" content="{{ $tour->seo_image ? asset('storage/' . $tour->seo_image) : ($tour->cover_photo ? asset('storage/' . $tour->cover_photo) : asset('logo.png')) }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ request()->url() }}">
<meta property="twitter:title" content="{{ $tour->seo_title ?: $tour->title }}">
<meta property="twitter:description" content="{{ $tour->seo_description ?: Str::limit(strip_tags($tour->description), 160) }}">
<meta property="twitter:image" content="{{ $tour->seo_image ? asset('storage/' . $tour->seo_image) : ($tour->cover_photo ? asset('storage/' . $tour->cover_photo) : asset('logo.png')) }}">
@endsection

@section('pages')
<section class="gallery">
    <div class="gmz-carousel-with-lightbox" data-count="{{ $tour->photos->count() }}">
        @forelse($tour->photos as $photo)
        <a href="{{ asset('storage/' . $photo->photo) }}">
            <img src="{{ asset('storage/' . $photo->photo) }}" 
                 alt="{{ $tour->title }}" 
                 style="width: 100%; height: 400px; object-fit: cover; display: block;"
                 loading="lazy" />
        </a>
        @empty
        <a href="{{ $tour->cover_photo ? asset('storage/' . $tour->cover_photo) : 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&h=600&fit=crop&crop=center' }}">
            <img src="{{ $tour->cover_photo ? asset('storage/' . $tour->cover_photo) : 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&h=600&fit=crop&crop=center' }}" 
                 alt="{{ $tour->title }}" 
                 style="width: 100%; height: 400px; object-fit: cover; display: block;"
                 loading="lazy" />
        </a>
        @endforelse
    </div>
</section>

@if($tour->video_link)
<!-- Video Section -->
<section class="video-section" style="background: #f8f9fa; padding: 40px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="video-container" style="position: relative; width: 100%; height: 0; padding-bottom: 56.25%; background: #000; border-radius: 8px; overflow: hidden;">
                    @php
                        $videoUrl = $tour->video_link;
                        $embedUrl = '';
                        
                        // YouTube
                        if (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false) {
                            if (strpos($videoUrl, 'youtu.be') !== false) {
                                $videoId = substr($videoUrl, strrpos($videoUrl, '/') + 1);
                            } else {
                                parse_str(parse_url($videoUrl, PHP_URL_QUERY), $query);
                                $videoId = $query['v'] ?? '';
                            }
                            $embedUrl = 'https://www.youtube.com/embed/' . $videoId . '?rel=0&modestbranding=1';
                        }
                        // Vimeo
                        elseif (strpos($videoUrl, 'vimeo.com') !== false) {
                            $videoId = substr($videoUrl, strrpos($videoUrl, '/') + 1);
                            $embedUrl = 'https://player.vimeo.com/video/' . $videoId . '?title=0&byline=0&portrait=0';
                        }
                        // Direct video file or other platforms
                        else {
                            $embedUrl = $videoUrl;
                        }
                    @endphp
                    
                    @if($embedUrl)
                        <iframe src="{{ $embedUrl }}" 
                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" 
                                frameborder="0" 
                                allowfullscreen
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                        </iframe>
                    @else
                        <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: white; text-align: center;">
                            <div>
                                <i class="fas fa-play-circle" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                                <p>Video preview not available</p>
                                <a href="{{ $videoUrl }}" target="_blank" class="btn btn-primary">Watch Video</a>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="text-center mt-3">
                    <p class="text-muted mb-0">
                        <i class="fas fa-video me-2"></i>
                        Promotional video for {{ $tour->title }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

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
                <h1 class="post-title mb-0"
                    style="font-size:2.3rem;font-weight:700;line-height:1.2;flex:1 1 auto;color:#222;">
                    {{ $tour->title }}
                </h1>
                <div class="add-wishlist-wrapper" style="margin-left:8px;">
                    <a href="#gmz-login-popup" class="add-wishlist gmz-box-popup" data-effect="mfp-zoom-in"
                        style="display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:50%;background:#f5f5f5;box-shadow:0 2px 8px rgba(0,0,0,0.06);transition:background 0.2s;">
                        <i class="fal fa-heart" style="font-size:1.5rem;color:#ff5722;transition:color 0.2s;"></i>
                    </a>
                </div>
            </div>
            <p class="location">
                <i class="fal fa-map-marker-alt"></i> {{ $tour->location }}
            </p>

            <div class="meta">
                <ul class="row gx-3 gy-2" style="list-style:none;padding:0;margin:0;">
                    <li class="col-6 col-md-4 mb-2">
                        <div class="d-flex flex-column">
                            <span class="label text-muted" style="font-size:13px;">Type</span>
                            <span class="value fw-semibold" style="font-size:15px;">{{ $tour->category ? $tour->category->category : 'Tour' }}</span>
                        </div>
                    </li>
                    <li class="col-6 col-md-4 mb-2">
                        <div class="d-flex flex-column">
                            <span class="label text-muted" style="font-size:13px;">Price</span>
                            <span class="value fw-semibold" style="font-size:15px;">USD {{ number_format($tour->base_price, 2) }}/person</span>
                        </div>
                    </li>
                    <li class="col-6 col-md-4 mb-2">
                        <div class="d-flex flex-column">
                            <span class="label text-muted" style="font-size:13px;">Rating</span>
                            <span class="value fw-semibold" style="font-size:15px;">{{ $tour->ratings ? number_format($tour->ratings, 1) : '5.0' }}/5</span>
                        </div>
                    </li>
                </ul>
            </div>
            <hr>
            <section class="description">
                <h2 class="section-title">Detail</h2>
                <div class="section-content">
                    <p>
                        <span style="color: rgb(0, 0, 0)">{{ $tour->description ?: 'Experience an unforgettable adventure with ' . $tour->title . '. Located in ' . $tour->location . ', this tour offers exceptional experiences and memorable moments for an amazing journey.' }}</span>
                    </p>
                </div>
            </section>
            <hr>
            <section class="feature">
                <h2 class="section-title">Tour Highlights</h2>
                <div class="section-content">
                    <div class="d-flex flex-wrap">
                        @forelse($tour->features as $feature)
                        <div class="facility-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            @if($feature->icon)
                                <i class="mdi {{ $feature->icon }} me-3"
                                    style="font-size: 1.5rem; color: #2e8b57; width: 24px; text-align: center;"></i>
                            @else
                                <i class="mdi mdi-check-circle me-3"
                                    style="font-size: 1.5rem; color: #2e8b57; width: 24px; text-align: center;"></i>
                            @endif
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">{{ $feature->name }}</span>
                        </div>
                        @empty
                        <!-- Default tour highlights if no features are set -->
                        <div class="facility-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="mdi mdi-camera me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Professional Guide</span>
                        </div>
                        <div class="facility-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="mdi mdi-car me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Transportation</span>
                        </div>
                        <div class="facility-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="mdi mdi-food me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Meals Included</span>
                        </div>
                        <div class="facility-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="mdi mdi-shield-check me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Safety Equipment</span>
                        </div>
                        @endforelse
                    </div>
                </div>
            </section>
            <hr>
            <section class="feature">
                <h2 class="section-title">Tour Itinerary</h2>
                <div class="section-content">
                    <div class="d-flex flex-wrap">
                        <div class="service-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-spa me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span
                                style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Massages</span>
                        </div>
                        <div class="service-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-concierge-bell me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Concierge
                                Service</span>
                        </div>
                        <div class="service-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-utensils me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Room
                                Service</span>
                        </div>
                        <div class="service-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-broom me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span
                                style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Housekeeping</span>
                        </div>
                        <div class="service-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-car me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Airport
                                Transfer</span>
                        </div>
                        <div class="service-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-plane me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Tour
                                Booking</span>
                        </div>
                        <div class="service-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-tshirt me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Laundry
                                Service</span>
                        </div>
                        <div class="service-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-phone me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">24/7
                                Support</span>
                        </div>
                    </div>
                </div>
            </section>
            </section>

            <hr>
            <section class="map">
                <h2 class="section-title">Tour Location On Map</h2>
                <div id="address-map-container" style="width: 100%; height: 400px">
                    @if($tour->lat && $tour->long)
                        <iframe width="100%" height="100%" frameborder="0" style="border:0; border-radius: 8px;"
                            src="https://www.google.com/maps?q={{ $tour->lat }},{{ $tour->long }}&output=embed" allowfullscreen aria-hidden="false"
                            tabindex="0" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    @else
                        <iframe width="100%" height="100%" frameborder="0" style="border:0; border-radius: 8px;"
                            src="https://www.google.com/maps?q={{ $tour->location }}&output=embed" allowfullscreen aria-hidden="false"
                            tabindex="0" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    @endif
                </div>
            </section>
            <hr>


            <div class="reviews-section mt-4" id="review-section">
                <h3 class="comment-count mb-4">Reviews for this Tour</h3>

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
                                Amazing stay with beautiful views!</h6>
                            <p class="review-text mb-0" style="font-size: 14px; color: #666; line-height: 1.5;">
                                The hotel exceeded our expectations. The room was clean, comfortable, and had a stunning
                                view of the ocean.
                                The staff was incredibly friendly and helpful throughout our stay. The facilities were
                                top-notch,
                                especially the swimming pool and spa services. Highly recommended!
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
                                Perfect location and excellent service</h6>
                            <p class="review-text mb-0" style="font-size: 14px; color: #666; line-height: 1.5;">
                                We had a wonderful time at this hotel. The location is perfect - right on the beach with
                                easy access to local attractions.
                                The concierge team helped us book amazing tours and the room service was prompt and
                                delicious.
                                The balcony view was breathtaking!
                            </p>
                        </div>
                    </div>

                    <!-- Review 3 -->
                    <div class="review-item d-flex mb-4 p-3"
                        style="background: #f8f9fa; border-radius: 12px; border: 1px solid #e9ecef;">
                        <div class="review-avatar me-3" style="flex-shrink: 0;">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=60&h=60&fit=crop&crop=face"
                                alt="Mike Johnson"
                                style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; display: block;">
                        </div>
                        <div class="review-content flex-grow-1">
                            <div class="review-header d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="reviewer-name mb-1"
                                        style="font-size: 16px; font-weight: 600; color: #333;">Mike Johnson</h5>
                                    <div class="review-rating mb-1">
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star-o text-muted"></i>
                                    </div>
                                </div>
                                <small class="text-muted">2 weeks ago</small>
                            </div>
                            <h6 class="review-title mb-2" style="font-size: 14px; font-weight: 500; color: #555;">Great
                                facilities and friendly staff</h6>
                            <p class="review-text mb-0" style="font-size: 14px; color: #666; line-height: 1.5;">
                                The hotel has excellent facilities including a great swimming pool and fitness center.
                                The staff was very accommodating and helped with all our requests. The only minor issue
                                was
                                the WiFi speed in our room, but overall it was a great experience.
                            </p>
                        </div>
                    </div>

                    <!-- Review 4 -->
                    <div class="review-item d-flex mb-4 p-3"
                        style="background: #f8f9fa; border-radius: 12px; border: 1px solid #e9ecef;">
                        <div class="review-avatar me-3" style="flex-shrink: 0;">
                            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=60&h=60&fit=crop&crop=face"
                                alt="Emma Davis"
                                style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; display: block;">
                        </div>
                        <div class="review-content flex-grow-1">
                            <div class="review-header d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="reviewer-name mb-1"
                                        style="font-size: 16px; font-weight: 600; color: #333;">Emma Davis</h5>
                                    <div class="review-rating mb-1">
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                    </div>
                                </div>
                                <small class="text-muted">3 weeks ago</small>
                            </div>
                            <h6 class="review-title mb-2" style="font-size: 14px; font-weight: 500; color: #555;">Luxury
                                experience with attention to detail</h6>
                            <p class="review-text mb-0" style="font-size: 14px; color: #666; line-height: 1.5;">
                                This hotel truly delivers a luxury experience. Every detail was perfect - from the
                                welcome drink
                                to the turn-down service. The spa treatments were exceptional and the restaurant served
                                delicious local cuisine. We'll definitely be back!
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
                        
                        <input type="hidden" name="post_id" value="121" />
                        <input type="hidden" name="comment_id" value="0" />
                        <input type="hidden" name="comment_type" value="tour" />
                        
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
                                        placeholder="Share your experience with this tour..."
                                        class="form-control gmz-validation" data-validation="required" rows="5"></textarea>
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
        <div class="col-lg-4">
            <div class="siderbar-single">

                {{-- booking card --}}
                <div class="card mb-4 booking-card rounded"
                    style="overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                    <div class="card-header" style="background: #2e8b57; color: white; padding: 15px;">
                        <h5 class="mb-0" style="font-size: 1.2rem; font-weight: 600;">
                            <i class="fas fa-calendar-check me-2"></i>Book This Tour
                        </h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="price-display mb-3 text-center">
                            <span class="fw-bold" style="font-size: 1.5rem; color: #2e8b57;">USD {{ number_format($tour->base_price, 2) }}</span>
                            <span style="font-size: 14px; color: #888;">/person</span>
                        </div>
                        
                        <div class="d-grid">
                            <a href="{{ route('confirm-booking', ['deal_id' => $tour->id]) }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-calendar-check me-2"></i>Book This Tour
                            </a>
                        </div>
                    </div>
                </div>
                {{-- contact info --}}
                <div class="card mb-4 contact-card rounded"
                    style="overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                    <div class="card-header" style="background: #f8f9fa; padding: 15px;">
                        <h5 class="mb-0" style="font-size: 1.2rem; font-weight: 600; color: #333;">
                            <i class="fas fa-phone me-2"></i>Need Help?
                        </h5>
                    </div>
                    <div class="card-body p-3">
                        <p class="mb-3" style="color: #666; font-size: 14px;">
                            Contact us for more information about this tour.
                        </p>
                        <div class="contact-info">
                            <div class="contact-item d-flex align-items-center mb-2">
                                <i class="fas fa-phone me-2" style="color: #2e8b57; width: 20px;"></i>
                                <span style="font-size: 14px; color: #333;">+255 123 456 789</span>
                            </div>
                            <div class="contact-item d-flex align-items-center mb-2">
                                <i class="fas fa-envelope me-2" style="color: #2e8b57; width: 20px;"></i>
                                <span style="font-size: 14px; color: #333;">info@zanzibarbookings.com</span>
                            </div>
                            <div class="contact-item d-flex align-items-center">
                                <i class="fas fa-clock me-2" style="color: #2e8b57; width: 20px;"></i>
                                <span style="font-size: 14px; color: #333;">24/7 Support</span>
                            </div>
                        </div>
                    </div>
                </div>


           
        </div>
    </div>
</div>
</div>


<section class="list-tour list-tour--grid py-40 bg-gray-100 mb-0 nearby">
    <div class="container">
        <h2 class="section-title mb-20">Tours Near By</h2>
        <div class="row">
            @forelse($nearbyTours as $nearbyTour)
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="tour-item tour-item--grid" data-plugin="matchHeight">
                    <div class="tour-item__thumbnail position-relative">
                        @if($nearbyTour->is_featured)
                        <span class="tour-item__label position-absolute"
                            style="top: 12px; left: 12px; z-index: 2; background: #ff5722; color: #fff; padding: 4px 12px; border-radius: 6px; font-size: 14px;">Featured</span>
                        @endif
                        <a href="{{route('view-tour', ['id' => $hashids->encode($nearbyTour->id)])}}" style="display:block;">
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
                            <div class="star-rating">
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                            </div>
                        </div>
                        <h3 class="tour-item__title" style="font-size:1.25rem;font-weight:600;">
                            <a href="{{route('view-tour', ['id' => $hashids->encode($nearbyTour->id)])}}" style="color:#222;text-decoration:none;">{{ $nearbyTour->title }}</a>
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
                            <a class="btn btn-primary btn-sm tour-item__view-detail" href="{{route('view-tour', ['id' => $hashids->encode($nearbyTour->id)])}}"
                                style="font-size:1rem;padding:8px 22px;border-radius:7px;">
                                View Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-center">No nearby tours found.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

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

.calculation-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
    font-size: 13px;
}

.calculation-item .label {
    color: #666;
    font-weight: 500;
}

.calculation-item .value {
    color: #333;
    font-weight: 600;
}

.price-calculation {
    transition: all 0.3s ease;
}

.price-calculation:hover {
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15);
}

.booking-form .field-wrapper {
    position: relative;
}

.booking-form .field-wrapper input,
.booking-form .field-wrapper select,
.booking-form .field-wrapper textarea {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    transition: border-color 0.3s ease;
    font-size: 14px;
}

.booking-form .field-wrapper input:focus,
.booking-form .field-wrapper select:focus,
.booking-form .field-wrapper textarea:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.booking-form label {
    font-size: 11px;
    font-weight: 600;
    color: #333;
    margin-bottom: 2px;
    display: block;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.booking-form  {
    margin-bottom: 10px !important;
}

.booking-form .mb-4 {
    margin-bottom: 15px !important;
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

.booking-form .field-wrapper {
    position: relative;
}

.booking-form .field-wrapper i {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
    z-index: 2;
    font-size: 12px;
}

.booking-form .field-wrapper input,
.booking-form .field-wrapper select,
.booking-form .field-wrapper textarea {
    padding: 6px 6px 6px 35px;
    border: 1px solid #ddd;
    border-radius: 6px;
    transition: border-color 0.3s ease;
    font-size: 13px;
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
    
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('checkin').min = today;
    document.getElementById('checkout').min = today;
    
    // Room price per night
    const roomPricePerNight = 120;
    
    // Price calculation function
    function calculatePrice() {
        const rooms = parseInt(document.getElementById('rooms').value) || 0;
        const checkin = document.getElementById('checkin').value;
        const checkout = document.getElementById('checkout').value;
        
        if (rooms > 0 && checkin && checkout) {
            const checkinDate = new Date(checkin);
            const checkoutDate = new Date(checkout);
            
            // Calculate number of nights
            const timeDiff = checkoutDate.getTime() - checkinDate.getTime();
            const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
            
            if (nights > 0) {
                const total = rooms * nights * roomPricePerNight;
                
                // Update display
                document.getElementById('room-price').textContent = `$${roomPricePerNight}`;
                document.getElementById('rooms-count').textContent = rooms;
                document.getElementById('nights').textContent = nights;
                document.getElementById('calculation').textContent = `${rooms} × ${nights} × $${roomPricePerNight} = $${total}`;
                document.getElementById('total-price').textContent = `$${total}`;
            } else {
                resetPriceDisplay();
            }
        } else {
            resetPriceDisplay();
        }
    }
    
    // Reset price display
    function resetPriceDisplay() {
        document.getElementById('room-price').textContent = '$120';
        document.getElementById('rooms-count').textContent = '0';
        document.getElementById('nights').textContent = '0';
        document.getElementById('calculation').textContent = '0 × 0 = $0';
        document.getElementById('total-price').textContent = '$0';
    }
    
    // Event listeners for price calculation
    document.getElementById('rooms').addEventListener('change', calculatePrice);
    document.getElementById('checkin').addEventListener('change', function() {
        // Set minimum checkout date to day after checkin
        const checkinDate = new Date(this.value);
        const nextDay = new Date(checkinDate);
        nextDay.setDate(nextDay.getDate() + 1);
        document.getElementById('checkout').min = nextDay.toISOString().split('T')[0];
        calculatePrice();
    });
    document.getElementById('checkout').addEventListener('change', calculatePrice);
    
    // Form submission
    document.getElementById('booking-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = {
            rooms: document.getElementById('rooms').value,
            guests: document.getElementById('guests').value,
            checkin: document.getElementById('checkin').value,
            checkout: document.getElementById('checkout').value,
            first_name: document.getElementById('first_name').value,
            last_name: document.getElementById('last_name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            special_requests: document.getElementById('special_requests').value,
            total_price: document.getElementById('total-price').textContent
        };
        
        // Show loading
        const loader = document.querySelector('.gmz-loader');
        loader.style.display = 'block';
        
        // Simulate booking process (replace with actual API call)
        setTimeout(() => {
            loader.style.display = 'none';
            alert('Booking confirmed! You will receive a confirmation email shortly.');
            
            // Reset form
            document.getElementById('booking-form').reset();
            resetPriceDisplay();
            
            // Close modal (if using Magnific Popup)
            if (typeof $.magnificPopup !== 'undefined') {
                $.magnificPopup.close();
            }
        }, 2000);
    });
});
</script>

@endsection