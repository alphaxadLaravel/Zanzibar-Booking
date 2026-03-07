{{-- Home / Index page SEO --}}
@php
$title = $home_seo_title ?? 'Zanzibar Bookings - Hotels, Tours & Car Rentals';
$description = $home_seo_description ?? 'Book hotels, apartments, tours, activities and car rentals in Zanzibar. Find the best deals and plan your trip with Zanzibar Bookings.';
$keywords = $home_seo_keywords ?? null;
$image = $home_seo_image ?? null;
@endphp

@section('title')
{{ $title }}
@endsection

@section('meta')
<meta name="description" content="{{ $description }}">
@if($keywords)
<meta name="keywords" content="{{ $keywords }}">
@endif

<meta property="og:type" content="website">
<meta property="og:url" content="{{ request()->url() }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image ? asset('storage/' . $image) : asset('logo.png') }}">

<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ request()->url() }}">
<meta property="twitter:title" content="{{ $title }}">
<meta property="twitter:description" content="{{ $description }}">
<meta property="twitter:image" content="{{ $image ? asset('storage/' . $image) : asset('logo.png') }}">
@endsection
