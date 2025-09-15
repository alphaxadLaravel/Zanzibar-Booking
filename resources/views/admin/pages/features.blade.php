@extends('admin.layouts.app')

@section('title')
Features | {{env('APP_NAME')}}
@endsection

@section('content')

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">System Features</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#createFeatureModal">
                    <i class="mdi mdi-plus"></i> New Feature
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="">
                            <tr>
                                <th class="px-3 py-2">#</th>
                                <th class="px-3 py-2">Feature Name</th>
                                <th class="px-3 py-2">Icon</th>
                                <th class="px-3 py-2">Status</th>
                                <th class="px-3 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($features as $index => $feature)
                            <tr>
                                <td class="px-3 py-2">{{ $index + 1 }}</td>
                                <td class="px-3 py-2">
                                    <span class="fw-medium">{{ $feature->name }}</span>
                                </td>
                                <td class="px-3 py-2 d-flex align-items-center gap-2">
                                    <i class="mdi mdi-{{ $feature->icon }} fs-2 text-dark"></i>
                                </td>
                                <td class="px-3 py-2">
                                    @if($feature->status)
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal" data-bs-target="#editFeatureModal{{ $feature->id }}">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>

                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteFeatureModal{{ $feature->id }}">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Feature Modal for each row -->
                            <div class="modal fade" id="editFeatureModal{{ $feature->id }}" tabindex="-1"
                                aria-labelledby="editFeatureModal{{ $feature->id }}Label" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.features.update', $feature->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editFeatureModal{{ $feature->id }}Label">
                                                    Edit Feature</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="edit_name{{ $feature->id }}" class="form-label">Feature
                                                        Name <span class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        id="edit_name{{ $feature->id }}" name="name"
                                                        value="{{ $feature->name }}" required>
                                                    @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit_icon{{ $feature->id }}" class="form-label">Icon
                                                        Class <span class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('icon') is-invalid @enderror"
                                                        id="edit_icon{{ $feature->id }}" name="icon"
                                                        value="{{ $feature->icon }}" placeholder="e.g., mdi mdi-star"
                                                        required>
                                                    <div class="form-text">Enter the MDI icon class (e.g., mdi mdi-star,
                                                        mdi mdi-heart)</div>
                                                    @error('icon')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary"
                                                    data-loading-text="Updating...">Update Feature</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Feature Modal for each row -->
                            <div class="modal fade" id="deleteFeatureModal{{ $feature->id }}" tabindex="-1"
                                aria-labelledby="deleteFeatureModal{{ $feature->id }}Label" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.features.delete', $feature->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteFeatureModal{{ $feature->id }}Label">
                                                    Delete Feature</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <i class="mdi mdi-alert-circle-outline fs-1 text-warning mb-3"></i>
                                                    <h5>Are you sure?</h5>
                                                    <p class="text-muted">You are about to delete the feature
                                                        <strong>"{{ $feature->name }}"</strong>. This action cannot be
                                                        undone.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-danger"
                                                    data-loading-text="Deleting...">
                                                    <i class="mdi mdi-delete me-1"></i> Delete Feature
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="6" class="px-3 py-4 text-center text-muted">
                                    <i class="mdi mdi-information-outline fs-1 d-block mb-2"></i>
                                    No features found. <button type="button" class="btn btn-link p-0"
                                        data-bs-toggle="modal" data-bs-target="#createFeatureModal">Create your first
                                        feature</button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Feature Modal -->
<div class="modal fade" id="createFeatureModal" tabindex="-1" aria-labelledby="createFeatureModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.features.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createFeatureModalLabel">Create New Feature</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_name" class="form-label">Feature Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="create_name"
                            name="name" value="{{ old('name') }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="create_icon" class="form-label">Icon Class <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('icon') is-invalid @enderror" id="create_icon"
                            name="icon" value="{{ old('icon') }}" placeholder="e.g., mdi mdi-star" required>
                        <div class="form-text">Enter the MDI icon class (e.g., mdi mdi-star, mdi mdi-heart)</div>
                        @error('icon')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" data-loading-text="Creating...">Create
                        Feature</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection