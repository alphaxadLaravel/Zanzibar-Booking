@extends('admin.layouts.app')

@section('title', $prefillPartner ? 'Add partner' : 'Add user')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="page-title mb-0">{{ $prefillPartner ? 'Add partner' : 'Add user' }}</h4>
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Users</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Back to list</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('admin.users.store') }}" method="POST" class="row g-3">
                @csrf
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
                    <label class="form-label">Role</label>
                    @php
                        $defaultRoleId = old('role_id');
                        if ($defaultRoleId === null || $defaultRoleId === '') {
                            $defaultRoleId = $prefillPartner
                                ? optional($roles->firstWhere('name', 'Partner'))->id
                                : (optional($roles->firstWhere('name', 'User'))->id ?? $roles->first()->id);
                        }
                    @endphp
                    <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" @selected((int) $defaultRoleId === (int) $role->id)>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Account status</label>
                    <select name="status" class="form-select" required>
                        <option value="1" @selected(old('status', '1') == '1')>Active</option>
                        <option value="0" @selected(old('status') == '0')>Inactive / pending</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Create account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
