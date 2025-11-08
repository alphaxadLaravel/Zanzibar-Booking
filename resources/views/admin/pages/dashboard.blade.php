@extends('admin.layouts.app')

@section('content')

@php
    use Illuminate\Support\Str;
@endphp

<div class="row row-cols-xxl-4 row-cols-lg-3 row-cols-md-2 row-cols-1 align-items-center mt-2">
    <!-- Tours Counter -->
    <div class="col">
        <div class="card">
            <div class="card-body p-2">
                <a href="#!" class="text-muted float-end mt-n1 fs-sm"><i class="ti ti-external-link"></i></a>
                <h5 class="mb-1" title="Total Tours">Tours</h5>
                <div class="d-flex align-items-center gap-1">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title text-bg-light rounded-circle fs-16">
                            <i class="ti ti-map-pin"></i>
                        </span>
                    </div>
                    <h4 class="mb-0">{{ number_format($stats['tours_count']) }}</h4>
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
<div class="row mt-3 g-3">
    <div class="col-xl-6 col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Users</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentUsers as $user)
                        @php
                            $initials = strtoupper(
                                trim(mb_substr($user->firstname ?? '', 0, 1) . mb_substr($user->lastname ?? '', 0, 1))
                                ?: mb_substr($user->email ?? 'NA', 0, 2)
                            );
                            $roleName = optional($user->role)->name ?? 'Customer';
                            $roleKey = strtolower($roleName);
                            $roleBadge = str_contains($roleKey, 'admin') ? 'warning' : (str_contains($roleKey, 'vip') ? 'primary' : 'secondary');
                        @endphp
                        <div class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-xs">
                                        <span class="avatar-title rounded-circle bg-primary text-white fs-12">{{ $initials }}</span>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">
                                            {{ $user->full_name ?? trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? '')) ?: $user->email }}
                                        </div>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                                <span class="badge badge-soft-{{ $roleBadge }}">{{ Str::headline($roleName) }}</span>
                            </div>
                            <small class="text-muted d-block mt-2">{{ optional($user->created_at)->diffForHumans() }}</small>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted py-4">
                            No recent users found.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Deals</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentDeals as $deal)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-semibold text-truncate" title="{{ $deal->title }}">{{ $deal->title }}</div>
                                    <small class="text-muted">
                                        {{ Str::headline($deal->type ?? 'Deal') }}
                                        @if($deal->location)
                                            • {{ $deal->location }}
                                        @endif
                                    </small>
                                </div>
                                <span class="badge badge-soft-primary">{{ optional($deal->category)->name ?? 'Uncategorised' }}</span>
                            </div>
                            <small class="text-muted d-block mt-2">{{ optional($deal->created_at)->diffForHumans() }}</small>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted py-4">
                            No recent deals added.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
