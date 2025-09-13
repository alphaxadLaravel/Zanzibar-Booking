@extends('website.layouts.app')

@section('pages')
<section class="gallery">
    <div class="gmz-carousel-with-lightbox" data-count="33">
        @for ($i = 0; $i < 10; $i++) <a
            href="{{asset('https://www.zanzibarbookings.com/storage/2024/02/28/emarald-michamvi-34-1709067436.jpg')}}">
            <img src="{{asset('https://www.zanzibarbookings.com/storage/2024/02/28/emarald-michamvi-34-1709067436.jpg')}}"
                alt="home slider" />
            </a>
            @endfor
    </div>
</section>
<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="../index.html">Home</a></li>
            <li><span>Aluna Paje</span></li>
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
                    Aluna Paje
                </h1>
                <div class="add-wishlist-wrapper" style="margin-left:8px;">
                    <a href="#gmz-login-popup" class="add-wishlist gmz-box-popup" data-effect="mfp-zoom-in"
                        style="display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:50%;background:#f5f5f5;box-shadow:0 2px 8px rgba(0,0,0,0.06);transition:background 0.2s;">
                        <i class="fal fa-heart" style="font-size:1.5rem;color:#ff5722;transition:color 0.2s;"></i>
                    </a>
                </div>
            </div>
            <p class="location">
                <i class="fal fa-map-marker-alt"></i> Paje Beach, Paje
            </p>

            <div class="meta">
                <ul class="row gx-3 gy-2" style="list-style:none;padding:0;margin:0;">
                    <li class="col-6 col-md-4 mb-2">
                        <div class="d-flex flex-column">
                            <span class="label text-muted" style="font-size:13px;">Type</span>
                            <span class="value fw-semibold" style="font-size:15px;">Mid range Hotels</span>
                        </div>
                    </li>
                    <li class="col-6 col-md-4 mb-2">
                        <div class="d-flex flex-column">
                            <span class="label text-muted" style="font-size:13px;">Checkin</span>
                            <span class="value fw-semibold" style="font-size:15px;">2:00 pm</span>
                        </div>
                    </li>
                    <li class="col-6 col-md-4 mb-2">
                        <div class="d-flex flex-column">
                            <span class="label text-muted" style="font-size:13px;">Checkout</span>
                            <span class="value fw-semibold" style="font-size:15px;">10:00 am</span>
                        </div>
                    </li>

                </ul>

            </div>
            <hr>
            <section class="description">
                <h2 class="section-title">Detail</h2>
                <div class="section-content">
                    <p>
                        <span style="color: rgb(0, 0, 0)">Aluna Paje is a new accommodation in the heart of the
                            lively center of Paje. Aluna is characterized by a personal
                            approach, good service and comfortable rooms. Feeling at
                            home away from home. The hotel is built in European
                            architecture and offers a unique combination of modern
                            design with African details and a natural and green
                            environment. </span>
                    </p>
                </div>
            </section>
            <hr>
            <section class="feature">
                <h2 class="section-title">Facilities</h2>
                <div class="section-content">
                    <div class="d-flex flex-wrap">
                        <!-- Row 1 -->
                        <div class="facility-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-home me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">The entire
                                place is
                                yours</span>
                        </div>
                        <div class="facility-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-expand-arrows-alt me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">116 m²
                                size</span>
                        </div>
                        <div class="facility-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-swimming-pool me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Swimming
                                Pool</span>
                        </div>
                        <div class="facility-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-umbrella-beach me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span
                                style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Balcony</span>
                        </div>
                        <div class="facility-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-parking me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Free on-site
                                parking</span>
                        </div>

                        <!-- Row 2 -->
                        <div class="facility-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-shower me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Private
                                bathroom</span>
                        </div>
                        <div class="facility-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-wifi me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Free
                                WiFi</span>
                        </div>
                        <div class="facility-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-eye me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">View</span>
                        </div>
                        <div class="facility-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-bath me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Bath</span>
                        </div>
                        <div class="facility-card d-flex align-items-center p-3 mb-3 mx-2"
                            style="background: white; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 60px; flex: 0 0 auto; min-width: 200px;">
                            <i class="fas fa-snowflake me-3"
                                style="font-size: 1.5rem; color: #333; width: 24px; text-align: center;"></i>
                            <span style="font-size: 14px; font-weight: 500; color: #333; line-height: 1.4;">Air
                                conditioning</span>
                        </div>
                    </div>
                </div>
            </section>
            <hr>
            <section class="feature">
                <h2 class="section-title">Hotel Services</h2>
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
                <h2 class="section-title">Hotel Location On Map</h2>
                <div id="address-map-container" style="width: 100%; height: 400px">
                    <iframe width="100%" height="100%" frameborder="0" style="border:0; border-radius: 8px;"
                        src="https://www.google.com/maps?q=Zanzibar&output=embed" allowfullscreen aria-hidden="false"
                        tabindex="0" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </section>
            <hr>


            <div class="reviews-section mt-4" id="review-section">
                <h3 class="comment-count mb-4">Reviews for this Hotel</h3>

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
                        <input type="hidden" name="comment_type" value="hotel" />
                        
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
                                        placeholder="Share your experience with this hotel..."
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

                {{-- rooms list --}}
                @for ($i = 0; $i < 6; $i++) 
                <div class="card mb-4 room-card rounded"
                    style=" overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                    <div class="row g-0 align-items-center">
                        <div class="col-4 d-flex align-items-center justify-content-center"
                            style="background: #f8f9fa;">
                            <div class="rounded"
                                style="width: 80px; height: 80px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                <img src="https://www.zanzibarbookings.com/storage/2024/02/28/emarald-michamvi-34-1709067436.jpg"
                                    alt="Room Image" class="rounded"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="card-body p-3">
                                <h5 class="card-title mb-1" style="font-size: 1.1rem; font-weight: 600;">Deluxe Double
                                    Room</h5>
                                <div class="mb-2" style="font-size: 13px; color: #666;">
                                    <i class="fa fa-user"></i> 2 Guests &nbsp; | &nbsp;
                                    <i class="fa fa-bed"></i> 1 King Bed
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="fw-bold" style="font-size: 1.1rem; color: #ff5722;">$120</span>
                                        <span style="font-size: 13px; color: #888;">/ night</span>
                                    </div>
                                    <a href="#book-room" class="btn btn-primary btn-sm gmz-box-popup"
                                        data-effect="mfp-zoom-in" style="font-size: 13px;">Book Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            <div class="white-popup mfp-with-anim mfp-hide gmz-popup-form" id="book-room">
                <div class="popup-inner">
                    <h4 class="popup-title">Deluxe Double Room</h4>
                    <div class="popup-content">
                        <form class="booking-form" id="booking-form">
                            <div class="gmz-loader">
                                <div class="loader-inner">
                                    <div class="spinner-grow text-info align-self-center loader-lg"></div>
                                </div>
                            </div>
                            

                            <!-- Booking Form Fields -->
                            <div class="form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="field-wrapper input">
                                            <label for="rooms">NUMBER OF ROOMS</label>
                                            <i class="fal fa-bed"></i>
                                            <select id="rooms" name="rooms" class="form-control gmz-validation" data-validation="required">
                                                <option value="">Select rooms</option>
                                                <option value="1">1 Room</option>
                                                <option value="2">2 Rooms</option>
                                                <option value="3">3 Rooms</option>
                                                <option value="4">4 Rooms</option>
                                                <option value="5">5 Rooms</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="field-wrapper input">
                                            <label for="guests">NUMBER OF GUESTS</label>
                                            <i class="fal fa-users"></i>
                                            <select id="guests" name="guests" class="form-control gmz-validation" data-validation="required">
                                                <option value="">Select guests</option>
                                                <option value="1">1 Guest</option>
                                                <option value="2">2 Guests</option>
                                                <option value="3">3 Guests</option>
                                                <option value="4">4 Guests</option>
                                                <option value="5">5 Guests</option>
                                                <option value="6">6 Guests</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="field-wrapper input">
                                            <label for="checkin">CHECK-IN DATE</label>
                                            <i class="fal fa-calendar-alt"></i>
                                            <input id="checkin" name="checkin" type="date" class="form-control gmz-validation" 
                                                   data-validation="required" min="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="field-wrapper input">
                                            <label for="checkout">CHECK-OUT DATE</label>
                                            <i class="fal fa-calendar-alt"></i>
                                            <input id="checkout" name="checkout" type="date" class="form-control gmz-validation" 
                                                   data-validation="required" min="">
                                        </div>
                                    </div>
                                </div>
                                <!-- Price Calculation Display -->
                                <div class="price-calculation my-4 p-2" style="background: #e8f5e8; border-radius: 8px; border-left: 4px solid #28a745;">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="calculation-item">
                                                <span class="label">Price per Room/Night:</span>
                                                <span class="value" id="room-price">$120</span>
                                            </div>
                                            <div class="calculation-item">
                                                <span class="label">Number of Rooms:</span>
                                                <span class="value" id="rooms-count">0</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="calculation-item">
                                                <span class="label">Number of Nights:</span>
                                                <span class="value" id="nights">0</span>
                                            </div>
                                            <div class="calculation-item">
                                                <span class="label">Calculation:</span>
                                                <span class="value" id="calculation">0 × 0 = $0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="margin: 10px 0;">
                                    <div class="total-price text-center">
                                        <h5 class="mb-0">
                                            <span class="label">Total Price: </span>
                                            <span class="value text-success" id="total-price" style="font-size: 1.5rem; font-weight: 700;">$0</span>
                                        </h5>
                                        <small class="text-muted">Rooms × Nights × Price per Room/Night</small>
                                    </div>
                                </div>

                                <div class="gmz-message"></div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary w-100" style="
                                        text-align: center;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        padding: 12px 24px;
                                        font-size: 16px;
                                        font-weight: 600;
                                    ">
                                        <i class="fal fa-calendar-check me-2"></i>
                                        Confirm Booking
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endfor
           
        </div>
    </div>
</div>
</div>


<section class="list-hotel list-hotel--grid py-40 bg-gray-100 mb-0 nearby">
    <div class="container">
        <h2 class="section-title mb-20">Hotels Near By</h2>
        <div class="row">
            @for ($i = 0; $i < 3; $i++) <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="tour-item tour-item--grid" data-plugin="matchHeight">
                    <div class="tour-item__thumbnail position-relative">
                        <span class="tour-item__label position-absolute"
                            style="top: 12px; left: 12px; z-index: 2; background: #ff5722; color: #fff; padding: 4px 12px; border-radius: 6px; font-size: 14px;">Featured</span>
                        <a href="{{route('view-hotel')}}" style="display:block;">
                            <img src="https://www.zanzibarbookings.com/storage/2023/09/12/dji-0973-hdr-sky5-3-1694521316-360x240.jpg"
                                alt="Zanzibar Slave Route &amp; Heritage Tour" loading="eager" width="360" height="240"
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
                            <a href="{{route('view-hotel')}}" style="color:#222;text-decoration:none;">Opera Hotel</a>
                        </h3>
                        <div class="tour-item__meta" style="margin:18px 0 12px 0;">
                            <div class="i-meta d-flex align-items-center" style="font-size:15px;color:#888;">
                                <i class="fas fa-map-marker-alt me-2"></i>Jambiani, Zanzibar
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center" style="margin-top:18px;">
                            <div class="tour-item__price">
                                <span class="_retail" style="color:#2e8b57;font-size:1.3rem;font-weight:600;">USD
                                    120.00</span>
                                <span class="_unit" style="color:#2e8b57;font-size:1rem;">/Night</span>
                            </div>
                            <a class="btn btn-primary btn-sm tour-item__view-detail" href="{{route('view-hotel')}}"
                                style="font-size:1rem;padding:8px 22px;border-radius:7px;">
                                View Detail
                            </a>
                        </div>
                    </div>
                </div>
        </div>
        @endfor


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