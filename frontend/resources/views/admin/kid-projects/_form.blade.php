@php
    $editing = isset($project);
    $expiredValue = $editing && $project->expired_dt ? $project->expired_dt->format('Y-m-d') : null;
@endphp
<div class="vstack gap-3">
    <div>
        <label for="scratch_id" class="form-label">Scratch Project ID</label>
        <input type="text" name="scratch_id" id="scratch_id" class="form-control admin-input" value="{{ old('scratch_id', $project->scratch_id ?? '') }}" placeholder="1188610598" required>
    </div>
    <div>
        <label for="title" class="form-label">Project Title</label>
        <input type="text" name="title" id="title" class="form-control admin-input" value="{{ old('title', $project->title ?? '') }}" placeholder="Kelompok Kancil" required>
    </div>
    <div>
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" rows="3" class="form-control admin-input" placeholder="Short summary">{{ old('description', $project->description ?? '') }}</textarea>
    </div>
    <div>
        <label for="instructions" class="form-label">Instructions</label>
        <textarea name="instructions" id="instructions" rows="4" class="form-control admin-input" placeholder="Optional classroom notes">{{ old('instructions', $project->instructions ?? '') }}</textarea>
    </div>
    <div>
        <label for="expired_dt" class="form-label">Expired Date</label>
        <input type="date" name="expired_dt" id="expired_dt" class="form-control admin-input" value="{{ old('expired_dt', $expiredValue) }}">
        <small class="text-muted">Leave empty if the project should stay visible indefinitely.</small>
    </div>
</div>
<div class="d-flex flex-wrap gap-3 pt-3">
    <button type="submit" class="auth-btn" style="max-width: 220px;">{{ $editing ? 'Update Project' : 'Create Project' }}</button>
    <a href="{{ route('admin.kid-projects.index') }}" class="admin-btn admin-btn--outline">Cancel</a>
</div>
