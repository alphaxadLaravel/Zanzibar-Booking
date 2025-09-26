@extends('admin.layouts.app')

@section('title', 'Deals')

@section('content')
@livewire('deals-list', ['dealType' => $dealType])
@endsection
