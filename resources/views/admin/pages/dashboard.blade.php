@extends('admin.layouts.app')

@section('content')

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

<!-- Recent Bookings and Users Section -->
<div class="row mt-3">
    <!-- Recent Bookings Card -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Bookings</h5>
                <a href="#!" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="">
                            <tr>
                                <th class="px-3 py-2">Booking ID</th>
                                <th class="px-3 py-2">Customer</th>
                                <th class="px-3 py-2">Service</th>
                                <th class="px-3 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-3 py-2"><span class="badge badge-soft-primary">#BK001</span></td>
                                <td class="px-3 py-2">John Smith</td>
                                <td class="px-3 py-2">Hotel Room</td>
                                <td class="px-3 py-2"><span class="badge badge-soft-success">Confirmed</span></td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2"><span class="badge badge-soft-primary">#BK002</span></td>
                                <td class="px-3 py-2">Sarah Johnson</td>
                                <td class="px-3 py-2">City Tour</td>
                                <td class="px-3 py-2"><span class="badge badge-soft-warning">Pending</span></td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2"><span class="badge badge-soft-primary">#BK003</span></td>
                                <td class="px-3 py-2">Mike Wilson</td>
                                <td class="px-3 py-2">Car Rental</td>
                                <td class="px-3 py-2"><span class="badge badge-soft-success">Confirmed</span></td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2"><span class="badge badge-soft-primary">#BK004</span></td>
                                <td class="px-3 py-2">Emma Davis</td>
                                <td class="px-3 py-2">Apartment</td>
                                <td class="px-3 py-2"><span class="badge badge-soft-info">Processing</span></td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2"><span class="badge badge-soft-primary">#BK005</span></td>
                                <td class="px-3 py-2">David Brown</td>
                                <td class="px-3 py-2">Adventure Tour</td>
                                <td class="px-3 py-2"><span class="badge badge-soft-success">Confirmed</span></td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2"><span class="badge badge-soft-primary">#BK006</span></td>
                                <td class="px-3 py-2">Lisa Anderson</td>
                                <td class="px-3 py-2">Beach Resort</td>
                                <td class="px-3 py-2"><span class="badge badge-soft-warning">Pending</span></td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2"><span class="badge badge-soft-primary">#BK007</span></td>
                                <td class="px-3 py-2">Robert Taylor</td>
                                <td class="px-3 py-2">Mountain Tour</td>
                                <td class="px-3 py-2"><span class="badge badge-soft-success">Confirmed</span></td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2"><span class="badge badge-soft-primary">#BK008</span></td>
                                <td class="px-3 py-2">Jennifer Lee</td>
                                <td class="px-3 py-2">Luxury Suite</td>
                                <td class="px-3 py-2"><span class="badge badge-soft-info">Processing</span></td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2"><span class="badge badge-soft-primary">#BK009</span></td>
                                <td class="px-3 py-2">Michael Garcia</td>
                                <td class="px-3 py-2">Business Trip</td>
                                <td class="px-3 py-2"><span class="badge badge-soft-success">Confirmed</span></td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2"><span class="badge badge-soft-primary">#BK010</span></td>
                                <td class="px-3 py-2">Amanda White</td>
                                <td class="px-3 py-2">Family Package</td>
                                <td class="px-3 py-2"><span class="badge badge-soft-warning">Pending</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users Card -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Users</h5>
                <a href="#!" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="">
                            <tr>
                                <th class="px-3 py-2">User</th>
                                <th class="px-3 py-2">Email</th>
                                <th class="px-3 py-2">Role</th>
                                <th class="px-3 py-2">Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-3 py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-2">
                                            <span class="avatar-title rounded-circle bg-primary text-white fs-12">JS</span>
                                        </div>
                                        John Smith
                                    </div>
                                </td>
                                <td class="px-3 py-2">john@example.com</td>
                                <td class="px-3 py-2"><span class="badge badge-soft-secondary">Customer</span></td>
                                <td class="px-3 py-2">2 hours ago</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-2">
                                            <span class="avatar-title rounded-circle bg-success text-white fs-12">SJ</span>
                                        </div>
                                        Sarah Johnson
                                    </div>
                                </td>
                                <td class="px-3 py-2">sarah@example.com</td>
                                <td class="px-3 py-2"><span class="badge badge-soft-secondary">Customer</span></td>
                                <td class="px-3 py-2">4 hours ago</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-2">
                                            <span class="avatar-title rounded-circle bg-warning text-white fs-12">MW</span>
                                        </div>
                                        Mike Wilson
                                    </div>
                                </td>
                                <td class="px-3 py-2">mike@example.com</td>
                                <td class="px-3 py-2"><span class="badge badge-soft-primary">VIP</span></td>
                                <td class="px-3 py-2">6 hours ago</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-2">
                                            <span class="avatar-title rounded-circle bg-info text-white fs-12">ED</span>
                                        </div>
                                        Emma Davis
                                    </div>
                                </td>
                                <td class="px-3 py-2">emma@example.com</td>
                                <td class="px-3 py-2"><span class="badge badge-soft-secondary">Customer</span></td>
                                <td class="px-3 py-2">1 day ago</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-2">
                                            <span class="avatar-title rounded-circle bg-danger text-white fs-12">DB</span>
                                        </div>
                                        David Brown
                                    </div>
                                </td>
                                <td class="px-3 py-2">david@example.com</td>
                                <td class="px-3 py-2"><span class="badge badge-soft-secondary">Customer</span></td>
                                <td class="px-3 py-2">2 days ago</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-2">
                                            <span class="avatar-title rounded-circle bg-secondary text-white fs-12">LA</span>
                                        </div>
                                        Lisa Anderson
                                    </div>
                                </td>
                                <td class="px-3 py-2">lisa@example.com</td>
                                <td class="px-3 py-2"><span class="badge badge-soft-primary">VIP</span></td>
                                <td class="px-3 py-2">3 days ago</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-2">
                                            <span class="avatar-title rounded-circle bg-dark text-white fs-12">RT</span>
                                        </div>
                                        Robert Taylor
                                    </div>
                                </td>
                                <td class="px-3 py-2">robert@example.com</td>
                                <td class="px-3 py-2"><span class="badge badge-soft-secondary">Customer</span></td>
                                <td class="px-3 py-2">1 week ago</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-2">
                                            <span class="avatar-title rounded-circle bg-primary text-white fs-12">JL</span>
                                        </div>
                                        Jennifer Lee
                                    </div>
                                </td>
                                <td class="px-3 py-2">jennifer@example.com</td>
                                <td class="px-3 py-2"><span class="badge badge-soft-secondary">Customer</span></td>
                                <td class="px-3 py-2">1 week ago</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-2">
                                            <span class="avatar-title rounded-circle bg-success text-white fs-12">MG</span>
                                        </div>
                                        Michael Garcia
                                    </div>
                                </td>
                                <td class="px-3 py-2">michael@example.com</td>
                                <td class="px-3 py-2"><span class="badge badge-soft-warning">Admin</span></td>
                                <td class="px-3 py-2">2 weeks ago</td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div><!-- end row -->

@endsection
