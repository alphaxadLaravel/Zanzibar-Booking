@extends('admin.layouts.app')

@section('title', 'Home Page SEO')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-3">
        <div class="col d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="page-title mb-0">Home Page SEO</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.system.settings') }}">Settings</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Home Page SEO</li>
                </ol>
            </nav>
        </div>
    </div>

    @if(session('success'))
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('admin.home.seo.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">SEO Settings for Home Page</h5>
                <small class="text-muted">Configure meta tags for the website homepage (index). Same as deal SEO.</small>
            </div>
            <div class="card-body row g-3">
                <div class="col-md-12">
                    <label class="form-label">SEO Title</label>
                    <input type="text" class="form-control @error('home_seo_title') is-invalid @enderror" name="home_seo_title"
                        value="{{ old('home_seo_title', $settings->home_seo_title ?? '') }}"
                        placeholder="e.g. Zanzibar Bookings - Hotels, Tours & Car Rentals">
                    <small class="text-muted">Leave empty to use default. Optimal length: 50-60 characters</small>
                    @error('home_seo_title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label">SEO Description</label>
                    <textarea class="form-control @error('home_seo_description') is-invalid @enderror" name="home_seo_description" rows="3"
                        placeholder="Enter SEO description (recommended: 150-160 characters)">{{ old('home_seo_description', $settings->home_seo_description ?? '') }}</textarea>
                    <small class="text-muted">Optimal length: 150-160 characters</small>
                    @error('home_seo_description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label">SEO Keywords</label>
                    <textarea class="form-control @error('home_seo_keywords') is-invalid @enderror" name="home_seo_keywords" rows="2"
                        placeholder="e.g. zanzibar, hotels, tours, car rental, booking">{{ old('home_seo_keywords', $settings->home_seo_keywords ?? '') }}</textarea>
                    <small class="text-muted">Separate keywords with commas</small>
                    @error('home_seo_keywords')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label">SEO Image</label>
                    <input type="file" class="form-control @error('home_seo_image') is-invalid @enderror" name="home_seo_image" accept="image/*">
                    <small class="text-muted">Recommended: 1200x630px for social sharing. Leave empty to use site logo</small>
                    @if(!empty($settings->home_seo_image))
                    <div class="mt-2">
                        <small class="text-muted">Current: </small>
                        <img src="{{ asset('storage/' . $settings->home_seo_image) }}" alt="SEO" class="img-thumbnail" style="max-height: 80px;">
                    </div>
                    @endif
                    @error('home_seo_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Save Home Page SEO
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
