@extends('website.layouts.app')

@section('pages')
@livewire('all-deals-listing', ['dealType' => 'activity'])
@endsection
