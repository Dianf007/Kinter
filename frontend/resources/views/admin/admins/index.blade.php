@extends('layouts.admin.app')
@section('title', 'Kelola Admin')

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
        .table {
            color: var(--admin-text);
        }
        .table th {
            color: var(--admin-text);
            background: var(--admin-bg);
        }
        .text-muted {
            color: var(--admin-text-muted) !important;
        }
        /* Table hover state yang support dark/light mode */
        .table tbody tr:hover {
            background: var(--admin-bg) !important;
            color: var(--admin-text) !important;
        }
        .table tbody tr:hover td {
            color: var(--admin-text) !important;
        }
        .table tbody tr:hover a,
        .table tbody tr:hover button {
            color: inherit;
        }
    </style>
@endpush

@section('content')
<div class="dashboard-wrapper">
    <div class="admin-card">
        <div class="admin-card__header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="mb-1">Kelola Admin</h4>
                <p class="mb-0">Buat, ubah, dan assign sekolah sesuai role.</p>
            </div>
            <a href="{{ route('admin.admins.create') }}" class="admin-btn admin-btn--solid">+ Admin Baru</a>
        </div>
        <div class="admin-card__body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="GET" class="filter-bar" id="adminFilters">
                <input type="text" name="q" id="filterQ" value="{{ request('q') }}" placeholder="Cari username / role">
                <select name="role" id="filterRole">
                    <option value="">-- Semua Role --</option>
                    @foreach(($roleFilterOptions ?? []) as $opt)
                        <option value="{{ $opt }}" {{ request('role') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
                <select name="school_id" id="filterSchool">
                    <option value="">-- Semua Sekolah --</option>
                    @foreach(($schools ?? []) as $school)
                        <option value="{{ $school->id }}" {{ (string)request('school_id') === (string)$school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="admin-btn admin-btn--solid" style="padding: 10px 20px;">Terapkan</button>
                <a href="{{ route('admin.admins.index') }}" class="admin-btn admin-btn--outline">Reset</a>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="adminsTable">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Sekolah</th>
                            <th>Dibuat</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($admins as $admin)
                            @php
                                $role = $admin->role ?? 'admin';
                                $schoolText = '-';
                                if ($role === 'admin') {
                                    $schoolText = $admin->school->name ?? '-';
                                } elseif ($role === 'superadmin') {
                                    $schoolText = $admin->managedSchools->pluck('name')->implode(', ');
                                    $schoolText = $schoolText ?: '-';
                                }
                            @endphp
                            <tr>
                                <td><strong>{{ $admin->username }}</strong></td>
                                <td>{{ $role }}</td>
                                <td>{{ $schoolText }}</td>
                                <td>{{ $admin->created_at ? $admin->created_at->format('Y-m-d H:i') : '-' }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('admin.admins.edit', $admin) }}" class="admin-btn admin-btn--solid btn-sm">Edit</a>
                                        <button type="button"
                                            class="admin-btn admin-btn--outline btn-sm js-delete-admin"
                                            data-username="{{ $admin->username }}"
                                            data-action="{{ route('admin.admins.destroy', $admin) }}"
                                            style="color:#b83d3d;border-color:rgba(184,61,61,0.4);">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada data admin.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($admins, 'links'))
                <div class="pt-3" id="ssrPagination">
                    {{ $admins->links() }}
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
                <p class="mb-2">Kamu yakin ingin menghapus admin <strong id="deleteAdminName">-</strong>?</p>
                <div class="small text-muted">Aksi ini tidak bisa dibatalkan.</div>
            </div>
            <div class="modal-footer" style="border-top:1px solid rgba(28,31,46,0.08);">
                <button type="button" class="admin-btn admin-btn--outline" data-bs-dismiss="modal">Batal</button>
                <form method="POST" id="deleteAdminForm" style="margin:0;">
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
            var deleteNameEl = document.getElementById('deleteAdminName');
            var deleteFormEl = document.getElementById('deleteAdminForm');
            var deleteModal = deleteModalEl ? new bootstrap.Modal(deleteModalEl) : null;

            var filterForm = document.getElementById('adminFilters');
            var filterQ = document.getElementById('filterQ');
            var filterRole = document.getElementById('filterRole');
            var filterSchool = document.getElementById('filterSchool');

            function currentFilterParams() {
                return {
                    q: filterQ ? filterQ.value : '',
                    role: filterRole ? filterRole.value : '',
                    school_id: filterSchool ? filterSchool.value : ''
                };
            }

            // If DataTables CDN is blocked/offline, keep SSR table visible.
            if (typeof DataTable !== 'undefined') {
                // prevent SSR pagination duplication when DataTables is active
                var ssrPagination = document.getElementById('ssrPagination');
                if (ssrPagination) ssrPagination.style.display = 'none';

                var table = new DataTable('#adminsTable', {
                    serverSide: true,
                    processing: true,
                    pageLength: 10,
                    ajax: {
                        url: @json(route('admin.admins.data')),
                        type: 'GET',
                        data: function (d) {
                            var p = currentFilterParams();
                            d.q = p.q;
                            d.role = p.role;
                            d.school_id = p.school_id;
                        }
                    },
                    searching: false,
                    columns: [
                        {
                            data: 'username',
                            render: function (data) {
                                return '<strong>' + escapeHtml(data) + '</strong>';
                            }
                        },
                        { data: 'role' },
                        { data: 'school' },
                        { data: 'created_at' },
                        {
                            data: 'id',
                            orderable: false,
                            searchable: false,
                            render: function (_id, _type, row) {
                                var editUrl = row.edit_url;
                                var destroyUrl = row.destroy_url;
                                var username = row.username;

                                return (
                                    '<div class="d-flex flex-wrap gap-2">' +
                                        '<a href="' + editUrl + '" class="admin-btn admin-btn--solid btn-sm">Edit</a>' +
                                        '<button type="button" class="admin-btn admin-btn--outline btn-sm js-delete-admin" ' +
                                            'data-username="' + escapeHtml(username) + '" ' +
                                            'data-action="' + destroyUrl + '" ' +
                                            'style="color:#b83d3d;border-color:rgba(184,61,61,0.4);">Hapus</button>' +
                                    '</div>'
                                );
                            }
                        }
                    ]
                });

                if (filterForm) {
                    filterForm.addEventListener('submit', function (e) {
                        e.preventDefault();
                        table.ajax.reload();
                    });
                }
            }

            document.addEventListener('click', function (e) {
                var btn = e.target.closest('.js-delete-admin');
                if (!btn) return;

                var username = btn.getAttribute('data-username') || '-';
                var action = btn.getAttribute('data-action');
                if (!action || !deleteModal || !deleteNameEl || !deleteFormEl) return;

                deleteNameEl.textContent = username;
                deleteFormEl.setAttribute('action', action);
                deleteModal.show();
            });
        })();
    </script>
@endpush
