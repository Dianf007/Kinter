@php
    $editing = isset($admin);
    $currentRoleValue = old('role', $admin->role ?? ($roleOptions[0] ?? 'admin'));
    $selectedSchoolId = old('school_id', $admin->school_id ?? '');
    $selectedSchoolIds = old('school_ids', isset($admin) ? $admin->managedSchools->pluck('id')->all() : []);
@endphp

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="vstack gap-3">
    <div>
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control admin-input" value="{{ old('username', $admin->username ?? '') }}" required>
    </div>

    <div>
        <label for="password" class="form-label">Password {{ $editing ? '(opsional)' : '' }}</label>
        <input type="password" name="password" id="password" class="form-control admin-input" {{ $editing ? '' : 'required' }}>
        @if($editing)
            <small class="text-muted">Kosongkan jika tidak ingin mengganti password.</small>
        @endif
    </div>

    <div>
        <label for="role" class="form-label">Role</label>
        @if(!empty($roleLocked))
            <input type="text" class="form-control admin-input" value="{{ $admin->role ?? 'admin' }}" disabled>
            <small class="text-muted">Role tidak bisa diubah untuk akun ini.</small>
        @else
            <select name="role" id="role" class="form-select" required>
                @foreach($roleOptions as $opt)
                    <option value="{{ $opt }}" {{ $currentRoleValue === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        @endif
    </div>

    <div id="adminSchoolWrap">
        <label for="school_id" class="form-label">Sekolah (untuk admin)</label>
        <select name="school_id" id="school_id" class="form-select">
            <option value="">-- Pilih Sekolah --</option>
            @foreach($schools as $school)
                <option value="{{ $school->id }}" {{ (string)$selectedSchoolId === (string)$school->id ? 'selected' : '' }}>{{ $school->name }}</option>
            @endforeach
        </select>
    </div>

    <div id="superadminSchoolsWrap">
        <label class="form-label">Sekolah yang dikelola (untuk superadmin)</label>
        <div class="row g-2">
            @foreach($schools as $school)
                <div class="col-md-6">
                    <label class="d-flex align-items-center gap-2 p-2" style="border:1px solid rgba(28,31,46,0.12);border-radius:14px;">
                        <input type="checkbox" name="school_ids[]" value="{{ $school->id }}" {{ in_array($school->id, (array)$selectedSchoolIds, true) ? 'checked' : '' }}>
                        <span>{{ $school->name }}</span>
                    </label>
                </div>
            @endforeach
        </div>
        <small class="text-muted">Pilih minimal 1 sekolah.</small>
    </div>
</div>

<div class="d-flex flex-wrap gap-3 pt-3">
    <button type="submit" class="auth-btn" style="max-width: 220px;">{{ $editing ? 'Update Admin' : 'Create Admin' }}</button>
    <a href="{{ route('admin.admins.index') }}" class="admin-btn admin-btn--outline">Cancel</a>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var roleSelect = document.getElementById('role');
        var adminWrap = document.getElementById('adminSchoolWrap');
        var superWrap = document.getElementById('superadminSchoolsWrap');

        function applyRoleUI(role) {
            if (!adminWrap || !superWrap) return;
            var isSuper = role === 'superadmin';
            adminWrap.style.display = isSuper ? 'none' : 'block';
            superWrap.style.display = isSuper ? 'block' : 'none';
        }

        if (roleSelect) {
            applyRoleUI(roleSelect.value);
            roleSelect.addEventListener('change', function () {
                applyRoleUI(this.value);
            });
        } else {
            applyRoleUI(@json($admin->role ?? 'admin'));
        }
    });
</script>
