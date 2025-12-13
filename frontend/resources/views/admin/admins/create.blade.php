@extends('layouts.admin.app')
@section('title', 'Tambah Admin')

@section('content')
<div class="dashboard-wrapper">
    <div class="admin-card">
        <div class="admin-card__header">
            <h4 class="mb-1">Tambah Admin</h4>
            <p class="mb-0">Buat akun admin / superadmin dan assign sekolah.</p>
        </div>
        <div class="admin-card__body">
            <form method="POST" action="{{ route('admin.admins.store') }}">
                @csrf
                @include('admin.admins._form', [
                    'roleOptions' => $roleOptions,
                    'schools' => $schools,
                    'roleLocked' => false,
                ])
            </form>
        </div>
    </div>
</div>
@endsection
