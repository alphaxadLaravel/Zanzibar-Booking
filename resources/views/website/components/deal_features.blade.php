{{-- Deal Features Component --}}
@props(['deal', 'type' => 'include', 'title' => 'Features'])

<section class="feature">
    <h4 class="section-title">{{ $title }}</h4>
    <div class="section-content">
        <div class="d-flex flex-wrap" style="gap: 10px;">
            @if($type === 'include')
                @forelse($deal->features as $feature)
                <div class="facility-card d-flex align-items-center px-3 py-2 mb-2"
                    style="background: #fff; border-radius: 6px; border: 1px solid #e0e0e0; min-height: 38px; flex: 0 0 auto; min-width: 140px; max-width: 220px;">
                    @if($feature->icon)
                    <i class="mdi {{ $feature->icon }} me-2"
                        style="font-size: 1.2rem; color: #2e8b57; width: 20px; text-align: center;"></i>
                    @else
                    <i class="mdi mdi-check-circle me-2"
                        style="font-size: 1.2rem; color: #2e8b57; width: 20px; text-align: center;"></i>
                    @endif
                    <span style="font-size: 13px; font-weight: 500; color: #333; line-height: 1.3;">{{ $feature->name }}</span>
                </div>
                @empty
                <div class="text-muted" style="font-size: 14px;">No features listed.</div>
                @endforelse
            @elseif($type === 'tour_include')
                @forelse($deal->tourIncludes->where('type', 'include') as $tourInclude)
                <div class="facility-card d-flex align-items-center px-3 py-2 mb-2"
                    style="background: #fff; border-radius: 6px; border: 1px solid #e0e0e0; min-height: 38px; flex: 0 0 auto; min-width: 140px; max-width: 220px;">
                    @if($tourInclude->feature->icon)
                    <i class="mdi {{ $tourInclude->feature->icon }} me-2"
                        style="font-size: 1.2rem; color: #2e8b57; width: 20px; text-align: center;"></i>
                    @else
                    <i class="mdi mdi-check-circle me-2"
                        style="font-size: 1.2rem; color: #2e8b57; width: 20px; text-align: center;"></i>
                    @endif
                    <span style="font-size: 13px; font-weight: 500; color: #333; line-height: 1.3;">{{ $tourInclude->feature->name }}</span>
                </div>
                @empty
                <div class="text-muted" style="font-size: 14px;">No includes listed.</div>
                @endforelse
            @elseif($type === 'tour_exclude')
                @forelse($deal->tourIncludes->where('type', 'exclude') as $tourExclude)
                <div class="facility-card d-flex align-items-center px-3 py-2 mb-2"
                    style="background: #fff; border-radius: 6px; border: 1px solid #e0e0e0; min-height: 38px; flex: 0 0 auto; min-width: 140px; max-width: 220px;">
                    @if($tourExclude->feature->icon)
                    <i class="mdi {{ $tourExclude->feature->icon }} me-2"
                        style="font-size: 1.2rem; color: #dc3545; width: 20px; text-align: center;"></i>
                    @else
                    <i class="mdi mdi-close-circle me-2"
                        style="font-size: 1.2rem; color: #dc3545; width: 20px; text-align: center;"></i>
                    @endif
                    <span style="font-size: 13px; font-weight: 500; color: #333; line-height: 1.3;">{{ $tourExclude->feature->name }}</span>
                </div>
                @empty
                <div class="text-muted" style="font-size: 14px;">No excludes listed.</div>
                @endforelse
            @endif
        </div>
    </div>
</section>
