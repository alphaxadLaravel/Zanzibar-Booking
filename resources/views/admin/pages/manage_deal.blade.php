@extends('admin.layouts.app')

@section('title', isset($deal) ? 'Edit Deal' : 'Add New Deal')

@section('content')
<div class="container-fluid">

    <div class="d-flex flex-wrap align-items-center justify-content-between my-3">
        <div>
            <h4 class="page-title mb-0">{{ ucfirst($type) }} Management</h4>
        </div>
        <div>
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">{{ ucfirst($type) }} Management</li>
            </ol>
        </div>
    </div>

    @include('admin.layouts.alerts')

    <form id="dealForm"
        action="{{ isset($deal) ? route('admin.manage-deal.update', [$deal->id, $type]) : route('admin.manage-deal.store', $type) }}"
        method="POST" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @if(isset($deal))
        @method('PUT')
        @endif
        <!-- Deal Details Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>
                    {{ ucfirst($type) }} Details</h5>
            </div>
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title" value="{{ old('title', $deal->title ?? '') }}"
                        placeholder="Enter deal title">
                    @error('title')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Category <span class="text-danger">*</span></label>
                    <select class="form-select" name="category_id" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (old('category_id', $deal->category_id ?? '') ==
                            $category->id) ? 'selected' : '' }}>
                            {{ $category->category }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Base Price <span class="text-danger">*</span></label>
                    <div class="input-group mb-3">
                        <input type="number" class="form-control" name="base_price"
                            value="{{ old('base_price', $deal->base_price ?? '') }}" step="0.01"
                            placeholder="Enter base price">
                        <div class="input-group-append">
                            <span class="input-group-text">USD</span>
                        </div>
                    </div>
                    @error('base_price')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Star Rating <span class="text-danger">*</span></label>
                    <select class="form-select" name="star_rating" required>
                        <option value="">Select Star Rating</option>
                        <option value="1" {{ (old('star_rating', $deal->star_rating ?? '') == '1') ? 'selected' : '' }}>1 Star</option>
                        <option value="2" {{ (old('star_rating', $deal->star_rating ?? '') == '2') ? 'selected' : '' }}>2 Stars</option>
                        <option value="3" {{ (old('star_rating', $deal->star_rating ?? '') == '3') ? 'selected' : '' }}>3 Stars</option>
                        <option value="4" {{ (old('star_rating', $deal->star_rating ?? '') == '4') ? 'selected' : '' }}>4 Stars</option>
                        <option value="5" {{ (old('star_rating', $deal->star_rating ?? '') == '5') ? 'selected' : '' }}>5 Stars</option>
                    </select>
                    @error('star_rating')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <div>
                        <textarea id="description" rows="4" class="form-control d-none" name="description"
                            placeholder="Enter description">{{ old('description', $deal->description ?? '') }}</textarea>
                        <div id="description-editor"></div>
                    </div>
                    @error('description')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label">Policies</label>
                    <div>
                        <textarea id="policies" rows="3" class="form-control d-none" name="policies"
                            placeholder="Enter policies">{{ old('policies', $deal->policies ?? '') }}</textarea>
                        <div id="policies-editor"></div>
                    </div>
                    @error('policies')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                @if($type == 'package')
                <div class="col-md-4">
                    <label class="form-label">Package Period <span class="text-danger">*</span></label>
                    <div class="input-group mb-3">
                        <input type="number" class="form-control" name="tour_period"
                            value="{{ old('tour_period', $typeSpecificData['tour']->period ?? '') }}"
                            placeholder="Enter number of days" min="1" required>
                        <div class="input-group-append">
                            <span class="input-group-text">DAY(S)</span>
                        </div>
                    </div>
                    @error('tour_period')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                @endif
                @if($type == 'activity')
                <input type="hidden" name="tour_period" value="1">
                @endif
                @if($type == 'package' || $type == 'activity')
                <div class="col-md-4">
                    <label class="form-label">Max People <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="max_people"
                        value="{{ old('max_people', $typeSpecificData['tour']->max_people ?? '') }}" min="1"
                        placeholder="e.g. 10" required>
                    @error('max_people')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Adult Price <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="adult_price"
                        value="{{ old('adult_price', $typeSpecificData['tour']->adult_price ?? '') }}" step="0.01"
                        placeholder="e.g. 100.00" required>
                    @error('adult_price')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Child Price <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="child_price"
                        value="{{ old('child_price', $typeSpecificData['tour']->child_price ?? '') }}" step="0.01"
                        placeholder="e.g. 50.00" required>
                    @error('child_price')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                @endif

                @if($type == 'car')
                <div class="col-md-3">
                    <label class="form-label">Car Capacity <span class="text-danger">*</span></label>
                    <div class="input-group mb-3">
                        <input type="number" class="form-control" name="car_capacity"
                            value="{{ old('car_capacity', $typeSpecificData['car']->capacity ?? '') }}" min="1"
                            placeholder="e.g. 4" required>
                        <div class="input-group-append">
                            <span class="input-group-text">People</span>
                        </div>
                    </div>
                    @error('car_capacity')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Transmission <span class="text-danger">*</span></label>
                    <select class="form-control" name="transmission" required>
                        <option value="">Select Transmission</option>
                        <option value="manual" {{ (old('transmission', $typeSpecificData['car']->transmission ?? '') ==
                            'manual') ? 'selected' : '' }}>Manual</option>
                        <option value="automatic" {{ (old('transmission', $typeSpecificData['car']->transmission ?? '')
                            == 'automatic') ? 'selected' : '' }}>Automatic</option>
                    </select>
                    @error('transmission')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fuel <span class="text-danger">*</span></label>
                    <select class="form-control" name="fuel" required>
                        <option value="">Select Fuel Type</option>
                        <option value="petrol" {{ (old('fuel', $typeSpecificData['car']->fuel ?? '') == 'petrol') ?
                            'selected' : '' }}>Petrol</option>
                        <option value="diesel" {{ (old('fuel', $typeSpecificData['car']->fuel ?? '') == 'diesel') ?
                            'selected' : '' }}>Diesel</option>
                        <option value="electric" {{ (old('fuel', $typeSpecificData['car']->fuel ?? '') == 'electric') ?
                            'selected' : '' }}>Electric</option>
                        <option value="hybrid" {{ (old('fuel', $typeSpecificData['car']->fuel ?? '') == 'hybrid') ?
                            'selected' : '' }}>Hybrid</option>
                    </select>
                    @error('fuel')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Air Condition</label>
                    <select class="form-control" name="air_condition">
                        <option value="0" {{ (old('air_condition', $typeSpecificData['car']->air_condition ?? '0') ==
                            '0') ? 'selected' : '' }}>No</option>
                        <option value="1" {{ (old('air_condition', $typeSpecificData['car']->air_condition ?? '0') ==
                            '1') ? 'selected' : '' }}>Yes</option>
                    </select>
                    @error('air_condition')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">GPS</label>
                    <select class="form-control" name="gps">
                        <option value="0" {{ (old('gps', $typeSpecificData['car']->gps ?? '0') == '0') ? 'selected' : ''
                            }}>No</option>
                        <option value="1" {{ (old('gps', $typeSpecificData['car']->gps ?? '0') == '1') ? 'selected' : ''
                            }}>Yes</option>
                    </select>
                    @error('gps')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label">Car Contract Document</label>
                    <input type="file" class="form-control" name="car_contract_document"
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    @if(isset($typeSpecificData['car']) && $typeSpecificData['car']->terms)
                    <small class="text-muted">Current: <a href="{{ Storage::url($typeSpecificData['car']->terms) }}"
                            target="_blank">View Document</a></small>
                    @endif
                </div>
                @endif

            </div>
        </div>

        @if ($type == 'car' || $type == 'hotel' || $type == 'apartment')
        <div class="card mb-4">
            <div class="card-header">
                <h5>{{ ucfirst($type) }} Features</h5>
            </div>
            <div class="card-body row g-3">
                <div class="col-12">
                    @if($features->count() > 0)
                    <div class="row">
                        @foreach($features as $feature)
                        <div class="col-md-4 mb-2">
                            <div class="form-check d-flex align-items-center" style="cursor: pointer;">
                                <input class="form-check-input me-2" type="checkbox" name="features[]"
                                    value="{{ $feature->id }}" id="feature-{{ $feature->id }}" {{ in_array($feature->id,
                                old('features', isset($deal) ? $deal->features->pluck('id')->toArray() : [])) ?
                                'checked' : '' }}>
                                <label class="form-check-label d-flex align-items-center"
                                    for="feature-{{ $feature->id }}" style="cursor: pointer;">
                                    <i class="mdi {{ $feature->icon }} me-2"
                                        style="font-size: 1.2rem; cursor: pointer;"></i>
                                    {{ $feature->name }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="mdi mdi-information-outline me-2"></i>
                        No {{ $type }} features available. <a href="{{ route('admin.features') }}"
                            class="alert-link">Add some features</a> first.
                    </div>
                    @endif
                    @error('features')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        @endif


        @if ($type == 'package' || $type == 'activity')
        <div class="card mb-4">
            <div class="card-header">
                <h5>{{ ucfirst($type) }} Includes (Count: {{ $tourIncludes->count() }})</h5>
            </div>
            <div class="card-body row g-3">
                <div class="col-12">
                    @if($tourIncludes->count() > 0)
                    <div class="row">
                        @foreach($tourIncludes as $include)
                        <div class="col-md-4 mb-2">
                            <div class="form-check d-flex align-items-center" style="cursor: pointer;">
                                <input class="form-check-input me-2" type="checkbox" name="tour_includes[]"
                                    value="{{ $include->id }}" id="tour-include-{{ $include->id }}" {{
                                    in_array($include->id, old('tour_includes',
                                isset($typeSpecificData['tour_includes']) ?
                                $typeSpecificData['tour_includes']->pluck('feature_id')->toArray() : [])) ? 'checked' :
                                ''
                                }}>
                                <label class="form-check-label d-flex align-items-center"
                                    for="tour-include-{{ $include->id }}" style="cursor: pointer;">
                                    <i class="mdi {{ $include->icon }} me-2"
                                        style="font-size: 1.2rem; cursor: pointer;"></i>
                                    {{ $include->name }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="mdi mdi-information-outline me-2"></i>
                        No {{ $type }} includes available. <a href="{{ route('admin.features') }}" class="alert-link">Add some
                            include features</a> first.
                    </div>
                    @endif
                    @error('tour_includes')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5>{{ ucfirst($type) }} Excludes (Count: {{ $tourExcludes->count() }})</h5>
            </div>
            <div class="card-body row g-3">
                <div class="col-12">
                    @if($tourExcludes->count() > 0)
                    <div class="row">
                        @foreach($tourExcludes as $exclude)
                        <div class="col-md-4 mb-2">
                            <div class="form-check d-flex align-items-center" style="cursor: pointer;">
                                <input class="form-check-input me-2" type="checkbox" name="tour_excludes[]"
                                    value="{{ $exclude->id }}" id="tour-exclude-{{ $exclude->id }}" {{
                                    in_array($exclude->id, old('tour_excludes',
                                isset($typeSpecificData['tour_excludes']) ?
                                $typeSpecificData['tour_excludes']->pluck('feature_id')->toArray() : [])) ? 'checked' :
                                ''
                                }}>
                                <label class="form-check-label d-flex align-items-center"
                                    for="tour-exclude-{{ $exclude->id }}" style="cursor: pointer;">
                                    <i class="mdi {{ $exclude->icon }} me-2"
                                        style="font-size: 1.2rem; cursor: pointer;"></i>
                                    {{ $exclude->name }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="mdi mdi-information-outline me-2"></i>
                        No {{ $type }} excludes available. <a href="{{ route('admin.features') }}" class="alert-link">Add some
                            exclude features</a> first.
                    </div>
                    @endif
                    @error('tour_excludes')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        @endif

        <!-- Location Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Location</h5>
            </div>
            <div class="card-body row g-3">
                <div class="col-md-12">
                    <label class="form-label">Location (Search on Map)</label>
                    <input type="text" class="form-control" id="location-input" name="location"
                        value="{{ old('location', $deal->location ?? '') }}" placeholder="Search for a place...">
                    @error('location')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Latitude</label>
                    <input type="text" class="form-control" id="lat-input" name="lat"
                        value="{{ old('lat', $deal->lat ?? '') }}" placeholder="Latitude" readonly>
                    @error('lat')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Longitude</label>
                    <input type="text" class="form-control" id="lng-input" name="long"
                        value="{{ old('long', $deal->long ?? '') }}" placeholder="Longitude" readonly>
                    @error('long')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label">Map Location (Address)</label>
                    <input type="text" class="form-control" id="map-location-input" name="map_location"
                        value="{{ old('map_location', $deal->map_location ?? '') }}" placeholder="Map location address"
                        readonly>
                    @error('map_location')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <div id="map" style="height: 300px; width: 100%; background: #eaeaea; border-radius: 8px;"></div>
                </div>
            </div>
        </div>

        <!-- Images Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Images</h5>
            </div>
            <div class="card-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Cover Photo @if(!isset($deal))<span
                            class="text-danger">*</span>@endif</label>
                    <input type="file" class="form-control" name="cover_photo" id="cover-photo-input" accept="image/*"
                        {{ !isset($deal) ? 'required' : '' }}>
                    <div class="mt-2" id="cover-photo-preview">
                        @if(isset($deal) && $deal->cover_photo)
                        <img src="{{ Storage::url($deal->cover_photo) }}" alt="Cover Preview"
                            style="width:100px; height:75px; object-fit:cover; border-radius:4px;">
                        @else
                        <!-- Placeholder image preview, will be replaced on file select -->
                        <img src="https://media.istockphoto.com/id/1147544807/vector/thumbnail-image-vector-graphic.jpg?s=612x612&w=0&k=20&c=rnCKVbdxqkjlcs3xH87-9gocETqpspHFXu5dIGB4wuM="
                            alt="Cover Preview" style="width:100px; height:75px; object-fit:cover; border-radius:4px;">
                        @endif
                    </div>
                    @error('cover_photo')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label">Other Images</label>
                    <div class="border rounded p-3" style="min-height: 120px; background: #f8f9fa;">
                        <input type="file" class="form-control mb-2" name="other_images[]" id="other-images-input"
                            accept="image/*" multiple>
                        <div class="d-flex flex-wrap gap-2" id="other-images-preview">
                            @if(isset($deal) && $deal->photos->count() > 0)
                            @foreach($deal->photos as $photo)
                            <img src="{{ Storage::url($photo->photo) }}" alt="Deal Photo"
                                style="width:100px; height:75px; object-fit:cover; border-radius:4px;">
                            @endforeach
                            @else
                            @for ($i = 0; $i < 6; $i++) <img
                                src="https://media.istockphoto.com/id/1147544807/vector/thumbnail-image-vector-graphic.jpg?s=612x612&w=0&k=20&c=rnCKVbdxqkjlcs3xH87-9gocETqpspHFXu5dIGB4wuM="
                                alt="Image {{ $i + 1 }}"
                                style="width:100px; height:75px; object-fit:cover; border-radius:4px;">
                                @endfor
                                @endif
                        </div>
                    </div>
                    <small class="text-muted">You can drop or select multiple images.</small>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Video Link</label>
                    <input type="url" class="form-control" name="video_link"
                        value="{{ old('video_link', $deal->video_link ?? '') }}"
                        placeholder="Enter video URL (YouTube, Vimeo, etc.)">
                    <small class="text-muted">Add a promotional or showcase video for this deal. Supports YouTube,
                        Vimeo, and other video platforms.</small>
                    @error('video_link')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        @if($type === 'hotel')
        <!-- Nearby Locations Card -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Nearby Locations</h5>
                <button type="button" class="btn btn-sm btn-success" id="add-nearby-location">
                    <i class="mdi mdi-plus"></i> Add Location
                </button>
            </div>
            <div class="card-body">
                <div id="nearby-locations-container">
                    @php
                    // Get old input data for nearby locations
                    $oldNearbyLocations = old('nearby_locations', []);
                    $existingLocations = isset($deal) ? $deal->nearbyLocations->toArray() : [];

                    // Merge old input with existing data
                    $allLocations = [];
                    $maxIndex = max(count($oldNearbyLocations), count($existingLocations));

                    for ($i = 0; $i < $maxIndex; $i++) { if (isset($oldNearbyLocations[$i])) {
                        $allLocations[$i]=$oldNearbyLocations[$i]; } elseif (isset($existingLocations[$i])) {
                        $allLocations[$i]=$existingLocations[$i]; } } @endphp @if(count($allLocations)> 0)
                        @foreach($allLocations as $index => $location)
                        <div class="nearby-location-item border rounded p-3 mb-3" data-index="{{ $index }}">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nearby_locations[{{ $index }}][title]"
                                        value="{{ $location['title'] ?? '' }}" placeholder="e.g., Zanzibar Airport"
                                        required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select" name="nearby_locations[{{ $index }}][category]"
                                        required>
                                        <option value="">Select Category</option>
                                        @foreach(\App\Models\NearbyLocation::getAvailableCategories() as $key => $value)
                                        <option value="{{ $key }}" {{ ($location['category'] ?? '' )==$key ? 'selected'
                                            : '' }}>
                                            {{ $value }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Distance (km) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control"
                                        name="nearby_locations[{{ $index }}][distance_km]"
                                        value="{{ $location['distance_km'] ?? '' }}" step="0.1" min="0"
                                        placeholder="e.g., 2.5" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Actions</label>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-danger remove-nearby-location">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="text-center text-muted py-4">
                            <i class="mdi mdi-map-marker fa-2x mb-2"></i>
                            <p>No nearby locations added yet. Click "Add Location" to get started.</p>
                        </div>
                        @endif
                </div>
            </div>
        </div>
        @endif

        <!-- SEO Section Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>SEO Settings</h5>
                <small class="text-muted">Configure SEO meta tags for better search engine visibility</small>
            </div>
            <div class="card-body row g-3">
                <div class="col-md-12">
                    <label class="form-label">SEO Title</label>
                    <input type="text" class="form-control" name="seo_title"
                        value="{{ old('seo_title', $deal->seo_title ?? '') }}"
                        placeholder="Enter SEO title (recommended: 50-60 characters)" maxlength="60">
                    <small class="text-muted">Leave empty to use deal title. Optimal length: 50-60 characters</small>
                    @error('seo_title')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label">SEO Description</label>
                    <textarea class="form-control" name="seo_description" rows="3"
                        placeholder="Enter SEO description (recommended: 150-160 characters)"
                        maxlength="160">{{ old('seo_description', $deal->seo_description ?? '') }}</textarea>
                    <small class="text-muted">Leave empty to use deal description. Optimal length: 150-160
                        characters</small>
                    @error('seo_description')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label">SEO Keywords</label>
                    <textarea class="form-control" name="seo_keywords" rows="2"
                        placeholder="Enter keywords separated by commas (e.g., hotel, beach, resort, luxury)">{{ old('seo_keywords', $deal->seo_keywords ?? '') }}</textarea>
                    <small class="text-muted">Separate keywords with commas. Focus on relevant terms for this
                        deal</small>
                    @error('seo_keywords')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label">SEO Image</label>
                    <input type="file" class="form-control" name="seo_image" accept="image/*">
                    <small class="text-muted">Recommended: 1200x630px for social media sharing. Leave empty to use cover
                        photo</small>
                    @if(isset($deal) && $deal->seo_image)
                    <div class="mt-2">
                        <small class="text-muted">Current SEO image: </small>
                        <a href="{{ Storage::url($deal->seo_image) }}" target="_blank" class="text-primary">View
                            Image</a>
                    </div>
                    @endif
                    @error('seo_image')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Status & Submit Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Status & Submit</h5>
            </div>
            <div class="card-body row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="publish" {{ (old('status', isset($deal) ? ($deal->status ? 'publish' : 'draft') :
                            'publish')) == 'publish' ? 'selected' : '' }}>Publish</option>
                        <option value="draft" {{ (old('status', isset($deal) ? ($deal->status ? 'publish' : 'draft') :
                            'publish')) == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                    @error('status')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 text-end">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="btn-text">{{ isset($deal) ? 'Update Deal' : 'Create Deal' }}</span>
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            {{ isset($deal) ? 'Updating...' : 'Creating...' }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
    // Cover photo preview
    document.getElementById('cover-photo-input').addEventListener('change', function(e) {
        const preview = document.getElementById('cover-photo-preview');
        preview.innerHTML = '';
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                const img = document.createElement('img');
                img.src = ev.target.result;
                img.style.width = '100px';
                img.style.height = '75px';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '4px';
                preview.appendChild(img);
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Other images preview
    document.getElementById('other-images-input').addEventListener('change', function(e) {
        const preview = document.getElementById('other-images-preview');
        preview.innerHTML = '';
        if (e.target.files) {
            Array.from(e.target.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    const img = document.createElement('img');
                    img.src = ev.target.result;
                    img.style.width = '100px';
                    img.style.height = '75px';
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '4px';
                    img.classList.add('me-2');
                    preview.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        }
    });

    // Quill editor config for Description and Policies
    // Add image insert, alignment, and resizing support to Quill toolbar
    var quillToolbarOptions = [
        [{ 'header': [1, 2, false] }],
        ['bold', 'italic', 'underline', 'link', 'image'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'align': [] }],
        ['clean']
    ];

    // Register align style for image positioning
    var AlignStyle = Quill.import('attributors/style/align');
    Quill.register(AlignStyle, true);

    // Add custom image handler for inserting images
    function imageHandler() {
        const range = this.quill.getSelection();
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        input.onchange = () => {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const base64ImageSrc = e.target.result;
                    this.quill.insertEmbed(range.index, 'image', base64ImageSrc, Quill.sources.USER);
                    // Optionally, insert a newline after image
                    this.quill.insertText(range.index + 1, '\n', Quill.sources.SILENT);
                };
                reader.readAsDataURL(file);
            }
        };
    }

    // Add image resize and alignment support using quill-image-resize-module (must be included in your project)
    // If not included, resizing will not work, but alignment and width can be set via custom UI below

    var descriptionQuill = new Quill('#description-editor', {
        theme: 'snow',
        modules: {
            toolbar: quillToolbarOptions
        }
    });
    var policiesQuill = new Quill('#policies-editor', {
        theme: 'snow',
        modules: {
            toolbar: quillToolbarOptions
        }
    });

    // Set initial content from textarea
    descriptionQuill.root.innerHTML = document.getElementById('description').value;
    policiesQuill.root.innerHTML = document.getElementById('policies').value;

    // On form submit, update textarea values with Quill HTML and show loading
    document.getElementById('dealForm').addEventListener('submit', function(e) {
        document.getElementById('description').value = descriptionQuill.root.innerHTML;
        document.getElementById('policies').value = policiesQuill.root.innerHTML;
        
        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoading = submitBtn.querySelector('.btn-loading');
        
        submitBtn.disabled = true;
        btnText.classList.add('d-none');
        btnLoading.classList.remove('d-none');
    });

    // Google Maps Places Autocomplete and marker
    let map, marker, autocomplete;
    function initMap() {
        // Use placeholder values if no lat/lng provided
        const latValue = document.getElementById('lat-input').value;
        const lngValue = document.getElementById('lng-input').value;
        const initialLatLng = { 
            lat: latValue ? parseFloat(latValue) : 0, 
            lng: lngValue ? parseFloat(lngValue) : 0 
        };
        map = new google.maps.Map(document.getElementById('map'), {
            center: initialLatLng,
            zoom: 13
        });
        marker = new google.maps.Marker({
            position: initialLatLng,
            map: map,
            draggable: true
        });

        // Update lat/lng on marker drag
        marker.addListener('dragend', function() {
            const pos = marker.getPosition();
            document.getElementById('lat-input').value = pos.lat();
            document.getElementById('lng-input').value = pos.lng();
            geocodeLatLng(pos);
        });

        // Autocomplete
        autocomplete = new google.maps.places.Autocomplete(document.getElementById('location-input'));
        autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();
            if (!place.geometry) return;
            map.setCenter(place.geometry.location);
            marker.setPosition(place.geometry.location);
            document.getElementById('lat-input').value = place.geometry.location.lat();
            document.getElementById('lng-input').value = place.geometry.location.lng();
            document.getElementById('map-location-input').value = place.formatted_address || '';
        });
    }

    // Reverse geocode for marker drag
    function geocodeLatLng(latlng) {
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ location: latlng }, (results, status) => {
            if (status === "OK" && results[0]) {
                document.getElementById('map-location-input').value = results[0].formatted_address;
                document.getElementById('location-input').value = results[0].formatted_address;
            }
        });
    }

    // Nearby Locations Management
    let nearbyLocationIndex = {{ count($allLocations ?? []) }};
    
    // Add new nearby location
    document.getElementById('add-nearby-location').addEventListener('click', function() {
        const container = document.getElementById('nearby-locations-container');
        
        // Remove empty state if it exists
        const emptyState = container.querySelector('.text-center');
        if (emptyState) {
            emptyState.remove();
        }
        
        const locationItem = document.createElement('div');
        locationItem.className = 'nearby-location-item border rounded p-3 mb-3';
        locationItem.setAttribute('data-index', nearbyLocationIndex);
        
        const categories = @json(\App\Models\NearbyLocation::getAvailableCategories());
        let categoryOptions = '<option value="">Select Category</option>';
        for (const [key, value] of Object.entries(categories)) {
            categoryOptions += `<option value="${key}">${value}</option>`;
        }
        
        locationItem.innerHTML = `
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nearby_locations[${nearbyLocationIndex}][title]" 
                           placeholder="e.g., Zanzibar Airport" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category <span class="text-danger">*</span></label>
                    <select class="form-select" name="nearby_locations[${nearbyLocationIndex}][category]" required>
                        ${categoryOptions}
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Distance (km) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="nearby_locations[${nearbyLocationIndex}][distance_km]" 
                           step="0.1" min="0" placeholder="e.g., 2.5" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Actions</label>
                    <div class="d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-danger remove-nearby-location">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        container.appendChild(locationItem);
        nearbyLocationIndex++;
    });
    
    // Remove nearby location
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-nearby-location')) {
            e.target.closest('.nearby-location-item').remove();
            
            // Show empty state if no locations left
            const container = document.getElementById('nearby-locations-container');
            const items = container.querySelectorAll('.nearby-location-item');
            if (items.length === 0) {
                container.innerHTML = `
                    <div class="text-center text-muted py-4">
                        <i class="mdi mdi-map-marker fa-2x mb-2"></i>
                        <p>No nearby locations added yet. Click "Add Location" to get started.</p>
                    </div>
                `;
            }
        }
    });
</script>
<script
    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap"
    async defer></script>
@endpush
@endsection