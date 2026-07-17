@extends('errors.layout')

@section('icon', 'mdi-map-search-outline')
@section('code', '404')
@section('title', 'Page Not Found')
@section('message', 'The page you are looking for might have been moved, deleted, or never existed. Let us help you get back on track.')

@section('actions')
    <a href="/" class="btn-primary-custom">
        <i class="mdi mdi-home-outline"></i> Go to Homepage
    </a>
    <a href="/contact-us" class="btn-outline-custom">
        <i class="mdi mdi-email-outline"></i> Contact Support
    </a>
@endsection
