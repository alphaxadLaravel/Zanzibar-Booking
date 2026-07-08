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
                                    <th>Rating</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $index => $review)
                                @php
                                    $hashedId = $hashids->encode($review->id);
                                    $reviewStatus = $review->moderation_status;
                                @endphp
                                <tr>
                                    <td>{{ $reviews->firstItem() + $index }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ Str::limit($review->deal->title ?? 'N/A', 35) }}</div>
                                        <small class="text-muted text-capitalize">{{ $review->deal->type ?? '' }}</small>
                                    </td>
                                    <td>{{ Str::limit($review->reviewer_name, 25) }}</td>
                                    <td>
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="ti ti-star{{ $i <= $review->rating ? '-filled text-warning' : ' text-muted' }}"></i>
                                        @endfor
                                    </td>
                                    <td>
                                        @if($reviewStatus === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($reviewStatus === 'declined')
                                            <span class="badge bg-danger">Declined</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">{{ $review->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-info btn-view-review"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewReviewModal"
                                                data-deal="{{ e($review->deal->title ?? 'N/A') }}"
                                                data-deal-type="{{ $review->deal->type ?? '' }}"
                                                data-reviewer="{{ e($review->reviewer_name) }}"
                                                data-email="{{ e($review->user->email ?? 'N/A') }}"
                                                data-title="{{ e($review->review_title) }}"
                                                data-content="{{ e($review->review_content) }}"
                                                data-rating="{{ $review->rating }}"
                                                data-status="{{ $review->status_label }}"
                                                data-date="{{ $review->created_at->format('M d, Y h:i A') }}"
                                                title="View details">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            @permission('reviews.manage')
                                                @if(in_array($reviewStatus, ['pending', 'declined']))
                                                <form action="{{ route('admin.reviews.approve', $hashedId) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Accept" data-loading-text="Approving...">
                                                        <i class="ti ti-check"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                @if(in_array($reviewStatus, ['pending', 'approved']))
                                                <form action="{{ route('admin.reviews.decline', $hashedId) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Decline" data-loading-text="Declining...">
                                                        <i class="ti ti-x"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                <form action="{{ route('admin.reviews.delete', $hashedId) }}" method="POST" class="d-inline"
                                                    onsubmit="return confirm('Delete this review permanently?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete" data-loading-text="Deleting...">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            @endpermission
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No reviews found.</td>
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

<div class="modal fade" id="viewReviewModal" tabindex="-1" aria-labelledby="viewReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewReviewModalLabel">Review Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small mb-1">Deal</label>
                        <div class="fw-semibold" id="modal-deal"></div>
                        <small class="text-muted text-capitalize" id="modal-deal-type"></small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small mb-1">Status</label>
                        <div id="modal-status"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small mb-1">Reviewer</label>
                        <div id="modal-reviewer"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small mb-1">Email</label>
                        <div id="modal-email"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small mb-1">Rating</label>
                        <div id="modal-rating"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small mb-1">Submitted</label>
                        <div id="modal-date"></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small mb-1">Review Title</label>
                        <div class="fw-semibold" id="modal-title"></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small mb-1">Review Content</label>
                        <div class="border rounded p-3 bg-light" id="modal-content" style="white-space: pre-wrap;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.btn-view-review').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('modal-deal').textContent = this.dataset.deal || 'N/A';
            document.getElementById('modal-deal-type').textContent = this.dataset.dealType || '';
            document.getElementById('modal-reviewer').textContent = this.dataset.reviewer || 'N/A';
            document.getElementById('modal-email').textContent = this.dataset.email || 'N/A';
            document.getElementById('modal-title').textContent = this.dataset.title || '';
            document.getElementById('modal-content').textContent = this.dataset.content || '';
            document.getElementById('modal-date').textContent = this.dataset.date || '';

            const rating = parseInt(this.dataset.rating, 10) || 0;
            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                starsHtml += `<i class="ti ti-star${i <= rating ? '-filled text-warning' : ' text-muted'}"></i>`;
            }
            document.getElementById('modal-rating').innerHTML = starsHtml;

            const status = this.dataset.status || 'Pending';
            let badgeClass = 'bg-warning text-dark';
            if (status === 'Approved') badgeClass = 'bg-success';
            if (status === 'Declined') badgeClass = 'bg-danger';
            document.getElementById('modal-status').innerHTML = `<span class="badge ${badgeClass}">${status}</span>`;
        });
    });
</script>
@endpush
