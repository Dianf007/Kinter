@extends('layouts.admin.app')
@section('title', 'Kelola Kelas')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css">
    <style>
        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            margin-bottom: 16px;
        }
        .filter-bar input,
        .filter-bar select {
            min-width: 200px;
            border-radius: 16px;
            border: 1px solid rgba(28,31,46,0.12);
            padding: 10px 14px;
        }
        @media (max-width: 576px) {
            .filter-bar input,
            .filter-bar select {
                min-width: unset;
                flex: 1;
            }
        }
        .modal.admin-modal .modal-content {
            border-radius: 20px;
            border: 1px solid rgba(75,107,251,0.12);
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(28,31,46,0.18);
        }
        .modal.admin-modal .modal-header {
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
            color: #fff;
            border-bottom: none;
        }
        .modal.admin-modal .modal-title {
            font-weight: 600;
        }
        .modal.admin-modal .btn-close {
            filter: invert(1) grayscale(1);
            opacity: .85;
        }
        .dt-container .dt-search input {
            border-radius: 16px;
            border: 1px solid rgba(28,31,46,0.12);
            padding: 8px 12px;
        }
        .dt-container .dt-length select {
            border-radius: 16px;
            border: 1px solid rgba(28,31,46,0.12);
            padding: 8px 12px;
        }
    </style>
@endpush
@section('content')
<div class="dashboard-wrapper">
    <div class="admin-card">
        <div class="admin-card__header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="mb-1">Kelola Kelas</h4>
                <p class="mb-0">Buat, ubah, dan atur kelas per sekolah.</p>
            </div>
            <a href="{{ route('admin.classrooms.create') }}" class="admin-btn admin-btn--solid">+ Kelas Baru</a>
        </div>
        <div class="admin-card__body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="GET" class="filter-bar" id="classroomFilters">
                <input type="text" name="q" id="filterQ" value="{{ request('q', request('search')) }}" placeholder="Cari nama / kode kelas">
                <select name="status" id="filterStatus">
                    <option value="">-- Semua Status --</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>aktif</option>
                    <option value="non-aktif" {{ request('status') === 'non-aktif' ? 'selected' : '' }}>non-aktif</option>
                </select>
                <select name="school_id" id="filterSchool">
                    <option value="">-- Semua Sekolah --</option>
                    @foreach(($schools ?? []) as $school)
                        <option value="{{ $school->id }}" {{ (string)request('school_id') === (string)$school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="admin-btn admin-btn--solid" style="padding: 10px 20px;">Terapkan</button>
                <a href="{{ route('admin.classrooms.index') }}" class="admin-btn admin-btn--outline">Reset</a>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="classroomsTable">
                    <thead>
                        <tr>
                            <th>Nama Kelas</th>
                            <th>Kode</th>
                            <th>Sekolah</th>
                            <th>Wali Kelas</th>
                            <th>Status</th>
                            <th style="width: 190px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classrooms as $classroom)
                            <tr>
                                <td><strong>{{ $classroom->name }}</strong></td>
                                <td>{{ $classroom->code ?? '-' }}</td>
                                <td>{{ $classroom->school->name ?? '-' }}</td>
                                <td>{{ $classroom->waliKelas->name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ ($classroom->status ?? 'aktif') === 'aktif' ? 'success' : 'secondary' }}">{{ $classroom->status ?? 'aktif' }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('admin.classrooms.edit', $classroom) }}" class="admin-btn admin-btn--solid btn-sm">Edit</a>
                                        <button type="button"
                                            class="admin-btn admin-btn--outline btn-sm js-delete-classroom"
                                            data-name="{{ $classroom->name }}"
                                            data-action="{{ route('admin.classrooms.destroy', $classroom) }}"
                                            style="color:#b83d3d;border-color:rgba(184,61,61,0.4);">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada data kelas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($classrooms, 'links'))
                <div class="pt-3" id="ssrPagination">
                    {{ $classrooms->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Modal (modern) -->
<div class="modal fade admin-modal" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Kamu yakin ingin menghapus kelas <strong id="deleteClassroomName">-</strong>?</p>
                <div class="small text-muted">Aksi ini tidak bisa dibatalkan.</div>
            </div>
            <div class="modal-footer" style="border-top:1px solid rgba(28,31,46,0.08);">
                <button type="button" class="admin-btn admin-btn--outline" data-bs-dismiss="modal">Batal</button>
                <form method="POST" id="deleteClassroomForm" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-btn admin-btn--solid" style="box-shadow:none;">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
    <script>
        (function () {
            function escapeHtml(str) {
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            }

            var deleteModalEl = document.getElementById('confirmDeleteModal');
            var deleteNameEl = document.getElementById('deleteClassroomName');
            var deleteFormEl = document.getElementById('deleteClassroomForm');
            var deleteModal = deleteModalEl ? new bootstrap.Modal(deleteModalEl) : null;

            document.querySelectorAll('.js-delete-classroom').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    if (deleteNameEl) deleteNameEl.textContent = btn.getAttribute('data-name') || '-';
                    if (deleteFormEl) deleteFormEl.action = btn.getAttribute('data-action') || '';
                    if (deleteModal) deleteModal.show();
                });
            });

            var filterForm = document.getElementById('classroomFilters');
            var filterQ = document.getElementById('filterQ');
            var filterStatus = document.getElementById('filterStatus');
            var filterSchool = document.getElementById('filterSchool');

            function currentFilterParams() {
                return {
                    q: filterQ ? filterQ.value : '',
                    status: filterStatus ? filterStatus.value : '',
                    school_id: filterSchool ? filterSchool.value : ''
                };
            }

            // If DataTables CDN is blocked/offline, keep SSR table visible.
            if (typeof DataTable !== 'undefined') {
                var ssrPagination = document.getElementById('ssrPagination');
                if (ssrPagination) ssrPagination.style.display = 'none';

                var table = new DataTable('#classroomsTable', {
                    serverSide: true,
                    processing: true,
                    pageLength: 10,
                    ajax: {
                        url: @json(route('admin.classrooms.data')),
                        type: 'GET',
                        data: function (d) {
                            var p = currentFilterParams();
                            d.q = p.q;
                            d.status = p.status;
                            d.school_id = p.school_id;
                        }
                    },
                    searching: false,
                    columns: [
                        {
                            data: 'name',
                            render: function (data) { return '<strong>' + escapeHtml(data) + '</strong>'; }
                        },
                        { data: 'code' },
                        { data: 'school' },
                        { data: 'wali_kelas' },
                        {
                            data: 'status',
                            render: function (data) {
                                var v = (data || 'aktif');
                                var badge = v === 'aktif' ? 'success' : 'secondary';
                                return '<span class="badge bg-' + badge + '">' + escapeHtml(v) + '</span>';
                            }
                        },
                        {
                            data: 'id',
                            orderable: false,
                            searchable: false,
                            render: function (_id, _type, row) {
                                var editUrl = row.edit_url;
                                var destroyUrl = row.destroy_url;
                                var name = row.name;
                                return (
                                    '<div class="d-flex flex-wrap gap-2">'
                                    + '<a href="' + editUrl + '" class="admin-btn admin-btn--solid btn-sm">Edit</a>'
                                    + '<button type="button" class="admin-btn admin-btn--outline btn-sm js-delete-classroom" '
                                    + 'data-name="' + escapeHtml(name) + '" data-action="' + destroyUrl + '" '
                                    + 'style="color:#b83d3d;border-color:rgba(184,61,61,0.4);">Hapus</button>'
                                    + '</div>'
                                );
                            }
                        }
                    ]
                });

                // re-bind delete buttons created by DataTables
                document.getElementById('classroomsTable').addEventListener('click', function (e) {
                    var btn = e.target.closest('.js-delete-classroom');
                    if (!btn) return;
                    if (deleteNameEl) deleteNameEl.textContent = btn.getAttribute('data-name') || '-';
                    if (deleteFormEl) deleteFormEl.action = btn.getAttribute('data-action') || '';
                    if (deleteModal) deleteModal.show();
                });

                if (filterForm) {
                    filterForm.addEventListener('submit', function (e) {
                        e.preventDefault();
                        table.ajax.reload();
                    });
                }
            }
        })();
    </script>
@endpush