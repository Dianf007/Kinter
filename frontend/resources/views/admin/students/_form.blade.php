@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label for="school_id" class="form-label">Sekolah</label>
        <select name="school_id" id="school_id" class="form-select" required>
            <option value="">Pilih Sekolah</option>
            @foreach($schools as $school)
                <option value="{{ $school->id }}" {{ old('school_id', $student->school_id ?? $schoolId ?? session('admin_school_id')) == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="name" class="form-label">Nama Lengkap</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $student->name ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label for="email" class="form-label">Email <span style="color: var(--admin-danger);">*</span></label>
        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $student->email ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label for="student_id" class="form-label">NIS</label>
        <input type="text" name="student_id" id="student_id" class="form-control" value="{{ old('student_id', $student->student_id ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label for="password" class="form-label">Password @if(isset($student)) (biarkan kosong jika tidak ingin ubah) @endif <span style="color: var(--admin-danger);">*</span></label>
        <input type="password" name="password" id="password" class="form-control" {{ isset($student) ? '' : 'required' }}>
        @if(!isset($student))
            <small style="color: var(--admin-text-muted);">Minimal 6 karakter</small>
        @endif
    </div>
    <div class="col-md-6">
        <label for="student_code" class="form-label">Kode Siswa</label>
        <input type="text" name="student_code" id="student_code" class="form-control" value="{{ old('student_code', $student->student_code ?? '') }}">
    </div>
    <div class="col-md-4">
        <label for="class" class="form-label">Kelas <span style="color: var(--admin-danger);">*</span></label>
        <input type="text" name="class" id="class" class="form-control" value="{{ old('class', $student->class ?? '') }}" placeholder="Contoh: Grade 5A" required>
    </div>
    <div class="col-md-4">
        <label for="gender" class="form-label">Jenis Kelamin</label>
        <select name="gender" id="gender" class="form-select">
            <option value="">-</option>
            <option value="L" {{ old('gender', $student->gender ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
            <option value="P" {{ old('gender', $student->gender ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
        </select>
    </div>
    <div class="col-md-4">
        <label for="birth_date" class="form-label">Tanggal Lahir</label>
        <input type="date" name="birth_date" id="birth_date" class="form-control" value="{{ old('birth_date', $student->birth_date ?? '') }}">
    </div>
    <div class="col-md-6">
        <label for="phone" class="form-label">No. HP</label>
        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $student->phone ?? '') }}">
    </div>
    <div class="col-md-6">
        <label for="address" class="form-label">Alamat</label>
        <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $student->address ?? '') }}">
    </div>
    <div class="col-12">
        <label for="catatan" class="form-label">Catatan</label>
        <textarea name="catatan" id="catatan" class="form-control" rows="3">{{ old('catatan', $student->catatan ?? '') }}</textarea>
    </div>
</div>
