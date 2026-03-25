@extends('layouts.app')
@section('title', 'Branch Details')
@section('page-title', 'Branch Details')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $branch->name }} <code>{{ $branch->code }}</code></h3>
        <div class="card-tools">
            <a href="{{ route('superadmin.branches.edit', $branch) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr><th>Name</th><td>{{ $branch->name }}</td></tr>
                    <tr><th>Code</th><td><code>{{ $branch->code }}</code></td></tr>
                    <tr><th>Address</th><td>{{ $branch->address ?? '-' }}</td></tr>
                    <tr><th>Opening Date</th><td>{{ $branch->opening_date?->format('d M Y') ?? '-' }}</td></tr>
                    <tr><th>Status</th><td><span class="badge badge-{{ $branch->is_active ? 'success' : 'danger' }}">{{ $branch->is_active ? 'Active' : 'Inactive' }}</span></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <div class="info-box bg-info"><span class="info-box-icon"><i class="fas fa-users"></i></span><div class="info-box-content"><span class="info-box-text">Staff</span><span class="info-box-number">{{ $branch->users->count() }}</span></div></div>
                <div class="info-box bg-success"><span class="info-box-icon"><i class="fas fa-user-tie"></i></span><div class="info-box-content"><span class="info-box-text">Customers</span><span class="info-box-number">{{ $branch->customers->count() }}</span></div></div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('superadmin.branches.index') }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Back to Branches</a>
    </div>
</div>
@endsection
