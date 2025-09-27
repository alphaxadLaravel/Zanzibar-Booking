{{-- Deal Itinerary Component --}}
@props(['deal'])

@if($deal->itineraries && $deal->itineraries->count() > 0)
<style>
    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        left: 22px;
        /* centers line with badge */
        width: 2px;
        height: 100%;
        background: #dee2e6;
        /* Bootstrap gray */
        z-index: 0;
    }

    .timeline-marker {
        position: relative;
        z-index: 1;
    }
</style>

<section class="itinerary my-4">
    <h4 class="fw-bold mb-4">Itinerary</h4>

    <div class="timeline position-relative">
        @foreach($deal->itineraries as $index => $itinerary)
        <div class="timeline-item d-flex mb-5">
            <!-- Circle with number -->
            <div class="timeline-marker d-flex flex-column align-items-center">
                <span
                    class="badge rounded-circle bg-dark text-white fs-6 d-flex align-items-center justify-content-center"
                    style="width:45px; height:45px;">
                    {{ $index + 1 }}
                </span>
                @if(!$loop->last)
                <div class="timeline-line flex-grow-1 bg-dark" style="width:2px;"></div>
                @endif
            </div>

            <!-- Content -->
            <div class="ms-4">
                <h6 class="fw-bold mb-2">{{ $itinerary->title ?? 'Day ' . ($index + 1) }}</h6>
                <p class="mb-3">{!! $itinerary->description !!}</p>

                <div class="d-flex flex-wrap gap-3 small text-muted align-items-center">
                    @if(!empty($itinerary->location))
                    <div>
                        <i class="bi bi-geo-alt me-1"></i>{{ $itinerary->location }}
                    </div>
                    @endif

                    @if(!empty($itinerary->location) && !empty($itinerary->time))
                    <span class="mx-1">•</span>
                    @endif

                    @if(!empty($itinerary->time))
                    <div>
                        <i class="bi bi-clock me-1"></i>{{ $itinerary->time }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif
