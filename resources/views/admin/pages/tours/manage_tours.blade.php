@extends('admin.layouts.app')

@section('title', 'Manage Tour')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <div>
            <h4 class="page-title mb-0">Tour Management</h4>
        </div>
        <div>
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tours') }}">Tours</a></li>
                <li class="breadcrumb-item active">Tour Management</li>
            </ol>
        </div>
    </div>

    <!-- Tour Details Card -->
    <div class="row ">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between" style="gap: 1.5rem;">
                    <div style="flex: 0 0 120px; max-width: 120px;">
                        @if($tour->cover_photo)
                        <img src="{{ Storage::url($tour->cover_photo) }}" alt="{{ $tour->title }}"
                            class="img-fluid rounded" style="max-height: 100px; width: 100px; object-fit: cover;">
                        @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                            style="height: 100px;">
                            <i class="ti ti-tour text-muted" style="font-size: 2.5rem;"></i>
                        </div>
                        @endif
                    </div>
                    <!-- Tour Info -->
                    <div style="flex: 1 1 auto;">
                        <h4 class="mb-1">{{ $tour->title }}</h4>
                        <p class="mb-1 text-muted">
                            <i class="ti ti-map-pin me-1"></i>
                            {{ $tour->location ?? 'Location not specified' }}
                        </p>
                        <div class="mt-2">
                            <div class="row g-2">
                                <div class="col-auto">
                                    <span class="badge bg-light text-dark border">
                                        <strong>Period:</strong>
                                        <span class="ms-1">{{ $tour->tours->period ?? 'N/A' }} DAY(S)</span>
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <span class="badge bg-light text-dark border">
                                        <strong>Max People:</strong>
                                        <span class="ms-1">{{ $tour->tours->max_people ?? 'N/A' }}</span>
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <span class="badge bg-light text-dark border">
                                        <strong>Base Price:</strong>
                                        <span class="ms-1">${{ number_format($tour->base_price, 2) }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if($tour->tours)
                        <div class="mt-2">
                            <small class="text-muted">
                                Adult: ${{ number_format($tour->tours->adult_price, 2) }} |
                                Child: ${{ number_format($tour->tours->child_price, 2) }}
                            </small>
                        </div>
                        @endif
                    </div>
                    <div class="d-flex align-items-start" style="flex: 0 0 auto; gap: 0.5rem;">
                        <a href="{{ route('admin.manage-deal.edit', [$hashids->encode($tour->id), 'tour']) }}"
                            class="btn btn-outline-primary btn-sm">
                            <i class="ti ti-edit"></i> Edit Tour
                        </a>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                            data-bs-target="#deleteTourModal">
                            <i class="ti ti-trash"></i> Delete Tour
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Itinerary Management -->
    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tour Itinerary</h5>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#addItineraryModal">
                        <i class="ti ti-plus"></i> Add Itinerary Item
                    </button>
                </div>
                <div class="card-body">
                    @if($tour->itineraries->count() > 0)
                    <div class="accordion" id="itineraryAccordion">
                        @foreach($tour->itineraries as $itinerary)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingItem{{ $itinerary->id }}">
                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseItem{{ $itinerary->id }}"
                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                    aria-controls="collapseItem{{ $itinerary->id }}">
                                    <i class="mdi mdi-walk me-2"></i>
                                    {{ $itinerary->title }}
                                </button>
                            </h2>
                            <div id="collapseItem{{ $itinerary->id }}"
                                class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                aria-labelledby="headingItem{{ $itinerary->id }}" data-bs-parent="#itineraryAccordion">
                                <div class="accordion-body">
                                    <div
                                        class="d-flex flex-column flex-md-row justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="mb-2">
                                                @if($itinerary->time)
                                                <span class="text-muted">
                                                    <i class="ti ti-clock me-1"></i><b>{{ $itinerary->time }}</b> :
                                                </span>
                                                @endif
                                                @if($itinerary->location)
                                                <span class="text-muted">
                                                    <i class="ti ti-map-pin me-1"></i>{{ $itinerary->location }}
                                                </span>
                                                @endif
                                            </div>
                                            @if($itinerary->description)
                                            <p class="card-text">{!! $itinerary->description !!}</p>
                                            @endif
                                        </div>
                                        <div class="d-flex flex-column align-items-end ms-md-3 mt-3 mt-md-0"
                                            style="gap: 0.5rem;">
                                            <button type="button" class="btn btn-outline-primary btn-sm mb-1"
                                                data-bs-toggle="modal" data-bs-target="#editItineraryModal"
                                                data-itinerary-id="{{ $hashids->encode($itinerary->id) }}">
                                                <i class="mdi mdi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#deleteItineraryModal"
                                                data-itinerary-id="{{ $hashids->encode($itinerary->id) }}"
                                                data-title="{{ $itinerary->title }}">
                                                <i class="mdi mdi-trash-can"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="ti ti-calendar text-muted" style="font-size: 3rem;"></i>
                        <h5 class="text-muted mt-3">No Itinerary Items</h5>
                        <p class="text-muted">Start by adding itinerary items for this tour.</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addItineraryModal">
                            <i class="ti ti-plus"></i> Add First Itinerary Item
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Itinerary Modal -->
<div class="modal fade" id="addItineraryModal" tabindex="-1" aria-labelledby="addItineraryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.tours.itinerary.store', $hashids->encode($tour->id)) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addItineraryModalLabel">Add Itinerary Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" placeholder="Enter activity title"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Time</label>
                            <input type="text" class="form-control" name="time" placeholder="e.g., 9:00 AM">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" name="location" placeholder="e.g., Stone Town">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <div>
                                <textarea id="description" rows="4" class="form-control d-none" name="description"
                                    placeholder="Describe the activity..."></textarea>
                                <div id="description-editor"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Itinerary Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Itinerary Modal -->
<div class="modal fade" id="editItineraryModal" tabindex="-1" aria-labelledby="editItineraryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editItineraryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editItineraryModalLabel">Edit Itinerary Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" id="edit-title"
                                placeholder="Enter activity title" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Time</label>
                            <input type="text" class="form-control" name="time" id="edit-time"
                                placeholder="e.g., 9:00 AM">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" name="location" id="edit-location"
                                placeholder="e.g., Stone Town">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <div>
                                <textarea id="edit-description" rows="4" class="form-control d-none" name="description"
                                    placeholder="Describe the activity..."></textarea>
                                <div id="edit-description-editor"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Itinerary Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Itinerary Modal -->
<div class="modal fade" id="deleteItineraryModal" tabindex="-1" aria-labelledby="deleteItineraryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deleteItineraryForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteItineraryModalLabel">Delete Itinerary Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this itinerary item?</p>
                    <div class="alert alert-warning">
                        <strong id="delete-item-title"></strong>
                    </div>
                    <p class="text-muted">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Itinerary Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Tour Modal -->
<div class="modal fade" id="deleteTourModal" tabindex="-1" aria-labelledby="deleteTourModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.tours.delete', $hashids->encode($tour->id)) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteTourModalLabel">Delete Tour</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this tour?</p>
                    <div class="alert alert-danger">
                        <strong>{{ $tour->title }}</strong>
                    </div>
                    <p class="text-muted">This will permanently delete:</p>
                    <ul class="text-muted">
                        <li>Tour details and pricing</li>
                        <li>All itinerary items</li>
                        <li>Tour photos</li>
                        <li>All associated data</li>
                    </ul>
                    <p class="text-danger"><strong>This action cannot be undone.</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Tour</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
    // Quill editor config for Description
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

    // Initialize Quill editors
    var descriptionQuill = new Quill('#description-editor', {
        theme: 'snow',
        modules: {
            toolbar: quillToolbarOptions
        }
    });

    var editDescriptionQuill = new Quill('#edit-description-editor', {
        theme: 'snow',
        modules: {
            toolbar: quillToolbarOptions
        }
    });

    // Set initial content from textarea
    descriptionQuill.root.innerHTML = document.getElementById('description').value;
    editDescriptionQuill.root.innerHTML = document.getElementById('edit-description').value;

    // On form submit, update textarea values with Quill HTML
    document.addEventListener('DOMContentLoaded', function() {
        // Add Itinerary Modal form submission
        const addForm = document.querySelector('#addItineraryModal form');
        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                document.getElementById('description').value = descriptionQuill.root.innerHTML;
            });
        }

        // Edit Itinerary Modal form submission
        const editForm = document.querySelector('#editItineraryForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                document.getElementById('edit-description').value = editDescriptionQuill.root.innerHTML;
            });
        }
    });

  

    
    // Handle Edit Itinerary Modal
    document.getElementById('editItineraryModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const itineraryId = button.getAttribute('data-itinerary-id');
        
        // Set form action
        const updateUrl = `{{ route('admin.tours.itinerary.update', [$hashids->encode($tour->id), 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', itineraryId);
        const getUrl = `{{ route('admin.tours.itinerary.get', [$hashids->encode($tour->id), 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', itineraryId);
        
        console.log('Update URL:', updateUrl);
        console.log('Get URL:', getUrl);
        
        document.getElementById('editItineraryForm').action = updateUrl;
        
        // Fetch itinerary data via AJAX
        fetch(getUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    console.error('Error:', data.error);
                    return;
                }
                
                // Populate form fields
                document.getElementById('edit-time').value = data.time || '';
                document.getElementById('edit-title').value = data.title;
                document.getElementById('edit-location').value = data.location || '';
                
                // Set description in editor
                editDescriptionQuill.root.innerHTML = data.description || '';
            })
            .catch(error => {
                console.error('Error fetching itinerary data:', error);
            });
    });

    // Handle Delete Itinerary Modal
    document.getElementById('deleteItineraryModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const itineraryId = button.getAttribute('data-itinerary-id');
        const title = button.getAttribute('data-title');
        
        // Set form action
        const deleteUrl = `{{ route('admin.tours.itinerary.delete', [$hashids->encode($tour->id), 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', itineraryId);
        console.log('Delete URL:', deleteUrl);
        
        document.getElementById('deleteItineraryForm').action = deleteUrl;
        
        // Set title in modal
        document.getElementById('delete-item-title').textContent = title;
    });

    // Reset form when modal is closed
    document.getElementById('addItineraryModal').addEventListener('hidden.bs.modal', function () {
        document.querySelector('#addItineraryModal form').reset();
        descriptionQuill.setContents([]);
    });

    document.getElementById('editItineraryModal').addEventListener('hidden.bs.modal', function () {
        document.querySelector('#editItineraryForm').reset();
        editDescriptionQuill.setContents([]);
    });
</script>
@endpush