@extends('layouts.app')
@section('title', 'FD Setup Details')
@section('page-title', 'FD Setup Details')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">FD Scheme #{{ $fdSetup->id }}</h3>
        <div class="card-tools">
            <a href="{{ route('superadmin.fd-setups.edit', $fdSetup) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr><th>Description</th><td>{{ $fdSetup->description }}</td></tr>
            <tr><th>Interest Rate</th><td>{{ $fdSetup->interest_rate }}% per annum</td></tr>
            <tr><th>Duration</th><td>{{ $fdSetup->duration_days }} days</td></tr>
            <tr><th>Senior Citizen</th><td>{!! $fdSetup->is_senior_citizen ? '<span class="badge badge-info">Yes</span>' : 'No' !!}</td></tr>
            <tr><th>Special ROI</th><td>{!! $fdSetup->is_special_roi ? '<span class="badge badge-warning">Yes</span>' : 'No' !!}</td></tr>
            <tr><th>Status</th><td><span class="badge badge-{{ $fdSetup->is_active ? 'success' : 'danger' }}">{{ $fdSetup->is_active ? 'Active' : 'Inactive' }}</span></td></tr>
        </table>
    </div>
    <div class="card-footer">
        <a href="{{ route('superadmin.fd-setups.index') }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Back to FD Setups</a>
    </div>
</div>
@endsection
