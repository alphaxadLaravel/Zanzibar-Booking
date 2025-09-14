@extends('admin.layouts.app')

@section('title', 'Add New Hotel Room')

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
                        <li class="breadcrumb-item"><a href="{{ route('admin.hotels.rooms', $hotel_id) }}">Rooms</a></li>
                        <li class="breadcrumb-item active">Add New Room</li>
                    </ol>
                </div>
                <h4 class="page-title">Add New Hotel Room</h4>
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
                    <h5 class="card-title mb-0">Room Information - Step 1: General</h5>
                </div>
                <div class="card-body">
                    <form id="roomForm" action="{{ route('admin.hotels.rooms.store', $hotel_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Step 1: General Information -->
                        <div class="step-content" id="step1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="room_number" class="form-label">Room Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('room_number') is-invalid @enderror" 
                                               id="room_number" name="room_number" value="{{ old('room_number') }}" required>
                                        @error('room_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="room_type" class="form-label">Room Type <span class="text-danger">*</span></label>
                                        <select class="form-select @error('room_type') is-invalid @enderror" id="room_type" name="room_type" required>
                                            <option value="">Select Room Type</option>
                                            <option value="standard" {{ old('room_type') == 'standard' ? 'selected' : '' }}>Standard Room</option>
                                            <option value="deluxe" {{ old('room_type') == 'deluxe' ? 'selected' : '' }}>Deluxe Room</option>
                                            <option value="suite" {{ old('room_type') == 'suite' ? 'selected' : '' }}>Suite</option>
                                            <option value="family" {{ old('room_type') == 'family' ? 'selected' : '' }}>Family Room</option>
                                            <option value="presidential" {{ old('room_type') == 'presidential' ? 'selected' : '' }}>Presidential Suite</option>
                                        </select>
                                        @error('room_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_adults" class="form-label">Max Adults <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('max_adults') is-invalid @enderror" 
                                               id="max_adults" name="max_adults" value="{{ old('max_adults') }}" min="1" max="10" required>
                                        @error('max_adults')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_children" class="form-label">Max Children</label>
                                        <input type="number" class="form-control @error('max_children') is-invalid @enderror" 
                                               id="max_children" name="max_children" value="{{ old('max_children') }}" min="0" max="10">
                                        @error('max_children')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="room_size" class="form-label">Room Size (sq ft)</label>
                                <input type="number" class="form-control @error('room_size') is-invalid @enderror" 
                                       id="room_size" name="room_size" value="{{ old('room_size') }}" min="1">
                                @error('room_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Room Description <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
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
                                        <label for="floor" class="form-label">Floor <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('floor') is-invalid @enderror" 
                                               id="floor" name="floor" value="{{ old('floor') }}" min="1" max="50" required>
                                        @error('floor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="view_type" class="form-label">View Type</label>
                                        <select class="form-select @error('view_type') is-invalid @enderror" id="view_type" name="view_type">
                                            <option value="">Select View Type</option>
                                            <option value="city" {{ old('view_type') == 'city' ? 'selected' : '' }}>City View</option>
                                            <option value="ocean" {{ old('view_type') == 'ocean' ? 'selected' : '' }}>Ocean View</option>
                                            <option value="garden" {{ old('view_type') == 'garden' ? 'selected' : '' }}>Garden View</option>
                                            <option value="mountain" {{ old('view_type') == 'mountain' ? 'selected' : '' }}>Mountain View</option>
                                            <option value="pool" {{ old('view_type') == 'pool' ? 'selected' : '' }}>Pool View</option>
                                        </select>
                                        @error('view_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="bed_type" class="form-label">Bed Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('bed_type') is-invalid @enderror" id="bed_type" name="bed_type" required>
                                    <option value="">Select Bed Type</option>
                                    <option value="single" {{ old('bed_type') == 'single' ? 'selected' : '' }}>Single Bed</option>
                                    <option value="double" {{ old('bed_type') == 'double' ? 'selected' : '' }}>Double Bed</option>
                                    <option value="queen" {{ old('bed_type') == 'queen' ? 'selected' : '' }}>Queen Bed</option>
                                    <option value="king" {{ old('bed_type') == 'king' ? 'selected' : '' }}>King Bed</option>
                                    <option value="twin" {{ old('bed_type') == 'twin' ? 'selected' : '' }}>Twin Beds</option>
                                </select>
                                @error('bed_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Step 3: Pricing -->
                        <div class="step-content d-none" id="step3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="base_price" class="form-label">Base Price per Night <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control @error('base_price') is-invalid @enderror" 
                                                   id="base_price" name="base_price" value="{{ old('base_price') }}" step="0.01" required>
                                            @error('base_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="weekend_price" class="form-label">Weekend Price per Night</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control @error('weekend_price') is-invalid @enderror" 
                                                   id="weekend_price" name="weekend_price" value="{{ old('weekend_price') }}" step="0.01">
                                            @error('weekend_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="extra_bed_price" class="form-label">Extra Bed Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control @error('extra_bed_price') is-invalid @enderror" 
                                                   id="extra_bed_price" name="extra_bed_price" value="{{ old('extra_bed_price') }}" step="0.01">
                                            @error('extra_bed_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="cleaning_fee" class="form-label">Cleaning Fee</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control @error('cleaning_fee') is-invalid @enderror" 
                                                   id="cleaning_fee" name="cleaning_fee" value="{{ old('cleaning_fee') }}" step="0.01">
                                            @error('cleaning_fee')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Amenities -->
                        <div class="step-content d-none" id="step4">
                            <div class="mb-3">
                                <label class="form-label">Room Amenities</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="wifi" name="amenities[]" value="wifi" {{ in_array('wifi', old('amenities', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="wifi">Free WiFi</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="tv" name="amenities[]" value="tv" {{ in_array('tv', old('amenities', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="tv">TV</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="ac" name="amenities[]" value="ac" {{ in_array('ac', old('amenities', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="ac">Air Conditioning</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="minibar" name="amenities[]" value="minibar" {{ in_array('minibar', old('amenities', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="minibar">Minibar</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="safe" name="amenities[]" value="safe" {{ in_array('safe', old('amenities', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="safe">Safe</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="balcony" name="amenities[]" value="balcony" {{ in_array('balcony', old('amenities', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="balcony">Balcony</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="kitchenette" name="amenities[]" value="kitchenette" {{ in_array('kitchenette', old('amenities', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="kitchenette">Kitchenette</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="jacuzzi" name="amenities[]" value="jacuzzi" {{ in_array('jacuzzi', old('amenities', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="jacuzzi">Jacuzzi</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="workspace" name="amenities[]" value="workspace" {{ in_array('workspace', old('amenities', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="workspace">Workspace</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Media -->
                        <div class="step-content d-none" id="step5">
                            <div class="mb-3">
                                <label for="room_images" class="form-label">Room Images</label>
                                <input type="file" class="form-control @error('room_images') is-invalid @enderror" 
                                       id="room_images" name="room_images[]" multiple accept="image/*">
                                @error('room_images')
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
                                        <label for="check_in_time" class="form-label">Check-in Time</label>
                                        <input type="time" class="form-control @error('check_in_time') is-invalid @enderror" 
                                               id="check_in_time" name="check_in_time" value="{{ old('check_in_time', '15:00') }}">
                                        @error('check_in_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="check_out_time" class="form-label">Check-out Time</label>
                                        <input type="time" class="form-control @error('check_out_time') is-invalid @enderror" 
                                               id="check_out_time" name="check_out_time" value="{{ old('check_out_time', '11:00') }}">
                                        @error('check_out_time')
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
                                        Active (Room will be available for booking)
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
                                    <i class="ti ti-device-floppy"></i> Save Room
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
    
    // Update card header title
    updateCardHeader();
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
    cardTitle.textContent = `Room Information - Step ${currentStep}: ${stepNames[currentStep - 1]}`;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateStepIndicators();
    updateNavigationButtons();
    updateCardHeader();
});
</script>
@endsection
