@php
    $rating = $rating ?? 5; // Default to 5 stars if not provided
    $size = $size ?? 'normal'; // normal, small, large
    $showText = $showText ?? false; // Whether to show rating text
    
    // Size classes
    $sizeClass = '';
    switch($size) {
        case 'small':
            $sizeClass = 'fa-sm';
            break;
        case 'large':
            $sizeClass = 'fa-lg';
            break;
        default:
            $sizeClass = '';
    }
@endphp

<div class="star-rating d-flex align-items-center">
    @for($i = 1; $i <= 5; $i++)
        <i class="mdi mdi-star {{ $i <= $rating ? 'text-warning' : 'text-muted' }} {{ $sizeClass }}" style="font-size: 1.5rem;"></i>
    @endfor
    @if($showText)
        <span class="ms-2 text-muted small">{{ $rating }}/5</span>
    @endif
</div>
