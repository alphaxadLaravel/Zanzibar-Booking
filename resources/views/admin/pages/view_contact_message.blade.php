@extends('admin.layouts.app')

@section('title', 'View Contact Message')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row align-items-center mb-3">
        <div class="col d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="page-title mb-0">Contact Message Details</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.contact.messages') }}">Contact Messages</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View Message</li>
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

    <!-- Message Details -->
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1">{{ $message->subject }}</h5>
                        <small class="text-muted">Received {{ $message->created_at->diffForHumans() }}</small>
                    </div>
                    @if($message->status === 'new')
                    <span class="badge bg-primary">New</span>
                    @elseif($message->status === 'read')
                    <span class="badge bg-info">Read</span>
                    @else
                    <span class="badge bg-success">Replied</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="mb-4 pb-3 border-bottom">
                        <div class="d-flex align-items-center mb-2">
                            <i class="mdi mdi-account-circle me-2 fs-4 text-primary"></i>
                            <div>
                                <strong>{{ $message->full_name }}</strong>
                                <br>
                                <a href="mailto:{{ $message->email }}" class="text-muted">{{ $message->email }}</a>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="bg-light p-4 rounded">
                            <p style="white-space: pre-wrap; margin-bottom: 0;">{{ $message->content }}</p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex gap-2">
                            <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}" class="btn btn-primary">
                                <i class="mdi mdi-reply"></i> Reply via Email
                            </a>
                            <a href="{{ route('admin.contact.messages') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Back
                            </a>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <form action="{{ route('admin.contact.message.status', $hashedId) }}" method="POST" class="d-inline">
                                @csrf
                                <select name="status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                    <option value="new" {{ $message->status === 'new' ? 'selected' : '' }}>New</option>
                                    <option value="read" {{ $message->status === 'read' ? 'selected' : '' }}>Read</option>
                                    <option value="replied" {{ $message->status === 'replied' ? 'selected' : '' }}>Replied</option>
                                </select>
                            </form>
                            
                            <form action="{{ route('admin.contact.message.delete', $hashedId) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="mdi mdi-delete"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
