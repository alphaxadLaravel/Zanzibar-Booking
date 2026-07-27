@extends('website.layouts.app')

@section('title', 'Hotel & Beds - Zanzibar Bookings')
@section('meta')
<meta name="description" content="Browse and book live hotel availability across Zanzibar and Tanzania via Hotelbeds">
@endsection

@section('pages')
@livewire('global-hotel-search-page')
@endsection
