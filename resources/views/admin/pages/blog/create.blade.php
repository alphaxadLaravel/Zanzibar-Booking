@extends('admin.layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', isset($blog) ? 'Edit Blog Post' : 'Add New Blog Post')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row align-items-center mb-3">
        <div class="col d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="page-title mb-0">{{ isset($blog) ? 'Edit Blog Post' : 'Add New Blog Post' }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.blog') }}">Blog</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ isset($blog) ? 'Edit Post' : 'Add New
                        Post' }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Page Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ isset($blog) ? 'Edit Blog Post Information' : 'Blog Post Information'
                        }}</h5>
                </div>
                <div class="card-body">

                    <form
                        action="{{ isset($blog) ? route('admin.blog.update', $blog->id) : route('admin.blog.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($blog))
                        @method('PUT')
                        @endif

                        <div class="row">

                            <div class="col-md-12">
                                <div class="mb-3">

                                    <div class="mt-1" id="cover-photo-preview">
                                        @if(isset($blog) && $blog->cover_photo)
                                        <img src="{{ Storage::url($blog->cover_photo) }}" alt="Current Cover"
                                            style="width:100px; height:75px; object-fit:cover; border-radius:4px;">
                                        @else
                                        <img src="https://media.istockphoto.com/id/1147544807/vector/thumbnail-image-vector-graphic.jpg?s=612x612&w=0&k=20&c=rnCKVbdxqkjlcs3xH87-9gocETqpspHFXu5dIGB4wuM="
                                            alt="Cover Preview"
                                            style="width:100px; height:75px; object-fit:cover; border-radius:4px;">
                                        @endif
                                    </div>
                                    <label for="cover_photo" class="form-label">Cover Photo</label>
                                    <input type="file" class="form-control @error('cover_photo') is-invalid @enderror"
                                        id="cover_photo" name="cover_photo" accept="image/*">

                                    @error('cover_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Post Title <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title', $blog->title ?? '') }}" required>
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('category_id') is-invalid @enderror"
                                        id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $blog->category_id ??
                                            '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->category }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="preview_text" class="form-label">Preview Text</label>
                            <textarea class="form-control @error('preview_text') is-invalid @enderror" id="preview_text"
                                name="preview_text" rows="3"
                                placeholder="Brief description of the post...">{{ old('preview_text', $blog->preview_text ?? '') }}</textarea>
                            @error('preview_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span
                                    class="text-danger">*</span></label>
                            <div>
                                <textarea id="description" rows="4"
                                    class="form-control d-none @error('description') is-invalid @enderror"
                                    name="description"
                                    placeholder="Enter description">{{ old('description', $blog->description ?? '') }}</textarea>
                                <div id="description-editor"></div>
                            </div>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="row align-items-end">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" name="status"
                                        required>
                                        <option value="">Select Status</option>
                                        <option value="draft" {{ old('status', isset($blog) ? ($blog->status ?
                                            'published' : 'draft') : '') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ old('status', isset($blog) ? ($blog->status ?
                                            'published' : 'draft') : '') == 'published' ? 'selected' : '' }}>Published
                                        </option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn btn-primary"
                                    data-loading-text="{{ isset($blog) ? 'Updating...' : 'Saving...' }}">
                                    <i class="ti ti-device-floppy"></i> {{ isset($blog) ? 'Update Post' : 'Save Post' }}
                                </button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
    // Cover photo preview
    document.getElementById('cover_photo').addEventListener('change', function(e) {
        const preview = document.getElementById('cover-photo-preview');
        preview.innerHTML = '';
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                const img = document.createElement('img');
                img.src = ev.target.result;
                img.style.width = '100px';
                img.style.height = '75px';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '4px';
                preview.appendChild(img);
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Quill editor for Description
    var quillToolbarOptions = [
        [{ 'header': [1, 2, false] }],
        ['bold', 'italic', 'underline', 'link', 'image'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'align': [] }],
        ['clean']
    ];

    var descriptionQuill = new Quill('#description-editor', {
        theme: 'snow',
        modules: {
            toolbar: quillToolbarOptions
        }
    });

    // Set initial content from textarea
    descriptionQuill.root.innerHTML = document.getElementById('description').value;

    // On form submit, update textarea with Quill HTML
    document.querySelector('form').addEventListener('submit', function() {
        // Sync Quill content to textarea
        document.getElementById('description').value = descriptionQuill.root.innerHTML;
    });
</script>
@endpush
@endsection