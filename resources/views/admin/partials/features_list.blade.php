<div class="table-responsive">
    <table class="table table-bordered table-hover mb-0" id="features-table">
        <thead>
            <tr>
                <th class="px-3 py-2">#</th>
                <th class="px-3 py-2">Feature Name</th>
                <th class="px-3 py-2">Type</th>
                <th class="px-3 py-2">Icon</th>
                <th class="px-3 py-2">Status</th>
                <th class="px-3 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($features as $index => $feature)
            <tr data-feature-id="{{ $feature->id }}"
                data-feature-name="{{ strtolower($feature->name) }}"
                data-feature-type="{{ strtolower($feature->type) }}">
                <td class="px-3 py-2">{{ $features->firstItem() + $index }}</td>
                <td class="px-3 py-2">
                    <span class="fw-medium">{{ $feature->name }}</span>
                </td>
                <td class="px-3 py-2">{{ ucfirst($feature->type) }}</td>
                <td class="px-3 py-2">
                    <i class="mdi {{ $feature->icon }} fs-2 text-dark"></i>
                </td>
                <td class="px-3 py-2">
                    @if($feature->status)
                    <span class="badge bg-success">Active</span>
                    @else
                    <span class="badge bg-secondary">Inactive</span>
                    @endif
                </td>
                <td class="px-3 py-2">
                    <div class="d-flex gap-2 flex-wrap">
                        <form action="{{ route('admin.features.toggle-status', $feature->id) }}" method="POST"
                            class="feature-toggle-status-form">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                class="btn btn-sm {{ $feature->status ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                title="{{ $feature->status ? 'Deactivate' : 'Activate' }}">
                                <i class="mdi {{ $feature->status ? 'mdi-eye-off-outline' : 'mdi-eye-outline' }}"></i>
                            </button>
                        </form>
                        <button type="button" class="btn btn-sm btn-outline-primary"
                            data-bs-toggle="modal" data-bs-target="#editFeatureModal{{ $feature->id }}">
                            <i class="mdi mdi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger"
                            data-bs-toggle="modal" data-bs-target="#deleteFeatureModal{{ $feature->id }}">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-3 py-4 text-center text-muted">
                    <i class="mdi mdi-information-outline fs-1 d-block mb-2"></i>
                    No features found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($features->hasPages())
<div class="card-body border-top py-3" id="features-pagination">
    {{ $features->links() }}
</div>
@endif

@foreach($features as $feature)
<div class="modal fade" id="editFeatureModal{{ $feature->id }}" tabindex="-1"
    aria-labelledby="editFeatureModal{{ $feature->id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.features.update', $feature->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editFeatureModal{{ $feature->id }}Label">Edit Feature</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name{{ $feature->id }}" class="form-label">Feature Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name{{ $feature->id }}" name="name"
                            value="{{ $feature->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_type{{ $feature->id }}" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit_type{{ $feature->id }}" name="type" required>
                            <option value="">Select Type</option>
                            <option value="hotel" {{ $feature->type == 'hotel' ? 'selected' : '' }}>Hotel</option>
                            <option value="include" {{ $feature->type == 'include' ? 'selected' : '' }}>Include</option>
                            <option value="exclude" {{ $feature->type == 'exclude' ? 'selected' : '' }}>Exclude</option>
                            <option value="car" {{ $feature->type == 'car' ? 'selected' : '' }}>Car</option>
                            <option value="apartment" {{ $feature->type == 'apartment' ? 'selected' : '' }}>Apartment</option>
                            <option value="tour" {{ $feature->type == 'tour' ? 'selected' : '' }}>Tour</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon <span class="text-danger">*</span></label>
                        @include('admin.components.feature_icon_picker', [
                            'pickerId' => 'edit_' . $feature->id,
                            'inputId' => 'edit_icon_' . $feature->id,
                            'selected' => $feature->icon,
                        ])
                    </div>
                    <div class="mb-3">
                        <label for="edit_status{{ $feature->id }}" class="form-label">Status</label>
                        <select class="form-control" id="edit_status{{ $feature->id }}" name="status">
                            <option value="1" {{ $feature->status ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$feature->status ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" data-loading-text="Updating...">Update Feature</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteFeatureModal{{ $feature->id }}" tabindex="-1"
    aria-labelledby="deleteFeatureModal{{ $feature->id }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.features.delete', $feature->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteFeatureModal{{ $feature->id }}Label">Delete Feature</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="mdi mdi-alert-circle-outline fs-1 text-warning mb-3"></i>
                        <h5>Are you sure?</h5>
                        <p class="text-muted">You are about to delete the feature
                            <strong>"{{ $feature->name }}"</strong>. This action cannot be undone.
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" data-loading-text="Deleting...">
                        <i class="mdi mdi-delete me-1"></i> Delete Feature
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
