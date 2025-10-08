@extends('admin.layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row align-items-center mb-3">
        <div class="col d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="page-title mb-0">System Settings</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">System Settings</li>
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

    <!-- Settings Form -->
    <form action="{{ route('admin.system.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Contact Information -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Contact Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $settings->email) }}">
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Primary Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $settings->phone) }}">
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="secondary_phone" class="form-label">Secondary Phone</label>
                            <input type="text" class="form-control @error('secondary_phone') is-invalid @enderror" id="secondary_phone" name="secondary_phone" value="{{ old('secondary_phone', $settings->secondary_phone) }}">
                            @error('secondary_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Physical Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2">{{ old('address', $settings->address) }}</textarea>
                            @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="whatsapp_url" class="form-label">WhatsApp URL</label>
                            <input type="url" class="form-control @error('whatsapp_url') is-invalid @enderror" id="whatsapp_url" name="whatsapp_url" value="{{ old('whatsapp_url', $settings->whatsapp_url) }}" placeholder="https://wa.me/...">
                            @error('whatsapp_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Media Links -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Social Media Links</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="facebook_url" class="form-label">Facebook URL</label>
                            <input type="url" class="form-control @error('facebook_url') is-invalid @enderror" id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $settings->facebook_url) }}" placeholder="https://facebook.com/...">
                            @error('facebook_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="instagram_url" class="form-label">Instagram URL</label>
                            <input type="url" class="form-control @error('instagram_url') is-invalid @enderror" id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $settings->instagram_url) }}" placeholder="https://instagram.com/...">
                            @error('instagram_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="youtube_url" class="form-label">YouTube URL</label>
                            <input type="url" class="form-control @error('youtube_url') is-invalid @enderror" id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $settings->youtube_url) }}" placeholder="https://youtube.com/...">
                            @error('youtube_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tripadvisor_url" class="form-label">TripAdvisor URL</label>
                            <input type="url" class="form-control @error('tripadvisor_url') is-invalid @enderror" id="tripadvisor_url" name="tripadvisor_url" value="{{ old('tripadvisor_url', $settings->tripadvisor_url) }}" placeholder="https://tripadvisor.com/...">
                            @error('tripadvisor_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- About & Media -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">About & Media</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="about_text" class="form-label">About Text</label>
                            <textarea class="form-control @error('about_text') is-invalid @enderror" id="about_text" name="about_text" rows="4" placeholder="Brief description about your company">{{ old('about_text', $settings->about_text) }}</textarea>
                            @error('about_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="header_photo" class="form-label">Header Photo</label>
                                <input type="file" class="form-control @error('header_photo') is-invalid @enderror" id="header_photo" name="header_photo" accept="image/*">
                                @error('header_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($settings->header_photo)
                                <div class="mt-2">
                                    <small class="text-muted">Current: {{ basename($settings->header_photo) }}</small>
                                    <img src="{{ Storage::url($settings->header_photo) }}" alt="Header Photo" class="img-thumbnail mt-1" style="max-height: 100px;">
                                </div>
                                @endif
                                <small class="text-muted">Max size: 5MB (JPEG, PNG, JPG, WEBP)</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="video_file" class="form-label">Video File</label>
                                <input type="file" class="form-control @error('video_file') is-invalid @enderror" id="video_file" name="video_file" accept="video/*">
                                @error('video_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($settings->video_file)
                                <div class="mt-2">
                                    <small class="text-muted">Current: {{ basename($settings->video_file) }}</small>
                                </div>
                                @endif
                                <small class="text-muted">Max size: 50MB (MP4, MOV, AVI, WMV)</small>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="mdi mdi-content-save"></i> Save Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
