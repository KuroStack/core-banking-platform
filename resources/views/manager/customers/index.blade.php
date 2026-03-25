@extends('layouts.app')
@section('title', 'Customers')
@section('page-title', 'Customers')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Branch Customers</h3>
        <div class="card-tools">
            <form class="form-inline" method="GET">
                <input type="text" name="search" class="form-control form-control-sm mr-2" placeholder="Name, mobile, or #" value="{{ request('search') }}" style="width:180px;">
                <select name="approval_status" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('approval_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('approval_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('approval_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </form>
        </div>
    </div>
    <form action="{{ route('manager.customers.bulk-approve') }}" method="POST" id="bulkForm" data-confirm="Approve selected customers?" data-confirm-yes="Yes, approve all">
        @csrf
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll" aria-label="Select all"></th>
                        <th>#</th><th>Name</th><th>Mobile</th><th>Status</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $c)
                    <tr>
                        <td>
                            @if($c->approval_status === 'pending')
                                <input type="checkbox" name="customer_ids[]" value="{{ $c->id }}" class="bulk-check" aria-label="Select {{ $c->full_name }}">
                            @endif
                        </td>
                        <td>{{ $c->customer_number }}</td>
                        <td>{{ $c->full_name }}</td>
                        <td>{{ $c->mobile }}</td>
                        <td><span class="badge badge-{{ $c->approval_status == 'approved' ? 'success' : ($c->approval_status == 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($c->approval_status) }}</span></td>
                        <td>
                            <a href="{{ route('manager.customers.show', $c) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i> View</a>
                            @if($c->approval_status === 'pending')
                            <form action="{{ route('manager.customers.approve', $c) }}" method="POST" class="d-inline" data-confirm="Approve this customer?">@csrf<button class="btn btn-xs btn-success"><i class="fas fa-check"></i></button></form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">No customers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success btn-sm" id="bulkApproveBtn" disabled><i class="fas fa-check-double"></i> Approve Selected (<span id="selectedCount">0</span>)</button>
        </div>
    </form>
    @if($customers->hasPages())<div class="card-footer">{{ $customers->links() }}</div>@endif
</div>
@endsection
@push('scripts')
<script>
$('#selectAll').on('change', function() { $('.bulk-check').prop('checked', this.checked).trigger('change'); });
$(document).on('change', '.bulk-check', function() {
    var count = $('.bulk-check:checked').length;
    $('#selectedCount').text(count);
    $('#bulkApproveBtn').prop('disabled', count === 0);
});
</script>
@endpush
