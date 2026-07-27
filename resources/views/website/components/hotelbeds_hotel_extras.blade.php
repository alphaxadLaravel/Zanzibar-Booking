{{-- Hotelbeds static extras: reviews summary, issues, policies --}}
@php
    $reviews = $profile['reviews'] ?? [];
    $issues = $profile['issues'] ?? [];
    $phones = $profile['phones'] ?? [];
    $hasExtras = $reviews !== [] || $issues !== [] || $phones !== []
        || ! empty($profile['check_in_time']) || ! empty($profile['check_out_time']);
@endphp

@if($hasExtras)
    @if(!empty($profile['check_in_time']) || !empty($profile['check_out_time']))
        <hr>
        <section class="hotel-policies">
            <h4 class="section-title">Hotel Policies</h4>
            <div class="section-content">
                <ul class="list-unstyled mb-0" style="font-size: 0.95rem; color: #555;">
                    @if(!empty($profile['check_in_time']))
                        <li class="mb-2"><i class="mdi mdi-clock-outline me-2" style="color: #218080;"></i><strong>Check-in:</strong> {{ $profile['check_in_time'] }}</li>
                    @endif
                    @if(!empty($profile['check_out_time']))
                        <li class="mb-2"><i class="mdi mdi-clock-outline me-2" style="color: #218080;"></i><strong>Check-out:</strong> {{ $profile['check_out_time'] }}</li>
                    @endif
                </ul>
            </div>
        </section>
    @endif

    @if($reviews !== [])
        <hr>
        <section class="hotel-reviews-summary">
            <h4 class="section-title">Guest Ratings</h4>
            <div class="section-content">
                @foreach($reviews as $review)
                    <div class="d-flex align-items-center flex-wrap gap-3 mb-3 p-3 border rounded bg-white">
                        <div>
                            @include('website.components.star_rating', [
                                'rating' => min(5, max(0, (int) round((float) ($review['rate'] ?? 0)))),
                                'size' => 'small',
                            ])
                            <div class="fw-bold mt-1" style="font-size: 1.1rem; color: #333;">
                                {{ number_format((float) ($review['rate'] ?? 0), 1) }}/5
                            </div>
                        </div>
                        <div>
                            <div class="fw-semibold">{{ \App\Support\HotelbedsExtrasMapper::reviewSourceLabel($review['type'] ?? '') }}</div>
                            @if(!empty($review['review_count']))
                                <div class="text-muted small">Based on {{ number_format((int) $review['review_count']) }} review(s)</div>
                            @endif
                            <div class="text-muted small">Aggregate score from partner — not individual guest comments.</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if($issues !== [])
        <hr>
        <section class="hotel-notices">
            <h4 class="section-title">Important Notices</h4>
            <div class="section-content">
                @foreach($issues as $issue)
                    <div class="alert alert-warning border mb-3" style="font-size: 0.92rem;">
                        @if(!empty($issue['type']))
                            <div class="fw-semibold mb-1">{{ str_replace('_', ' ', $issue['type']) }}</div>
                        @endif
                        @if(!empty($issue['description']))
                            <p class="mb-2">{{ $issue['description'] }}</p>
                        @endif
                        @if(!empty($issue['date_from']) || !empty($issue['date_to']))
                            <div class="small text-muted">
                                @if(!empty($issue['date_from']))
                                    From {{ \Carbon\Carbon::parse($issue['date_from'])->format('M j, Y') }}
                                @endif
                                @if(!empty($issue['date_to']))
                                    to {{ \Carbon\Carbon::parse($issue['date_to'])->format('M j, Y') }}
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if($phones !== [])
        <hr>
        <section class="hotel-contact">
            <h4 class="section-title">Hotel Contact</h4>
            <div class="section-content">
                @foreach($phones as $phone)
                    <div class="mb-1"><i class="mdi mdi-phone me-2" style="color: #218080;"></i>{{ $phone }}</div>
                @endforeach
            </div>
        </section>
    @endif
@endif
