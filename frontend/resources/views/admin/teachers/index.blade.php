@extends('layouts.admin.app')
@section('title', 'Kelola Guru')
@push('styles')
<style>
    .filter-bar input, .filter-bar select {
        min-width: 180px;
        border-radius: 16px;
        border: 1px solid var(--admin-border);
        padding: 10px 14px;
        background: var(--admin-card-bg);
        color: var(--admin-text);
    }
    .filter-bar input::placeholder {
        color: var(--admin-text-muted);
    }
    .table { color: var(--admin-text); }
    .table th { color: var(--admin-text); background: var(--admin-bg); }
    .text-muted { color: var(--admin-text-muted) !important; }
    
    /* Table hover state yang support dark/light mode */
    .table tbody tr:hover {
        background: var(--admin-bg) !important;
        color: var(--admin-text) !important;
    }
    .table tbody tr:hover td {
        color: var(--admin-text) !important;
    }
    .table tbody tr:hover a,
    .table tbody tr:hover button {
        color: inherit;
    }
</style>
@endpush
@section('content')
<div class="dashboard-wrapper">
    <div class="admin-card">
        <div class="admin-card__header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="mb-1" style="color: var(--admin-text);">Kelola Guru</h4>
                <p class="mb-0" style="color: var(--admin-text-muted);">Daftar guru berdasarkan sekolah aktif. Filter, cari, dan kelola data guru.</p>
            </div>
            <a href="{{ route('admin.teachers.create') }}" class="admin-btn admin-btn--solid">+ Guru Baru</a>
        </div>
        <div class="admin-card__body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="GET" class="filter-bar d-flex flex-wrap gap-2 mb-3 align-items-center">
                <select name="school_id" onchange="this.form.submit()">
                    <option value="">-- Semua Sekolah --</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ (string)$schoolId === (string)$school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                    @endforeach
                </select>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama/email/telepon">
                <button type="submit" class="admin-btn admin-btn--solid">Cari</button>
                <a href="{{ route('admin.teachers.index') }}" class="admin-btn admin-btn--outline">Reset</a>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="cursor: pointer;">
                                <a href="{{ route('admin.teachers.index', array_merge(request()->query(), ['sort' => 'name', 'order' => request('order') === 'asc' && request('sort') === 'name' ? 'desc' : 'asc'])) }}" style="color: inherit; text-decoration: none;">
                                    Nama
                                    @if(request('sort') === 'name')
                                        {{ request('order') === 'asc' ? '↑' : '↓' }}
                                    @endif
                                </a>
                            </th>
                            <th style="cursor: pointer;">
                                <a href="{{ route('admin.teachers.index', array_merge(request()->query(), ['sort' => 'email', 'order' => request('order') === 'asc' && request('sort') === 'email' ? 'desc' : 'asc'])) }}" style="color: inherit; text-decoration: none;">
                                    Email
                                    @if(request('sort') === 'email')
                                        {{ request('order') === 'asc' ? '↑' : '↓' }}
                                    @endif
                                </a>
                            </th>
                            <th style="cursor: pointer;">
                                <a href="{{ route('admin.teachers.index', array_merge(request()->query(), ['sort' => 'phone', 'order' => request('order') === 'asc' && request('sort') === 'phone' ? 'desc' : 'asc'])) }}" style="color: inherit; text-decoration: none;">
                                    Telepon
                                    @if(request('sort') === 'phone')
                                        {{ request('order') === 'asc' ? '↑' : '↓' }}
                                    @endif
                                </a>
                            </th>
                            <th>Mata Pelajaran</th>
                            <th>Sekolah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher->name }}</td>
                            <td>{{ $teacher->email ?? '-' }}</td>
                            <td>{{ $teacher->phone ?? '-' }}</td>
                            <td>
                                @if($teacher->subjects->count() > 0)
                                    <span class="badge bg-info">
                                        {{ $teacher->subjects->pluck('name')->join(', ') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $teacher->school->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.teachers.edit', $teacher) }}" class="admin-btn admin-btn--solid btn-sm">Edit</a>
                                <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn admin-btn--outline btn-sm" onclick="return confirm('Yakin hapus guru?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted">Tidak ada data guru.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pt-3">
                {{ $teachers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
