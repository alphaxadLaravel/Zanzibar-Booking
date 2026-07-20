@extends('admin.layouts.app')

@section('title')
Features | {{env('APP_NAME')}}
@endsection

@push('styles')
<style>
    .feature-icon-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(42px, 1fr));
        gap: 6px;
    }

    .feature-icon-option {
        width: 42px;
        height: 42px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .feature-icon-option.d-none {
        display: none !important;
    }

    #features-table tbody tr.feature-row-hidden {
        display: none;
    }

    .feature-row-highlight {
        animation: featureHighlight 2s ease;
    }

    @keyframes featureHighlight {
        0%, 100% { background-color: transparent; }
        30% { background-color: rgba(255, 193, 7, 0.35); }
    }
</style>
@endpush

@section('content')

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h5 class="card-title mb-0">System Features</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#createFeatureModal">
                    <i class="mdi mdi-plus"></i> New Feature
                </button>
            </div>
            <div class="card-body border-bottom pb-3">
                <div class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                            <input type="text" id="features-search" class="form-control"
                                placeholder="Search features by name or type...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted" id="features-search-count">
                            Showing {{ $features->count() }} of {{ $features->count() }} features
                        </small>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
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
                                <td class="px-3 py-2">{{ $index + 1 }}</td>
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

                            <div class="modal fade" id="editFeatureModal{{ $feature->id }}" tabindex="-1"
                                aria-labelledby="editFeatureModal{{ $feature->id }}Label" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
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
                                                    <label for="edit_type{{ $feature->id }}" class="form-label">Type
                                                        <span class="text-danger">*</span></label>
                                                    <select class="form-control @error('type') is-invalid @enderror"
                                                        id="edit_type{{ $feature->id }}" name="type" required>
                                                        <option value="">Select Type</option>
                                                        <option value="hotel" {{ $feature->type == 'hotel' ? 'selected' : '' }}>Hotel</option>
                                                        <option value="include" {{ $feature->type == 'include' ? 'selected' : '' }}>Include</option>
                                                        <option value="exclude" {{ $feature->type == 'exclude' ? 'selected' : '' }}>Exclude</option>
                                                        <option value="car" {{ $feature->type == 'car' ? 'selected' : '' }}>Car</option>
                                                        <option value="apartment" {{ $feature->type == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                                        <option value="tour" {{ $feature->type == 'tour' ? 'selected' : '' }}>Tour</option>
                                                    </select>
                                                    @error('type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Icon <span class="text-danger">*</span></label>
                                                    @include('admin.components.feature_icon_picker', [
                                                        'pickerId' => 'edit_' . $feature->id,
                                                        'inputId' => 'edit_icon_' . $feature->id,
                                                        'selected' => $feature->icon,
                                                    ])
                                                    @error('icon')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
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
                            <tr id="features-empty-row">
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

<div class="modal fade" id="createFeatureModal" tabindex="-1" aria-labelledby="createFeatureModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.features.store') }}" method="POST" id="createFeatureForm">
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
                            name="name" value="{{ old('name') }}" required autocomplete="off">
                        <div id="create-feature-duplicate-warning" class="alert alert-warning mt-2 py-2 small d-none"></div>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="create_type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-control @error('type') is-invalid @enderror" id="create_type"
                            name="type" required>
                            <option value="">Select Type</option>
                            <option value="hotel" {{ old('type') == 'hotel' ? 'selected' : '' }}>Hotel</option>
                            <option value="include" {{ old('type') == 'include' ? 'selected' : '' }}>Include</option>
                            <option value="exclude" {{ old('type') == 'exclude' ? 'selected' : '' }}>Exclude</option>
                            <option value="car" {{ old('type') == 'car' ? 'selected' : '' }}>Car</option>
                            <option value="apartment" {{ old('type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                            <option value="tour" {{ old('type') == 'tour' ? 'selected' : '' }}>Tour</option>
                        </select>
                        @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Icon <span class="text-danger">*</span></label>
                        @include('admin.components.feature_icon_picker', [
                            'pickerId' => 'create',
                            'inputId' => 'create_icon',
                            'selected' => old('icon'),
                        ])
                        @error('icon')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="createFeatureSubmit"
                        data-loading-text="Creating...">Create Feature</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const existingFeatures = @json($existingFeatures);

    const searchInput = document.getElementById('features-search');
    const searchCount = document.getElementById('features-search-count');
    const tableRows = Array.from(document.querySelectorAll('#features-table tbody tr[data-feature-id]'));
    const totalFeatures = tableRows.length;

    function filterFeaturesTable() {
        const query = (searchInput?.value || '').trim().toLowerCase();
        let visible = 0;

        tableRows.forEach(function (row) {
            const name = row.dataset.featureName || '';
            const type = row.dataset.featureType || '';
            const match = !query || name.includes(query) || type.includes(query);
            row.classList.toggle('feature-row-hidden', !match);
            if (match) visible++;
        });

        if (searchCount) {
            searchCount.textContent = 'Showing ' + visible + ' of ' + totalFeatures + ' features';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterFeaturesTable);
    }

    function findDuplicateFeature(name) {
        const normalized = (name || '').trim().toLowerCase();
        if (!normalized) return null;

        return existingFeatures.find(function (feature) {
            return feature.name.toLowerCase() === normalized;
        }) || null;
    }

    function scrollToFeatureRow(featureId) {
        const row = document.querySelector('tr[data-feature-id="' + featureId + '"]');
        if (!row) return;

        row.classList.remove('feature-row-hidden');
        row.scrollIntoView({ behavior: 'smooth', block: 'center' });
        row.classList.add('feature-row-highlight');
        setTimeout(function () {
            row.classList.remove('feature-row-highlight');
        }, 2000);
        filterFeaturesTable();
    }

    const createNameInput = document.getElementById('create_name');
    const createTypeSelect = document.getElementById('create_type');
    const duplicateWarning = document.getElementById('create-feature-duplicate-warning');
    const createSubmitBtn = document.getElementById('createFeatureSubmit');

    function updateDuplicateWarning() {
        if (!duplicateWarning || !createNameInput) return;

        const duplicate = findDuplicateFeature(createNameInput.value);

        if (!duplicate) {
            duplicateWarning.classList.add('d-none');
            duplicateWarning.innerHTML = '';
            if (createSubmitBtn) createSubmitBtn.disabled = false;
            return;
        }

        duplicateWarning.classList.remove('d-none');
        duplicateWarning.innerHTML =
            '<i class="mdi mdi-alert me-1"></i> Feature <strong>' + duplicate.name + '</strong> already exists' +
            (duplicate.type ? ' (' + duplicate.type + ')' : '') + '. ' +
            '<button type="button" class="btn btn-link btn-sm p-0 align-baseline" data-view-feature="' + duplicate.id + '">View in list</button>';

        duplicateWarning.querySelector('[data-view-feature]')?.addEventListener('click', function () {
            const modal = bootstrap.Modal.getInstance(document.getElementById('createFeatureModal'));
            if (modal) modal.hide();
            scrollToFeatureRow(duplicate.id);
        });

        if (createSubmitBtn) {
            createSubmitBtn.disabled = !!duplicate;
        }
    }

    createNameInput?.addEventListener('input', updateDuplicateWarning);
    createTypeSelect?.addEventListener('change', updateDuplicateWarning);

    document.querySelectorAll('.feature-icon-picker').forEach(function (picker) {
        const search = picker.querySelector('.feature-icon-search');
        const options = picker.querySelectorAll('.feature-icon-option');

        search?.addEventListener('input', function () {
            const query = this.value.trim().toLowerCase();
            options.forEach(function (btn) {
                const icon = (btn.dataset.icon || '').toLowerCase();
                btn.classList.toggle('d-none', query && !icon.includes(query));
            });
        });

        options.forEach(function (btn) {
            btn.addEventListener('click', function () {
                const inputId = btn.dataset.inputId;
                const input = document.getElementById(inputId);
                const preview = document.getElementById(inputId + '_preview');
                const label = document.getElementById(inputId + '_label');
                const icon = btn.dataset.icon;

                if (input) input.value = icon;
                if (preview) preview.className = 'mdi ' + icon + ' fs-3';
                if (label) label.textContent = icon;

                picker.querySelectorAll('.feature-icon-option').forEach(function (other) {
                    other.classList.remove('btn-primary');
                    other.classList.add('btn-outline-secondary');
                });
                btn.classList.remove('btn-outline-secondary');
                btn.classList.add('btn-primary');
            });
        });
    });

    @if($errors->any() && old('name'))
        updateDuplicateWarning();
        const createModal = new bootstrap.Modal(document.getElementById('createFeatureModal'));
        createModal.show();
    @endif
});
</script>
@endpush
