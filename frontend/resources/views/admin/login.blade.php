@extends('layouts.admin.app')
@section('title', 'Admin Login')

@push('styles')
<style>
    .auth-wrapper {
        max-width: 420px;
        margin: 0 auto;
        width: 100%;
    }
    .auth-card__accent {
        padding: 24px;
        background: linear-gradient(135deg, rgba(75,107,251,0.15), rgba(127,99,244,0.25));
        border-bottom: 1px solid rgba(75,107,251,0.08);
        text-align: center;
    }
    .auth-card__accent h4 {
        margin: 0;
        font-weight: 600;
        color: #fff;
    }
    .form-floating label {
        color: #6c728d;
    }
    .admin-input {
        border-radius: 18px;
        border: 1px solid rgba(28,31,46,0.12);
        padding: 14px 18px;
        font-size: 0.95rem;
    }
    .admin-input:focus {
        border-color: rgba(75,107,251,0.7);
        box-shadow: 0 0 0 0.2rem rgba(75,107,251,0.15);
    }
    .auth-btn {
        border-radius: 16px;
        padding: 12px 18px;
        font-weight: 600;
        border: none;
        width: 100%;
        background: linear-gradient(135deg, #4b6bfb, #7f63f4);
        color: #fff;
        box-shadow: 0 18px 30px rgba(75,107,251,0.25);
        transition: transform 0.2s ease;
    }
    .auth-btn:hover {
        transform: translateY(-1px);
    }
    @media (max-width: 576px) {
        .admin-card {
            border-radius: 20px;
        }
        .auth-card__accent {
            padding: 18px;
        }
    }
</style>
@endpush

@section('content')
<div class="auth-wrapper">
    <div class="admin-card">
        <div class="auth-card__accent">
            <h4>Administrator Access</h4>
            <p class="mb-0 small text-white-50">Secure login for Pondok Koding team</p>
        </div>
        <div class="admin-card__body">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <form method="POST" action="{{ route('admin.login.submit') }}" class="vstack gap-3">
                @csrf
                <div>
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control admin-input" placeholder="Enter username" required autofocus>
                </div>
                <div>
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control admin-input" placeholder="Enter password" required>
                </div>
                <button type="submit" class="auth-btn">Sign In</button>
            </form>
        </div>
    </div>
</div>
@endsection