@extends('layouts.admin.app')
@section('title', 'Kid Project Scratch')
@section('admin-navbar')
    <span class="text-muted small">Manage Scratch content</span>
    <a href="{{ route('admin.logout') }}" class="admin-btn admin-btn--solid">Logout</a>
@endsection
@push('styles')
<style>
    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        margin-bottom: 20px;
    }
    .filter-bar input,
    .filter-bar select {
        min-width: 200px;
        border-radius: 16px;
        border: 1px solid rgba(28,31,46,0.12);
        padding: 10px 14px;
    }
    .projects-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 18px;
    }
    .project-card {
        border-radius: 20px;
        padding: 20px;
        background: #fff;
        border: 1px solid rgba(75,107,251,0.12);
        box-shadow: 0 15px 30px rgba(28,31,46,0.08);
        display: flex;
        flex-direction: column;
        gap: 10px;
        position: relative;
    }
    .project-card__badge {
        position: absolute;
        top: 18px;
        right: 18px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-active {
        background: rgba(73,198,146,0.15);
        color: #1f8f68;
    }
    .badge-expired {
        background: rgba(255,107,107,0.18);
        color: #b83d3d;
    }
    .project-card__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: auto;
    }
    .project-card__actions a,
    .project-card__actions button {
        flex: 1;
    }
    .project-card__actions form {
        flex: 1;
    }
    .empty-state {
        padding: 40px;
        text-align: center;
        color: #6c728d;
    }
    @media (max-width: 576px) {
        .filter-bar input,
        .filter-bar select {
            min-width: unset;
            flex: 1;
        }
        .project-card {
            padding: 18px;
        }
    }
</style>
@endpush
@section('content')
<div class="dashboard-wrapper">
    <div class="admin-card">
        <div class="admin-card__header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="mb-1">Kid Project Scratch</h4>
                <p class="mb-0">Pantau dan kelola project Scratch murid secara real-time.</p>
            </div>
            <a href="{{ route('admin.kid-projects.create') }}" class="admin-btn admin-btn--solid">+ Project Baru</a>
        </div>
        <div class="admin-card__body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="GET" class="filter-bar">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari judul / Scratch ID">
                <select name="status">
                    <option value="active" {{ $status !== 'all' ? 'selected' : '' }}>Aktif saja</option>
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Termasuk kadaluarsa</option>
                </select>
                <button type="submit" class="admin-btn admin-btn--solid" style="padding: 10px 20px;">Filter</button>
            </form>
            @if($projects->count())
                <div class="projects-grid">
                    @foreach($projects as $project)
                        @php
                            $isExpired = $project->is_expired;
                        @endphp
                        <div class="project-card">
                            <span class="project-card__badge {{ $isExpired ? 'badge-expired' : 'badge-active' }}">
                                {{ $isExpired ? 'Kadaluarsa' : 'Aktif' }}
                            </span>
                            <div>
                                <h5 class="mb-1">{{ $project->title ?: 'Tanpa Judul' }}</h5>
                                <p class="text-muted mb-2">Scratch ID: <strong>{{ $project->scratch_id }}</strong></p>
                            </div>
                            <p class="mb-2 small text-muted">{{ \Illuminate\Support\Str::limit($project->description, 110) }}</p>
                            <div class="small text-muted">
                                @if($project->expired_dt)
                                    Berlaku sampai <strong>{{ $project->expired_dt->translatedFormat('d M Y') }}</strong>
                                @else
                                    Tidak kadaluarsa
                                @endif
                            </div>
                            <div class="project-card__actions">
                                <a href="https://scratch.mit.edu/projects/{{ $project->scratch_id }}" target="_blank" rel="noopener noreferrer" class="admin-btn admin-btn--outline">Preview</a>
                                <a href="{{ route('admin.kid-projects.edit', $project) }}" class="admin-btn admin-btn--solid">Edit</a>
                                <form action="{{ route('admin.kid-projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Hapus project ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn admin-btn--outline" style="color:#b83d3d;border-color:rgba(184,61,61,0.4);">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="pt-4">
                    {{ $projects->links() }}
                </div>
            @else
                <div class="empty-state">
                    <h5 class="mb-1">Belum ada project</h5>
                    <p>Mulai tambahkan project Scratch agar siswa bisa memamerkan karya terbaiknya.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
