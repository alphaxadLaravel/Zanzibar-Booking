@extends('admin.layouts.app')

@section('title', 'Assign deals to partner')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-3">
        <div class="col">
            <h4 class="page-title mb-0">Assign deals to partner</h4>
            <p class="text-muted mb-0">Partner: <strong>{{ $partner->full_name }}</strong> ({{ $partner->email }})</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.partners') }}" class="btn btn-outline-secondary">Back to partners</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show">{{ session('info') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-body">
            <p class="text-muted">Select the deals this partner should manage. Saving will set the deal owner (<code>user_id</code>) to this partner so they appear in their admin dashboard. Deals can be moved from another partner or from admin-created listings.</p>

            <form action="{{ route('admin.partners.assign-deals.store', $hashids->encode($partner->id)) }}" method="POST">
                @csrf
                <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
                    <table class="table table-sm table-hover">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th style="width: 48px;">
                                    <input type="checkbox" id="checkAll" title="Select all">
                                </th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Current owner</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deals as $deal)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="deal_ids[]" value="{{ $deal->id }}" class="deal-cb"
                                            {{ (int) $deal->user_id === (int) $partner->id ? 'checked' : '' }}>
                                    </td>
                                    <td>{{ $deal->title }}</td>
                                    <td><span class="badge bg-secondary">{{ $deal->type }}</span></td>
                                    <td>
                                        @if($deal->user)
                                            {{ $deal->user->full_name }} (#{{ $deal->user_id }})
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 d-flex gap-2 align-items-center">
                    <button type="submit" class="btn btn-primary">Save assignments</button>
                    <small class="text-muted">Only checked deals are assigned to this partner. Unchecked deals are not changed.</small>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('checkAll')?.addEventListener('change', function() {
    document.querySelectorAll('.deal-cb').forEach(cb => { cb.checked = this.checked; });
});
</script>
@endsection
