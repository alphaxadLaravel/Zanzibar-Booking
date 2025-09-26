<div>
    <div class="container-fluid">
        <div class="row align-items-center mb-3">
            <div class="col d-flex justify-content-between align-items-center flex-wrap">
                <h4 class="page-title mb-0">{{ $dealTitle }}</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $dealTitle }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('admin.layouts.alerts')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">{{ $dealTitle }} Management</h5>
                            <div class="">
                                <a href="{{ route('admin.manage-deal', $dealType) }}" class="btn btn-primary">
                                    <i class="ti ti-plus"></i> Add New {{ $dealType }}
                                </a>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Cover</th>
                                        <th>Title</th>
                                        <th>Location</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($deals as $index => $deal)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($deal->cover_photo)
                                            <img src="{{ asset('storage/' . $deal->cover_photo) }}"
                                                alt="{{ $deal->title }}" class="rounded"
                                                style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                style="width: 50px; height: 50px;">
                                                <i class="ti ti-hotel text-muted"></i>
                                            </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-1">{{ $deal->title }}</h6>
                                                <small class="text-muted">{{ $deal->category->category ?? 'N/A'
                                                    }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <i class="ti ti-map-pin text-muted me-1"></i>
                                                {{ $deal->location ?? 'Not specified' }}
                                            </div>
                                        </td>
                                        <td>${{ number_format($deal->base_price, 2) }}/night</td>
                                        <td>
                                            @if($deal->status)
                                            <span class="badge bg-success">Active</span>
                                            @else
                                            <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.manage-deal.edit', [$hashids->encode($deal->id), $dealType]) }}"
                                                class="btn btn-sm btn-outline-primary me-1" title="Edit {{ $dealTitle }}">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            @if($dealType === 'hotel')
                                            <a href="{{ route('admin.hotels.manage', $hashids->encode($deal->id)) }}"
                                                class="btn btn-sm btn-outline-info me-1" title="Manage {{ $dealTitle }}">
                                                Manage {{ $dealTitle }}
                                            </a>
                                            @endif
                                            @if($dealType === 'activity' || $dealType === 'package')
                                            <a href="{{ route('admin.tours.manage', $hashids->encode($deal->id)) }}"
                                                class="btn btn-sm btn-outline-info me-1" title="Manage {{ $dealTitle }}">
                                                Manage {{ $dealTitle }}
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ti ti-hotel fs-1 d-block mb-2"></i>
                                                <h5>No {{ $dealType }} Found</h5>
                                                <p>Start by adding your first {{ $dealType }}.</p>
                                                <a href="{{ route('admin.manage-deal', $dealType) }}"
                                                    class="btn btn-primary">
                                                    <i class="ti ti-plus"></i> Add New {{ $dealType }}
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
</div>