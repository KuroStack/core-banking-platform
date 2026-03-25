<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password | Cooperative Bank ERP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { background: #435f7a; } .login-box { margin: 7% auto; }</style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="/" class="h1"><i class="fas fa-university text-primary"></i> <span class="font-weight-light">Coop</span><b>Bank</b></a>
            <p class="text-muted mt-1 mb-0">Reset your password</p>
        </div>
        <div class="card-body login-card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">@foreach($errors->all() as $error)<p class="mb-0">{{ $error }}</p>@endforeach</div>
            @endif
            <p>Enter your email address and we'll send you a password reset link.</p>
            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus aria-label="Email" aria-required="true">
                    <div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
            </form>
            <p class="mt-3 mb-0"><a href="{{ route('login') }}"><i class="fas fa-arrow-left"></i> Back to Login</a></p>
        </div>
    </div>
</div>
</body>
</html>
