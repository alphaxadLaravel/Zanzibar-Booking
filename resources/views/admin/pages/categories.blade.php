@extends('admin.layouts.app')

@section('title')
Categories | {{env('APP_NAME')}}
@endsection

@section('content')
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">System Categories</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#createCategoryModal">
                    <i class="fas fa-plus"></i> New Category
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="">
                            <tr>
                                <th class="px-3 py-2">#</th>
                                <th class="px-3 py-2">Category</th>
                                <th class="px-3 py-2">Type</th>
                                <th class="px-3 py-2">Status</th>
                                <th class="px-3 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td class="px-3 py-2">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-3 py-2">{{ $category->category }}</td>
                                <td class="px-3 py-2">
                                    {{ ucfirst($category->type) }}
                                </td>
                                <td class="px-3 py-2">
                                    <span class="badge bg-{{ $category->status ? 'success' : 'danger' }}">
                                        {{ $category->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-3 py-2">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary mx-2 "
                                            data-bs-toggle="modal"
                                            data-bs-target="#editCategoryModal{{ $category->id }}" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1"
                                            aria-labelledby="editCategoryModalLabel{{ $category->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="editCategoryModalLabel{{ $category->id }}">Edit
                                                            Category</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST"
                                                        action="{{ route('admin.categories.update', $category->id) }}"
                                                        id="editCategoryForm{{ $category->id }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="editCategoryName{{ $category->id }}"
                                                                    class="form-label">Category Name <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text"
                                                                    class="form-control @error('category') is-invalid @enderror"
                                                                    id="editCategoryName{{ $category->id }}"
                                                                    name="category"
                                                                    value="{{ old('category', $category->category) }}"
                                                                    required>
                                                                @error('category')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="editCategoryType{{ $category->id }}"
                                                                    class="form-label">Type
                                                                    <span class="text-danger">*</span></label>
                                                                <select
                                                                    class="form-select @error('type') is-invalid @enderror"
                                                                    id="editCategoryType{{ $category->id }}" name="type"
                                                                    required>
                                                                    <option value="">Select Type</option>
                                                                    <option value="tour" {{ old('type', $category->type)
                                                                        == 'tour' ? 'selected' : '' }}>Tour</option>
                                                                    <option value="hotel" {{ old('type', $category->
                                                                        type) == 'hotel' ? 'selected' : '' }}>Hotel
                                                                    </option>
                                                                    <option value="car" {{ old('type', $category->type)
                                                                        == 'car' ? 'selected' : '' }}>Car</option>
                                                                    <option value="blog" {{ old('type', $category->type)
                                                                        == 'blog' ? 'selected' : '' }}>Blog</option>
                                                                </select>
                                                                @error('type')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary" data-loading-text="Updating...">Update
                                                                Category</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteCategoryModal{{ $category->id }}" 
                                            title="Delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                        
                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade" id="deleteCategoryModal{{ $category->id }}" tabindex="-1"
                                            aria-labelledby="deleteCategoryModalLabel{{ $category->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteCategoryModalLabel{{ $category->id }}">
                                                            Confirm Deletion
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            <i class="ti ti-alert-triangle text-warning" style="font-size: 3rem;"></i>
                                                            <h5 class="mt-3">Are you sure?</h5>
                                                            <p class="text-muted">
                                                                You are about to delete the category 
                                                                <strong>"{{ $category->category }}"</strong> ({{ ucfirst($category->type) }}).
                                                                <br><br>
                                                                This action cannot be undone.
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form method="POST" action="{{ route('admin.categories.delete', $category->id) }}" 
                                                            style="display: inline;" id="deleteForm{{ $category->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger" id="deleteBtn{{ $category->id }}" data-loading-text="Deleting...">
                                                                <i class="ti ti-trash me-1"></i> Delete Category
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No categories found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCategoryModalLabel">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="createCategoryName" class="form-label">Category Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('category') is-invalid @enderror"
                            id="createCategoryName" name="category" value="{{ old('category') }}" required>
                        @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="createCategoryType" class="form-label">Type <span
                                class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="createCategoryType"
                            name="type" required>
                            <option value="">Select Type</option>
                            <option value="tour" {{ old('type')=='tour' ? 'selected' : '' }}>Tour</option>
                            <option value="hotel" {{ old('type')=='hotel' ? 'selected' : '' }}>Hotel</option>
                            <option value="car" {{ old('type')=='car' ? 'selected' : '' }}>Car</option>
                            <option value="blog" {{ old('type')=='blog' ? 'selected' : '' }}>Blog</option>
                        </select>
                        @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" data-loading-text="Saving...">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
