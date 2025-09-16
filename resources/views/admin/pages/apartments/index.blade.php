@extends('admin.layouts.app')

@section('title', 'All Apartments')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row align-items-center mb-3">
        <div class="col d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="page-title mb-0">All Apartments</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Apartments</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Apartments Management</h5>
                        <div class="">
                            <a href="{{ route('admin.manage-deal', 'apartment') }}" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Add New Apartment
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
                                @forelse($apartments as $index => $apartment)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($apartment->cover_photo)
                                        <img src="{{ asset('storage/' . $apartment->cover_photo) }}"
                                            alt="{{ $apartment->title }}" class="rounded"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                            style="width: 50px; height: 50px;">
                                            <i class="ti ti-building-apartment text-muted"></i>
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-1">{{ $apartment->title }}</h6>
                                            <small class="text-muted">{{ $apartment->category->category ?? 'N/A'
                                                }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <i class="ti ti-map-pin text-muted me-1"></i>
                                            {{ $apartment->location ?? 'Not specified' }}
                                        </div>
                                    </td>
                                    <td>${{ number_format($apartment->base_price, 2) }}/night</td>
                                    <td>
                                        @if($apartment->status)
                                        <span class="badge bg-success">Active</span>
                                        @else
                                        <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.manage-deal.edit', [$hashids->encode($apartment->id), 'apartment']) }}"
                                            class="btn btn-sm btn-outline-primary me-1" title="Edit Apartment">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger me-1"
                                            title="Delete Apartment" data-bs-toggle="modal"
                                            data-bs-target="#deleteApartmentModal{{ $apartment->id }}">
                                            <i class="mdi mdi-trash-can"></i>
                                        </button>
                                        <a href="#" class="btn btn-sm btn-outline-info me-1" title="Preview Apartment">
                                            <i class="mdi mdi-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>

                                <div class="modal fade" id="deleteApartmentModal{{ $apartment->id }}" tabindex="-1"
                                    aria-labelledby="deleteApartmentModalLabel{{ $apartment->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title"
                                                    id="deleteApartmentModalLabel{{ $apartment->id }}">
                                                    <i class="ti ti-alert-triangle me-2"></i>Confirm Apartment Deletion
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center mb-3">
                                                    <i class="ti ti-building-apartment"
                                                        style="font-size: 3rem; color: #dc3545;"></i>
                                                </div>
                                                <p class="text-center mb-3">Are you sure you want to delete <strong>"{{
                                                        $apartment->title }}"</strong>?</p>
                                                <div class="alert alert-danger" role="alert">
                                                    <i class="ti ti-alert-circle me-2"></i>
                                                    <strong>Critical Warning:</strong> This action will permanently
                                                    delete:
                                                    <ul class="mb-0 mt-2">
                                                        <li>Apartment information and details</li>
                                                        <li>Apartment photos and media files</li>
                                                        <li>Associated bookings and reservations</li>
                                                        <li>Reviews and ratings</li>
                                                    </ul>
                                                    <strong class="text-danger">This action cannot be undone!</strong>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="ti ti-x me-1"></i>Cancel
                                                </button>
                                                <form
                                                    action="{{ route('admin.apartments.delete', $hashids->encode($apartment->id)) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="ti ti-trash me-1"></i>Delete Apartment
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ti ti-building-apartment fs-1 d-block mb-2"></i>
                                            <h5>No Apartments Found</h5>
                                            <p>Start by adding your first apartment.</p>
                                            <a href="{{ route('admin.manage-deal', 'apartment') }}"
                                                class="btn btn-primary">
                                                <i class="ti ti-plus"></i> Add New Apartment
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


@endsection