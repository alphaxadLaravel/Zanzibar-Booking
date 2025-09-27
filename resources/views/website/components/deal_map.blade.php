{{-- Deal Map Component --}}
@props(['deal', 'title' => 'Location On Map'])

<section class="map">
    <h4 class="section-title mb-4">{{ $title }}</h4>
    <div id="address-map-container" style="width: 100%; height: 400px">
        @if($deal->lat && $deal->long)
        <iframe width="100%" height="100%" frameborder="0" style="border:0; border-radius: 8px;"
            src="https://www.google.com/maps?q={{ $deal->lat }},{{ $deal->long }}&output=embed"
            allowfullscreen aria-hidden="false" tabindex="0" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        @else
        <iframe width="100%" height="100%" frameborder="0" style="border:0; border-radius: 8px;"
            src="https://www.google.com/maps?q={{ $deal->location }}&output=embed" allowfullscreen
            aria-hidden="false" tabindex="0" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        @endif
    </div>
</section>
