@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <div class="admin-card" style="max-width:720px;margin:0 auto;">
        <div class="admin-card__header">
            <h4 class="mb-1">Pilih Sekolah</h4>
            <p class="mb-0">Pilih sekolah yang ingin Anda kelola. Anda bisa switch kapan saja dari header.</p>
        </div>
        <div class="admin-card__body">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @php
                $schools = $availableSchools ?? collect();
            @endphp

            @if($schools->count() === 0)
                <div class="alert alert-warning mb-0">Tidak ada sekolah yang tersedia untuk akun ini.</div>
            @else
                <form method="POST" action="{{ route('admin.school.switch') }}" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="form-label">Sekolah</label>
                        <select name="school_id" class="form-select" required>
                            <option value="">-- Pilih Sekolah --</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ (int) old('school_id', session('admin_school_id') ?? 0) === (int) $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="admin-btn admin-btn--solid">Lanjut</button>
                        <a href="{{ route('admin.logout') }}" class="admin-btn admin-btn--outline">Logout</a>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
