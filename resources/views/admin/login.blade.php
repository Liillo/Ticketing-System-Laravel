@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-dark">
    <div class="card shadow p-4" style="max-width:420px;width:100%;">
        <div class="text-center mb-4">
            <div class="mx-auto mb-3 rounded-circle bg-primary d-flex align-items-center justify-content-center"
                 style="width:64px;height:64;">
                <i class="bi bi-shield-lock text-white fs-3"></i>
            </div>
            <h3>Administrator Access</h3>
            <p class="text-muted">Sign in to access ticket validation</p>
        </div>

        @if ($errors->has('login'))
            <div class="alert alert-danger">{{ $errors->first('login') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="alert alert-info small">
                <strong>Demo:</strong><br>
                admin / admin123
            </div>

            <div class="d-flex gap-2">
                <a href="/" class="btn btn-outline-secondary w-50">Home</a>
                <button class="btn btn-primary w-50">Sign In</button>
            </div>
        </form>
    </div>
</div>
@endsection
