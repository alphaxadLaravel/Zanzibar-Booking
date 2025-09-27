@extends('website.layouts.app')

@section('pages')
<div class="feature-banner" style="width:100%; height:320px; overflow:hidden; position:relative;">
    <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="blog page" style="width:100%; height:100%; object-fit:cover; object-position:center;">
</div>
<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><span>Blog</span></li>
        </ul>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-lg-9 pb-5">
            <h2 class="archive-title">Blog</h2>
            <style>
                @media (max-width: 991.98px) {
                    .blog-card-flex {
                        flex-direction: column !important;
                    }
                    .blog-card-img-col,
                    .blog-card-content-col {
                        max-width: 100% !important;
                        flex: 0 0 100% !important;
                    }
                    .blog-card-img-col {
                        min-height: 180px;
                    }
                }
                @media (max-width: 575.98px) {
                    .blog-card-content-col {
                        padding: 1rem !important;
                    }
                    .blog-card-img-col img {
                        min-height: 140px !important;
                        max-height: 180px !important;
                    }
                }
            </style>
            <div class="row">
                @forelse($blogs as $blog)
                <div class="col-12 mb-4">
                    <div class="card d-flex flex-row blog-card-flex align-items-stretch shadow-sm border-0">
                        <div class="col-md-4 blog-card-img-col p-0 d-flex align-items-center mx-2">
                            <a href="{{route('view-blog', ['id' => $blog->id])}}" class="w-100 h-100 d-block">
                                <img src="{{ $blog->cover_photo ? asset('storage/' . $blog->cover_photo) : 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80' }}"
                                    class="img-fluid rounded-start w-100 h-100 object-fit-cover"
                                    style="min-height: 200px; max-height: 220px; object-fit: cover;"
                                    alt="{{ $blog->title }}">
                            </a>
                        </div>
                        <div class="col-md-8 blog-card-content-col p-4 d-flex flex-column position-relative">
                            <div>
                                <h3 class="card-title mb-2" style="font-size:1.3rem;">
                                    <a href="{{route('view-blog', ['id' => $blog->id])}}" class="text-decoration-none text-dark">
                                        {{ $blog->title }}
                                    </a>
                                </h3>
                                <div class="mb-2 text-muted small">
                                    <span>By {{ $blog->user ? $blog->user->firstname . ' ' . $blog->user->lastname : 'Zanzibar Bookings' }}</span>
                                    <span class="mx-2">|</span>
                                    <span><i class="far fa-calendar-alt"></i> {{ $blog->created_at->format('d/m/Y') }}</span>
                                    @if($blog->category)
                                    <span class="mx-2">|</span>
                                    <span><i class="far fa-folder"></i> {{ $blog->category->category }}</span>
                                    @endif
                                </div>
                                <p class="card-text mb-3">
                                    {{ $blog->preview_text ? Str::limit($blog->preview_text, 150) : Str::limit(strip_tags($blog->description), 150) }}
                                </p>
                            </div>
                            <div class="mt-auto d-flex flex-wrap justify-content-end align-items-end gap-2">
                                <a href="{{route('view-blog', ['id' => $blog->id])}}" class="btn btn-primary ms-2">
                                    Read More
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <h4>No blog posts found</h4>
                        <p class="text-muted">Check back later for new content!</p>
                    </div>
                </div>
                @endforelse
            </div>

            <nav>
                {{ $blogs->links() }}
            </nav>
        </div>
        <div class="col-lg-3">
            <div class="siderbar-single">
                <div class="widget-item widget-recent-post">
                    <h4 class="widget-title">Recent posts</h4>
                    <div class="widget-content">
                        @forelse($blogs->take(5) as $recentBlog)
                        <div class="post-item d-flex align-items-center mb-3">
                            <a href="{{route('view-blog', ['id' => $recentBlog->id])}}" class="flex-shrink-0" style="width: 60px; height: 60px; display: block; margin-right: 16px;">
                                <img src="{{ $recentBlog->cover_photo ? asset('storage/' . $recentBlog->cover_photo) : 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80' }}"
                                    alt="{{ $recentBlog->title }}"
                                    class="img-fluid rounded" style="object-fit: cover; width: 100%; height: 100%;" />
                            </a>
                            <div class="info">
                                <h6 class="mb-1" style="font-size: 1rem; max-height: 2.8em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; text-overflow: ellipsis;">
                                    <a href="{{route('view-blog', ['id' => $recentBlog->id])}}" class="text-dark text-decoration-none" style="display: inline; color: inherit;">
                                        {{ $recentBlog->title }}
                                    </a>
                                </h6>
                                <small class="text-muted">{{ $recentBlog->created_at->format('d/m/Y') }}</small>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-3">
                            <p class="text-muted small">No recent posts available</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="widget-item">
                    <h4 class="widget-title">Categories</h4>
                    <div class="widget-content">
                        <ul class="list-unstyled mb-0">
                            @forelse($categories as $category)
                            <li class="mb-2">
                                <a href="#" class="d-block px-3 py-2 rounded text-dark text-decoration-none category-link">
                                    <i class="fas fa-chevron-right me-2 text-primary"></i> {{ $category->category }}
                                </a>
                            </li>
                            @empty
                            <li class="mb-2">
                                <span class="d-block px-3 py-2 text-muted">No categories available</span>
                            </li>
                            @endforelse
                        </ul>
                    </div>
                    <style>
                        .category-link:hover, .category-link:focus {
                            background: #f0f4fa;
                            color: #0d6efd !important;
                            text-decoration: none;
                        }
                    </style>
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection