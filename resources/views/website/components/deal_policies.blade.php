{{-- Deal Policies Component --}}
@props(['deal', 'title' => 'Our Policies'])

<section class="description">
    <h4 class="section-title">{{ $title }}</h4>
    <div class="section-content">
        <p>
            {!! $deal->policies !!}
        </p>
    </div>
</section>
