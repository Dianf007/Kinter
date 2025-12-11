@extends('layouts.admin.app')
@section('title', 'Edit Kid Project')
@section('admin-navbar')
    <a href="{{ route('admin.kid-projects.index') }}" class="admin-btn admin-btn--outline">Kembali ke daftar</a>
    <a href="{{ route('admin.logout') }}" class="admin-btn admin-btn--solid">Logout</a>
@endsection
@section('content')
<div class="dashboard-wrapper">
    <div class="admin-card">
        <div class="admin-card__header">
            <h4 class="mb-1">Edit Project Scratch</h4>
            <p class="mb-0">Update informasi project untuk menjaga data siswa tetap akurat.</p>
        </div>
        <div class="admin-card__body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('admin.kid-projects.update', $project) }}" class="vstack gap-3">
                @csrf
                @method('PUT')
                @include('admin.kid-projects._form', ['project' => $project])
            </form>
        </div>
    </div>
</div>
@endsection
