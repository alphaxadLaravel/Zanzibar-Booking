@extends('admin.layouts.app')

@section('title', 'All Blog Posts')

@php
use Illuminate\Support\Facades\Storage;
use Hashids\Hashids;
$hashids = new Hashids('MchungajiZanzibarBookings', 10);
@endphp

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row align-items-center mb-3">
        <div class="col d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="page-title mb-0">All Blog Posts</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Blog</li>
                </ol>
            </nav>
        </div>
    </div>

    @include('admin.layouts.alerts')

    <!-- Page Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title mb-0">Blog Management</h5>
                        <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus"></i> Add New Post
                        </a>
                    </div>
                    <hr>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cover</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($blogs as $index => $blog)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($blog->cover_photo)
                                            <img src="{{ Storage::url($blog->cover_photo) }}" alt="Cover" 
                                                 style="width: 50px; height: 35px; object-fit: cover; border-radius: 4px;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 35px; border-radius: 4px;">
                                                <i class="ti ti-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <h6 class="mb-0">{{ $blog->title }}</h6>
                                                <small class="text-muted">{{ $blog->category->category ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                   
                                    <td>
                                        @if($blog->status == 1)
                                            <span class="badge bg-success">Published</span>
                                        @else
                                            <span class="badge bg-warning">Draft</span>
                                        @endif
                                    </td>
                                   
                                    <td>
                                        <span>{{ $blog->created_at->format('M d, Y') }}</span>
                                    </td>
                                    <td>
                                        
                                        <a href="{{ route('admin.blog.edit', $blog->id) }}" 
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteBlog({{ $blog->id }})" title="Delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                        <a href="{{ route('view-blog', ['id' => $hashids->encode($blog->id)]) }}" 
                                            target="_blank" class="btn btn-sm btn-outline-secondary" title="Preview">
                                             <i class="ti ti-arrow-up-right"></i>
                                         </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ti ti-article-off" style="font-size: 3rem;"></i>
                                            <p class="mt-2 mb-0">No blog posts found</p>
                                            <small>Create your first blog post to get started</small>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this blog post? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteBlog(id) {
    document.getElementById('deleteForm').action = '{{ route("admin.blog.delete", ":id") }}'.replace(':id', id);
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection
