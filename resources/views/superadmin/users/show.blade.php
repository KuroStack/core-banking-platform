@extends('layouts.app')
@section('title', 'User Details')
@section('page-title', 'User Details')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $user->name }}</h3>
        <div class="card-tools">
            <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr><th>Name</th><td>{{ $user->name }}</td></tr>
            <tr><th>Email</th><td>{{ $user->email }}</td></tr>
            <tr><th>Role</th><td><span class="badge badge-primary">{{ $user->role?->name ?? '-' }}</span></td></tr>
            <tr><th>Branch</th><td>{{ $user->branch?->name ?? '-' }}</td></tr>
            <tr><th>Employee Code</th><td>{{ $user->employee_code ?? '-' }}</td></tr>
            <tr><th>Designation</th><td>{{ $user->designation ?? '-' }}</td></tr>
            <tr><th>Status</th><td><span class="badge badge-{{ $user->is_active ? 'success' : 'danger' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></td></tr>
            <tr><th>Created</th><td>{{ $user->created_at?->format('d M Y H:i') }}</td></tr>
        </table>
    </div>
    <div class="card-footer">
        <a href="{{ route('superadmin.users.index') }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Back to Users</a>
    </div>
</div>
@endsection
