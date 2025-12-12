@extends('layouts.admin.app')
@section('content')
<div class="dashboard-wrapper">
    <div class="admin-card">
        <div class="admin-card__header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="mb-1">Jadwal Kelas</h4>
                <p class="mb-0">Kelola jadwal kelas, mapel, dan guru secara profesional.</p>
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
                            <option value="{{ $school->id }}" @if(request('school_id') == $school->id) selected @endif>{{ $school->name }}</option>
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
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Kelas</th>
                            <th>Ruang</th>
                            <th>Mapel & Guru</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->date }}</td>
                            <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                            <td>{{ $schedule->classroom->name ?? '-' }}</td>
                            <td>{{ $schedule->room->name ?? '-' }}</td>
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
                        <tr><td colspan="7" class="text-center">Tidak ada jadwal</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
