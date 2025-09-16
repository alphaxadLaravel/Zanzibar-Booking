@extends('admin.layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'All Tours')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row align-items-center mb-3">
        <div class="col d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="page-title mb-0">All Tours</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tours</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title mb-0">Tours Management</h5>
                        <a href="{{ route('admin.manage-deal', 'tour') }}" class="btn btn-primary">
                            <i class="ti ti-plus"></i> Add New Tour
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
                                    <th>Days</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tours as $index => $tour)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($tour->cover_photo)
                                        <img src="{{ Storage::url($tour->cover_photo) }}" alt="Tour Cover"
                                            style="width: 50px; height: 40px; object-fit: cover; border-radius: 4px;">
                                        @else
                                        <div class="bg-light d-flex align-items-center justify-content-center"
                                            style="width: 50px; height: 40px; border-radius: 4px;">
                                            <i class="mdi mdi-image text-muted"></i>
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-1">{{ $tour->title }}</h6>
                                            <small class="text-muted">{{ $tour->category->category ?? 'No Category'
                                                }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            <i class="mdi mdi-map-marker me-1"></i>
                                            {{ $tour->location ?? 'Not specified' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $tour->tours->period ?? 'N/A' }}
                                        DAY(S)
                                    </td>
                                    <td>
                                        <div>
                                            <strong class="text-primary">${{ number_format($tour->base_price, 2)
                                                }}</strong>
                                            @if($tour->tours)
                                            <br>
                                            <small class="text-muted">
                                                Adult: ${{ number_format($tour->tours->adult_price, 2) }} |
                                                Child: ${{ number_format($tour->tours->child_price, 2) }}
                                            </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($tour->status)
                                        <span class="badge bg-success">Published</span>
                                        @else
                                        <span class="badge bg-warning">Draft</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.manage-deal.edit', [$hashids->encode($tour->id), 'tour']) }}"
                                                class="btn btn-sm btn-outline-primary" title="Edit Tour">
                                                <i class="mdi mdi-pencil"></i> Edit
                                            </a>
                                            <a href="{{ route('admin.manage-deal', ['tour', $hashids->encode($tour->id)]) }}"
                                                class="btn btn-sm btn-outline-info" title="Manage Tour">
                                                <i class="mdi mdi-cog"></i> Manage Tour
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="mdi mdi-tour me-2" style="font-size: 2rem;"></i>
                                            <p class="mb-0">No tours found</p>
                                            <small>Create your first tour to get started</small>
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