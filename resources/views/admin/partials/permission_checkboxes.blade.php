@php
    $selected = $selected ?? [];
@endphp

<div class="permission-picker border rounded bg-white">
    <div class="p-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h6 class="mb-1">Access permissions</h6>
            <p class="text-muted small mb-0">Grouped like the admin sidebar. Create, edit, delete and manage actions automatically include view access.</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-primary" data-perm-action="select-all">Select all</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-perm-action="clear-all">Clear all</button>
        </div>
    </div>

    <div class="p-3">
        @foreach($permissionSections as $sectionIndex => $section)
            @if($sectionIndex > 0)
                <hr class="my-4">
            @endif

            <div class="permission-section mb-1" data-section-key="{{ $section['key'] }}">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <h6 class="text-uppercase text-muted small mb-0">
                        <i class="mdi {{ $section['icon'] }} me-1"></i>{{ $section['title'] }}
                    </h6>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-link p-0" data-perm-action="select-section">Select section</button>
                        <button type="button" class="btn btn-sm btn-link p-0 text-muted" data-perm-action="clear-section">Clear</button>
                    </div>
                </div>

                <div class="row g-3">
                    @foreach($section['modules'] as $module)
                        <div class="col-md-6 col-xl-4">
                            <div class="permission-module border rounded p-3 h-100" data-module-key="{{ $module['key'] }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold small">
                                        <i class="mdi {{ $module['icon'] }} me-1 text-secondary"></i>{{ $module['label'] }}
                                    </span>
                                    <button type="button" class="btn btn-sm btn-link p-0" data-perm-action="select-module">All</button>
                                </div>
                                @php
                                    $viewPermission = \App\Support\PermissionDependencies::viewPermissionForModule($module['permissions']);
                                @endphp
                                @foreach($module['permissions'] as $permission)
                                    <label class="permission-option d-flex align-items-center gap-2 mb-2" for="perm_{{ $permission->id }}">
                                        <input
                                            type="checkbox"
                                            class="form-check-input permission-checkbox mt-0 flex-shrink-0"
                                            name="permissions[]"
                                            id="perm_{{ $permission->id }}"
                                            value="{{ $permission->id }}"
                                            data-perm-slug="{{ $permission->slug }}"
                                            @if($viewPermission && $permission->id === $viewPermission->id)
                                                data-perm-view-for="{{ $module['key'] }}"
                                            @endif
                                            @if($viewPermission && $permission->id !== $viewPermission->id)
                                                data-perm-requires-view="{{ $module['key'] }}"
                                            @endif
                                            @checked(in_array($permission->id, $selected, true))
                                        >
                                        <span>{{ $permission->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

@once
    @push('styles')
    <style>
        .permission-option { cursor: pointer; user-select: none; font-size: 0.8125rem; }
        .permission-module .fw-semibold.small { font-size: 0.8rem; }
        .permission-checkbox { cursor: pointer; width: 1em; height: 1em; }
        [data-perm-action] { cursor: pointer; }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const picker = document.querySelector('.permission-picker');
            if (!picker) return;

            const allCheckboxes = () => picker.querySelectorAll('.permission-checkbox');

            const syncModuleViewPermissions = (moduleEl) => {
                if (!moduleEl) return;

                const viewCheckbox = moduleEl.querySelector('[data-perm-view-for]');
                if (!viewCheckbox) return;

                const actionCheckboxes = moduleEl.querySelectorAll('[data-perm-requires-view]');
                const anyActionChecked = Array.from(actionCheckboxes).some((cb) => cb.checked);

                if (anyActionChecked) {
                    viewCheckbox.checked = true;
                }
            };

            picker.addEventListener('change', function (event) {
                const checkbox = event.target.closest('.permission-checkbox');
                if (!checkbox) return;

                const moduleEl = checkbox.closest('.permission-module');
                const viewCheckbox = moduleEl?.querySelector('[data-perm-view-for]');

                if (viewCheckbox && checkbox === viewCheckbox && !checkbox.checked) {
                    const hasOtherActions = Array.from(
                        moduleEl.querySelectorAll('[data-perm-requires-view]')
                    ).some((cb) => cb.checked);

                    if (hasOtherActions) {
                        checkbox.checked = true;
                    }
                }

                if (checkbox.hasAttribute('data-perm-requires-view') && checkbox.checked) {
                    if (viewCheckbox) {
                        viewCheckbox.checked = true;
                    }
                }

                syncModuleViewPermissions(moduleEl);
            });

            picker.addEventListener('click', function (event) {
                const button = event.target.closest('[data-perm-action]');
                if (!button) return;

                const action = button.dataset.permAction;
                let targets = [];

                if (action === 'select-all' || action === 'clear-all') {
                    targets = allCheckboxes();
                } else if (action === 'select-section' || action === 'clear-section') {
                    targets = button.closest('.permission-section').querySelectorAll('.permission-checkbox');
                } else if (action === 'select-module') {
                    targets = button.closest('.permission-module').querySelectorAll('.permission-checkbox');
                }

                const checked = action.startsWith('select');
                targets.forEach((checkbox) => { checkbox.checked = checked; });

                if (action.startsWith('select')) {
                    picker.querySelectorAll('.permission-module').forEach((moduleEl) => {
                        syncModuleViewPermissions(moduleEl);
                    });
                }
            });

            picker.querySelectorAll('.permission-module').forEach((moduleEl) => {
                syncModuleViewPermissions(moduleEl);
            });
        });
    </script>
    @endpush
@endonce
