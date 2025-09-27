{{-- Deal Reviews Component --}}
@props(['deal', 'paginatedReviews' => null, 'reviewTitle' => 'Reviews'])

<div class="reviews-section mt-4" id="review-section">
    <div class="d-flex justify-content-between align-items-center my-3">
        <h4 class="comment-count">{{ $reviewTitle }}</h4>

        <div class="d-flex justify-content-center">
            <a href="#leaveReviewModal" class="btn btn-primary btn-lg fw-semibold gmz-box-popup"
                data-effect="mfp-zoom-in">
                <i class="fa fa-pen"></i> Leave a Review
            </a>
        </div>
        <div class="white-popup mfp-with-anim mfp-hide gmz-popup-form" id="leaveReviewModal">
            <div class="popup-inner">
                <h4 class="popup-title" id="leaveReviewModalLabel">Leave a Review</h4>
                <div class="popup-content">
                    <div class="comment-form-wrapper">
                        <form action="{{ route('deals.reviews.store', $deal->id) }}"
                            class="comment-form form-sm" method="post">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="review-title" class="form-label fw-semibold">Review
                                            Title *</label>
                                        <input id="review-title" type="text" name="review_title"
                                            class="form-control" placeholder="Enter your review title"
                                            required />
                                    </div>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <label for="comment_rating mb-2"
                                        class="form-label fw-semibold me-3 mb-0 d-flex align-items-center justify-content-between">
                                        <span>
                                            Your Rating *
                                        </span>
                                        <span id="star-display" class="ms-3"
                                            style="font-size: 1.3rem; color: #ffc107;"></span>

                                    </label>
                                    <select id="rating" name="rating" class="form-select form-control"
                                        required>
                                        <option value="">Select rating</option>
                                        <option value="1">1 Star</option>
                                        <option value="2">2 Stars</option>
                                        <option value="3">3 Stars</option>
                                        <option value="4">4 Stars</option>
                                        <option value="5">5 Stars</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="review-content" class="form-label fw-semibold">Your
                                            Review *</label>
                                        <textarea id="review-content" name="review_content"
                                            placeholder="Share your experience..."
                                            class="form-control" required rows="5"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit"
                                    class="btn btn-primary btn-lg text-uppercase fw-semibold">
                                    Submit Review
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Reviews List -->
    <div class="reviews-list" id="reviews-list">
        @if(isset($paginatedReviews) && method_exists($paginatedReviews, 'count') && $paginatedReviews->count() > 0)
        @foreach($paginatedReviews as $review)
        <div class="review-item d-flex mb-4 p-3"
            style="background: #f8f9fa; border-radius: 12px; border: 1px solid #e9ecef;">
            <div class="review-avatar" style="flex-shrink: 0; margin-right: 2rem;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($review->reviewer_name) }}&background=1C8D83&color=fff&size=60"
                    alt="{{ $review->reviewer_name }}"
                    style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; display: block;">
            </div>
            <div class="review-content flex-grow-1">
                <div class="review-header d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="reviewer-name mb-1"
                            style="font-size: 16px; font-weight: 600; color: #333;">{{ $review->reviewer_name }}</h5>
                        <div class="review-rating mb-1" style="font-size: 0.85rem;">
                            {!! $review->star_rating !!}
                        </div>
                    </div>
                    <small class="text-muted">{{ $review->formatted_date }}</small>
                </div>
                <h6 class="review-title mb-2" style="font-size: 14px; font-weight: 500; color: #555;">
                    {{ $review->review_title }}</h6>
                <p class="review-text mb-0" style="font-size: 14px; color: #666; line-height: 1.5;">
                    {{ $review->review_content }}
                </p>
            </div>
        </div>
        @endforeach
        @else
        <div class="text-center py-4">
            <i class="mdi mdi-star-outline fa-3x text-muted mb-3"></i>
            <p class="text-muted">No reviews yet. Be the first to leave a review!</p>
        </div>
        @endif
    </div>
</div>

{{-- Pagination for reviews --}}
@if(isset($paginatedReviews) && method_exists($paginatedReviews, 'hasPages') && $paginatedReviews->hasPages())
<div class="d-flex justify-content-center my-4">
    {{ $paginatedReviews->links() }}
</div>
@endif

<script>
    // Simple star rating display
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('rating');
        const starDisplay = document.getElementById('star-display');
        if (select && starDisplay) {
            select.addEventListener('change', function () {
                let val = parseInt(this.value);
                if (!val) {
                    starDisplay.innerHTML = '';
                    return;
                }
                let stars = '';
                for (let i = 0; i < val; i++) {
                    stars += '★';
                }
                starDisplay.textContent = stars;
            });
        }
    });
</script>
