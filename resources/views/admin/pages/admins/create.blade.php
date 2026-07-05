@extends('admin.layouts.app')

@section('title', 'Add admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="page-title mb-0">Add admin</h4>
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.admins.index') }}">Admins</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
            <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary">Back</a>
        </div>
    </div>

    <form action="{{ route('admin.admins.store') }}" method="POST">
        @csrf

        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Account details</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First name</label>
                        <input type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror" value="{{ old('firstname') }}" required>
                        @error('firstname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last name</label>
                        <input type="text" name="lastname" class="form-control @error('lastname') is-invalid @enderror" value="{{ old('lastname') }}" required>
                        @error('lastname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="off">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm password</label>
                        <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Account status</label>
                        <select name="status" class="form-select" required>
                            <option value="1" @selected(old('status', '1') == '1')>Active</option>
                            <option value="0" @selected(old('status') == '0')>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.partials.permission_checkboxes', [
            'permissionSections' => $permissionSections,
            'selected' => array_map('intval', old('permissions', [])),
        ])

        <div class="mt-3">
            <button type="submit" class="btn btn-primary" data-loading-text="Creating...">Create admin</button>
        </div>
    </form>
</div>
@endsection
