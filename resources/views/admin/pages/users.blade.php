@extends('admin.layouts.app')

@section('title', 'All Users')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-3">
        <div class="col">
            <div class="page-title-box mb-0">
                <h4 class="page-title mb-1">Users</h4>
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </div>
        </div>
        <div class="col-auto d-flex flex-wrap gap-2 justify-content-end">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="mdi mdi-account-plus me-1"></i> Add user
            </a>
            <a href="{{ route('admin.users.create', ['partner' => 1]) }}" class="btn btn-outline-primary">
                <i class="mdi mdi-handshake-outline me-1"></i> Add partner
            </a>
            <a href="{{ route('admin.partners') }}" class="btn btn-outline-secondary">Partners list</a>
        </div>
    </div>

    @php $s = $userStats ?? ['total' => 0, 'active' => 0, 'inactive' => 0, 'verified' => 0, 'suspended' => 0]; @endphp

    <div class="row g-2 mb-3">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body py-3 px-3 d-flex align-items-center" style="min-height: 96px;">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <h4 class="mb-0 text-primary">{{ $s['total'] }}</h4>
                            <p class="mb-0 text-muted small">{{ $s['active'] }} active, {{ $s['inactive'] }} inactive</p>
                        </div>
                        <i class="ti ti-users text-primary opacity-75" style="font-size: 1.6rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body py-3 px-3 d-flex align-items-center" style="min-height: 96px;">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <h4 class="mb-0 text-info">{{ $s['verified'] }}</h4>
                            <p class="mb-0 text-muted small">Verified email</p>
                        </div>
                        <i class="ti ti-mail-check text-info opacity-75" style="font-size: 1.6rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body py-3 px-3 d-flex align-items-center" style="min-height: 96px;">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <h4 class="mb-0 text-danger">{{ $s['suspended'] }}</h4>
                            <p class="mb-0 text-muted small">Suspended</p>
                        </div>
                        <i class="ti ti-ban text-danger opacity-75" style="font-size: 1.6rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h5 class="card-title mb-0">Directory</h5>
                    <span class="text-muted small">{{ $users->total() }} records</span>
                </div>
                <div class="card-body pt-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mt-3">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($users->count() > 0)
                        <div class="table-responsive mt-2">
                            <table class="table table-hover align-middle mb-0" style="min-width: 920px;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-nowrap" style="width:48px;">#</th>
                                        <th>User</th>
                                        <th class="text-nowrap">Phone</th>
                                        <th class="text-nowrap">Role</th>
                                        <th class="text-nowrap text-center">Partner</th>
                                        <th class="text-nowrap">Account</th>
                                        <th class="text-nowrap text-center">Verified</th>
                                        <th class="text-nowrap">Joined</th>
                                        <th class="text-end text-nowrap" style="width: 200px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr class="{{ $user->is_suspended ? 'table-secondary' : '' }}">
                                            <td class="text-muted">{{ $user->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                                                        style="width:42px;height:42px;font-size:0.9rem;font-weight:600;">
                                                        {{ strtoupper(substr($user->firstname, 0, 1)) }}{{ strtoupper(substr($user->lastname, 0, 1)) }}
                                                    </div>
                                                    <div class="min-w-0">
                                                        <div class="fw-medium text-truncate">{{ $user->full_name }}</div>
                                                        <div class="text-muted text-truncate" style="font-size: 0.9rem;">{{ $user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-muted">{{ $user->phone ?: '—' }}</td>
                                            <td>{{ $user->role?->name ?? '—' }}</td>
                                            <td class="text-center">{{ optional($user->role)->name === 'Partner' ? 'Yes' : '—' }}</td>
                                            <td>
                                                @if($user->status)
                                                    <span class="text-success">Active</span>
                                                @else
                                                    <span class="text-warning">Inactive</span>
                                                @endif
                                                @if($user->is_suspended)
                                                    <span class="text-danger"> · Blocked</span>
                                                @endif
                                            </td>
                                            <td class="text-center text-muted">{{ $user->email_verified_at ? 'Yes' : 'No' }}</td>
                                            <td class="text-muted text-nowrap">{{ $user->created_at->format('M j, Y') }}</td>
                                            <td class="text-end">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.users.show', $hashids->encode($user->id)) }}" class="btn btn-outline-secondary" title="View">View</a>
                                                    <a href="{{ route('admin.users.edit', $hashids->encode($user->id)) }}" class="btn btn-outline-secondary" title="Edit">Edit</a>
                                                    @if(optional($user->role)->name === 'Partner')
                                                        <a href="{{ route('admin.partners.assign-deals', $hashids->encode($user->id)) }}" class="btn btn-outline-secondary" title="Deals">Deals</a>
                                                    @endif
                                                </div>
                                                @if(optional($user->role)->name === 'Partner' && (int) $user->status !== 1)
                                                    <div class="mt-2 d-flex gap-1 justify-content-end flex-wrap">
                                                        <form action="{{ route('admin.users.partner.approve', $user->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                                        </form>
                                                        <form action="{{ route('admin.users.partner.reject', $user->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm">Reject</button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <p class="mb-2">No users found.</p>
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add user</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
