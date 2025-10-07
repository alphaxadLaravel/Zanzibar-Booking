@extends('admin.layouts.app')

@section('title', 'All Users')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
                <h4 class="page-title">All Users</h4>
            </div>
        </div>
    </div>

    <!-- User Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0 text-primary">{{ $users->total() }}</h4>
                            <p class="mb-0 text-muted">Total Users</p>
                        </div>
                        <div class="align-self-center">
                            <i class="ti ti-users text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0 text-success">{{ $users->where('status', 1)->count() }}</h4>
                            <p class="mb-0 text-muted">Active Users</p>
                        </div>
                        <div class="align-self-center">
                            <i class="ti ti-user-check text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0 text-warning">{{ $users->where('status', 0)->count() }}</h4>
                            <p class="mb-0 text-muted">Inactive Users</p>
                        </div>
                        <div class="align-self-center">
                            <i class="ti ti-user-x text-warning" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0 text-info">{{ $users->where('email_verified_at', '!=', null)->count() }}</h4>
                            <p class="mb-0 text-muted">Verified Users</p>
                        </div>
                        <div class="align-self-center">
                            <i class="ti ti-mail-check text-info" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Users</h5>
                    <div class="card-tools">
                        <span class="badge bg-primary">{{ $users->total() }} Total</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($users->count() > 0)
                        <!-- Users Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Email Verified</th>
                                        <th>Registered</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <strong>#{{ $user->id }}</strong>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        {{ strtoupper(substr($user->firstname, 0, 1)) }}{{ strtoupper(substr($user->lastname, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $user->full_name }}</strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone ?? 'N/A' }}</td>
                                            <td>
                                                @if($user->role)
                                                    <span class="badge bg-info">{{ $user->role->name }}</span>
                                                @else
                                                    <span class="badge bg-secondary">No Role</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->status)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->email_verified_at)
                                                    <span class="badge bg-success">
                                                        <i class="ti ti-check"></i> Verified
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="ti ti-clock"></i> Pending
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $user->created_at->format('M d, Y') }}</small><br>
                                                <small class="text-muted">{{ $user->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.users.edit', $hashids->encode($user->id)) }}" class="btn btn-sm btn-outline-primary" title="Edit User">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="toggleUserStatus('{{ $hashids->encode($user->id) }}', {{ $user->status }})" title="Toggle Status">
                                                        <i class="ti ti-toggle-{{ $user->status ? 'left' : 'right' }}"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteUser('{{ $hashids->encode($user->id) }}')" title="Delete User">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->links() }}
                        </div>
                    @else
                        <!-- No Users Message -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="ti ti-users" style="font-size: 4rem; color: #dee2e6;"></i>
                            </div>
                            <h4 class="text-muted">No Users Found</h4>
                            <p class="text-muted">There are no users registered in the system yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleUserStatus(userId, currentStatus) {
    const action = currentStatus ? 'deactivate' : 'activate';
    const statusText = currentStatus ? 'inactive' : 'active';
    
    if (confirm(`Are you sure you want to ${action} this user?`)) {
        // Create a form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/users/${userId}/toggle-status`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PUT';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        // Create a form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/users/${userId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
