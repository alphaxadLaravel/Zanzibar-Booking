@extends('website.layouts.app')

@section('title', 'Booking Cart - Zanzibar Bookings')
@section('meta')
<meta name="description" content="Review your selected bookings and proceed to checkout">
@endsection

@section('pages')
<!-- Breadcrumb Section -->
<div class="container py-4">
    <h4 class="mb-0 fw-bold">Booking Cart ({{ $cartItems->count() }})</h4>
</div>
<!-- Cart Section -->
<section class="cart-section py-2">
    <div class="container">
       

        @if($cartItems->count() > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="cart-items">


                    @foreach($cartItems as $item)
                    <div class="cart-item card mb-4 shadow-sm" data-item-id="{{ $item->id }}" data-hashed-id="{{ $hashids->encode($item->id) }}">
                        <div class="card-body p-4">
                            <div class="row align-items-center">

                                <!-- Item Image -->
                                <div class="col-md-3">
                                    @php
                                    $room = $item->room;
                                    $imageSrc = null;

                                    if ($room && $room->photos && $room->photos->count() > 0) {
                                    $imageSrc = asset('storage/' . $room->photos->first()->photo);
                                    } elseif ($room && $room->cover_photo) {
                                    $imageSrc = asset('storage/' . $room->cover_photo);
                                    } elseif ($item->deal->photos && $item->deal->photos->count() > 0) {
                                    $imageSrc = asset('storage/' . $item->deal->photos->first()->photo);
                                    } elseif ($item->deal->cover_photo) {
                                    $imageSrc = asset('storage/' . $item->deal->cover_photo);
                                    }
                                    @endphp

                                    @if($imageSrc)
                                    <img src="{{ $imageSrc }}" alt="{{ $room ? $room->title : $item->deal->title }}"
                                        class="img-fluid rounded"
                                        style="height: 120px; object-fit: cover; width: 100%;">
                                    @else
                                    <div class="placeholder-image bg-light rounded d-flex align-items-center justify-content-center"
                                        style="height: 120px;">
                                        <i class="mdi mdi-image-outline text-muted" style="font-size: 2rem;"></i>
                                    </div>
                                    @endif
                                </div>

                                <!-- Item Details -->
                                <div class="col-md-9">
                                    <!-- Hotel Title and Buttons -->
                                    <div class="mb-3 gap-2">
                                        <div class="w-100">
                                            <h5 class="fw-bold mb-0 text-truncate" style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width: 100%;">
                                                <a href="{{ route('view-hotel', ['id' => $hashids->encode($item->deal->id)]) }}"
                                                    class="text-decoration-none text-dark">
                                                    {{ $item->deal->title }}
                                                </a>
                                            </h5>
                                        </div>
                                        <div class="d-flex flex-row justify-content-end align-items-center ms-2 mt-2 mt-lg-0"
                                             style="gap: 0.5rem;"
                                             >
                                            <button type="button" class="btn btn-outline-danger btn-sm mx-2 mx-lg-2"
                                                onclick="removeFromCart({{ $item->id }})">
                                                <i class="mdi mdi-close me-1"></i> REMOVE
                                            </button>
                                            <button type="button" class="btn btn-primary btn-sm"
                                                onclick="bookSingleItem({{ $item->id }})">
                                                <i class="mdi mdi-calendar-check me-1"></i> BOOK NOW
                                            </button>
                                        </div>
                                    </div>
                                    <style>
                                        @media (max-width: 991.98px) {
                                            .cart-item .d-flex.flex-row.justify-content-end.align-items-center.ms-2.mt-2.mt-lg-0 {
                                                flex-direction: row;
                                                justify-content: flex-start !important;
                                                margin-left: 0 !important;
                                                margin-top: 0.75rem !important;
                                            }
                                            .cart-item h5.text-truncate {
                                                max-width: 100% !important;
                                                white-space: normal !important;
                                                overflow: visible !important;
                                                text-overflow: unset !important;
                                            }
                                        }
                                        @media (min-width: 992px) {
                                            .cart-item .mb-3.gap-2 {
                                                display: flex;
                                                flex-direction: row;
                                                justify-content: space-between;
                                                align-items: center;
                                            }
                                            .cart-item .d-flex.flex-row.justify-content-end.align-items-center.ms-2.mt-2.mt-lg-0 {
                                                margin-top: 0 !important;
                                            }
                                            .cart-item h5.text-truncate {
                                                max-width: 260px !important;
                                                white-space: nowrap !important;
                                                overflow: hidden !important;
                                                text-overflow: ellipsis !important;
                                            }
                                        }
                                    </style>

                                    <!-- Booking Details -->
                                    <div class="bg-light p-3 rounded">
                                        <div class="row">
                                            @if($room)
                                            <div class="col-6 col-md-4 mb-2">
                                                <small class="text-muted"><i class="mdi mdi-numeric me-1"></i>Number of
                                                    Rooms</small><br>
                                                <strong>{{ $item->number_rooms }} Room{{ $item->number_rooms > 1 ? 's' : ''
                                                    }}</strong>
                                            </div>
                                            <div class="col-6 col-md-4 mb-2">
                                                <small class="text-muted"><i class="mdi mdi-bed me-1"></i>Room
                                                    Name</small><br>
                                                <strong>{{ $room->title }}</strong>
                                            </div>
                                            <div class="col-6 col-md-4 mb-2">
                                                <small class="text-muted"><i class="mdi mdi-currency-usd me-1"></i>Total
                                                    Price</small><br>
                                                <strong class="text-success">${{ number_format($item->total_price, 2)
                                                    }}</strong>
                                            </div>
                                            @endif

                                            @if($item->check_in && $item->check_out)
                                            <div class="col-6 col-md-4 mb-2">
                                                <small class="text-muted">Check-in</small><br>
                                                <strong>{{ \Carbon\Carbon::parse($item->check_in)->format('M d, Y')
                                                    }}</strong>
                                            </div>
                                            <div class="col-6 col-md-4 mb-2">
                                                <small class="text-muted">Check-out</small><br>
                                                <strong>{{ \Carbon\Carbon::parse($item->check_out)->format('M d, Y')
                                                    }}</strong>
                                            </div>
                                            @endif

                                            @if($item->adults || $item->children)
                                            <div class="col-6 col-md-4 mb-2">
                                                <small class="text-muted">Guests</small><br>
                                                <strong>
                                                    {{ $item->adults }} Adult{{ $item->adults > 1 ? 's' : '' }}
                                                    @if($item->children > 0)
                                                    , {{ $item->children }} Child{{ $item->children > 1 ? 'ren' : ''
                                                    }}
                                                    @endif
                                                </strong>
                                            </div>
                                            @endif

                                            @if(!empty($item->pickup_location))
                                            <div class="col-6 col-md-4 mb-2">
                                                <small class="text-muted">Pickup</small><br>
                                                <strong>{{ $item->pickup_location }}
                                                    @if(!empty($item->pickup_time))
                                                    at {{ $item->pickup_time }}
                                                    @endif
                                                </strong>
                                            </div>
                                            @endif

                                            @if(!empty($item->return_location))
                                            <div class="col-6 col-md-4 mb-2">
                                                <small class="text-muted">Return</small><br>
                                                <strong>{{ $item->return_location }}
                                                    @if(!empty($item->return_time))
                                                    at {{ $item->return_time }}
                                                    @endif
                                                </strong>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                    @endforeach


                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-lg-4">
                <div class="cart-summary">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="mdi mdi-cart me-2"></i>Order Summary
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="summary-item d-flex justify-content-between mb-3">
                                <span class="text-muted">Items ({{ $cartItems->count() }}):</span>
                                <span class="fw-bold">${{ number_format($totalAmount, 2) }}</span>
                            </div>
                            <div class="summary-item d-flex justify-content-between mb-3">
                                <span class="text-muted">Service Fee:</span>
                                <span class="text-success">FREE</span>
                            </div>
                            <hr class="my-3">
                            <div class="summary-total d-flex justify-content-between mb-4">
                                <strong class="fs-5">Total:</strong>
                                <strong class="fs-4 text-success">${{ number_format($totalAmount, 2) }}</strong>
                            </div>

                            <div class="d-grid gap-3">
                                <button type="button" class="btn btn-primary btn-lg w-100" onclick="bookAllDeals()">
                                    <i class="mdi mdi-calendar-check me-2"></i>BOOK ALL NOW
                                </button>
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                <h6 class="text-muted mb-3">
                                    <i class="mdi mdi-shield-check me-1"></i>Payment Methods
                                </h6>
                                <div class="payment-methods">
                                    <div class="payment-method d-flex align-items-center mb-2">
                                        <i class="mdi mdi-credit-card text-primary me-2"></i>
                                        <span>Pesapal Gateways</span>
                                    </div>

                                    <div class="payment-method d-flex align-items-center">
                                        <i class="mdi mdi-cash text-warning me-2"></i>
                                        <span>Pay Offline</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Empty Cart -->
        <div class="empty-cart text-center py-5">
            <div class="empty-cart-icon mb-4">
                <i class="mdi mdi-cart-outline text-muted" style="font-size: 5rem;"></i>
            </div>
            <h3 class="empty-cart-title mb-3">Your cart is empty</h3>
            <p class="empty-cart-text text-muted mb-4">
                Looks like you haven't added any bookings to your cart yet.<br>
                Start exploring our amazing deals and add them to your cart!
            </p>
            <div class="empty-cart-actions">
                <a href="{{ route('hotels') }}" class="btn btn-primary me-3">
                    <i class="mdi mdi-bed me-2"></i>Browse Hotels
                </a>
                <a href="{{ route('activities') }}" class="btn btn-outline-primary me-3">
                    <i class="mdi mdi-map-marker me-2"></i>Browse Activities
                </a>
                <a href="{{ route('cars') }}" class="btn btn-outline-primary">
                    <i class="mdi mdi-car me-2"></i>Browse Cars
                </a>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

@push('scripts')
<script>
    // CSRF token setup
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Update cart count in header
function updateCartCount() {
    $.get('{{ route("cart") }}', function(data) {
        // Parse the cart count from the page
        var cartCount = $(data).find('.cart-items .cart-item').length;
        $('#cart-count').text(cartCount);
        
        if (cartCount === 0) {
            $('#cart-count').hide();
        } else {
            $('#cart-count').show();
        }
    });
}

// Remove item from cart
function removeFromCart(itemId) {
    if (confirm('Are you sure you want to remove this item from your cart?')) {
        $.ajax({
            url: '{{ route("cart.remove") }}',
            method: 'POST',
            data: {
                item_id: itemId
            },
            success: function(response) {
                if (response.success) {
                    // Remove the item from DOM
                    $(`.cart-item[data-item-id="${itemId}"]`).fadeOut(300, function() {
                        $(this).remove();
                        updateCartCount();
                        
                        // Check if cart is empty
                        if ($('.cart-item').length === 0) {
                            location.reload(); // Reload to show empty cart
                        } else {
                            // Update total
                            location.reload();
                        }
                    });
                    
                    // Show success message
                    showToast('Item removed from cart successfully', 'success');
                }
            },
            error: function(xhr) {
                showToast('Error removing item from cart', 'error');
            }
        });
    }
}


// Book single item
function bookSingleItem(itemId) {
    // Get the hashed ID from the data attribute
    const hashedId = $('.cart-item[data-item-id="' + itemId + '"]').data('hashed-id');
    // Redirect to confirm booking page with single cart item
    window.location.href = '{{ route("confirm-booking") }}?cart_items=' + hashedId;
}

// Book all deals in cart at once
function bookAllDeals() {
    // Get all hashed cart item IDs
    const cartItemHashedIds = [];
    $('.cart-item').each(function() {
        const hashedId = $(this).data('hashed-id');
        if (hashedId) {
            cartItemHashedIds.push(hashedId);
        }
    });

    // Redirect to confirm booking page with cart items
    if (cartItemHashedIds.length > 0) {
        window.location.href = '{{ route("confirm-booking") }}?cart_items=' + cartItemHashedIds.join(',');
    }
}
</script>
@endpush