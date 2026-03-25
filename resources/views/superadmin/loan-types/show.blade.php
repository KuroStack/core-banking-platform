@extends('layouts.app')
@section('title', 'Loan Type Details')
@section('page-title', 'Loan Type Details')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $loanType->name }}</h3>
        <div class="card-tools">
            <a href="{{ route('superadmin.loan-types.edit', $loanType) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr><th>Name</th><td>{{ $loanType->name }}</td></tr>
            <tr><th>Interest Rate</th><td>{{ $loanType->interest_rate }}% per annum</td></tr>
            <tr><th>Duration</th><td>{{ $loanType->duration_months }} months</td></tr>
            <tr><th>Max Amount</th><td>{{ number_format($loanType->max_amount, 2) }}</td></tr>
            <tr><th>Installments</th><td>{{ $loanType->num_installments }}</td></tr>
            <tr><th>Frequency</th><td>{{ $loanType->frequency ?? 'MONTHLY' }}</td></tr>
            <tr><th>Description</th><td>{{ $loanType->description ?? '-' }}</td></tr>
            <tr><th>Status</th><td><span class="badge badge-{{ $loanType->is_active ? 'success' : 'danger' }}">{{ $loanType->is_active ? 'Active' : 'Inactive' }}</span></td></tr>
        </table>
    </div>
    <div class="card-footer">
        <a href="{{ route('superadmin.loan-types.index') }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Back to Loan Types</a>
    </div>
</div>
@endsection
