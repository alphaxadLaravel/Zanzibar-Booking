{{-- Deal Description Component --}}
@props(['deal', 'title' => 'Overview'])

<section class="description">
    <h4 class="section-title">{{ $title }}</h4>
    <div class="section-content">
        <p>
            {!! $deal->description !!}
        </p>
    </div>
</section>
