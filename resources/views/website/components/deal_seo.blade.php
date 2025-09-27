{{-- Deal SEO Component --}}
@props(['deal'])

@php
use Illuminate\Support\Str;
@endphp

@section('title')
{{ $deal->seo_title ?: $deal->title }} - Zanzibar Bookings
@endsection

@section('meta')
<meta name="description" content="{{ $deal->seo_description ?: Str::limit(strip_tags($deal->description), 160) }}">
@if($deal->seo_keywords)
<meta name="keywords" content="{{ $deal->seo_keywords }}">
@endif

<meta property="og:type" content="website">
<meta property="og:url" content="{{ request()->url() }}">
<meta property="og:title" content="{{ $deal->seo_title ?: $deal->title }}">
<meta property="og:description"
    content="{{ $deal->seo_description ?: Str::limit(strip_tags($deal->description), 160) }}">
<meta property="og:image"
    content="{{ $deal->seo_image ? asset('storage/' . $deal->seo_image) : ($deal->cover_photo ? asset('storage/' . $deal->cover_photo) : asset('logo.png')) }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ request()->url() }}">
<meta property="twitter:title" content="{{ $deal->seo_title ?: $deal->title }}">
<meta property="twitter:description"
    content="{{ $deal->seo_description ?: Str::limit(strip_tags($deal->description), 160) }}">
<meta property="twitter:image"
    content="{{ $deal->seo_image ? asset('storage/' . $deal->seo_image) : ($deal->cover_photo ? asset('storage/' . $deal->cover_photo) : asset('logo.png')) }}">
@endsection
