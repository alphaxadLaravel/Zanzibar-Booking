{{-- Deal Meta Information Component --}}
@props(['deal', 'type' => 'hotel'])

<div class="meta">
    <ul class="meta row gy-2 mb-4" style="list-style: none; padding: 0; margin: 0;">
        <li class="col-6 col-md-4 d-flex align-items-stretch mb-3 mb-md-0">
            <div class="d-flex flex-nowrap align-items-center w-100 border rounded bg-white pl-3 py-2 h-100"
                style="min-height:70px; border-color: #218080;">
                <span
                    class="d-flex align-items-center justify-content-center rounded bg-light flex-shrink-0"
                    style="width:32px; height:32px; background: #e6f4f1 !important; margin-right: 18px;">
                    @if($type === 'car')
                        <i class="mdi mdi-car" style="color: #218080; font-size: 1.2rem;"></i>
                    @else
                        <i class="mdi mdi-{{ $type === 'hotel' ? 'home-city' : 'compass' }}" style="color: #218080; font-size: 1.2rem;"></i>
                    @endif
                </span>
                <div class="flex-grow-1" style="min-width:0;">
                    <div class="fw-bold text-dark"
                        style="font-size: 1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $deal->category ? $deal->category->category : ucfirst($type) }}
                    </div>
                    <div class="text-muted small" style="white-space:nowrap;">Type</div>
                </div>
            </div>
        </li>
        <li class="col-6 col-md-4 d-flex align-items-stretch mb-3 mb-md-0">
            <div class="d-flex flex-nowrap align-items-center w-100 border rounded bg-white px-3 py-2 h-100"
                style="min-height:70px; border-color: #218080;">
                <span
                    class="d-flex align-items-center justify-content-center rounded bg-light flex-shrink-0"
                    style="width:32px; height:32px; background: #e6f4f1 !important; margin-right: 18px;">
                    @if($type === 'package' || $type === 'activity')
                        <i class="mdi mdi-account-group" style="color: #218080; font-size: 1.2rem;"></i>
                    @else
                        <i class="mdi mdi-currency-usd" style="color: #218080; font-size: 1.2rem;"></i>
                    @endif
                </span>
                <div class="flex-grow-1" style="min-width:0;">
                    <div class="fw-bold text-dark"
                        style="font-size: 1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        @if($type === 'package' || $type === 'activity')
                            {{ $deal->tours ? $deal->tours->max_people : 'N/A' }} People
                        @elseif($type === 'hotel')
                            {{ priceForUser($deal->base_price, 2) }}/night
                        @elseif($type === 'car')
                            {{ priceForUser($deal->base_price, 2) }}/day
                        @else
                            {{ priceForUser($deal->base_price, 2) }}/person
                        @endif
                    </div>
                    <div class="text-muted small" style="white-space:nowrap;">
                        @if($type === 'package' || $type === 'activity')
                            Min People
                        @else
                            Price
                        @endif
                    </div>
                </div>
            </div>
        </li>
        <li class="col-6 col-md-4 d-flex align-items-stretch mb-3 mb-md-0">
            <div class="d-flex flex-nowrap align-items-center w-100 border rounded bg-white px-3 py-2 h-100"
                style="min-height:70px; border-color: #218080;">
                <span
                    class="d-flex align-items-center justify-content-center rounded bg-light flex-shrink-0"
                    style="width:32px; height:32px; background: #e6f4f1 !important; margin-right: 18px;">
                    <i class="mdi mdi-star" style="color: #218080; font-size: 1.2rem;"></i>
                </span>
                <div class="flex-grow-1" style="min-width:0;">
                    <div class="fw-bold text-dark"
                        style="font-size: 1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $deal->ratings ? number_format($deal->ratings, 1) : '5.0' }}/5
                    </div>
                    <div class="text-muted small" style="white-space:nowrap;">Rating</div>
                </div>
            </div>
        </li>
    </ul>
</div>
