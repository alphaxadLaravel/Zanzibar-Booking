@extends('admin.layouts.app')

@section('title', 'Add New Hotel')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.hotels') }}">Hotels</a></li>
                        <li class="breadcrumb-item active">Add New Hotel</li>
                    </ol>
                </div>
                <h4 class="page-title">Add New Hotel</h4>
            </div>
        </div>
    </div>

    <!-- Step Indicator -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="step-item active clickable" onclick="jumpToStep(1)">
                            <div class="step-number">1</div>
                            <div class="step-label">General</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-item clickable" onclick="jumpToStep(2)">
                            <div class="step-number">2</div>
                            <div class="step-label">Location</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-item clickable" onclick="jumpToStep(3)">
                            <div class="step-number">3</div>
                            <div class="step-label">Pricing</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-item clickable" onclick="jumpToStep(4)">
                            <div class="step-number">4</div>
                            <div class="step-label">Amenities</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-item clickable" onclick="jumpToStep(5)">
                            <div class="step-number">5</div>
                            <div class="step-label">Media</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-item clickable" onclick="jumpToStep(6)">
                            <div class="step-number">6</div>
                            <div class="step-label">Policies</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Hotel Information - Step 1: General</h5>
                </div>
                <div class="card-body">
                    <form id="hotelForm" action="{{ route('admin.hotels.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Step 1: General Information -->
                        <div class="step-content" id="step1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Hotel Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="rating" class="form-label">Rating</label>
                                        <select class="form-select @error('rating') is-invalid @enderror" id="rating" name="rating">
                                            <option value="">Select Rating</option>
                                            <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                                            <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                                            <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                                            <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                                            <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                                        </select>
                                        @error('rating')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Step 2: Location -->
                        <div class="step-content d-none" id="step2">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                               id="location" name="location" value="{{ old('location') }}" required>
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Full Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                                  id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="latitude" class="form-label">Latitude</label>
                                        <input type="number" class="form-control @error('latitude') is-invalid @enderror" 
                                               id="latitude" name="latitude" value="{{ old('latitude') }}" step="any">
                                        @error('latitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="longitude" class="form-label">Longitude</label>
                                        <input type="number" class="form-control @error('longitude') is-invalid @enderror" 
                                               id="longitude" name="longitude" value="{{ old('longitude') }}" step="any">
                                        @error('longitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Pricing -->
                        <div class="step-content d-none" id="step3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Price per Night <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                                   id="price" name="price" value="{{ old('price') }}" step="0.01" required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="currency" class="form-label">Currency</label>
                                        <select class="form-select @error('currency') is-invalid @enderror" id="currency" name="currency">
                                            <option value="USD" {{ old('currency', 'USD') == 'USD' ? 'selected' : '' }}>USD</option>
                                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                            <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP</option>
                                            <option value="JPY" {{ old('currency') == 'JPY' ? 'selected' : '' }}>JPY</option>
                                        </select>
                                        @error('currency')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Amenities -->
                        <div class="step-content d-none" id="step4">
                            <div class="mb-3">
                                <label class="form-label">Hotel Amenities</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="wifi" name="amenities[]" value="wifi" {{ in_array('wifi', old('amenities', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="wifi">Free WiFi</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="pool" name="amenities[]" value="pool" {{ in_array('pool', old('amenities', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="pool">Swimming Pool</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="gym" name="amenities[]" value="gym" {{ in_array('gym', old('amenities', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="gym">Fitness Center</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="restaurant" name="amenities[]" value="restaurant" {{ in_array('restaurant', old('amenities', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="restaurant">Restaurant</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="spa" name="amenities[]" value="spa" {{ in_array('spa', old('amenities', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="spa">Spa</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="parking" name="amenities[]" value="parking" {{ in_array('parking', old('amenities', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="parking">Parking</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="concierge" name="amenities[]" value="concierge" {{ in_array('concierge', old('amenities', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="concierge">Concierge</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="room_service" name="amenities[]" value="room_service" {{ in_array('room_service', old('amenities', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="room_service">Room Service</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="business_center" name="amenities[]" value="business_center" {{ in_array('business_center', old('amenities', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="business_center">Business Center</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Media -->
                        <div class="step-content d-none" id="step5">
                            <div class="mb-3">
                                <label for="images" class="form-label">Hotel Images</label>
                                <input type="file" class="form-control @error('images') is-invalid @enderror" 
                                       id="images" name="images[]" multiple accept="image/*">
                                @error('images')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">You can select multiple images</div>
                            </div>
                        </div>

                        <!-- Step 6: Policies -->
                        <div class="step-content d-none" id="step6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="contact_email" class="form-label">Contact Email</label>
                                        <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                               id="contact_email" name="contact_email" value="{{ old('contact_email') }}">
                                        @error('contact_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="contact_phone" class="form-label">Contact Phone</label>
                                        <input type="tel" class="form-control @error('contact_phone') is-invalid @enderror" 
                                               id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}">
                                        @error('contact_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active (Hotel will be visible to customers)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary" id="prevBtn" onclick="changeStep(-1)" style="display: none;">
                                <i class="ti ti-arrow-left"></i> Previous
                            </button>
                            <div class="ms-auto">
                                <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeStep(1)">
                                    Next <i class="ti ti-arrow-right"></i>
                                </button>
                                <button type="submit" class="btn btn-success d-none" id="submitBtn">
                                    <i class="ti ti-device-floppy"></i> Save Hotel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.step-item {
    text-align: center;
    flex: 1;
}

.step-item.clickable {
    cursor: pointer;
    transition: all 0.3s ease;
}

.step-item.clickable:hover {
    transform: translateY(-2px);
}

.step-item.clickable:hover .step-number {
    background-color: #0b5ed7;
    color: white;
    transform: scale(1.1);
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
    font-weight: bold;
    transition: all 0.3s ease;
}

.step-item.active .step-number {
    background-color: #0d6efd;
    color: white;
}

.step-item.completed .step-number {
    background-color: #198754;
    color: white;
}

.step-label {
    font-size: 14px;
    color: #6c757d;
    font-weight: 500;
    transition: color 0.3s ease;
}

.step-item.active .step-label {
    color: #0d6efd;
    font-weight: 600;
}

.step-item.completed .step-label {
    color: #198754;
    font-weight: 600;
}

.step-item.clickable:hover .step-label {
    color: #0b5ed7;
    font-weight: 600;
}

.step-line {
    flex: 1;
    height: 2px;
    background-color: #e9ecef;
    margin: 0 10px;
    margin-top: 20px;
    transition: background-color 0.3s ease;
}

.step-line.completed {
    background-color: #198754;
}

.step-content {
    min-height: 400px;
}
</style>

<script>
let currentStep = 1;
const totalSteps = 6;

function changeStep(direction) {
    const prevStep = currentStep;
    currentStep += direction;
    
    if (currentStep < 1) currentStep = 1;
    if (currentStep > totalSteps) currentStep = totalSteps;
    
    // Update step indicators
    updateStepIndicators();
    
    // Show/hide step content
    document.getElementById(`step${prevStep}`).classList.add('d-none');
    document.getElementById(`step${currentStep}`).classList.remove('d-none');
    
    // Update navigation buttons
    updateNavigationButtons();
}

function jumpToStep(stepNumber) {
    if (stepNumber < 1 || stepNumber > totalSteps) return;
    
    const prevStep = currentStep;
    currentStep = stepNumber;
    
    // Update step indicators
    updateStepIndicators();
    
    // Show/hide step content
    document.getElementById(`step${prevStep}`).classList.add('d-none');
    document.getElementById(`step${currentStep}`).classList.remove('d-none');
    
    // Update navigation buttons
    updateNavigationButtons();
    
    // Update card header title
    updateCardHeader();
}

function updateStepIndicators() {
    for (let i = 1; i <= totalSteps; i++) {
        const stepItem = document.querySelector(`.step-item:nth-child(${i * 2 - 1})`);
        const stepLine = document.querySelector(`.step-line:nth-child(${i * 2})`);
        
        // Remove all classes
        stepItem.classList.remove('active', 'completed');
        if (stepLine) stepLine.classList.remove('completed');
        
        if (i < currentStep) {
            // Completed steps
            stepItem.classList.add('completed');
            if (stepLine) stepLine.classList.add('completed');
        } else if (i === currentStep) {
            // Current step
            stepItem.classList.add('active');
        }
    }
}

function updateNavigationButtons() {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    prevBtn.style.display = currentStep === 1 ? 'none' : 'block';
    
    if (currentStep === totalSteps) {
        nextBtn.classList.add('d-none');
        submitBtn.classList.remove('d-none');
    } else {
        nextBtn.classList.remove('d-none');
        submitBtn.classList.add('d-none');
    }
}

function updateCardHeader() {
    const stepNames = ['General', 'Location', 'Pricing', 'Amenities', 'Media', 'Policies'];
    const cardTitle = document.querySelector('.card-header h5');
    cardTitle.textContent = `Hotel Information - Step ${currentStep}: ${stepNames[currentStep - 1]}`;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateStepIndicators();
    updateNavigationButtons();
    updateCardHeader();
});
</script>
@endsection
