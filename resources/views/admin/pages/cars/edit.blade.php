@extends('admin.layouts.app')

@section('title', 'Edit Car')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.cars') }}">Cars</a></li>
                        <li class="breadcrumb-item active">Edit Car</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Car</h4>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Car Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cars.update', $id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="make" class="form-label">Make <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('make') is-invalid @enderror" 
                                           id="make" name="make" value="{{ old('make', 'Toyota') }}" required>
                                    @error('make')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="model" class="form-label">Model <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                           id="model" name="model" value="{{ old('model', 'Camry') }}" required>
                                    @error('model')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="year" class="form-label">Year <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('year') is-invalid @enderror" 
                                           id="year" name="year" value="{{ old('year', '2023') }}" min="2000" max="2024" required>
                                    @error('year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="sedan" {{ old('type', 'sedan') == 'sedan' ? 'selected' : '' }}>Sedan</option>
                                        <option value="suv" {{ old('type', 'sedan') == 'suv' ? 'selected' : '' }}>SUV</option>
                                        <option value="hatchback" {{ old('type', 'sedan') == 'hatchback' ? 'selected' : '' }}>Hatchback</option>
                                        <option value="coupe" {{ old('type', 'sedan') == 'coupe' ? 'selected' : '' }}>Coupe</option>
                                        <option value="convertible" {{ old('type', 'sedan') == 'convertible' ? 'selected' : '' }}>Convertible</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="price_per_day" class="form-label">Price per Day <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control @error('price_per_day') is-invalid @enderror" 
                                               id="price_per_day" name="price_per_day" value="{{ old('price_per_day', '50') }}" step="0.01" required>
                                        @error('price_per_day')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fuel_type" class="form-label">Fuel Type</label>
                                    <select class="form-select @error('fuel_type') is-invalid @enderror" id="fuel_type" name="fuel_type">
                                        <option value="">Select Fuel Type</option>
                                        <option value="gasoline" {{ old('fuel_type', 'gasoline') == 'gasoline' ? 'selected' : '' }}>Gasoline</option>
                                        <option value="diesel" {{ old('fuel_type', 'gasoline') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                        <option value="hybrid" {{ old('fuel_type', 'gasoline') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                        <option value="electric" {{ old('fuel_type', 'gasoline') == 'electric' ? 'selected' : '' }}>Electric</option>
                                    </select>
                                    @error('fuel_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="transmission" class="form-label">Transmission</label>
                                    <select class="form-select @error('transmission') is-invalid @enderror" id="transmission" name="transmission">
                                        <option value="">Select Transmission</option>
                                        <option value="manual" {{ old('transmission', 'automatic') == 'manual' ? 'selected' : '' }}>Manual</option>
                                        <option value="automatic" {{ old('transmission', 'automatic') == 'automatic' ? 'selected' : '' }}>Automatic</option>
                                    </select>
                                    @error('transmission')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="features" class="form-label">Features</label>
                                    <textarea class="form-control @error('features') is-invalid @enderror" 
                                              id="features" name="features" rows="3" 
                                              placeholder="GPS, Bluetooth, Air Conditioning...">{{ old('features', 'GPS, Bluetooth, Air Conditioning, USB Port') }}</textarea>
                                    @error('features')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="images" class="form-label">Car Images</label>
                                    <input type="file" class="form-control @error('images') is-invalid @enderror" 
                                           id="images" name="images[]" multiple accept="image/*">
                                    @error('images')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">You can select multiple images. Leave empty to keep existing images.</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="5" required>{{ old('description', 'This is a sample car description. Replace with actual car details.') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (Car will be visible to customers)
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy"></i> Update Car
                            </button>
                            <a href="{{ route('admin.cars') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
