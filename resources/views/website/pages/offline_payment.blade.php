@extends('website.layouts.app')

@section('title', 'Offline Payment - Zanzibar Bookings')
@section('meta')
<meta name="description" content="Complete your booking payment via bank transfer">
@endsection

@section('pages')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="row">


                <!-- Bank Transfer Details -->
                <div class="col-lg-6 mb-4">
                    <div class="card border rounded-1">
                        <div class="card-header">
                            <h5 class="mb-0 fw-bold"><i class="mdi mdi-bank me-2"></i>Bank Transfer Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="payment-instructions mb-4">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="mdi mdi-information me-2"></i>Payment Instructions
                                </h6>
                                <ol class="mb-0">
                                    <li>Transfer the exact amount: <strong>{{ priceForUser($booking->total_amount, 2) }}</strong></li>
                                    <li>After paying, send the receipt to our email or WhatsApp numbers of Zanzibar Bookings.</li>
                                    <li>Don't hesitate to call or contact us after you have made the booking or after you have paid.</li>
                                </ol>
                            </div>

                            <div class="bank-details">
                                <h6 class="fw-bold text-success mb-3">
                                    <i class="mdi mdi-bank-transfer me-2"></i>Bank Details
                                </h6>

                                <div class="bank-detail-item mb-2">
                                    <div class="row">
                                        <div class="col-5"><strong>Bank Name:</strong></div>
                                        <div class="col-7">EQUITY BANK TANZANIA LIMITED</div>
                                    </div>
                                </div>

                                <div class="bank-detail-item mb-2">
                                    <div class="row">
                                        <div class="col-5"><strong>Branch:</strong></div>
                                        <div class="col-7">MALINDI, ZANZIBAR</div>
                                    </div>
                                </div>

                                <div class="bank-detail-item mb-2">
                                    <div class="row">
                                        <div class="col-5"><strong>Account Name:</strong></div>
                                        <div class="col-7">S & A ZANZIBAR BOOKINGS</div>
                                    </div>
                                </div>

                                <div class="bank-detail-item mb-2">
                                    <div class="row">
                                        <div class="col-5"><strong>Account Number:</strong></div>
                                        <div class="col-7">
                                            <span class="copy-text" data-text="3014211938950">
                                                3014211938950
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-primary ms-2 copy-btn" title="Copy">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </span>
                                            <small class="text-muted d-block">USD</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="bank-detail-item mb-2">
                                    <div class="row">
                                        <div class="col-5"><strong>Swift Code:</strong></div>
                                        <div class="col-7">EQBLTZTZ</div>
                                    </div>
                                </div>

                                <div class="bank-detail-item mb-2">
                                    <div class="row">
                                        <div class="col-5"><strong>Bank Code:</strong></div>
                                        <div class="col-7">047</div>
                                    </div>
                                </div>

                                <div class="bank-detail-item mb-2">
                                    <div class="row">
                                        <div class="col-5"><strong>Branch Address:</strong></div>
                                        <div class="col-7">Zanzibar Branch, Mlandege Street</div>
                                    </div>
                                </div>

                                <div class="bank-detail-item mb-2">
                                    <div class="row">
                                        <div class="col-5"><strong>Location:</strong></div>
                                        <div class="col-7">ZANZIBAR, TANZANIA</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Details -->
                <div class="col-lg-6 mb-4">


                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="card border rounded-1">
                                <div class="card-header">
                                    <h5 class="mb-0 fw-bold"><i class="mdi mdi-calendar-check me-2"></i>Booking Details
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <strong>Booking Reference:</strong><br>
                                            <span class="text-primary fw-bold">{{ $booking->booking_code }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Booking Date:</strong><br>
                                            {{ $booking->created_at->format('M d, Y') }}
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <strong>Customer Name:</strong><br>
                                            {{ $booking->fullname }}
                                        </div>
                                        <div class="col-6">
                                            <strong>Email:</strong><br>
                                            {{ $booking->email }}
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <strong>Phone:</strong><br>
                                            {{ $booking->phone }}
                                        </div>
                                        <div class="col-6">
                                            <strong>Country:</strong><br>
                                            {{ $booking->country }}
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong class="fs-5">Total Amount:</strong>
                                        <span class="fs-4 fw-bold text-success">{{ priceForUser($booking->total_amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="card border rounded-1">
                                <div class="card-body text-center">
                                    <h6 class="fw-bold mb-3">
                                        <i class="mdi mdi-help-circle me-2"></i>Need Help?
                                    </h6>
                                    <p class="mb-3">
                                        If you have any questions about this payment or need assistance, please contact
                                        us:
                                    </p>
                                    <div class="row">
                                        <div class="col-md-4 mb-2">
                                            <i class="mdi mdi-email text-primary me-2"></i>
                                            <strong>Email:</strong> info@zanzibarbookings.com
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <i class="mdi mdi-phone text-primary me-2"></i>
                                            <strong>Phone:</strong> +255 XXX XXX XXX
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <i class="mdi mdi-whatsapp text-success me-2"></i>
                                            <strong>WhatsApp:</strong> +255 XXX XXX XXX
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="d-flex justify-content-end">
                                <div class="">
                                    <a href="{{ route('payment.process', $booking->id) }}" class="btn btn-outline-primary me-3">
                                        <i class="mdi mdi-credit-card me-2"></i>PAY BY PESAPAL
                                    </a>
                                    <button type="button" class="btn btn-primary" onclick="window.print()">
                                        <i class="mdi mdi-printer me-2"></i>Print Payment Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Copy to clipboard functionality
    document.querySelectorAll('.copy-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const copyText = this.closest('.copy-text').getAttribute('data-text');
            
            // Create temporary input element
            const tempInput = document.createElement('input');
            tempInput.value = copyText;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            
            // Show feedback
            const originalIcon = this.innerHTML;
            this.innerHTML = '<i class="mdi mdi-check"></i>';
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-success');
            
            setTimeout(() => {
                this.innerHTML = originalIcon;
                this.classList.remove('btn-success');
                this.classList.add('btn-outline-primary');
            }, 2000);
        });
    });
});
</script>
@endpush