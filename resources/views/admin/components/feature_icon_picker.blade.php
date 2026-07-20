@php
    $pickerId = $pickerId ?? 'default';
    $inputId = $inputId ?? 'feature_icon_' . $pickerId;
    $selected = $selected ?? '';
    $icons = config('feature_icons', []);
@endphp

<div class="feature-icon-picker" data-picker-id="{{ $pickerId }}">
    <input type="hidden" name="icon" id="{{ $inputId }}" value="{{ $selected }}" required>

    <div class="d-flex align-items-center gap-2 mb-2">
        <div class="feature-icon-preview border rounded d-flex align-items-center justify-content-center bg-light"
            style="width:48px;height:48px;">
            <i class="mdi {{ $selected ?: 'mdi-star-outline' }} fs-3" id="{{ $inputId }}_preview"></i>
        </div>
        <div class="flex-grow-1">
            <div class="small text-muted">Selected icon</div>
            <div class="fw-medium" id="{{ $inputId }}_label">{{ $selected ?: 'None selected' }}</div>
        </div>
    </div>

    <input type="text" class="form-control form-control-sm mb-2 feature-icon-search"
        placeholder="Search icons..." autocomplete="off">

    <div class="feature-icon-grid border rounded p-2 bg-white" style="max-height:220px;overflow-y:auto;">
        @foreach($icons as $icon)
        <button type="button"
            class="feature-icon-option btn btn-sm {{ $selected === $icon ? 'btn-primary' : 'btn-outline-secondary' }}"
            data-icon="{{ $icon }}" data-input-id="{{ $inputId }}" title="{{ str_replace('mdi-', '', $icon) }}">
            <i class="mdi {{ $icon }} fs-5"></i>
        </button>
        @endforeach
    </div>
</div>
