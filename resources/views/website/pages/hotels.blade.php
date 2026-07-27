@extends('website.layouts.app')

@section('pages')
<div class="container py-3">
    <div class="alert alert-primary d-flex flex-wrap justify-content-between align-items-center gap-2 mb-0">
        <div>
            <strong>Need more options?</strong>
            Search live availability across hundreds of partner hotels in Zanzibar & Tanzania.
        </div>
        <a href="{{ route('hotels.global.index') }}" class="btn btn-sm btn-light">Search partner hotels</a>
    </div>
</div>
@livewire('all-deals-listing', ['dealType' => 'hotel'])
@endsection