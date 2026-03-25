@extends('layouts.app')
@section('title', 'Account Type Details')
@section('page-title', 'Account Type Details')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $accountType->name }}</h3>
        <div class="card-tools">
            <a href="{{ route('superadmin.account-types.edit', $accountType) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr><th>Name</th><td>{{ $accountType->name }}</td></tr>
            <tr><th>Type</th><td>{{ $accountType->type }}</td></tr>
            <tr><th>Minimum Balance</th><td>{{ number_format($accountType->minimum_balance, 2) }}</td></tr>
            <tr><th>Interest Rate</th><td>{{ $accountType->interest_rate }}%</td></tr>
            <tr><th>Interest Calc Days</th><td>{{ $accountType->interest_calculation_days ?? 365 }}</td></tr>
            <tr><th>Status</th><td><span class="badge badge-{{ $accountType->is_active ? 'success' : 'danger' }}">{{ $accountType->is_active ? 'Active' : 'Inactive' }}</span></td></tr>
        </table>
    </div>
    <div class="card-footer">
        <a href="{{ route('superadmin.account-types.index') }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Back to Account Types</a>
    </div>
</div>
@endsection
