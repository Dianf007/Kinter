@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label for="school_id" class="form-label">Sekolah</label>
        <select name="school_id" id="school_id" class="form-select select2" required>
            <option value="">Pilih Sekolah</option>
            @foreach($schools as $school)
                <option value="{{ $school->id }}" {{ old('school_id', $classroom->school_id ?? '') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="name" class="form-label">Nama Kelas</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $classroom->name ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label for="code" class="form-label">Kode Kelas</label>
        <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $classroom->code ?? '') }}">
    </div>
    <div class="col-md-6">
        <label for="teacher_id" class="form-label">Wali Kelas (opsional)</label>
        <select name="teacher_id" id="teacher_id" class="form-select select2">
            <option value="">Pilih Guru</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" {{ old('teacher_id', $classroom->teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-12">
        <label for="teachers" class="form-label">Guru Pengajar (boleh lebih dari satu)</label>
        <select name="teachers[]" id="teachers" class="form-select select2" multiple>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" {{ (isset($classroom) && $classroom->teachers->contains($teacher->id)) || (collect(old('teachers'))->contains($teacher->id)) ? 'selected' : '' }}>{{ $teacher->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-12">
        <label for="description" class="form-label">Deskripsi (opsional)</label>
        <textarea name="description" id="description" class="form-control">{{ old('description', $classroom->description ?? '') }}</textarea>
    </div>
    <div class="col-md-4">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select" required>
            <option value="aktif" {{ old('status', $classroom->status ?? 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="non-aktif" {{ old('status', $classroom->status ?? '') == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
        </select>
    </div>
</div>