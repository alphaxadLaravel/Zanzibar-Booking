@extends('errors.layout')

@section('icon', 'mdi-wrench-outline')
@section('code', '503')
@section('title', 'We\'ll Be Right Back')
@section('message', 'Zanzibar Bookings is temporarily unavailable while we perform maintenance. Please check back shortly.')

@section('actions')
    <a href="javascript:location.reload()" class="btn-primary-custom">
        <i class="mdi mdi-refresh"></i> Refresh Page
    </a>
    <a href="/" class="btn-outline-custom">
        <i class="mdi mdi-home-outline"></i> Go to Homepage
    </a>
@endsection
