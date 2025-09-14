@extends('admin.layouts.app')

@section('title', 'General Settings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">General Settings</li>
                    </ol>
                </div>
                <h4 class="page-title">General Settings</h4>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Site Configuration</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.general.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="site_name" class="form-label">Site Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('site_name') is-invalid @enderror" 
                                           id="site_name" name="site_name" value="{{ old('site_name', 'Zbook Travel') }}" required>
                                    @error('site_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="site_tagline" class="form-label">Site Tagline</label>
                                    <input type="text" class="form-control @error('site_tagline') is-invalid @enderror" 
                                           id="site_tagline" name="site_tagline" value="{{ old('site_tagline', 'Your Perfect Travel Companion') }}">
                                    @error('site_tagline')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_email" class="form-label">Contact Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                           id="contact_email" name="contact_email" value="{{ old('contact_email', 'contact@zbook.com') }}" required>
                                    @error('contact_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_phone" class="form-label">Contact Phone</label>
                                    <input type="tel" class="form-control @error('contact_phone') is-invalid @enderror" 
                                           id="contact_phone" name="contact_phone" value="{{ old('contact_phone', '+1-555-0123') }}">
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="site_description" class="form-label">Site Description</label>
                            <textarea class="form-control @error('site_description') is-invalid @enderror" 
                                      id="site_description" name="site_description" rows="4">{{ old('site_description', 'Zbook is your one-stop destination for all travel needs. Book hotels, apartments, cars, and tours with ease.') }}</textarea>
                            @error('site_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="default_currency" class="form-label">Default Currency</label>
                                    <select class="form-select @error('default_currency') is-invalid @enderror" id="default_currency" name="default_currency">
                                        <option value="USD" {{ old('default_currency', 'USD') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                        <option value="EUR" {{ old('default_currency', 'USD') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                        <option value="GBP" {{ old('default_currency', 'USD') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                        <option value="JPY" {{ old('default_currency', 'USD') == 'JPY' ? 'selected' : '' }}>JPY - Japanese Yen</option>
                                    </select>
                                    @error('default_currency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="timezone" class="form-label">Timezone</label>
                                    <select class="form-select @error('timezone') is-invalid @enderror" id="timezone" name="timezone">
                                        <option value="UTC" {{ old('timezone', 'UTC') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                        <option value="America/New_York" {{ old('timezone', 'UTC') == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                        <option value="America/Los_Angeles" {{ old('timezone', 'UTC') == 'America/Los_Angeles' ? 'selected' : '' }}>America/Los_Angeles</option>
                                        <option value="Europe/London" {{ old('timezone', 'UTC') == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                                    </select>
                                    @error('timezone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" 
                                       {{ old('maintenance_mode') ? 'checked' : '' }}>
                                <label class="form-check-label" for="maintenance_mode">
                                    Maintenance Mode (Site will be temporarily unavailable)
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy"></i> Save Settings
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.settings.security') }}" class="btn btn-outline-primary">
                            <i class="ti ti-shield"></i> Security Settings
                        </a>
                        <button class="btn btn-outline-success">
                            <i class="ti ti-database"></i> Backup Database
                        </button>
                        <button class="btn btn-outline-warning">
                            <i class="ti ti-refresh"></i> Clear Cache
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
