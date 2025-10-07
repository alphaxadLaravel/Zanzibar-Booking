@extends('admin.layouts.app')

@section('title', 'Update '.$page->page)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row align-items-center mb-3">
        <div class="col d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="page-title mb-0">Update {{ $page->page }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Update {{ $page->page }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Success Message -->
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

    <!-- Page Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Update {{ $page->page }} Information</h5>
                </div>
                <div class="card-body">

                    <form action="{{ route('admin.manage.content.update', $slug) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="content" class="form-label">Content</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" id="content"
                                        name="content" rows="3" style="display: none;"
                                        placeholder="Enter content">{{ old('content', $page->content ?? '') }}</textarea>
                                    <div id="content-editor" style="min-height: 300px;"></div>
                                    @error('content')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-end">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary" data-loading-text="Updating..">
                                    <i class="ti ti-device-floppy"></i> Update Content
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
    // Quill editor for Description
    var quillToolbarOptions = [
        [{ 'header': [1, 2, false] }],
        ['bold', 'italic', 'underline', 'link', 'image'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'align': [] }],
        ['clean']
    ];

    var contentQuill = new Quill('#content-editor', {
        theme: 'snow',
        modules: {
            toolbar: quillToolbarOptions
        }
    });

    // Set initial content from textarea
    contentQuill.root.innerHTML = document.getElementById('content').value;

    // On form submit, update textarea with Quill HTML
    document.querySelector('form').addEventListener('submit', function() {
        // Sync Quill content to textarea
        document.getElementById('content').value = contentQuill.root.innerHTML;
    });
</script>
@endpush
@endsection