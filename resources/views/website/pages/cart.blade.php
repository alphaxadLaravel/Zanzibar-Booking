@extends('website.layouts.app')

@section('title', 'Booking Cart - Zanzibar Bookings')
@section('meta')
<meta name="description" content="Review your selected bookings and proceed to checkout">
@endsection

@section('pages')
<!-- Breadcrumb Section -->
<section class="breadcrumb-section bg-light py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="breadcrumb-title mb-0">Booking Cart</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-md-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cart</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Cart Section -->
<section class="cart-section py-5">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($cartItems->count() > 0)
            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <div class="cart-items">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="cart-section-title">Your Bookings ({{ $cartItems->count() }} items)</h2>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearCart()">
                                <i class="mdi mdi-trash-can-outline me-1"></i>Clear Cart
                            </button>
                        </div>

                        @foreach($cartItems as $item)
                            <div class="cart-item card mb-3" data-item-key="{{ $item['key'] }}">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <!-- Item Image -->
                                        <div class="col-md-3">
                                            <div class="cart-item-image">
                                                @if($item['deal']->photos && $item['deal']->photos->count() > 0)
                                                    <img src="{{ asset('storage/' . $item['deal']->photos->first()->photo) }}" 
                                                         alt="{{ $item['deal']->title }}" 
                                                         class="img-fluid rounded">
                                                @else
                                                    <div class="placeholder-image bg-light rounded d-flex align-items-center justify-content-center">
                                                        <i class="mdi mdi-image-outline text-muted" style="font-size: 3rem;"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Item Details -->
                                        <div class="col-md-6">
                                            <div class="cart-item-details">
                                                <h5 class="cart-item-title mb-2">
                                                    <a href="{{ route('view-hotel', ['id' => $hashids->encode($item['deal']->id)]) }}" 
                                                       class="text-decoration-none">
                                                        {{ $item['deal']->title }}
                                                    </a>
                                                </h5>
                                                <p class="cart-item-type text-muted mb-2">
                                                    <i class="mdi mdi-{{ $item['deal']->type === 'hotel' ? 'bed' : ($item['deal']->type === 'car' ? 'car' : 'map-marker') }} me-1"></i>
                                                    {{ ucfirst($item['deal']->type) }}
                                                    @if($item['deal']->category)
                                                        - {{ $item['deal']->category->name }}
                                                    @endif
                                                </p>
                                                <p class="cart-item-location text-muted mb-2">
                                                    <i class="mdi mdi-map-marker me-1"></i>
                                                    {{ $item['deal']->location }}
                                                </p>

                                                <!-- Booking Details -->
                                                <div class="booking-details">
                                                    @if($item['check_in'] && $item['check_out'])
                                                        <div class="booking-detail-item">
                                                            <strong>Check-in:</strong> {{ \Carbon\Carbon::parse($item['check_in'])->format('M d, Y') }}
                                                        </div>
                                                        <div class="booking-detail-item">
                                                            <strong>Check-out:</strong> {{ \Carbon\Carbon::parse($item['check_out'])->format('M d, Y') }}
                                                        </div>
                                                    @endif
                                                    
                                                    @if($item['adults'] || $item['children'])
                                                        <div class="booking-detail-item">
                                                            <strong>Guests:</strong> 
                                                            {{ $item['adults'] }} Adult{{ $item['adults'] > 1 ? 's' : '' }}
                                                            @if($item['children'] > 0)
                                                                , {{ $item['children'] }} Child{{ $item['children'] > 1 ? 'ren' : '' }}
                                                            @endif
                                                        </div>
                                                    @endif

                                                    @if($item['pickup_location'])
                                                        <div class="booking-detail-item">
                                                            <strong>Pickup:</strong> {{ $item['pickup_location'] }}
                                                            @if($item['pickup_time'])
                                                                at {{ $item['pickup_time'] }}
                                                            @endif
                                                        </div>
                                                    @endif

                                                    @if($item['return_location'])
                                                        <div class="booking-detail-item">
                                                            <strong>Return:</strong> {{ $item['return_location'] }}
                                                            @if($item['return_time'])
                                                                at {{ $item['return_time'] }}
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Item Actions & Price -->
                                        <div class="col-md-3">
                                            <div class="cart-item-actions text-end">
                                                <div class="quantity-controls mb-3">
                                                    <label class="form-label">Quantity:</label>
                                                    <div class="input-group input-group-sm" style="width: 120px;">
                                                        <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity('{{ $item['key'] }}', -1)">-</button>
                                                        <input type="number" class="form-control text-center" 
                                                               value="{{ $item['quantity'] }}" 
                                                               min="1" 
                                                               id="qty-{{ $item['key'] }}"
                                                               readonly>
                                                        <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity('{{ $item['key'] }}', 1)">+</button>
                                                    </div>
                                                </div>

                                                <div class="cart-item-price mb-3">
                                                    <div class="price-per-item">
                                                        ${{ number_format($item['deal']->base_price, 2) }} each
                                                    </div>
                                                    <div class="price-subtotal">
                                                        <strong>${{ number_format($item['subtotal'], 2) }}</strong>
                                                    </div>
                                                </div>

                                                <button type="button" class="btn btn-outline-danger btn-sm" 
                                                        onclick="removeFromCart('{{ $item['key'] }}')">
                                                    <i class="mdi mdi-trash-can-outline me-1"></i>Remove
                                                </button>
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
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Order Summary</h5>
                            </div>
                            <div class="card-body">
                                <div class="summary-item d-flex justify-content-between mb-2">
                                    <span>Items ({{ $cartItems->count() }}):</span>
                                    <span>${{ number_format($totalAmount, 2) }}</span>
                                </div>
                                <div class="summary-item d-flex justify-content-between mb-2">
                                    <span>Service Fee:</span>
                                    <span>$0.00</span>
                                </div>
                                <hr>
                                <div class="summary-total d-flex justify-content-between mb-4">
                                    <strong>Total:</strong>
                                    <strong>${{ number_format($totalAmount, 2) }}</strong>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="{{ route('confirm-booking') }}" class="btn btn-primary btn-lg">
                                        <i class="mdi mdi-credit-card me-2"></i>Proceed to Checkout
                                    </a>
                                    <a href="{{ route('index') }}" class="btn btn-outline-secondary">
                                        <i class="mdi mdi-arrow-left me-2"></i>Continue Shopping
                                    </a>
                                </div>

                                <div class="mt-4">
                                    <h6 class="text-muted mb-3">Payment Methods</h6>
                                    <div class="payment-methods">
                                        <div class="payment-method d-flex align-items-center mb-2">
                                            <i class="mdi mdi-credit-card text-primary me-2"></i>
                                            <span>Credit/Debit Card</span>
                                        </div>
                                        <div class="payment-method d-flex align-items-center mb-2">
                                            <i class="mdi mdi-bank text-success me-2"></i>
                                            <span>Bank Transfer</span>
                                        </div>
                                        <div class="payment-method d-flex align-items-center">
                                            <i class="mdi mdi-cash text-warning me-2"></i>
                                            <span>Pay on Arrival</span>
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
function removeFromCart(itemKey) {
    if (confirm('Are you sure you want to remove this item from your cart?')) {
        $.ajax({
            url: '{{ route("cart.remove") }}',
            method: 'POST',
            data: {
                item_key: itemKey
            },
            success: function(response) {
                if (response.success) {
                    // Remove the item from DOM
                    $(`.cart-item[data-item-key="${itemKey}"]`).fadeOut(300, function() {
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
                    showAlert('Item removed from cart successfully', 'success');
                }
            },
            error: function(xhr) {
                showAlert('Error removing item from cart', 'danger');
            }
        });
    }
}

// Update item quantity
function updateQuantity(itemKey, change) {
    var input = $(`#qty-${itemKey}`);
    var currentQty = parseInt(input.val());
    var newQty = currentQty + change;
    
    if (newQty < 1) {
        removeFromCart(itemKey);
        return;
    }
    
    // Update the quantity in session via AJAX
    $.ajax({
        url: '{{ route("cart.add") }}',
        method: 'POST',
        data: {
            deal_id: $('.cart-item[data-item-key="' + itemKey + '"]').data('deal-id'),
            quantity: change,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                input.val(newQty);
                location.reload(); // Reload to update totals
            }
        },
        error: function(xhr) {
            showAlert('Error updating quantity', 'danger');
        }
    });
}

// Clear entire cart
function clearCart() {
    if (confirm('Are you sure you want to clear your entire cart? This action cannot be undone.')) {
        $.ajax({
            url: '{{ route("cart.clear") }}',
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            },
            error: function(xhr) {
                showAlert('Error clearing cart', 'danger');
            }
        });
    }
}

// Show alert message
function showAlert(message, type) {
    var alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('.container').prepend(alertHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
@endpush
