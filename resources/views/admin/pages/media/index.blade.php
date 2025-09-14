@extends('admin.layouts.app')

@section('title', 'Media Library')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Media</li>
                    </ol>
                </div>
                <h4 class="page-title">Media Library</h4>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">Media Management</h5>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                <i class="ti ti-upload"></i> Upload Media
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Media Grid -->
                    <div class="row">
                        <!-- Sample Media Items -->
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="card">
                                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Media Item">
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-1">hotel-image-1.jpg</h6>
                                    <small class="text-muted">300x200 • 45KB</small>
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteMedia(1)">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="card">
                                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Media Item">
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-1">apartment-view.jpg</h6>
                                    <small class="text-muted">300x200 • 52KB</small>
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteMedia(2)">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="card">
                                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Media Item">
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-1">car-interior.jpg</h6>
                                    <small class="text-muted">300x200 • 38KB</small>
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteMedia(3)">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="card">
                                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Media Item">
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-1">tour-destination.jpg</h6>
                                    <small class="text-muted">300x200 • 41KB</small>
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteMedia(4)">
                                            <i class="ti ti-trash"></i>
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
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Media</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="media_files" class="form-label">Select Files</label>
                        <input type="file" class="form-control" id="media_files" name="media_files[]" multiple accept="image/*,video/*">
                        <div class="form-text">You can select multiple files. Supported formats: JPG, PNG, GIF, MP4, MOV</div>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">Select Category</option>
                            <option value="hotels">Hotels</option>
                            <option value="apartments">Apartments</option>
                            <option value="cars">Cars</option>
                            <option value="tours">Tours</option>
                            <option value="blog">Blog</option>
                            <option value="general">General</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control" id="description" name="description" rows="3" 
                                  placeholder="Brief description of the media files..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="uploadForm" class="btn btn-primary">Upload Files</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this media file? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteMedia(id) {
    document.getElementById('deleteForm').action = '{{ route("admin.media.delete", ":id") }}'.replace(':id', id);
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection
