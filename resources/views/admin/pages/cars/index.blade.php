@extends('admin.layouts.app')

@section('title', 'All Cars')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row align-items-center mb-3">
        <div class="col d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="page-title mb-0">All Cars</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cars</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Page Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Cars Management</h5>
                        <a href="{{ route('admin.manage-deal', 'car') }}" class="btn btn-primary">
                            <i class="ti ti-plus"></i> Add New Car
                        </a>
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
                                    <th>Capacity</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cars as $index => $car)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($car->cover_photo)
                                        <img src="{{ Storage::url($car->cover_photo) }}" alt="Cover"
                                            style="width: 50px; height: 40px; object-fit: cover; border-radius: 4px;">
                                        @else
                                        <div class="bg-light d-flex align-items-center justify-content-center"
                                            style="width: 50px; height: 40px; border-radius: 4px;">
                                            <i class="ti ti-image text-muted"></i>
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $car->title }}</strong>
                                            @if($car->category)
                                            <br><small class="text-muted">{{ $car->category->category }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <i class="ti ti-map-pin me-1"></i>
                                            {{ $car->location ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($car->car && isset($car->car->capacity))
                                        {{ $car->car->capacity }} People
                                        @else
                                        <span class="text-muted">N/A</span>
                                        @endif
                                    </td>

                                    <td>
                                        <strong class="text-success">${{ number_format($car->base_price, 2) }}</strong>
                                    </td>
                                    <td>
                                        @if($car->status)
                                        <span class="badge bg-success">Published</span>
                                        @else
                                        <span class="badge bg-warning">Draft</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.manage-deal.edit', [$hashids->encode($car->id), 'car']) }}"
                                            class="btn btn-sm btn-outline-primary me-1" title="Edit Car">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                         <button type="button" class="btn btn-sm btn-outline-danger me-1" 
                                                 title="Delete Car" data-bs-toggle="modal" data-bs-target="#deleteCarModal{{ $car->id }}">
                                             <i class="mdi mdi-trash-can"></i>
                                         </button>
                                        <a href="#"
                                            class="btn btn-sm btn-outline-info me-1" title="Manage Car">
                                            Manage Car
                                        </a>
                                    </td>
                                </tr>

                                <div class="modal fade" id="deleteCarModal{{ $car->id }}" tabindex="-1" aria-labelledby="deleteCarModalLabel{{ $car->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteCarModalLabel{{ $car->id }}">
                                                    <i class="mdi mdi-alert-circle text-danger me-2"></i>
                                                    Confirm Car Deletion
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center mb-3">
                                                    <i class="mdi mdi-car text-danger" style="font-size: 3rem;"></i>
                                                </div>
                                                <p class="text-center mb-3">
                                                    Are you sure you want to delete the car <strong>{{ $car->title }}</strong>?
                                                </p>
                                                <div class="alert alert-warning">
                                                    <i class="mdi mdi-alert me-2"></i>
                                                    <strong>Warning:</strong> This action cannot be undone. All associated data including photos, features, and bookings will be permanently deleted.
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="mdi mdi-close me-1"></i>Cancel
                                                </button>
                                                <form method="POST" action="{{ route('admin.cars.delete', $car->id) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="mdi mdi-trash me-1"></i>Delete Car
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ti ti-car-off" style="font-size: 3rem;"></i>
                                            <p class="mt-2 mb-0">No cars found</p>
                                            <small>Start by adding your first car</small>
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
