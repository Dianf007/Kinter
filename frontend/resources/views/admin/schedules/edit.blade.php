@extends('layouts.admin.app')
@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Edit Jadwal Kelas</h1>
    <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}" class="card p-4">
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Sekolah</label>
                <select name="school_id" class="form-select" required>
                    <option value="">-- Pilih Sekolah --</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" @if($schedule->school_id == $school->id) selected @endif>{{ $school->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Kelas</label>
                <select name="classroom_id" class="form-select" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" @if($schedule->classroom_id == $classroom->id) selected @endif>{{ $classroom->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Ruang</label>
                <select name="room_id" class="form-select" required>
                    <option value="">-- Pilih Ruang --</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" @if($schedule->room_id == $room->id) selected @endif>{{ $room->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <label>Tanggal</label>
                <input type="date" name="date" class="form-control" value="{{ $schedule->date }}" required>
            </div>
            <div class="col-md-3">
                <label>Jam Mulai</label>
                <input type="time" name="start_time" class="form-control" value="{{ $schedule->start_time }}" required>
            </div>
            <div class="col-md-3">
                <label>Jam Selesai</label>
                <input type="time" name="end_time" class="form-control" value="{{ $schedule->end_time }}" required>
            </div>
            <div class="col-md-3">
                <label>Catatan</label>
                <input type="text" name="note" class="form-control" value="{{ $schedule->note }}">
            </div>
        </div>
        <div class="mb-3">
            <label>Mapel & Guru (multi)</label>
            <div id="mapel-guru-list">
                @foreach($schedule->scheduleSubjectTeachers as $i => $sst)
                <div class="row mb-2">
                    <div class="col-md-5">
                        <select name="subject_ids[]" class="form-select" required>
                            <option value="">-- Pilih Mapel --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" @if($sst->subject_id == $subject->id) selected @endif>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <select name="teacher_ids[]" class="form-select" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" @if($sst->teacher_id == $teacher->id) selected @endif>{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        @if($i == 0)
                        <button type="button" class="btn btn-success add-mapel-guru">+</button>
                        @else
                        <button type="button" class="btn btn-danger remove-mapel-guru">-</button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.add-mapel-guru').onclick = function() {
            let row = this.closest('.row').cloneNode(true);
            row.querySelectorAll('select').forEach(sel => sel.value = '');
            row.querySelector('.add-mapel-guru').outerHTML = '<button type="button" class="btn btn-danger remove-mapel-guru">-</button>';
            document.getElementById('mapel-guru-list').appendChild(row);
        };
        document.getElementById('mapel-guru-list').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-mapel-guru')) {
                e.target.closest('.row').remove();
            }
        });
    });
</script>
@endsection
