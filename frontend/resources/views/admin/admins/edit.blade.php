@extends('layouts.admin.app')
@section('title', 'Edit Admin')

@section('content')
<div class="dashboard-wrapper">
    <div class="admin-card">
        <div class="admin-card__header">
            <h4 class="mb-1">Edit Admin</h4>
            <p class="mb-0">Update username, password, role, dan assignment sekolah.</p>
        </div>
        <div class="admin-card__body">
            <form method="POST" action="{{ route('admin.admins.update', $admin) }}">
                @csrf
                @method('PUT')
                @include('admin.admins._form', [
                    'admin' => $admin,
                    'roleOptions' => $roleOptions,
                    'schools' => $schools,
                    'roleLocked' => $roleLocked,
                ])
            </form>
        </div>
    </div>
</div>
@endsection
