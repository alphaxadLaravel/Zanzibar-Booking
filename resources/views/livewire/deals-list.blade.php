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
                                        <td>
                                            ${{ number_format($deal->base_price, 2) }}
                                            @if($dealType === 'hotel' || $dealType === 'apartment')
                                            /night
                                            @elseif($dealType === 'car')
                                            /day
                                            @elseif($dealType === 'package' || $dealType === 'activity')
                                            /person
                                            @endif
                                        </td>
                                        <td>
                                            @if($deal->status)
                                            <span class="badge bg-success">Active</span>
                                            @else
                                            <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($dealType === 'car' || $dealType === 'apartment')
                                                <!-- For Cars and Apartments: Delete, Preview, Edit -->
                                                <div class="d-flex gap-1">
                                                    <!-- Delete Button -->
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteDeal('{{ $hashids->encode($deal->id) }}', '{{ $dealType }}', '{{ $deal->title }}')"
                                                        title="Delete {{ $dealTitle }}">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                    
                                                    <!-- Preview Button -->
                                                    @if($dealType === 'apartment')
                                                        <a href="{{ route('view-apartment', $hashids->encode($deal->id)) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-info"
                                                            title="Preview {{ $dealTitle }}">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                    @elseif($dealType === 'car')
                                                        <a href="{{ route('view-car', $hashids->encode($deal->id)) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-info"
                                                            title="Preview {{ $dealTitle }}">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                    @endif
                                                    
                                                    <!-- Edit Button -->
                                                    <a href="{{ route('admin.manage-deal.edit', [$hashids->encode($deal->id), $dealType]) }}"
                                                        class="btn btn-sm btn-outline-primary"
                                                        title="Edit {{ $dealTitle }}">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                </div>
                                            @else
                                                <!-- For Hotels, Packages, Activities: Edit and Manage -->
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



    <!-- Delete Deal Modal -->
    <div class="modal fade" id="deleteDealModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="ti ti-alert-triangle me-2"></i>Confirm Deal Deletion
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="ti ti-trash" style="font-size: 3rem; color: #dc3545;"></i>
                    </div>
                    <p class="text-center mb-3">Are you sure you want to delete this deal?</p>
                    <div class="alert alert-danger" role="alert">
                        <i class="ti ti-alert-circle me-2"></i>
                        <strong>Critical Warning:</strong> This action will permanently delete:
                        <ul class="mb-0 mt-2">
                            <li>Deal information and details</li>
                            <li>All photos and media files</li>
                            <li>Associated bookings and reservations</li>
                            <li>Reviews and ratings</li>
                            <li>All related data</li>
                        </ul>
                        <strong class="text-danger">This action cannot be undone!</strong>
                    </div>
                    <div class="text-center">
                        <strong id="deal-title-to-delete"></strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Cancel
                    </button>
                    <form id="deleteDealForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="ti ti-trash me-1"></i>Delete Deal
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Delete deal function
    function deleteDeal(dealId, dealType, dealTitle) {
        // Set the deal title in the modal
        document.getElementById('deal-title-to-delete').textContent = dealTitle;
        
        // Set the form action URL based on deal type
        const deleteForm = document.getElementById('deleteDealForm');
        
        switch(dealType) {
            case 'hotel':
                deleteForm.action = `/admin/hotels/${dealId}`;
                break;
            case 'apartment':
                deleteForm.action = `/admin/apartments/${dealId}`;
                break;
            case 'car':
                deleteForm.action = `/admin/cars/${dealId}`;
                break;
            case 'package':
                deleteForm.action = `/admin/packages/${dealId}`;
                break;
            case 'activity':
                deleteForm.action = `/admin/activities/${dealId}`;
                break;
            default:
                deleteForm.action = `/admin/deals/${dealId}`;
        }
        
        // Show the delete modal
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteDealModal'));
        deleteModal.show();
    }

    // Handle form submission for deleting deal
    document.getElementById('deleteDealForm').addEventListener('submit', function(e) {
        // Let the form submit naturally - the server will handle the deletion and redirect
    });
    </script>
</div>