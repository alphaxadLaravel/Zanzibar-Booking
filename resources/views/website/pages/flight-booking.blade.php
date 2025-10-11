@extends('website.layouts.app')

@section('pages')
<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="{{ route('index') }}">Home</a></li>
            <li><a href="{{ route('flights.index') }}">Flights</a></li>
            <li><span>Book Flight</span></li>
        </ul>
    </div>
</div>

<section class="booking-section py-40 bg-gray-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!-- Flight Summary Card -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-plane me-2"></i>Flight Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <h6 class="text-muted mb-1">From</h6>
                                <h4>{{ $flight['departure']['city'] }}</h4>
                                <p class="mb-0">{{ $flight['departure']['time'] }}</p>
                                <small class="text-muted">{{ $flight['departure']['airport'] }}</small>
                            </div>
                            <div class="col-md-4 text-center">
                                <i class="fas fa-plane" style="font-size: 2rem; color: #007bff;"></i>
                                <p class="mb-0"><small>{{ $flight['duration'] }}</small></p>
                            </div>
                            <div class="col-md-4 text-end">
                                <h6 class="text-muted mb-1">To</h6>
                                <h4>{{ $flight['arrival']['city'] }}</h4>
                                <p class="mb-0">{{ $flight['arrival']['time'] }}</p>
                                <small class="text-muted">{{ $flight['arrival']['airport'] }}</small>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <small class="text-muted">Airline</small>
                                <p class="mb-0 fw-bold">{{ $flight['airline'] }}</p>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Flight Number</small>
                                <p class="mb-0 fw-bold">{{ $flight['flight_number'] }}</p>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Class</small>
                                <p class="mb-0 fw-bold">{{ ucfirst(strtolower($flight['cabin_class'])) }}</p>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Stops</small>
                                <p class="mb-0 fw-bold">{{ $flight['stops'] == 0 ? 'Non-stop' : $flight['stops'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Form -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-users me-2"></i>Passenger Information</h5>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('flights.booking.process') }}" method="POST" id="booking-form">
                            @csrf
                            <input type="hidden" name="flight_id" value="{{ $flight['id'] }}">

                            <!-- Contact Information -->
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2 mb-3">Contact Details</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="contact_email" 
                                            value="{{ auth()->user()->email ?? old('contact_email') }}" required>
                                        <small class="text-muted">Booking confirmation will be sent here</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" name="contact_phone" 
                                            value="{{ old('contact_phone') }}" placeholder="+255 XXX XXX XXX" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Passenger Count -->
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2 mb-3">Number of Passengers</h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Adults (12+ years) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="adults" id="adults" 
                                            value="{{ old('adults', 1) }}" min="1" max="9" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Children (2-11 years)</label>
                                        <input type="number" class="form-control" name="children" id="children" 
                                            value="{{ old('children', 0) }}" min="0" max="9">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Infants (Under 2)</label>
                                        <input type="number" class="form-control" name="infants" id="infants" 
                                            value="{{ old('infants', 0) }}" min="0" max="9">
                                    </div>
                                </div>
                            </div>

                            <!-- Passenger Details Container -->
                            <div id="passengers-container">
                                <!-- Passenger forms will be dynamically added here -->
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="#" target="_blank">terms and conditions</a> and <a href="#" target="_blank">privacy policy</a>
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-lock me-2"></i>Proceed to Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Price Summary Sidebar -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Price Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Base Fare</span>
                            <span class="fw-bold">{{ $flight['currency'] }} {{ number_format($flight['price'], 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Taxes & Fees</span>
                            <span class="fw-bold">Included</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Passengers</span>
                            <span class="fw-bold" id="passenger-count">1</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="mb-0">Total</h5>
                            <h5 class="mb-0 text-primary" id="total-price">{{ $flight['currency'] }} {{ number_format($flight['price'], 2) }}</h5>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Price may change based on availability
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.passenger-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    background: #f8f9fa;
}
.passenger-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #dee2e6;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const adultsInput = document.getElementById('adults');
    const childrenInput = document.getElementById('children');
    const infantsInput = document.getElementById('infants');
    const passengersContainer = document.getElementById('passengers-container');
    const basePrice = {{ $flight['price'] }};
    const currency = '{{ $flight['currency'] }}';

    // Generate passenger forms based on count
    function updatePassengerForms() {
        const adults = parseInt(adultsInput.value) || 0;
        const children = parseInt(childrenInput.value) || 0;
        const infants = parseInt(infantsInput.value) || 0;
        const total = adults + children + infants;

        // Update passenger count display
        document.getElementById('passenger-count').textContent = total;
        
        // Update total price (simplified - adults full price, children 75%, infants 10%)
        const totalPrice = (adults * basePrice) + (children * basePrice * 0.75) + (infants * basePrice * 0.10);
        document.getElementById('total-price').textContent = currency + ' ' + totalPrice.toFixed(2);

        // Clear existing forms
        passengersContainer.innerHTML = '';

        let passengerIndex = 0;

        // Add adult forms
        for (let i = 0; i < adults; i++) {
            addPassengerForm('adult', i + 1, passengerIndex);
            passengerIndex++;
        }

        // Add children forms
        for (let i = 0; i < children; i++) {
            addPassengerForm('child', i + 1, passengerIndex);
            passengerIndex++;
        }

        // Add infant forms
        for (let i = 0; i < infants; i++) {
            addPassengerForm('infant', i + 1, passengerIndex);
            passengerIndex++;
        }
    }

    function addPassengerForm(type, number, index) {
        const typeLabel = type.charAt(0).toUpperCase() + type.slice(1);
        const html = `
            <div class="passenger-card">
                <div class="passenger-header">
                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>${typeLabel} ${number}</h6>
                    <span class="badge bg-primary">${typeLabel}</span>
                </div>
                <input type="hidden" name="passengers[${index}][type]" value="${type}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="passengers[${index}][first_name]" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="passengers[${index}][last_name]" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="passengers[${index}][date_of_birth]" 
                            max="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Gender</label>
                        <select class="form-control" name="passengers[${index}][gender]">
                            <option value="">Select</option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Passport Number (Optional)</label>
                        <input type="text" class="form-control" name="passengers[${index}][passport_number]" 
                            placeholder="For international flights">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nationality (Optional)</label>
                        <input type="text" class="form-control" name="passengers[${index}][nationality]" 
                            placeholder="e.g., Tanzanian">
                    </div>
                </div>
            </div>
        `;
        passengersContainer.insertAdjacentHTML('beforeend', html);
    }

    // Event listeners
    adultsInput.addEventListener('change', updatePassengerForms);
    childrenInput.addEventListener('change', updatePassengerForms);
    infantsInput.addEventListener('change', updatePassengerForms);

    // Initialize forms
    updatePassengerForms();

    // Form validation
    document.getElementById('booking-form').addEventListener('submit', function(e) {
        const total = parseInt(adultsInput.value) + parseInt(childrenInput.value) + parseInt(infantsInput.value);
        if (total === 0) {
            e.preventDefault();
            alert('Please select at least one passenger');
            return false;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
        submitBtn.disabled = true;
    });
});
</script>

@endsection

