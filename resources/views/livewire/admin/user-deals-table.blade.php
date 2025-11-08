<div>
    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Search deals..." wire:model.live="search">
        </div>
        <div class="col-md-3 mt-2 mt-md-0">
            <select class="form-select" wire:model.live="type">
                <option value="all">All Types</option>
                <option value="hotel">Hotels</option>
                <option value="apartment">Apartments</option>
                <option value="package">Packages</option>
                <option value="activity">Activities</option>
                <option value="car">Cars</option>
                <option value="tour">Tours</option>
            </select>
        </div>
    </div>

    @if($deals->count())
        <div class="row g-3">
            @foreach($deals as $deal)
                <div class="col-md-4">
                    <div class="card h-100">
                        @if($deal->cover_photo)
                            <img src="{{ asset('storage/' . $deal->cover_photo) }}" class="card-img-top" alt="{{ $deal->title }}"
                                 style="height: 160px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 160px;">
                                <i class="ti ti-photo text-muted fs-1"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title mb-1">{{ $deal->title }}</h5>
                            <p class="text-muted mb-2">{{ ucfirst($deal->type) }} • {{ $deal->location ?? 'No location' }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-{{ $deal->status ? 'success' : 'warning' }}">
                                    {{ $deal->status ? 'Active' : 'Pending' }}
                                </span>
                                <small class="text-muted">{{ $deal->created_at->format('M d, Y') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $deals->links() }}
        </div>
    @else
        <p class="text-muted mb-0">No deals found for this partner.</p>
    @endif
</div>

