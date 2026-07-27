@extends('website.layouts.app')

@section('title', 'Hotel & Beds - Tanzania & Zanzibar')
@section('meta')
<meta name="description" content="Browse and book live hotel availability across Tanzania and Zanzibar via Hotelbeds">
@endsection

@section('pages')
@livewire('global-hotel-search-page')
@endsection
