@extends('layouts.admin.app')
@section('title', 'Edit Kelas')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <style>
        .form-label {
            color: var(--admin-text);
            font-weight: 500;
        }
        .form-control, .form-select {
            background: var(--admin-card-bg);
            color: var(--admin-text);
            border: 1px solid var(--admin-border);
        }
        .form-control:focus, .form-select:focus {
            background: var(--admin-card-bg);
            color: var(--admin-text);
            border-color: var(--admin-primary);
        }
        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--multiple {
            background: var(--admin-card-bg);
            border: 1px solid var(--admin-border);
            min-height: calc(2.25rem + 2px);
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered,
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            color: var(--admin-text);
            line-height: calc(2.25rem);
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: var(--admin-primary);
            border: none;
            color: #fff;
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
        .select2-container--default .select2-search--dropdown .select2-search__field {
            background: var(--admin-card-bg);
            color: var(--admin-text);
            border: 1px solid var(--admin-border);
        }
    </style>
@endpush

@section('content')
<div class="dashboard-wrapper">
    <div class="admin-card">
        <div class="admin-card__header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="mb-1" style="color: var(--admin-text);">Edit Kelas</h4>
                <p class="mb-0" style="color: var(--admin-text-muted);">Perbarui informasi kelas {{ $classroom->name }}.</p>
            </div>
            <a href="{{ route('admin.classrooms.index') }}" class="admin-btn admin-btn--outline">Kembali</a>
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

            <form action="{{ route('admin.classrooms.update', $classroom->id) }}" method="POST">
                @method('PUT')
                @include('admin.classrooms._form')
                <div class="mt-4 d-flex flex-wrap gap-2">
                    <button type="submit" class="admin-btn admin-btn--solid">Update Kelas</button>
                    <a href="{{ route('admin.classrooms.index') }}" class="admin-btn admin-btn--outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function () {
            $('.select2').select2({ width: '100%' });

            $('#teachers').select2({
                width: '100%',
                closeOnSelect: false,
                tags: true,
                createTag: function () { return null; }
            });
        });
    </script>
@endpush