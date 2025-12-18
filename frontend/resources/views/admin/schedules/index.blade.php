@extends('layouts.admin.app')
@push('styles')
<style>
    .form-select {
        background: var(--admin-card-bg);
        color: var(--admin-text);
        border: 1px solid var(--admin-border);
    }
    .table {
        color: var(--admin-text);
    }
    .table th {
        color: var(--admin-text);
        background: var(--admin-bg);
    }
    .text-center {
        color: var(--admin-text-muted);
    }
</style>
@endpush
@section('content')
<div class="dashboard-wrapper">
    <div class="admin-card">
        <div class="admin-card__header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="mb-1" style="color: var(--admin-text);">Jadwal Kelas</h4>
                <p class="mb-0" style="color: var(--admin-text-muted);">Kelola jadwal kelas, mapel, dan guru secara profesional.</p>
            </div>
            <a href="{{ route('admin.schedules.create') }}" class="admin-btn admin-btn--solid">+ Jadwal Baru</a>
        </div>
        <div class="admin-card__body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="GET" class="filter-bar row g-2 mb-3">
                <div class="col-md-4">
                    <select name="school_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Pilih Sekolah --</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" @if((string) request('school_id', session('admin_school_id')) === (string) $school->id) selected @endif>{{ $school->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="classroom_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" @if(request('classroom_id') == $classroom->id) selected @endif>{{ $classroom->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Kelas</th>
                            <th>Mapel & Guru</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $schedule)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($schedule->date)->locale('id')->isoFormat('dddd') }}</td>
                            <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                            <td>{{ $schedule->classroom->name ?? '-' }}</td>
                            <td>
                                @foreach($schedule->scheduleSubjectTeachers as $sst)
                                    <div><b>{{ $sst->subject->name ?? '-' }}</b> - {{ $sst->teacher->name ?? '-' }}</div>
                                @endforeach
                            </td>
                            <td>{{ $schedule->note }}</td>
                            <td>
                                <a href="{{ route('admin.schedules.edit', $schedule) }}" class="admin-btn admin-btn--solid btn-sm">Edit</a>
                                <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn admin-btn--outline btn-sm" onclick="return confirm('Yakin hapus jadwal?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center">Tidak ada jadwal</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
