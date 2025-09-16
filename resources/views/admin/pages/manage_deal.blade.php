@extends('admin.layouts.app')

@section('title', isset($deal) ? 'Edit Deal' : 'Add New Deal')

@section('content')
<div class="container-fluid">

    <form id="dealForm" action="{{ isset($deal) ? route('admin.manage-deal.update', [$deal->id, $type]) : route('admin.manage-deal.store', $type) }}" method="POST" enctype="multipart/form-data"
        autocomplete="off">
        @csrf
        @if(isset($deal))
            @method('PUT')
        @endif
        <!-- Deal Details Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Deal Details</h5>
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
                         <option value="{{ $category->id }}" {{ (old('category_id', $deal->category_id ?? '') == $category->id) ? 'selected' : '' }}>
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
                         <input type="number" class="form-control" name="base_price" value="{{ old('base_price', $deal->base_price ?? '') }}"
                             step="0.01" placeholder="Enter base price">
                         <div class="input-group-append">
                             <span class="input-group-text">USD</span>
                         </div>
                     </div>
                     @error('base_price')
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

                 {{-- Show Tour Inputs only if $type == "tour" --}}
                 @if($type == 'tour')
                     <div class="col-md-4">
                         <label class="form-label">Tour Period <span class="text-danger">*</span></label>
                         <input type="text" class="form-control" name="tour_period" value="{{ old('tour_period', $typeSpecificData['tour']->period ?? '') }}"
                             placeholder="e.g. 3 days, 2 nights" required>
                         @error('tour_period')
                         <div class="text-danger">{{ $message }}</div>
                         @enderror
                     </div>
                     <div class="col-md-4">
                         <label class="form-label">Max People <span class="text-danger">*</span></label>
                         <input type="number" class="form-control" name="max_people" value="{{ old('max_people', $typeSpecificData['tour']->max_people ?? '') }}" min="1"
                             placeholder="e.g. 10" required>
                         @error('max_people')
                         <div class="text-danger">{{ $message }}</div>
                         @enderror
                     </div>
                     <div class="col-md-4">
                         <label class="form-label">Adult Price <span class="text-danger">*</span></label>
                         <input type="number" class="form-control" name="adult_price" value="{{ old('adult_price', $typeSpecificData['tour']->adult_price ?? '') }}"
                             step="0.01" placeholder="e.g. 100.00" required>
                         @error('adult_price')
                         <div class="text-danger">{{ $message }}</div>
                         @enderror
                     </div>
                     <div class="col-md-4">
                         <label class="form-label">Child Price <span class="text-danger">*</span></label>
                         <input type="number" class="form-control" name="child_price" value="{{ old('child_price', $typeSpecificData['tour']->child_price ?? '') }}"
                             step="0.01" placeholder="e.g. 50.00" required>
                         @error('child_price')
                         <div class="text-danger">{{ $message }}</div>
                         @enderror
                     </div>
                 @endif

                 {{-- Show Car Inputs only if $type == "car" --}}
                 @if($type == 'car')
                     <div class="col-md-4">
                         <label class="form-label">Car Capacity <span class="text-danger">*</span></label>
                         <input type="number" class="form-control" name="car_capacity" value="{{ old('car_capacity', $typeSpecificData['car']->capacity ?? '') }}"
                             min="1" placeholder="e.g. 4" required>
                         @error('car_capacity')
                         <div class="text-danger">{{ $message }}</div>
                         @enderror
                     </div>
                     <div class="col-md-4">
                         <label class="form-label">Transmission <span class="text-danger">*</span></label>
                         <select class="form-control" name="transmission" required>
                             <option value="">Select Transmission</option>
                             <option value="manual" {{ (old('transmission', $typeSpecificData['car']->transmission ?? '') == 'manual') ? 'selected' : '' }}>Manual</option>
                             <option value="automatic" {{ (old('transmission', $typeSpecificData['car']->transmission ?? '') == 'automatic') ? 'selected' : '' }}>Automatic</option>
                         </select>
                         @error('transmission')
                         <div class="text-danger">{{ $message }}</div>
                         @enderror
                     </div>
                     <div class="col-md-4">
                         <label class="form-label">Fuel <span class="text-danger">*</span></label>
                         <select class="form-control" name="fuel" required>
                             <option value="">Select Fuel Type</option>
                             <option value="petrol" {{ (old('fuel', $typeSpecificData['car']->fuel ?? '') == 'petrol') ? 'selected' : '' }}>Petrol</option>
                             <option value="diesel" {{ (old('fuel', $typeSpecificData['car']->fuel ?? '') == 'diesel') ? 'selected' : '' }}>Diesel</option>
                             <option value="electric" {{ (old('fuel', $typeSpecificData['car']->fuel ?? '') == 'electric') ? 'selected' : '' }}>Electric</option>
                             <option value="hybrid" {{ (old('fuel', $typeSpecificData['car']->fuel ?? '') == 'hybrid') ? 'selected' : '' }}>Hybrid</option>
                         </select>
                         @error('fuel')
                         <div class="text-danger">{{ $message }}</div>
                         @enderror
                     </div>
                     <div class="col-md-4">
                         <label class="form-label">Air Condition</label>
                         <select class="form-control" name="air_condition">
                             <option value="0" {{ (old('air_condition', $typeSpecificData['car']->air_condition ?? '0') == '0') ? 'selected' : '' }}>No</option>
                             <option value="1" {{ (old('air_condition', $typeSpecificData['car']->air_condition ?? '0') == '1') ? 'selected' : '' }}>Yes</option>
                         </select>
                         @error('air_condition')
                         <div class="text-danger">{{ $message }}</div>
                         @enderror
                     </div>
                     <div class="col-md-4">
                         <label class="form-label">GPS</label>
                         <select class="form-control" name="gps">
                             <option value="0" {{ (old('gps', $typeSpecificData['car']->gps ?? '0') == '0') ? 'selected' : '' }}>No</option>
                             <option value="1" {{ (old('gps', $typeSpecificData['car']->gps ?? '0') == '1') ? 'selected' : '' }}>Yes</option>
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
                         <small class="text-muted">Current: <a href="{{ Storage::url($typeSpecificData['car']->terms) }}" target="_blank">View Document</a></small>
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
                                     value="{{ $feature->id }}" id="feature-{{ $feature->id }}" {{ 
                                     in_array($feature->id, old('features', isset($deal) ? $deal->features->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
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


        @if ($type == 'tour')
        <div class="card mb-4">
            <div class="card-header">
                <h5>Tour Features</h5>
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
                                old('features', [])) ? 'checked' : '' }}>
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
                        No tour features available. <a href="{{ route('admin.features') }}" class="alert-link">Add some
                            tour features</a> first.
                    </div>
                    @endif
                    @error('features')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5>Tour Includes</h5>
            </div>
            <div class="card-body row g-3">
                <div class="col-12">
                    @if($tourIncludes->count() > 0)
                    <div class="row">
                         @foreach($tourIncludes as $include)
                         <div class="col-md-4 mb-2">
                             <div class="form-check d-flex align-items-center" style="cursor: pointer;">
                                 <input class="form-check-input me-2" type="checkbox" name="tour_includes[]"
                                     value="{{ $include->name }}" id="tour-include-{{ $include->id }}" {{
                                     in_array($include->name, old('tour_includes', isset($typeSpecificData['tour_includes']) ? $typeSpecificData['tour_includes']->pluck('title')->toArray() : [])) ? 'checked' : '' }}>
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
                        No tour includes available. <a href="{{ route('admin.features') }}" class="alert-link">Add some
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
                <h5>Tour Excludes</h5>
            </div>
            <div class="card-body row g-3">
                <div class="col-12">
                    @if($tourExcludes->count() > 0)
                    <div class="row">
                         @foreach($tourExcludes as $exclude)
                         <div class="col-md-4 mb-2">
                             <div class="form-check d-flex align-items-center" style="cursor: pointer;">
                                 <input class="form-check-input me-2" type="checkbox" name="tour_excludes[]"
                                     value="{{ $exclude->name }}" id="tour-exclude-{{ $exclude->id }}" {{
                                     in_array($exclude->name, old('tour_excludes', isset($typeSpecificData['tour_excludes']) ? $typeSpecificData['tour_excludes']->pluck('title')->toArray() : [])) ? 'checked' : '' }}>
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
                        No tour excludes available. <a href="{{ route('admin.features') }}" class="alert-link">Add some
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
                     <input type="text" class="form-control" id="lat-input" name="lat" value="{{ old('lat', $deal->lat ?? '') }}"
                         placeholder="Latitude" readonly>
                     @error('lat')
                     <div class="text-danger">{{ $message }}</div>
                     @enderror
                 </div>
                 <div class="col-md-6">
                     <label class="form-label">Longitude</label>
                     <input type="text" class="form-control" id="lng-input" name="long" value="{{ old('long', $deal->long ?? '') }}"
                         placeholder="Longitude" readonly>
                     @error('long')
                     <div class="text-danger">{{ $message }}</div>
                     @enderror
                 </div>
                 <div class="col-md-12">
                     <label class="form-label">Map Location (Address)</label>
                     <input type="text" class="form-control" id="map-location-input" name="map_location"
                         value="{{ old('map_location', $deal->map_location ?? '') }}" placeholder="Map location address" readonly>
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
                     <label class="form-label">Cover Photo {{ !isset($deal) ? '<span class="text-danger">*</span>' : '' }}</label>
                     <input type="file" class="form-control" name="cover_photo" id="cover-photo-input" accept="image/*" {{ !isset($deal) ? 'required' : '' }}>
                     <div class="mt-2" id="cover-photo-preview">
                         @if(isset($deal) && $deal->cover_photo)
                             <img src="{{ Storage::url($deal->cover_photo) }}" alt="Cover Preview" style="width:100px; height:75px; object-fit:cover; border-radius:4px;">
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
                                     <img src="{{ Storage::url($photo->photo) }}" alt="Deal Photo" style="width:100px; height:75px; object-fit:cover; border-radius:4px;">
                                 @endforeach
                             @else
                                 @for ($i = 0; $i < 6; $i++) 
                                     <img src="https://media.istockphoto.com/id/1147544807/vector/thumbnail-image-vector-graphic.jpg?s=612x612&w=0&k=20&c=rnCKVbdxqkjlcs3xH87-9gocETqpspHFXu5dIGB4wuM="
                                         alt="Image {{ $i + 1 }}" style="width:100px; height:75px; object-fit:cover; border-radius:4px;">
                                 @endfor
                             @endif
                         </div>
                     </div>
                     <small class="text-muted">You can drop or select multiple images.</small>
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
                         <option value="publish" {{ (old('status', isset($deal) ? ($deal->status ? 'publish' : 'draft') : 'publish')) == 'publish' ? 'selected' : '' }}>Publish</option>
                         <option value="draft" {{ (old('status', isset($deal) ? ($deal->status ? 'publish' : 'draft') : 'publish')) == 'draft' ? 'selected' : '' }}>Draft</option>
                     </select>
                     @error('status')
                     <div class="text-danger">{{ $message }}</div>
                     @enderror
                 </div>
                 <div class="col-md-6 text-end">
                     <button type="submit" class="btn btn-primary">{{ isset($deal) ? 'Update Deal' : 'Create Deal' }}</button>
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

    // On form submit, update textarea values with Quill HTML
    document.getElementById('dealForm').addEventListener('submit', function(e) {
        document.getElementById('description').value = descriptionQuill.root.innerHTML;
        document.getElementById('policies').value = policiesQuill.root.innerHTML;
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
</script>
<script
    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap"
    async defer></script>
@endpush
@endsection