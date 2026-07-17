@extends('errors.layout')

@section('icon', 'mdi-server-off')
@section('code', '500')
@section('title', 'Something Went Wrong')
@section('message', 'We hit an unexpected error while processing your request. Our team has been notified. Please try again in a few moments.')

@section('actions')
    <a href="/" class="btn-primary-custom">
        <i class="mdi mdi-home-outline"></i> Go to Homepage
    </a>
    <a href="javascript:location.reload()" class="btn-outline-custom">
        <i class="mdi mdi-refresh"></i> Try Again
    </a>
@endsection
