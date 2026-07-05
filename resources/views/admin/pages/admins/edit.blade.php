@extends('admin.layouts.app')

@section('title', 'Edit admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="page-title mb-0">Edit admin</h4>
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.admins.index') }}">Admins</a></li>
                    <li class="breadcrumb-item active">{{ $admin->full_name }}</li>
                </ol>
            </div>
            <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary">Back</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.admins.update', $hashids->encode($admin->id)) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Account details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">First name</label>
                                <input type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror" value="{{ old('firstname', $admin->firstname) }}" required>
                                @error('firstname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Last name</label>
                                <input type="text" name="lastname" class="form-control @error('lastname') is-invalid @enderror" value="{{ old('lastname', $admin->lastname) }}" required>
                                @error('lastname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $admin->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $admin->phone) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Account status</label>
                                <select name="status" class="form-select" required>
                                    <option value="1" @selected(old('status', $admin->status) == 1)>Active</option>
                                    <option value="0" @selected(old('status', $admin->status) == 0)>Inactive</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="is_suspended" id="is_suspended" value="1" @checked(old('is_suspended', $admin->is_suspended))>
                                    <label class="form-check-label" for="is_suspended">Suspend admin (block login)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                @include('admin.partials.permission_checkboxes', [
                    'permissionSections' => $permissionSections,
                    'selected' => array_map('intval', old('permissions', $admin->permissions->pluck('id')->all())),
                ])
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary" data-loading-text="Saving...">Save changes</button>
        </div>
    </form>

    <div class="row g-3 mt-1">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Change password</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.admins.password.update', $hashids->encode($admin->id)) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="password" class="form-label">New password</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm new password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required autocomplete="new-password">
                        </div>
                        <button type="submit" class="btn btn-warning w-100" data-loading-text="Updating...">
                            <i class="mdi mdi-key me-1"></i> Update password
                        </button>
                    </form>
                </div>
            </div>

            @if($admin->id !== auth()->id())
            <div class="card border-danger mt-3">
                <div class="card-header bg-danger-subtle">
                    <h5 class="card-title mb-0 text-danger">Danger zone</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        Deactivating revokes admin access and clears permissions. The account is kept for record keeping.
                    </p>
                    <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#deactivateAdminModal">
                        <i class="mdi mdi-account-off me-1"></i> Deactivate admin
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@if($admin->id !== auth()->id())
<div class="modal fade" id="deactivateAdminModal" tabindex="-1" aria-labelledby="deactivateAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deactivateAdminModalLabel">Deactivate admin account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to deactivate <strong>{{ $admin->full_name }}</strong>?</p>
                <ul class="small text-muted mb-0">
                    <li>They will be suspended and marked inactive</li>
                    <li>All assigned permissions will be removed</li>
                    <li>The account will <strong>not</strong> be deleted from the database</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.admins.delete', $hashids->encode($admin->id)) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" data-loading-text="Deactivating...">Yes, deactivate</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
