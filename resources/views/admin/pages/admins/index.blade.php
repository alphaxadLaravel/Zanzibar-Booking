@extends('admin.layouts.app')

@section('title', 'Admin accounts')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-3">
        <div class="col">
            <h4 class="page-title mb-0">Admin accounts</h4>
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Admins</li>
            </ol>
            <p class="text-muted small mb-0 mt-1">Only the Super Admin can create admins and control what each one can access.</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
                <i class="mdi mdi-shield-account me-1"></i> Add admin
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Permissions</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($admins as $admin)
                            <tr>
                                <td>{{ $admin->full_name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>
                                    @if($admin->is_suspended && !$admin->status && $admin->permissions->count() === 0)
                                        <span class="badge bg-dark">Deactivated</span>
                                    @elseif($admin->is_suspended)
                                        <span class="badge bg-danger">Suspended</span>
                                    @elseif($admin->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $admin->permissions->count() }} assigned</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.admins.edit', $hashids->encode($admin->id)) }}" class="btn btn-sm btn-outline-primary">Manage</a>
                                    @if($admin->id !== auth()->id())
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deactivateAdminModal{{ $admin->id }}"
                                        >
                                            Deactivate
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No admin accounts yet. Create one to delegate access.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($admins->hasPages())
            <div class="card-footer">{{ $admins->links() }}</div>
        @endif
    </div>
</div>

@foreach($admins as $admin)
    @if($admin->id !== auth()->id())
    <div class="modal fade" id="deactivateAdminModal{{ $admin->id }}" tabindex="-1" aria-labelledby="deactivateAdminModalLabel{{ $admin->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deactivateAdminModalLabel{{ $admin->id }}">Deactivate admin account</h5>
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
@endforeach
@endsection
