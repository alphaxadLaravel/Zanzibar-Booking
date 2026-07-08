@extends('admin.layouts.app')

@section('title', 'Deal Reviews')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-3">
        <div class="col d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="page-title mb-0">Deal Reviews</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Reviews</li>
                </ol>
            </nav>
        </div>
    </div>

    @include('admin.layouts.alerts')

    <div class="row mb-3">
        <div class="col-12">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.reviews', ['status' => 'all']) }}"
                    class="btn btn-sm {{ $status === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                    All ({{ $counts['all'] }})
                </a>
                <a href="{{ route('admin.reviews', ['status' => 'pending']) }}"
                    class="btn btn-sm {{ $status === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                    Pending ({{ $counts['pending'] }})
                </a>
                <a href="{{ route('admin.reviews', ['status' => 'approved']) }}"
                    class="btn btn-sm {{ $status === 'approved' ? 'btn-success' : 'btn-outline-success' }}">
                    Approved ({{ $counts['approved'] }})
                </a>
                <a href="{{ route('admin.reviews', ['status' => 'declined']) }}"
                    class="btn btn-sm {{ $status === 'declined' ? 'btn-danger' : 'btn-outline-danger' }}">
                    Declined ({{ $counts['declined'] }})
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Deal Reviews</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Deal</th>
                                    <th>Reviewer</th>
                                    <th>Title</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $index => $review)
                                @php $hashedId = $hashids->encode($review->id); @endphp
                                <tr>
                                    <td>{{ $reviews->firstItem() + $index }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $review->deal->title ?? 'N/A' }}</div>
                                        <small class="text-muted text-capitalize">{{ $review->deal->type ?? '' }}</small>
                                    </td>
                                    <td>{{ $review->reviewer_name }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ Str::limit($review->review_title, 40) }}</div>
                                        <small class="text-muted">{{ Str::limit($review->review_content, 80) }}</small>
                                    </td>
                                    <td>
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="ti ti-star{{ $i <= $review->rating ? '-filled text-warning' : ' text-muted' }}"></i>
                                        @endfor
                                    </td>
                                    <td>
                                        @if($review->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($review->status === 'declined')
                                            <span class="badge bg-danger">Declined</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $review->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @permission('reviews.manage')
                                                @if($review->status !== 'approved')
                                                <form action="{{ route('admin.reviews.approve', $hashedId) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-success" data-loading-text="Approving...">
                                                        <i class="ti ti-check"></i> Accept
                                                    </button>
                                                </form>
                                                @endif
                                                @if($review->status !== 'declined')
                                                <form action="{{ route('admin.reviews.decline', $hashedId) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-loading-text="Declining...">
                                                        <i class="ti ti-x"></i> Decline
                                                    </button>
                                                </form>
                                                @endif
                                                <form action="{{ route('admin.reviews.delete', $hashedId) }}" method="POST" class="d-inline"
                                                    onsubmit="return confirm('Delete this review permanently?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" data-loading-text="Deleting...">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            @endpermission
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">No reviews found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $reviews->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
