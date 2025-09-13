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
                @for ($i = 0; $i < 12; $i++)
                <div class="col-12 mb-4">
                    <div class="card d-flex flex-row blog-card-flex align-items-stretch shadow-sm border-0">
                        <div class="col-md-4 blog-card-img-col p-0 d-flex align-items-center mx-2">
                            <a href="{{route('view-blog')}}" class="w-100 h-100 d-block">
                                <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80"
                                    class="img-fluid rounded-start w-100 h-100 object-fit-cover"
                                    style="min-height: 200px; max-height: 220px; object-fit: cover;"
                                    alt="DISCOVER NATURE &amp; CULTURE IN ONE UNFORGETTABLE EXPERIENCE">
                            </a>
                        </div>
                        <div class="col-md-8 blog-card-content-col p-4 d-flex flex-column position-relative">
                            <div>
                                <h3 class="card-title mb-2" style="font-size:1.3rem;">
                                    <a href="{{route('view-blog')}}" class="text-decoration-none text-dark">
                                        DISCOVER NATURE &amp; CULTURE IN ONE UNFORGETTABLE EXPERIENCE
                                    </a>
                                </h3>
                                <div class="mb-2 text-muted small">
                                    <span>By Zanzibar Bookings</span>
                                    <span class="mx-2">|</span>
                                    <span><i class="far fa-calendar-alt"></i> 03/09/2025</span>
                                </div>
                                <p class="card-text mb-3">
                                    Experience the best of Zanzibar with our curated guides on nature, culture, and unforgettable adventures. Dive into the heart of the island and discover what makes it unique.
                                </p>
                            </div>
                            <div class="mt-auto d-flex flex-wrap justify-content-between align-items-end gap-2">
                                <div class="mb-2 mb-md-0">
                                    <!-- Tags removed -->
                                </div>
                                <a href="{{route('view-blog')}}" class="btn btn-primary btn-sm ms-2">
                                    Read More <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>

            <nav>
                <ul class="pagination">
                    <li class="page-item disabled" aria-disabled="true" aria-label="&laquo; Previous">
                        <span class="page-link" aria-hidden="true">&lsaquo;</span>
                    </li>

                    <li class="page-item active" aria-current="page">
                        <span class="page-link">1</span>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="blog4658.html?page=2">2</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="blog9ba9.html?page=3">3</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="blogfdb0.html?page=4">4</a>
                    </li>

                    <li class="page-item">
                        <a class="page-link" href="blog4658.html?page=2" rel="next"
                            aria-label="Next &raquo;">&rsaquo;</a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="col-lg-3">
            <div class="siderbar-single">
                <div class="widget-item widget-recent-post">
                    <h4 class="widget-title">Recent posts</h4>
                    <div class="widget-content">
                        @for ($i = 0; $i < 12; $i++)
                        <div class="post-item d-flex align-items-center mb-3">
                            <a href="{{route('view-blog')}}" class="flex-shrink-0" style="width: 60px; height: 60px; display: block; margin-right: 16px;">
                                <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80"
                                    alt="DISCOVER NATURE &amp; CULTURE IN ONE UNFORGETTABLE EXPERIENCE"
                                    class="img-fluid rounded" style="object-fit: cover; width: 100%; height: 100%;" />
                            </a>
                            <div class="info">
                                <h6 class="mb-1" style="font-size: 1rem; max-height: 2.8em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; text-overflow: ellipsis;">
                                    <a href="{{route('view-blog')}}" class="text-dark text-decoration-none" style="display: inline; color: inherit;">
                                        DISCOVER NATURE &amp; CULTURE IN ONE UNFORGETTABLE EXPERIENCE
                                    </a>
                                </h6>
                                <small class="text-muted">03/09/2025</small>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>

                <div class="widget-item">
                    <h4 class="widget-title">Categories</h4>
                    <div class="widget-content">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <a href="category/wedds-in-zanzibar.html" class="d-block px-3 py-2 rounded text-dark text-decoration-none category-link">
                                    <i class="fas fa-chevron-right me-2 text-primary"></i> Wedds in Zanzibar
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="category/visit-zanzibar.html" class="d-block px-3 py-2 rounded text-dark text-decoration-none category-link">
                                    <i class="fas fa-chevron-right me-2 text-primary"></i> Visit Zanzibar
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="category/zanzibar-top-attractions.html" class="d-block px-3 py-2 rounded text-dark text-decoration-none category-link">
                                    <i class="fas fa-chevron-right me-2 text-primary"></i> Zanzibar Top Attractions
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="category/snorkeling-site.html" class="d-block px-3 py-2 rounded text-dark text-decoration-none category-link">
                                    <i class="fas fa-chevron-right me-2 text-primary"></i> Snorkeling Site
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="category/historical.html" class="d-block px-3 py-2 rounded text-dark text-decoration-none category-link">
                                    <i class="fas fa-chevron-right me-2 text-primary"></i> Historical
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="category/domestic-tourism.html" class="d-block px-3 py-2 rounded text-dark text-decoration-none category-link">
                                    <i class="fas fa-chevron-right me-2 text-primary"></i> Domestic Tourism
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="category/marine-life.html" class="d-block px-3 py-2 rounded text-dark text-decoration-none category-link">
                                    <i class="fas fa-chevron-right me-2 text-primary"></i> Marine Life
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="category/zanzibar-history.html" class="d-block px-3 py-2 rounded text-dark text-decoration-none category-link">
                                    <i class="fas fa-chevron-right me-2 text-primary"></i> Zanzibar History
                                </a>
                            </li>
                            <li>
                                <a href="category/things-to-do-in-zanzibar.html" class="d-block px-3 py-2 rounded text-dark text-decoration-none category-link">
                                    <i class="fas fa-chevron-right me-2 text-primary"></i> Things to do in Zanzibar
                                </a>
                            </li>
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