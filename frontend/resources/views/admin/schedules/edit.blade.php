@extends('layouts.admin.app')
@section('title', 'Edit Jadwal Kelas')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <style>
        .form-label {
            color: var(--admin-text);
        }
        .form-text {
            color: var(--admin-text-muted);
        }
        .form-select, .form-control {
            background: var(--admin-card-bg);
            color: var(--admin-text);
            border: 1px solid var(--admin-border);
        }
        .select2-container--default .select2-selection--single {
            height: calc(2.25rem + 2px);
            border: 1px solid var(--admin-border);
            border-radius: var(--bs-border-radius);
            background: var(--admin-card-bg);
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: calc(2.25rem + 0px);
            padding-left: .75rem;
            color: var(--admin-text);
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(2.25rem + 2px);
            right: .5rem;
        }
        .select2-dropdown {
            background: var(--admin-card-bg);
            border: 1px solid var(--admin-border);
        }
        .select2-container--default .select2-results__option {
            color: var(--admin-text);
        }
        .select2-container--default .select2-results__option--highlighted {
            background: var(--admin-primary);
            color: #fff;
        }
    </style>
@endpush

@section('content')
<div class="dashboard-wrapper">
    <div class="admin-card">
        <div class="admin-card__header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="mb-1" style="color: var(--admin-text);">Edit Jadwal Kelas</h4>
                <p class="mb-0" style="color: var(--admin-text-muted);">Perbarui jadwal mingguan, mapel, dan guru.</p>
            </div>
            <a href="{{ route('admin.schedules.index') }}" class="admin-btn admin-btn--outline">Kembali</a>
        </div>

        <div class="admin-card__body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <div class="fw-semibold mb-1">Periksa input:</div>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Sekolah</label>
                        <select name="school_id" class="form-select" required>
                            <option value="">-- Pilih Sekolah --</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ (int) old('school_id', $schedule->school_id) === (int) $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kelas</label>
                        <select name="classroom_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}" {{ (int) old('classroom_id', $schedule->classroom_id) === (int) $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Ruang mengikuti kelas (otomatis).</div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Hari</label>
                        <select name="day_of_week" class="form-select" required>
                            @php
                                $days = [
                                    1 => 'Senin',
                                    2 => 'Selasa',
                                    3 => 'Rabu',
                                    4 => 'Kamis',
                                    5 => 'Jumat',
                                    6 => 'Sabtu',
                                    7 => 'Minggu',
                                ];
                                $currentDow = (int) (old('day_of_week') ?: ($schedule->date ? \Carbon\Carbon::parse($schedule->date)->dayOfWeekIso : 1));
                            @endphp
                            @foreach($days as $k => $label)
                                <option value="{{ $k }}" {{ $currentDow === $k ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jam Mulai</label>
                        <input type="time" name="start_time" class="form-control" value="{{ old('start_time', $schedule->start_time) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" name="end_time" class="form-control" value="{{ old('end_time', $schedule->end_time) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Catatan</label>
                        <input type="text" name="note" class="form-control" value="{{ old('note', $schedule->note) }}" placeholder="Opsional">
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                        <label class="form-label mb-0">Mapel & Guru (multi)</label>
                        <div class="d-flex gap-2">
                            <button type="button" class="admin-btn admin-btn--solid add-mapel-guru">+ Tambah Baris</button>
                            <button type="button" class="admin-btn admin-btn--outline" data-bs-toggle="modal" data-bs-target="#createMapelModal">+ Tambah Mapel</button>
                        </div>
                    </div>
                    <div id="mapel-guru-list">
                        @forelse($schedule->scheduleSubjectTeachers as $i => $sst)
                            <div class="row g-2 align-items-start mb-2 mapel-guru-row">
                                <div class="col-md-5">
                                    <select name="subject_ids[]" class="form-select select2" required>
                                        <option value="">-- Pilih Mapel --</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ (int) $sst->mapel_id === (int) $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <select name="teacher_ids[]" class="form-select select2" required>
                                        <option value="">-- Pilih Guru --</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ (int) $sst->teacher_id === (int) $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 d-grid">
                                    <button type="button" class="admin-btn admin-btn--outline remove-mapel-guru" {{ $i === 0 ? 'disabled' : '' }}>Hapus</button>
                                </div>
                            </div>
                        @empty
                            <div class="row g-2 align-items-start mb-2 mapel-guru-row">
                                <div class="col-md-5">
                                    <select name="subject_ids[]" class="form-select select2" required>
                                        <option value="">-- Pilih Mapel --</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <select name="teacher_ids[]" class="form-select select2" required>
                                        <option value="">-- Pilih Guru --</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 d-grid">
                                    <button type="button" class="admin-btn admin-btn--outline remove-mapel-guru" disabled>Hapus</button>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <button type="submit" class="admin-btn admin-btn--solid">Update</button>
                    <a href="{{ route('admin.schedules.index') }}" class="admin-btn admin-btn--outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Tunggu sampai jQuery sudah ready
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof jQuery === 'undefined') {
                console.error('jQuery is not loaded yet, retrying...');
                setTimeout(arguments.callee, 100);
                return;
            }

            const $ = jQuery;
            
            $(function () {
            function initSelect2($scope) {
                $scope.find('select.select2').select2({ width: '100%' });
            }

            initSelect2($(document));

            $('.add-mapel-guru').on('click', function () {
                let $firstRow = $('#mapel-guru-list .mapel-guru-row').first();

                $firstRow.find('select.select2').select2('destroy');
                let $clone = $firstRow.clone();
                initSelect2($firstRow);

                $clone.find('select').val('');
                $clone.find('button.remove-mapel-guru').prop('disabled', false);
                $('#mapel-guru-list').append($clone);
                initSelect2($clone);
                $clone.find('select').val('').trigger('change');
            });

            $('#mapel-guru-list').on('click', '.remove-mapel-guru', function () {
                if ($(this).is(':disabled')) return;
                let $row = $(this).closest('.mapel-guru-row');
                $row.find('select.select2').select2('destroy');
                $row.remove();
            });

            // Handle create mapel modal
            $('#createMapelForm').on('submit', function (e) {
                e.preventDefault();
                let $btn = $(this).find('button[type="submit"]');
                let $spinner = $btn.find('.spinner-border');
                let $text = $btn.find('.btn-text');
                let mapelName = $('#mapelName').val().trim();
                let schoolId = $('select[name="school_id"]').val();

                console.log('Form submitted:', { mapelName, schoolId });

                if (!mapelName) {
                    alert('Nama mapel tidak boleh kosong');
                    return;
                }

                if (!schoolId) {
                    alert('Pilih sekolah terlebih dahulu');
                    return;
                }

                $spinner.removeClass('d-none');
                $text.text('Menyimpan...');
                $btn.prop('disabled', true);

                console.log('Sending AJAX to:', '{{ route("admin.schedules.store-mapel") }}');

                $.ajax({
                    url: '{{ route("admin.schedules.store-mapel") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        school_id: schoolId,
                        name: mapelName,
                    },
                    success: function (response) {
                        console.log('AJAX success response:', response);
                        if (response.success) {
                            // Add new option to all subject selects
                            $('select[name="subject_ids[]"]').each(function () {
                                let $select = $(this);
                                let $newOption = $('<option></option>')
                                    .attr('value', response.mapel.id)
                                    .text(response.mapel.name);
                                $select.append($newOption);

                                // Reinitialize select2
                                if ($select.data('select2')) {
                                    $select.select2('destroy');
                                }
                                $select.select2({
                                    placeholder: '-- Pilih Mapel --',
                                    allowClear: true,
                                    width: '100%',
                                    minimumInputLength: 0
                                });
                            });

                            // Clear form and close modal
                            $('#createMapelForm')[0].reset();
                            let modal = bootstrap.Modal.getInstance(document.getElementById('createMapelModal'));
                            if (modal) modal.hide();

                            alert('Mapel berhasil ditambahkan!');
                        }
                    },
                    error: function (xhr) {
                        console.error('AJAX error:', xhr.status, xhr.responseJSON || xhr.responseText);
                        let message = 'Error: ' + (xhr.responseJSON?.message || xhr.responseText || 'Gagal menambahkan mapel');
                        alert(message);
                    },
                    complete: function () {
                        console.log('AJAX complete');
                        $spinner.addClass('d-none');
                        $text.text('Tambah Mapel');
                        $btn.prop('disabled', false);
                    }
                });
            });
        });
    </script>

    <!-- Modal Create Mapel -->
    <div class="modal fade" id="createMapelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="background: var(--admin-card-bg); border: 1px solid var(--admin-border); color: var(--admin-text);">
                <div class="modal-header" style="border-bottom: 1px solid var(--admin-border);">
                    <h5 class="modal-title">Tambah Mapel Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="createMapelForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Mapel</label>
                            <input type="text" id="mapelName" name="name" class="form-control" placeholder="Contoh: Matematika, Bahasa Indonesia" required>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid var(--admin-border);">
                        <button type="button" class="admin-btn admin-btn--outline" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="admin-btn admin-btn--solid">
                            <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
                            <span class="btn-text">Tambah Mapel</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
