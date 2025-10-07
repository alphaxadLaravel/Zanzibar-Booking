@extends('admin.layouts.app')

@section('title', 'Contact Messages')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row align-items-center mb-3">
        <div class="col d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="page-title mb-0">Contact Messages</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Contact Messages</li>
                </ol>
            </nav>
        </div>
    </div>

    @if(session('success'))
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Messages Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Contact Messages</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($messages as $message)
                                @php
                                    $hashedId = $hashids->encode($message->id);
                                @endphp
                                <tr>
                                    <td>{{ $message->id }}</td>
                                    <td>{{ $message->full_name }}</td>
                                    <td>{{ $message->email }}</td>
                                    <td>{{ Str::limit($message->subject, 50) }}</td>
                                    <td>
                                        @if($message->status === 'new')
                                        <span class="badge bg-primary">New</span>
                                        @elseif($message->status === 'read')
                                        <span class="badge bg-info">Read</span>
                                        @else
                                        <span class="badge bg-success">Replied</span>
                                        @endif
                                    </td>
                                    <td>{{ $message->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.contact.message.view', $hashedId) }}" class="btn btn-sm btn-primary">
                                            <i class="ti ti-eye"></i> View
                                        </a>
                                        <form action="{{ route('admin.contact.message.delete', $hashedId) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="ti ti-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">No messages found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $messages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
