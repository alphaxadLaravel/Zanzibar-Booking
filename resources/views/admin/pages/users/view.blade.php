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
                    <h4 class="mb-0">{{ $user->full_name }}</h4>
                    <p class="text-muted mb-2">{{ optional($user->role)->name ?? 'No Role Assigned' }}</p>
                    <div class="d-flex justify-content-center gap-2">
                        <span class="badge bg-{{ $user->status ? 'success' : 'danger' }}">{{ $user->status ? 'Active' : 'Inactive' }}</span>
                        <span class="badge bg-{{ $user->email_verified_at ? 'success' : 'warning' }}">{{ $user->email_verified_at ? 'Email Verified' : 'Pending Verification' }}</span>
                    </div>
                    <p class="mt-3 text-muted">
                        Registered on {{ $user->created_at->format('M d, Y h:i A') }}
                    </p>
                    <div class="d-flex justify-content-center gap-2 mt-2">
                        @if(optional($user->role)->name === 'Partner' && (int) $user->status !== 1)
                            <form action="{{ route('admin.users.partner.approve', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success btn-sm">Approve Partner</button>
                            </form>
                            <form action="{{ route('admin.users.partner.reject', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger btn-sm">Reject Partner</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Contact Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
                    <p><strong>Role:</strong> {{ optional($user->role)->name ?? 'N/A' }}</p>
                    <p><strong>Status:</strong> {{ $user->status ? 'Active' : 'Inactive' }}</p>
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
                        <h5 class="card-title mb-0">Partner Deals</h5>
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

