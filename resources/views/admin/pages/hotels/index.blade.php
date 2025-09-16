@extends('admin.layouts.app')

@section('title', 'All Hotels')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row align-items-center mb-3">
        <div class="col d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="page-title mb-0">All Hotels</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Hotels</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Hotels Management</h5>
                        <div class="">
                            <a href="{{ route('admin.manage-deal', 'hotel') }}" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Add New Hotel
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
                                @forelse($hotels as $index => $hotel)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($hotel->cover_photo)
                                        <img src="{{ asset('storage/' . $hotel->cover_photo) }}"
                                            alt="{{ $hotel->title }}" class="rounded"
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
                                            <h6 class="mb-1">{{ $hotel->title }}</h6>
                                            <small class="text-muted">{{ $hotel->category->category ?? 'N/A' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <i class="ti ti-map-pin text-muted me-1"></i>
                                            {{ $hotel->location ?? 'Not specified' }}
                                        </div>
                                    </td>
                                    <td>${{ number_format($hotel->base_price, 2) }}/night</td>
                                    <td>
                                        @if($hotel->status)
                                        <span class="badge bg-success">Active</span>
                                        @else
                                        <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.manage-deal.edit', [$hashids->encode($hotel->id), 'hotel']) }}"
                                            class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <a href="{{ route('admin.hotels.rooms', $hotel->id) }}"
                                            class="btn btn-sm btn-outline-info" title="View">
                                            Manage Hotel
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ti ti-hotel fs-1 d-block mb-2"></i>
                                            <h5>No Hotels Found</h5>
                                            <p>Start by adding your first hotel.</p>
                                            <a href="{{ route('admin.manage-deal', 'hotel') }}" class="btn btn-primary">
                                                <i class="ti ti-plus"></i> Add New Hotel
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

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this hotel? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- <script>
    function deleteHotel(hotelId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = "{{ route('admin.hotels.delete', '') }}/" + hotelId;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script> --}}

@endsection