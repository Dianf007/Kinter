@extends('layouts.admin.app')
@section('title', 'Edit Siswa')
@push('styles')
<style>
    .form-label { color: var(--admin-text); font-weight: 500; }
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
</style>
@endpush
@section('content')
<div class="dashboard-wrapper">
    <div class="admin-card">
        <div class="admin-card__header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="mb-1" style="color: var(--admin-text);">Edit Siswa</h4>
                <p class="mb-0" style="color: var(--admin-text-muted);">Perbarui data siswa.</p>
            </div>
            <a href="{{ route('admin.students.index') }}" class="admin-btn admin-btn--outline">Kembali</a>
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
            <form action="{{ route('admin.students.update', $student) }}" method="POST">
                @method('PUT')
                @include('admin.students._form')
                <div class="mt-4 d-flex flex-wrap gap-2">
                    <button type="submit" class="admin-btn admin-btn--solid">Update Siswa</button>
                    <a href="{{ route('admin.students.index') }}" class="admin-btn admin-btn--outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
