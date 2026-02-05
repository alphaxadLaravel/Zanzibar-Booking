@extends('website.layouts.app')

@section('title', 'Offline Payment - Zanzibar Bookings')
@section('meta')
<meta name="description" content="Complete your booking payment via bank transfer">
@endsection

@section('pages')
<style>
@media print {
    body * { visibility: hidden; }
    .print-payment-bill, .print-payment-bill * { visibility: visible; }
    .print-payment-bill {
        display: block !important;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 20px;
        background: #fff;
        font-size: 14px;
        color: #000;
    }
    .print-payment-bill .print-section { margin-bottom: 24px; }
    .print-payment-bill h1 { font-size: 20px; margin-bottom: 20px; }
    .print-payment-bill h2 { font-size: 16px; margin-bottom: 10px; border-bottom: 1px solid #333; padding-bottom: 4px; }
    .print-payment-bill table { width: 100%; border-collapse: collapse; }
    .print-payment-bill td { padding: 4px 8px; vertical-align: top; }
    .print-payment-bill .amount-row { font-size: 16px; font-weight: bold; }
    .no-print { display: none !important; }
}
</style>

<!-- Simple bill layout for print only (hidden on screen) -->
<div class="print-payment-bill" style="display: none;">
    <h1 style="font-size: 20px; margin-bottom: 20px;">Zanzibar Bookings – Payment Details</h1>

    <div class="print-section">
        <h2>Booking</h2>
        <table>
            <tr><td><strong>Reference:</strong></td><td>{{ $booking->booking_code }}</td></tr>
            <tr><td><strong>Date:</strong></td><td>{{ $booking->created_at->format('M d, Y') }}</td></tr>
            <tr><td><strong>Customer:</strong></td><td>{{ $booking->fullname }}</td></tr>
            <tr><td><strong>Email:</strong></td><td>{{ $booking->email }}</td></tr>
            <tr><td><strong>Phone:</strong></td><td>{{ $booking->phone }}</td></tr>
            <tr><td><strong>Total amount to pay:</strong></td><td class="amount-row">{{ priceForUser($booking->total_amount, 2) }}</td></tr>
        </table>
    </div>

    <div class="print-section">
        <h2>Payment instructions</h2>
        <p>Transfer the exact amount above to the account below. After paying, send the receipt to our email or WhatsApp.</p>
    </div>

    <div class="print-section">
        <h2>Bank details</h2>
        <table>
            <tr><td><strong>Bank:</strong></td><td>EQUITY BANK TANZANIA LIMITED</td></tr>
            <tr><td><strong>Branch:</strong></td><td>MALINDI, ZANZIBAR</td></tr>
            <tr><td><strong>Account name:</strong></td><td>S & A ZANZIBAR BOOKINGS</td></tr>
            <tr><td><strong>Account number:</strong></td><td>3014211938950 (USD)</td></tr>
            <tr><td><strong>Swift code:</strong></td><td>EQBLTZTZ</td></tr>
            <tr><td><strong>Bank code:</strong></td><td>047</td></tr>
            <tr><td><strong>Address:</strong></td><td>Zanzibar Branch, Mlandege Street, ZANZIBAR, TANZANIA</td></tr>
        </table>
    </div>

    <div class="print-section">
        <h2>Contact</h2>
        <p>Email: {{ $systemSettings->email ?? 'info@zanzibarbookings.com' }} &nbsp;|&nbsp; Phone: {{ $systemSettings->phone ?? '—' }}</p>
    </div>
</div>

<div class="container py-5 no-print">
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
                                            <strong>Email:</strong>
                                            <a href="mailto:{{ $systemSettings->email ?? 'info@zanzibarbookings.com' }}" class="text-decoration-none">{{ $systemSettings->email ?? 'info@zanzibarbookings.com' }}</a>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <i class="mdi mdi-phone text-primary me-2"></i>
                                            <strong>Phone:</strong>
                                            <a href="tel:{{ str_replace(' ', '', $systemSettings->phone ?? '') }}" class="text-decoration-none">{{ $systemSettings->phone ?? '—' }}</a>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <i class="mdi mdi-whatsapp text-success me-2"></i>
                                            <strong>WhatsApp:</strong>
                                            @if(!empty($systemSettings->whatsapp_url))
                                                <a href="{{ $systemSettings->whatsapp_url }}" target="_blank" rel="noopener" class="text-decoration-none">{{ $systemSettings->secondary_phone ?? $systemSettings->phone ?? 'Chat on WhatsApp' }}</a>
                                            @else
                                                <a href="tel:{{ str_replace(' ', '', $systemSettings->secondary_phone ?? $systemSettings->phone ?? '') }}" class="text-decoration-none">{{ $systemSettings->secondary_phone ?? $systemSettings->phone ?? '—' }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 no-print">
                            <div class="d-flex justify-content-end">
                                <div class="">
                                    <a href="{{ route('payment.process', ['bookingId' => $bookingIdHash]) }}" class="btn btn-outline-primary me-3">
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