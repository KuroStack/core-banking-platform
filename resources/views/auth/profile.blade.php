@extends('layouts.app')
@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-user"></i> Profile Information</h3></div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>Name</th><td>{{ $user->name }}</td></tr>
                    <tr><th>Email</th><td>{{ $user->email }}</td></tr>
                    <tr><th>Role</th><td><span class="badge badge-primary">{{ $user->role?->name }}</span></td></tr>
                    <tr><th>Branch</th><td>{{ $user->branch?->name ?? 'All Branches' }}</td></tr>
                    <tr><th>Employee Code</th><td>{{ $user->employee_code ?? '-' }}</td></tr>
                    <tr><th>Designation</th><td>{{ $user->designation ?? '-' }}</td></tr>
                    <tr><th>Status</th><td><span class="badge badge-{{ $user->is_active ? 'success' : 'danger' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></td></tr>
                    <tr><th>Member Since</th><td>{{ $user->created_at?->format('d M Y') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-warning card-outline">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-key"></i> Change Password</h3></div>
            <form action="{{ route('profile.password') }}" method="POST" data-validate>
                @csrf @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label>Current Password <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required aria-required="true">
                        @error('current_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>New Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required aria-required="true" minlength="8">
                        @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        <small class="form-text">Minimum 8 characters</small>
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required aria-required="true">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
