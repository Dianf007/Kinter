@extends('layouts.admin.app')
@section('title', 'Edit Kelas')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Edit Kelas</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.classrooms.update', $classroom->id) }}" method="POST">
                @method('PUT')
                @include('admin.classrooms._form')
                <div class="mt-4">
                    <button type="submit" class="btn btn-warning">Update</button>
                    <a href="{{ route('admin.classrooms.index') }}" class="btn btn-secondary">Batal</a>
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