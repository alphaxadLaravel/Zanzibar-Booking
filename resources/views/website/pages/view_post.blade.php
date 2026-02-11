@extends('website.layouts.app')

@section('pages')
<div class="feature-banner" style="width:100%; height:320px; overflow:hidden; position:relative;">
    <img src="{{ $blog->cover_photo ? asset('storage/' . $blog->cover_photo) : 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80' }}"
        alt="{{ $blog->title }}" style="width:100%; height:100%; object-fit:cover; object-position:center;">
</div>
<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="{{ route('blog') }}">Blog</a></li>
            <li><span>{{ $blog->title }}</span></li>
        </ul>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-lg-9 pb-5">
            <h4 class="post-title">
                {{ $blog->title }}
            </h4>
            <ul class="meta row gx-4 gy-2 mb-4" style="list-style: none; padding: 0; margin: 0;">
                <li class="col-6 col-md-3 d-flex align-items-stretch mb-3 mb-md-0">
                    <div class="d-flex flex-nowrap align-items-center w-100 border rounded bg-white px-3 py-2 h-100" style="min-height:70px; border-color: #218080;">
                        <span class="d-flex align-items-center justify-content-center rounded bg-light flex-shrink-0" style="width:32px; height:32px; background: #e6f4f1 !important; margin-right: 18px;">
                            <i class="mdi mdi-account" style="color: #218080; font-size: 1.2rem;"></i>
                        </span>
                        <div class="flex-grow-1" style="min-width:0;">
                            <div class="fw-bold text-dark" style="font-size: 1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $blog->user ? $blog->user->firstname . ' ' . $blog->user->lastname : 'Zanzibar Bookings' }}</div>
                            <div class="text-muted small" style="white-space:nowrap;">Author</div>
                        </div>
                    </div>
                </li>
                <li class="col-6 col-md-3 d-flex align-items-stretch mb-3 mb-md-0">
                    <div class="d-flex flex-nowrap align-items-center w-100 border rounded bg-white px-3 py-2 h-100" style="min-height:70px; border-color: #218080;">
                        <span class="d-flex align-items-center justify-content-center rounded bg-light flex-shrink-0" style="width:32px; height:32px; background: #e6f4f1 !important; margin-right: 18px;">
                            <i class="mdi mdi-calendar" style="color: #218080; font-size: 1.2rem;"></i>
                        </span>
                        <div class="flex-grow-1" style="min-width:0;">
                            <div class="fw-bold text-dark" style="font-size: 1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $blog->created_at->format('d/m/Y') }}</div>
                            <div class="text-muted small" style="white-space:nowrap;">Date</div>
                        </div>
                    </div>
                </li>
                <li class="col-6 col-md-3 d-flex align-items-stretch mb-3 mb-md-0">
                    <div class="d-flex flex-nowrap align-items-center w-100 border rounded bg-white px-3 py-2 h-100" style="min-height:70px; border-color: #218080;">
                        <span class="d-flex align-items-center justify-content-center rounded bg-light flex-shrink-0" style="width:32px; height:32px; background: #e6f4f1 !important; margin-right: 18px;">
                            <i class="mdi mdi-folder-open" style="color: #218080; font-size: 1.2rem;"></i>
                        </span>
                        <div class="flex-grow-1" style="min-width:0;">
                            <div class="fw-bold" style="font-size: 1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                @if($blog->category)
                                <a href="#" class="text-decoration-none" style="color: #218080;">{{ $blog->category->category }}</a>
                                @else
                                <span style="color: #218080;">Uncategorized</span>
                                @endif
                            </div>
                            <div class="text-muted small" style="white-space:nowrap;">Category</div>
                        </div>
                    </div>
                </li>
                <li class="col-6 col-md-3 d-flex align-items-stretch mb-3 mb-md-0">
                    <div class="d-flex flex-nowrap align-items-center w-100 border rounded bg-white px-3 py-2 h-100" style="min-height:70px; border-color: #218080;">
                        <span class="d-flex align-items-center justify-content-center rounded bg-light flex-shrink-0" style="width:32px; height:32px; background: #e6f4f1 !important; margin-right: 18px;">
                            <i class="mdi mdi-comment-multiple-outline" style="color: #218080; font-size: 1.2rem;"></i>
                        </span>
                        <div class="flex-grow-1" style="min-width:0;">
                            <div class="fw-bold text-dark" style="font-size: 1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">0</div>
                            <div class="text-muted small" style="white-space:nowrap;">Comments</div>
                        </div>
                    </div>
                </li>
            </ul>
            <section class="description">
                <div class="section-content">
                    {!! $blog->description !!}
                </div>
            </section>

            <div class="post-tags">
                Tags
                <a class="tag-item"
                    href="../tag/httpswwwzanzibarbookingscomtour-search.html">https://www.zanzibarbookings.com/tour-search</a>
            </div>
            <div class="gmz-comment-list mt-5" id="review-section">
                <h3 class="comment-count">0 comments for this post</h3>
            </div>

            <div class="post-comment parent-form" id="gmz-comment-section">
                <div class="comment-form-wrapper">
                    <form action="https://www.zanzibarbookings.com/add-comment"
                        class="comment-form form-sm gmz-form-action form-add-post-comment" method="post"
                        data-reload-time="1000">
                        <h3 class="comment-title">Leave a Review</h3>
                        <p class="notice">
                            Your email address will not be published. Required fields
                            are marked *
                        </p>
                        <div class="gmz-loader">
                            <div class="loader-inner">
                                <div class="spinner-grow text-info align-self-center loader-lg"></div>
                            </div>
                        </div>
                        <input type="hidden" name="post_id" value="41" />
                        <input type="hidden" name="comment_id" value="0" />
                        <input type="hidden" name="comment_type" value="post" />
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <input id="comment-name" type="text" name="comment_name"
                                    class="form-control gmz-validation" placeholder="Your name*"
                                    data-validation="required" />
                            </div>
                            <div class="form-group col-lg-6">
                                <input id="comment-email" type="email" name="comment_email"
                                    class="form-control gmz-validation" placeholder="Your email*"
                                    data-validation="required" />
                            </div>
                            <div class="form-group col-lg-12">
                                <textarea id="comment-content" name="comment_content" placeholder="Comment*"
                                    class="form-control gmz-validation" data-validation="required" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="gmz-message"></div>
                        <button type="submit" class="btn btn-primary text-uppercase">
                            Post Comment
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="siderbar-single">
                <div class="widget-item widget-recent-post">
                    <h4 class="widget-title">Recent posts</h4>
                    <div class="widget-content">
                        @forelse($recentBlogs as $recentBlog)
                        <div class="post-item d-flex align-items-center mb-3">
                            <a href="{{route('view-blog', ['id' => $hashids->encode($recentBlog->id)])}}" class="flex-shrink-0"
                                style="width: 60px; height: 60px; display: block; margin-right: 16px;">
                                <img src="{{ $recentBlog->cover_photo ? asset('storage/' . $recentBlog->cover_photo) : 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80' }}"
                                    alt="{{ $recentBlog->title }}"
                                    class="img-fluid rounded" style="object-fit: cover; width: 100%; height: 100%;" />
                            </a>
                            <div class="info">
                                <h6 class="mb-1"
                                    style="font-size: 1rem; max-height: 2.8em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; text-overflow: ellipsis;">
                                    <a href="{{route('view-blog', ['id' => $hashids->encode($recentBlog->id)])}}"
                                        class="text-dark text-decoration-none" style="display: inline; color: inherit;">
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
                    .category-link:hover,
                    .category-link:focus {
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