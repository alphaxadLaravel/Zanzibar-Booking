@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Users</a></li>
                        <li class="breadcrumb-item active">{{ $user->full_name }}</li>
                    </ol>
                </div>
                <h4 class="page-title">User Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                        {{ strtoupper(substr($user->firstname, 0, 1)) }}{{ strtoupper(substr($user->lastname, 0, 1)) }}
                    </div>
                    <h4 class="mb-1">{{ $user->full_name }}</h4>
                    <p class="text-muted mb-3">{{ optional($user->role)->name ?? 'No role assigned' }}</p>

                    <div class="text-start text-muted border rounded px-3 py-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                            <span>Account</span>
                            <span class="text-dark fw-medium">{{ $user->status ? 'Active' : 'Inactive' }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                            <span>Email</span>
                            <span class="text-dark fw-medium">{{ $user->email_verified_at ? 'Verified' : 'Not verified' }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2">
                            <span>Login</span>
                            <span class="text-dark fw-medium">{{ $user->is_suspended ? 'Suspended' : 'Allowed' }}</span>
                        </div>
                    </div>

                    <p class="text-muted mb-3">
                        Registered {{ $user->created_at->format('M d, Y · g:i A') }}
                    </p>

                    <div class="d-grid gap-2">
                        @if(isset($hashids))
                            <a href="{{ route('admin.users.edit', $hashids->encode($user->id)) }}" class="btn btn-outline-primary">Edit user</a>
                        @endif
                        @if(optional($user->role)->name === 'Partner' && isset($hashids))
                            <a href="{{ route('admin.partners.assign-deals', $hashids->encode($user->id)) }}" class="btn btn-outline-secondary">Assign deals</a>
                        @endif
                        @if(isset($hashids) && $user->id !== auth()->id())
                            @if(!$user->is_suspended)
                                <form action="{{ route('admin.users.suspend', $hashids->encode($user->id)) }}" method="POST" onsubmit="return confirm('Suspend this user? They will not be able to log in.');">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-danger w-100">Suspend user (block login)</button>
                                </form>
                            @else
                                <form action="{{ route('admin.users.unsuspend', $hashids->encode($user->id)) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success w-100">Remove suspension (allow login)</button>
                                </form>
                            @endif
                        @endif
                        @if(optional($user->role)->name === 'Partner' && (int) $user->status !== 1)
                            <form action="{{ route('admin.users.partner.approve', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success w-100">Approve partner</button>
                            </form>
                            <form action="{{ route('admin.users.partner.reject', $user->id) }}" method="POST" onsubmit="return confirm('Reject this partner request?');">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-outline-danger w-100">Reject partner</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Contact</h5>
                </div>
                <div class="card-body">
                    <p class="mb-3"><strong>Email</strong><br><span class="text-muted">{{ $user->email }}</span></p>
                    <p class="mb-3"><strong>Phone</strong><br><span class="text-muted">{{ $user->phone ?? '—' }}</span></p>
                    <p class="mb-0"><strong>Role</strong><br><span class="text-muted">{{ optional($user->role)->name ?? '—' }}</span></p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Bookings</h5>
                </div>
                <div class="card-body">
                    @livewire('admin.user-bookings-table', ['userId' => $user->id])
                </div>
            </div>

            @if(optional($user->role)->name === 'Partner')
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Partner deals</h5>
                    </div>
                    <div class="card-body">
                        @livewire('admin.user-deals-table', ['userId' => $user->id])
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
