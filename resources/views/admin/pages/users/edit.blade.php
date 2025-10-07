@extends('admin.layouts.app')

@section('title', 'Edit User - ' . $user->full_name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Users</a></li>
                        <li class="breadcrumb-item active">Edit User</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit User - {{ $user->full_name }}</h4>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Page Content -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">User Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $hashids->encode($user->id)) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="firstname" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" 
                                           value="{{ old('firstname', $user->firstname) }}" required>
                                    @error('firstname')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lastname" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" 
                                           value="{{ old('lastname', $user->lastname) }}" required>
                                    @error('lastname')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone" 
                                           value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role_id" class="form-label">Role</label>
                                    <select class="form-select" id="role_id" name="role_id" required>
                                        <option value="">Select Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" 
                                                    {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left"></i> Back to Users
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check"></i> Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">User Details</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                            {{ strtoupper(substr($user->firstname, 0, 1)) }}{{ strtoupper(substr($user->lastname, 0, 1)) }}
                        </div>
                        <h5>{{ $user->full_name }}</h5>
                        <p class="text-muted">{{ $user->email }}</p>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-2">
                        <strong>Current Role:</strong>
                        @if($user->role)
                            <span class="badge bg-info">{{ $user->role->name }}</span>
                        @else
                            <span class="badge bg-secondary">No Role</span>
                        @endif
                    </div>
                    
                    <div class="mb-2">
                        <strong>Status:</strong>
                        @if($user->status)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </div>
                    
                    <div class="mb-2">
                        <strong>Email Verified:</strong>
                        @if($user->email_verified_at)
                            <span class="badge bg-success">
                                <i class="ti ti-check"></i> Verified
                            </span>
                        @else
                            <span class="badge bg-warning">
                                <i class="ti ti-clock"></i> Pending
                            </span>
                        @endif
                    </div>
                    
                    <div class="mb-2">
                        <strong>Registered:</strong><br>
                        <small class="text-muted">{{ $user->created_at->format('M d, Y h:i A') }}</small>
                    </div>
                    
                    <div class="mb-2">
                        <strong>Last Updated:</strong><br>
                        <small class="text-muted">{{ $user->updated_at->format('M d, Y h:i A') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
