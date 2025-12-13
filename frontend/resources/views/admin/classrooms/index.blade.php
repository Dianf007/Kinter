@extends('layouts.admin.app')
@section('title', 'Kelola Kelas')
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
@endpush
@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Kelola Kelas</h5>
            <a href="{{ route('admin.classrooms.create') }}" class="btn btn-primary">Tambah Kelas</a>
        </div>
        <div class="card-body">
            <div class="filter-bar mb-3">
                <form method="GET" class="row g-2 align-items-center">
                    <div class="col-auto">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama/kode kelas..." value="{{ request('search') }}">
                    </div>
                    <div class="col-auto">
                        <select name="school_id" class="form-select select2">
                            <option value="">Semua Sekolah</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="non-aktif" {{ request('status') == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-outline-primary" type="submit">Filter</button>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="classrooms-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama Kelas</th>
                            <th>Kode</th>
                            <th>Sekolah</th>
                            <th>Wali Kelas</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classrooms as $i => $classroom)
                        <tr>
                            <td>{{ $classrooms->firstItem() + $i }}</td>
                            <td>{{ $classroom->name }}</td>
                            <td>{{ $classroom->code }}</td>
                            <td>{{ $classroom->school->name ?? '-' }}</td>
                            <td>{{ $classroom->waliKelas->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $classroom->status == 'aktif' ? 'success' : 'secondary' }}">{{ ucfirst($classroom->status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.classrooms.edit', $classroom->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $classroom->id }}" data-name="{{ $classroom->name }}">Hapus</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data kelas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $classrooms->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@include('admin.classrooms.modal-delete')
@endsection
@push('scripts')
<script src="https://cdn.datatables.net/2.1.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function() {
    $('.select2').select2({ width: '100%' });

    // Jika ada multi-select di halaman ini (future-proof)
    $('#teachers').select2({
        width: '100%',
        closeOnSelect: false,
        tags: true,
        createTag: function () { return null; }
    });
    // Modal hapus
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var name = button.getAttribute('data-name');
        deleteModal.querySelector('.modal-body span.name').textContent = name;
        deleteModal.querySelector('form').action = '/admin/classrooms/' + id;
    });
});
</script>
@endpush