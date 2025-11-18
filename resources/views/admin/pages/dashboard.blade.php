@extends('admin.layouts.app')

@section('content')

@php
    use Illuminate\Support\Str;
@endphp

<div class="row row-cols-xxl-4 row-cols-lg-3 row-cols-md-2 row-cols-1 align-items-center mt-2">
    <!-- Activities Counter -->
    <div class="col">
        <div class="card">
            <div class="card-body p-2">
                <a href="#!" class="text-muted float-end mt-n1 fs-sm"><i class="ti ti-external-link"></i></a>
                <h5 class="mb-1" title="Total Activities">Activities</h5>
                <div class="d-flex align-items-center gap-1">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title text-bg-light rounded-circle fs-16">
                            <i class="ti ti-map-pin"></i>
                        </span>
                    </div>
                    <h4 class="mb-0">{{ number_format($stats['activities_count']) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Packages Counter -->
    <div class="col">
        <div class="card">
            <div class="card-body p-2">
                <a href="#!" class="text-muted float-end mt-n1 fs-sm"><i class="ti ti-external-link"></i></a>
                <h5 class="mb-1" title="Total Packages">Packages</h5>
                <div class="d-flex align-items-center gap-1">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title text-bg-light rounded-circle fs-16">
                            <i class="ti ti-package"></i>
                        </span>
                    </div>
                    <h4 class="mb-0">{{ number_format($stats['packages_count']) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Hotels Counter -->
    <div class="col">
        <div class="card">
            <div class="card-body p-2">
                <a href="#!" class="text-muted float-end mt-n1 fs-sm"><i class="ti ti-external-link"></i></a>
                <h5 class="mb-1" title="Total Hotels">Hotels</h5>
                <div class="d-flex align-items-center gap-1">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title text-bg-light rounded-circle fs-16">
                            <i class="ti ti-building"></i>
                        </span>
                    </div>
                    <h4 class="mb-0">{{ number_format($stats['hotels_count']) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Apartments Counter -->
    <div class="col">
        <div class="card">
            <div class="card-body p-2">
                <a href="#!" class="text-muted float-end mt-n1 fs-sm"><i class="ti ti-external-link"></i></a>
                <h5 class="mb-1" title="Total Apartments">Apartments</h5>
                <div class="d-flex align-items-center gap-1">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title text-bg-light rounded-circle fs-16">
                            <i class="ti ti-home"></i>
                        </span>
                    </div>
                    <h4 class="mb-0">{{ number_format($stats['apartments_count']) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings Counter -->
    <div class="col">
        <div class="card">
            <div class="card-body p-2">
                <a href="#!" class="text-muted float-end mt-n1 fs-sm"><i class="ti ti-external-link"></i></a>
                <h5 class="mb-1" title="Total Bookings">Bookings</h5>
                <div class="d-flex align-items-center gap-1">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title text-bg-light rounded-circle fs-16">
                            <i class="ti ti-calendar-check"></i>
                        </span>
                    </div>
                    <h4 class="mb-0">{{ number_format($stats['bookings_count']) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Cars Counter -->
    <div class="col">
        <div class="card">
            <div class="card-body p-2">
                <a href="#!" class="text-muted float-end mt-n1 fs-sm"><i class="ti ti-external-link"></i></a>
                <h5 class="mb-1" title="Total Cars">Cars</h5>
                <div class="d-flex align-items-center gap-1">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title text-bg-light rounded-circle fs-16">
                            <i class="ti ti-car"></i>
                        </span>
                    </div>
                    <h4 class="mb-0">{{ number_format($stats['cars_count']) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Blog Posts Counter -->
    <div class="col">
        <div class="card">
            <div class="card-body p-2">
                <a href="#!" class="text-muted float-end mt-n1 fs-sm"><i class="ti ti-external-link"></i></a>
                <h5 class="mb-1" title="Blog Posts">Blog Posts</h5>
                <div class="d-flex align-items-center gap-1">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title text-bg-light rounded-circle fs-16">
                            <i class="ti ti-article"></i>
                        </span>
                    </div>
                    <h4 class="mb-0">{{ number_format($stats['blog_posts_count']) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Site Visits Counter -->
    <div class="col">
        <div class="card">
            <div class="card-body p-2">
                <a href="#!" class="text-muted float-end mt-n1 fs-sm"><i class="ti ti-eye"></i></a>
                <h5 class="mb-1" title="Site Visits">Site Visits</h5>
                <div class="d-flex align-items-center gap-1">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title text-bg-light rounded-circle fs-16">
                            <i class="ti ti-eye"></i>
                        </span>
                    </div>
                    <h4 class="mb-0">{{ number_format($stats['site_visits_count']) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Counter -->
    <div class="col">
        <div class="card">
            <div class="card-body p-2">
                <a href="#!" class="text-muted float-end mt-n1 fs-sm"><i class="ti ti-external-link"></i></a>
                <h5 class="mb-1" title="Total Revenue">Total Revenue</h5>
                <div class="d-flex align-items-center gap-1">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title text-bg-light rounded-circle fs-16">
                            <i class="ti ti-currency-dollar"></i>
                        </span>
                    </div>
                    <h4 class="mb-0">${{ number_format($stats['total_revenue'], 2) }}</h4>
                </div>
            </div>
        </div>
    </div>
</div><!-- end row -->

<!-- Recent Activity Cards -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Deals Added</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cover</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Location</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentDeals as $index => $deal)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($deal->cover_photo)
                                            <img src="{{ asset('storage/' . $deal->cover_photo) }}" alt="{{ $deal->title }}"
                                                class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                style="width: 50px; height: 50px;">
                                                <i class="ti ti-photo text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-truncate" title="{{ $deal->title }}">{{ $deal->title }}</div>
                                        <small class="text-muted">{{ optional($deal->category)->category ?? 'N/A' }}</small>
                                    </td>
                                    <td>{{ Str::headline($deal->type ?? 'Deal') }}</td>
                                    <td>{{ $deal->location ?? 'Not specified' }}</td>
                                    <td>${{ number_format($deal->base_price, 2) }}</td>
                                    <td>
                                        @if($deal->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ optional($deal->created_at)->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">No recent deals found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
