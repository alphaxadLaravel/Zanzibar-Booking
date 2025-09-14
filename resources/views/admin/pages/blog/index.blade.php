@extends('admin.layouts.app')

@section('title', 'All Blog Posts')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Blog</li>
                    </ol>
                </div>
                <h4 class="page-title">All Blog Posts</h4>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">Blog Management</h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Add New Post
                            </a>
                        </div>
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

                    <!-- Blog Posts Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Sample Data - Replace with actual data from database -->
                                <tr>
                                    <td>1</td>
                                    <td>Top 10 Travel Destinations for 2024</td>
                                    <td>John Doe</td>
                                    <td>Travel</td>
                                    <td>
                                        <span class="badge bg-success">Published</span>
                                    </td>
                                    <td>2024-01-15</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.blog.edit', 1) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteBlog(1)">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Budget Travel Tips for Students</td>
                                    <td>Jane Smith</td>
                                    <td>Tips</td>
                                    <td>
                                        <span class="badge bg-warning">Draft</span>
                                    </td>
                                    <td>2024-01-20</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.blog.edit', 2) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteBlog(2)">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
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
