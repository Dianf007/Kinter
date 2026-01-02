@extends('layouts.admin.app')
@section('title', 'Tambah Guru')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Dark mode styling for form controls */
    [data-theme="dark"] .form-control,
    [data-theme="dark"] .form-label,
    [data-theme="dark"] select {
        background-color: var(--admin-card-bg);
        color: var(--admin-text);
        border-color: var(--admin-border);
    }
    
    [data-theme="dark"] .form-control:focus {
        background-color: var(--admin-card-bg);
        color: var(--admin-text);
        border-color: var(--admin-primary);
    }
    
    /* Select2 dark mode */
    [data-theme="dark"] .select2-container--default .select2-selection--multiple {
        background-color: var(--admin-card-bg);
        border-color: var(--admin-border);
    }
    
    [data-theme="dark"] .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: var(--admin-primary);
    }
    
    [data-theme="dark"] .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: var(--admin-primary);
        border-color: var(--admin-primary);
        color: white;
    }
    
    [data-theme="dark"] .select2-dropdown {
        background-color: var(--admin-card-bg);
        border-color: var(--admin-border);
    }
    
    [data-theme="dark"] .select2-container--default .select2-results__option {
        color: var(--admin-text);
    }
    
    [data-theme="dark"] .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: var(--admin-primary);
        color: white;
    }
    
    [data-theme="dark"] .select2-container--default .select2-search--inline .select2-search__field {
        color: var(--admin-text);
    }
</style>
<div class="dashboard-wrapper">
    <div class="admin-card">
        <div class="admin-card__header">
            <h4 style="color: var(--admin-text);">Tambah Guru Baru</h4>
        </div>
        <div class="admin-card__body">
            <form action="{{ route('admin.teachers.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="school_id" class="form-label" style="color: var(--admin-text);">Sekolah <span class="text-danger">*</span></label>
                    <select name="school_id" id="school_id" class="form-control" required>
                        <option value="">-- Pilih Sekolah --</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ (old('school_id') ?? $currentSchoolId) == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                        @endforeach
                    </select>
                    @error('school_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label" style="color: var(--admin-text);">Nama Guru <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label" style="color: var(--admin-text);">Email</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label" style="color: var(--admin-text);">Telepon</label>
                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                    @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="subject_ids" class="form-label" style="color: var(--admin-text);">Mata Pelajaran </label>
                    <select name="subject_ids[]" id="subject_ids" class="form-control select2-multiple @error('subject_ids') is-invalid @enderror" multiple data-initial-subjects="{{ json_encode($subjects->pluck('id')->toArray()) }}">
                        @forelse($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ in_array($subject->id, old('subject_ids', [])) ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @empty
                            <option disabled>Pilih sekolah terlebih dahulu</option>
                        @endforelse
                    </select>
                    @error('subject_ids')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="admin-btn admin-btn--solid">Simpan</button>
                    <a href="{{ route('admin.teachers.index') }}" class="admin-btn admin-btn--outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    console.log('=== Teacher Form Initialization ===');
    console.log('Current School ID (from PHP):', '{{ $currentSchoolId ?? "undefined" }}');
    console.log('Initial Subjects:', {!! json_encode($subjects->toArray()) !!});
    
    // Initialize Select2 for subjects
    $('#subject_ids').select2({
        placeholder: 'Pilih mata pelajaran',
        allowClear: true,
        width: '100%'
    });
    console.log('Select2 initialized');

    // Function to load subjects
    function loadSubjects(schoolId) {
        const subjectSelect = $('#subject_ids');
        console.log('loadSubjects() called with schoolId:', schoolId);
        
        if (!schoolId) {
            console.log('No school selected - clearing subjects');
            subjectSelect.empty().append('<option disabled>Pilih sekolah terlebih dahulu</option>').trigger('change');
            return;
        }

        const apiUrl = '/admin/teachers/api/school-subjects/' + schoolId;
        console.log('Loading subjects from:', apiUrl);
        
        // Fetch subjects for selected school
        $.ajax({
            url: apiUrl,
            type: 'GET',
            dataType: 'json',
            headers: {
                'Accept': 'application/json'
            },
            success: function(data) {
                console.log('✓ AJAX Success! Subjects received:', data);
                subjectSelect.empty();
                
                if (!data || data.length === 0) {
                    console.log('No subjects found for this school');
                    subjectSelect.append('<option disabled>Tidak ada mata pelajaran di sekolah ini</option>').trigger('change');
                } else {
                    console.log('Adding ' + data.length + ' subjects');
                    data.forEach(function(subject) {
                        console.log('  - Subject ID ' + subject.id + ':', subject.name);
                        subjectSelect.append(
                            '<option value="' + subject.id + '">' + subject.name + '</option>'
                        );
                    });
                    subjectSelect.trigger('change');
                }
            },
            error: function(xhr, status, error) {
                console.error('✗ AJAX Error!');
                console.error('  Status:', xhr.status);
                console.error('  Error:', error);
                console.error('  Response:', xhr.responseText.substring(0, 200));
                
                // Fallback: show error message
                subjectSelect.empty().append('<option disabled>Error loading subjects (Status: ' + xhr.status + ')</option>').trigger('change');
            }
        });
    }

    // Load subjects when school changes
    $('#school_id').on('change', function() {
        const schoolId = $(this).val();
        const schoolName = $(this).find('option:selected').text();
        console.log('School changed:', { schoolId: schoolId, schoolName: schoolName });
        loadSubjects(schoolId);
    });
    
    // Check initial school selection
    console.log('=== Page Load Checks ===');
    const initialSchoolId = $('#school_id').val();
    const selectedOption = $('#school_id').find('option:selected');
    console.log('Initial dropdown value:', initialSchoolId);
    console.log('Selected option text:', selectedOption.text());
    console.log('All school options:', $('#school_id').find('option').map(function() { return $(this).val() + ':' + $(this).text(); }).get());
    
    // Load subjects if school already selected (initial load)
    if (initialSchoolId) {
        console.log('Triggering initial load for school:', initialSchoolId);
        loadSubjects(initialSchoolId);
    } else {
        console.log('No initial school selected');
    }
});
</script>
@endpush
