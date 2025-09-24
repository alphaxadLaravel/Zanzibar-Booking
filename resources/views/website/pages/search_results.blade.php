@extends('website.layouts.app')

@section('pages')
@livewire('search-results', ['location' => $location ?? '', 'category' => $category ?? '', 'name' => $name ?? ''])
@endsection
