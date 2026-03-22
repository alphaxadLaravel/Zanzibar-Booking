@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h4 class="page-title mb-0">Edit user</h4>
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Users</a></li>
                <li class="breadcrumb-item active">{{ $user->full_name }}</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $hashids->encode($user->id)) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First name</label>
                        <input type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror" value="{{ old('firstname', $user->firstname) }}" required>
                        @error('firstname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last name</label>
                        <input type="text" name="lastname" class="form-control @error('lastname') is-invalid @enderror" value="{{ old('lastname', $user->lastname) }}" required>
                        @error('lastname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Account status</label>
                        <select name="status" class="form-select" required>
                            <option value="1" @selected(old('status', $user->status) == 1)>Active</option>
                            <option value="0" @selected(old('status', $user->status) == 0)>Inactive</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_suspended" id="is_suspended" value="1" @checked(old('is_suspended', $user->is_suspended))>
                            <label class="form-check-label" for="is_suspended">Suspend user (block login)</label>
                        </div>
                        <small class="text-muted">Suspended users cannot sign in until this is unchecked.</small>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
