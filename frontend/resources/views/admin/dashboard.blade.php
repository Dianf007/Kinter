@extends('layouts.admin.app')
@section('title', 'Dashboard')

@section('admin-navbar')
    <span class="text-muted small">Signed in as <strong>admin</strong></span>
    <a href="{{ route('admin.logout') }}" class="admin-btn admin-btn--outline">Logout</a>
@endsection

@push('styles')
<style>
    .dashboard-wrapper {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 18px;
    }
    .metric-card {
        background: #fff;
        border-radius: 20px;
        padding: 20px;
        border: 1px solid rgba(28,31,46,0.08);
        box-shadow: 0 12px 30px rgba(28,31,46,0.06);
    }
    .metric-card__label {
        font-size: 0.9rem;
        color: #6c728d;
        margin-bottom: 6px;
    }
    .metric-card__value {
        font-size: 2rem;
        font-weight: 600;
        color: var(--admin-primary);
    }
    .quick-links {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
    }
    .quick-link {
        border-radius: 18px;
        padding: 16px;
        background: rgba(75,107,251,0.08);
        color: var(--admin-dark);
        text-decoration: none;
        font-weight: 500;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .quick-link span {
        font-size: 1.2rem;
    }
    .quick-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 25px rgba(75,107,251,0.2);
    }
    @media (max-width: 576px) {
        .metric-card__value {
            font-size: 1.6rem;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-wrapper">
    <div class="admin-card">
        <div class="admin-card__header">
            <h4 class="mb-2">Dashboard Overview</h4>
            <p class="mb-0">Monitor reports, classes, and teacher activities from a single screen.</p>
        </div>
        <div class="admin-card__body">
            <div class="dashboard-grid mb-4">
                <div class="metric-card">
                    <div class="metric-card__label">Active Students</div>
                    <div class="metric-card__value">{{ $stats['students'] ?? '—' }}</div>
                </div>
                <div class="metric-card">
                    <div class="metric-card__label">Daily Reports</div>
                    <div class="metric-card__value">{{ $stats['reports'] ?? '—' }}</div>
                </div>
                <div class="metric-card">
                    <div class="metric-card__label">Teachers</div>
                    <div class="metric-card__value">{{ $stats['teachers'] ?? '—' }}</div>
                </div>
            </div>
            <div class="quick-links">
                <a class="quick-link" href="{{ route('teacher.reports.index') }}">
                    Manage Reports <span>↗</span>
                </a>
                <a class="quick-link" href="{{ route('classes.index') }}">
                    View Classes <span>↗</span>
                </a>
                <a class="quick-link" href="{{ route('admin.kid-projects.index') }}">
                    Kid Projects <span>↗</span>
                </a>
                <a class="quick-link" href="{{ route('teachers.index') }}">
                    Teacher Directory <span>↗</span>
                </a>
                <a class="quick-link" href="{{ route('home') }}">
                    Public Site <span>↗</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection