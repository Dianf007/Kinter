@extends('layouts.admin.app')
@section('title', 'Kelola Siswa')
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
                <h4 class="mb-1" style="color: var(--admin-text);">Kelola Siswa</h4>
                <p class="mb-0" style="color: var(--admin-text-muted);">Daftar siswa berdasarkan sekolah aktif. Filter, cari, dan kelola data siswa.</p>
            </div>
            <a href="{{ route('admin.students.create') }}" class="admin-btn admin-btn--solid">+ Siswa Baru</a>
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
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama/NIS/email/kelas">
                <button type="submit" class="admin-btn admin-btn--solid">Cari</button>
                <a href="{{ route('admin.students.index') }}" class="admin-btn admin-btn--outline">Reset</a>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Kelas</th>
                            <th>Sekolah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td>{{ $student->student_id }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->class }}</td>
                            <td>{{ $student->school->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.students.edit', $student) }}" class="admin-btn admin-btn--solid btn-sm">Edit</a>
                                <form action="{{ route('admin.students.destroy', $student) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn admin-btn--outline btn-sm" onclick="return confirm('Yakin hapus siswa?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted">Tidak ada data siswa.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pt-3">
                {{ $students->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
