@extends('admin.layouts.app')

@section('title', 'Security Settings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Security Settings</li>
                    </ol>
                </div>
                <h4 class="page-title">Security Settings</h4>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Security Configuration</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.security.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <h6>Password Policy</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="min_password_length" class="form-label">Minimum Password Length</label>
                                        <input type="number" class="form-control @error('min_password_length') is-invalid @enderror" 
                                               id="min_password_length" name="min_password_length" value="{{ old('min_password_length', '8') }}" min="6" max="20">
                                        @error('min_password_length')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_expiry_days" class="form-label">Password Expiry (Days)</label>
                                        <input type="number" class="form-control @error('password_expiry_days') is-invalid @enderror" 
                                               id="password_expiry_days" name="password_expiry_days" value="{{ old('password_expiry_days', '90') }}" min="30" max="365">
                                        @error('password_expiry_days')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="require_uppercase" name="require_uppercase" value="1" 
                                           {{ old('require_uppercase', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="require_uppercase">
                                        Require uppercase letters
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="require_numbers" name="require_numbers" value="1" 
                                           {{ old('require_numbers', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="require_numbers">
                                        Require numbers
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="require_symbols" name="require_symbols" value="1" 
                                           {{ old('require_symbols') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="require_symbols">
                                        Require special characters
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-4">
                            <h6>Login Security</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_login_attempts" class="form-label">Max Login Attempts</label>
                                        <input type="number" class="form-control @error('max_login_attempts') is-invalid @enderror" 
                                               id="max_login_attempts" name="max_login_attempts" value="{{ old('max_login_attempts', '5') }}" min="3" max="10">
                                        @error('max_login_attempts')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="lockout_duration" class="form-label">Lockout Duration (Minutes)</label>
                                        <input type="number" class="form-control @error('lockout_duration') is-invalid @enderror" 
                                               id="lockout_duration" name="lockout_duration" value="{{ old('lockout_duration', '15') }}" min="5" max="60">
                                        @error('lockout_duration')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="two_factor_auth" name="two_factor_auth" value="1" 
                                           {{ old('two_factor_auth') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="two_factor_auth">
                                        Enable Two-Factor Authentication
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="session_timeout" name="session_timeout" value="1" 
                                           {{ old('session_timeout', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="session_timeout">
                                        Enable session timeout
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-4">
                            <h6>API Security</h6>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="api_rate_limiting" name="api_rate_limiting" value="1" 
                                           {{ old('api_rate_limiting', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="api_rate_limiting">
                                        Enable API rate limiting
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="api_rate_limit" class="form-label">API Rate Limit (requests per minute)</label>
                                <input type="number" class="form-control @error('api_rate_limit') is-invalid @enderror" 
                                       id="api_rate_limit" name="api_rate_limit" value="{{ old('api_rate_limit', '60') }}" min="10" max="1000">
                                @error('api_rate_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy"></i> Save Settings
                            </button>
                            <a href="{{ route('admin.settings.general') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left"></i> Back to General
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Security Status</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>SSL Certificate</span>
                            <span class="badge bg-success">Active</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Firewall</span>
                            <span class="badge bg-success">Enabled</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Backup Status</span>
                            <span class="badge bg-success">Up to Date</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Last Security Scan</span>
                            <span class="text-muted">2 hours ago</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-warning">
                            <i class="ti ti-shield-check"></i> Run Security Scan
                        </button>
                        <button class="btn btn-outline-info">
                            <i class="ti ti-key"></i> Generate API Key
                        </button>
                        <button class="btn btn-outline-danger">
                            <i class="ti ti-logout"></i> Logout All Users
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
