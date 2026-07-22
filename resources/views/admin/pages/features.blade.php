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

    .feature-row-highlight {
        animation: featureHighlight 2s ease;
    }

    @keyframes featureHighlight {
        0%, 100% { background-color: transparent; }
        30% { background-color: rgba(255, 193, 7, 0.35); }
    }

    #features-list-container.is-loading {
        opacity: 0.55;
        pointer-events: none;
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
                                placeholder="Search all features by name or type..."
                                value="{{ request('search') }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted" id="features-search-count">
                            @if($features->total())
                                Showing {{ $features->firstItem() }}–{{ $features->lastItem() }} of {{ $features->total() }} features
                            @else
                                Showing 0 of 0 features
                            @endif
                        </small>
                    </div>
                </div>
            </div>
            <div class="card-body p-0" id="features-list-container">
                @include('admin.partials.features_list')
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

                    <div class="mb-3">
                        <label for="create_status" class="form-label">Status</label>
                        <select class="form-control" id="create_status" name="status">
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
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
    const featuresUrl = @json(route('admin.features'));
    const checkNameUrl = @json(route('admin.features.check-name'));
    const searchInput = document.getElementById('features-search');
    const searchCount = document.getElementById('features-search-count');
    const listContainer = document.getElementById('features-list-container');
    const createNameInput = document.getElementById('create_name');
    const duplicateWarning = document.getElementById('create-feature-duplicate-warning');
    const createSubmitBtn = document.getElementById('createFeatureSubmit');
    let searchTimer = null;
    let duplicateTimer = null;

    function initFeatureIconPickers(scope) {
        (scope || document).querySelectorAll('.feature-icon-picker').forEach(function (picker) {
            if (picker.dataset.initialized === '1') return;
            picker.dataset.initialized = '1';

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
    }

    function updateSearchCount(from, to, total) {
        if (!searchCount) return;
        if (!total) {
            searchCount.textContent = 'Showing 0 of 0 features';
            return;
        }
        searchCount.textContent = 'Showing ' + from + '–' + to + ' of ' + total + ' features';
    }

    function loadFeatures(page) {
        const search = (searchInput?.value || '').trim();
        const url = new URL(featuresUrl, window.location.origin);
        url.searchParams.set('page', page || 1);
        if (search) {
            url.searchParams.set('search', search);
        } else {
            url.searchParams.delete('search');
        }

        if (listContainer) listContainer.classList.add('is-loading');

        return fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            credentials: 'same-origin',
        })
        .then(function (response) { return response.json(); })
        .then(function (data) {
            if (listContainer) {
                listContainer.innerHTML = data.html;
                listContainer.classList.remove('is-loading');
            }
            updateSearchCount(data.from, data.to, data.total);
            initFeatureIconPickers(listContainer);
            bindPaginationLinks();
            bindToggleStatusForms();

            const displayUrl = new URL(window.location.href);
            if (search) {
                displayUrl.searchParams.set('search', search);
            } else {
                displayUrl.searchParams.delete('search');
            }
            if (page > 1) {
                displayUrl.searchParams.set('page', page);
            } else {
                displayUrl.searchParams.delete('page');
            }
            window.history.replaceState({}, '', displayUrl.toString());
        })
        .catch(function () {
            if (listContainer) listContainer.classList.remove('is-loading');
        });
    }

    function bindPaginationLinks() {
        if (!listContainer) return;

        listContainer.querySelectorAll('#features-pagination a').forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const url = new URL(link.href);
                const page = url.searchParams.get('page') || 1;
                loadFeatures(page);
            });
        });
    }

    function bindToggleStatusForms() {
        if (!listContainer) return;

        listContainer.querySelectorAll('.feature-toggle-status-form').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                })
                .then(function (response) { return response.json(); })
                .then(function () {
                    const currentPage = new URL(window.location.href).searchParams.get('page') || 1;
                    loadFeatures(currentPage);
                });
            });
        });
    }

    function highlightFeatureRow(featureId) {
        const row = document.querySelector('tr[data-feature-id="' + featureId + '"]');
        if (!row) return false;

        row.scrollIntoView({ behavior: 'smooth', block: 'center' });
        row.classList.add('feature-row-highlight');
        setTimeout(function () {
            row.classList.remove('feature-row-highlight');
        }, 2000);
        return true;
    }

    function updateDuplicateWarning() {
        if (!duplicateWarning || !createNameInput) return;

        const name = createNameInput.value.trim();
        if (!name) {
            duplicateWarning.classList.add('d-none');
            duplicateWarning.innerHTML = '';
            if (createSubmitBtn) createSubmitBtn.disabled = false;
            return;
        }

        clearTimeout(duplicateTimer);
        duplicateTimer = setTimeout(function () {
            fetch(checkNameUrl + '?name=' + encodeURIComponent(name), {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.exists || !data.feature) {
                    duplicateWarning.classList.add('d-none');
                    duplicateWarning.innerHTML = '';
                    if (createSubmitBtn) createSubmitBtn.disabled = false;
                    return;
                }

                const duplicate = data.feature;
                duplicateWarning.classList.remove('d-none');
                duplicateWarning.innerHTML =
                    '<i class="mdi mdi-alert me-1"></i> Feature <strong>' + duplicate.name + '</strong> already exists' +
                    (duplicate.type ? ' (' + duplicate.type + ')' : '') + '. ' +
                    '<button type="button" class="btn btn-link btn-sm p-0 align-baseline" id="view-duplicate-feature">View in list</button>';

                document.getElementById('view-duplicate-feature')?.addEventListener('click', function () {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createFeatureModal'));
                    if (modal) modal.hide();

                    if (searchInput) searchInput.value = duplicate.name;
                    loadFeatures(1).then(function () {
                        if (!highlightFeatureRow(duplicate.id)) {
                            loadFeatures(1);
                        }
                    });
                });

                if (createSubmitBtn) createSubmitBtn.disabled = true;
            });
        }, 300);
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                loadFeatures(1);
            }, 350);
        });
    }

    createNameInput?.addEventListener('input', updateDuplicateWarning);

    initFeatureIconPickers(document);
    bindPaginationLinks();
    bindToggleStatusForms();

    @if($errors->any() && old('name'))
        updateDuplicateWarning();
        new bootstrap.Modal(document.getElementById('createFeatureModal')).show();
    @endif
});
</script>
@endpush
