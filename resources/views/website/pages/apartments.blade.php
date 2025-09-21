@extends('website.layouts.app')

@section('pages')
@livewire('all-deals-listing', ['dealType' => 'apartment'])
@endsection
