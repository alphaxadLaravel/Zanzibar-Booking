@extends('admin.layouts.app')

@section('title', 'Partners')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-3">
        <div class="col d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="page-title mb-0">Partners</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Users</a></li>
                    <li class="breadcrumb-item active">Partners</li>
                </ol>
            </nav>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All partners</h5>
            <span class="badge bg-primary">{{ $partners->total() }} total</span>
        </div>
        <div class="card-body">
            @if($partners->count())
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Account status</th>
                                <th>Suspended</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($partners as $partner)
                                <tr>
                                    <td><strong>{{ $partner->full_name }}</strong></td>
                                    <td>{{ $partner->email }}</td>
                                    <td>{{ $partner->phone ?? '—' }}</td>
                                    <td>
                                        @if($partner->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending / inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($partner->is_suspended)
                                            <span class="badge bg-danger">Suspended</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                    <td><small>{{ $partner->created_at->format('M d, Y') }}</small></td>
                                    <td>
                                        <a href="{{ route('admin.partners.assign-deals', $hashids->encode($partner->id)) }}" class="btn btn-sm btn-primary" title="Assign deals">
                                            <i class="mdi mdi-swap-horizontal"></i> Assign deals
                                        </a>
                                        @if(!$partner->is_suspended)
                                            <form action="{{ route('admin.users.suspend', $hashids->encode($partner->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('Suspend this partner? They will not be able to log in.');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Suspend</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.users.unsuspend', $hashids->encode($partner->id)) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-outline-success">Unsuspend</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.users.show', $hashids->encode($partner->id)) }}" class="btn btn-sm btn-outline-info"><i class="ti ti-eye"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">{{ $partners->links() }}</div>
            @else
                <p class="text-muted mb-0">No partners found.</p>
            @endif
        </div>
    </div>
</div>
@endsection
