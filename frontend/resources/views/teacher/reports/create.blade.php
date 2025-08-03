@extends('layouts.teacher')

@section('title', 'Buat Laporan Baru')

@push('styles')
<style>
    .form-container {
        max-width: 900px;
        margin: 0 auto;
        padding: var(--spacing-xl) var(--spacing-md);
        animation: fadeInUp 0.6s ease;
    }
    
    .form-card {
        background: var(--glass-bg);
        backdrop-filter: blur(25px);
        border: 1px solid var(--glass-border);
        border-radius: var(--border-radius-lg);
        padding: var(--spacing-xl);
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow);
    }
    
    .form-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: var(--accent-gradient);
        background-size: 300% 100%;
        animation: gradientShift 3s ease-in-out infinite;
    }
    
    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    .form-header {
        text-align: center;
        margin-bottom: var(--spacing-xl);
    }
    
    .form-header h1 {
        font-size: clamp(2rem, 4vw, 2.5rem);
        font-weight: 800;
        margin-bottom: var(--spacing-sm);
        background: var(--accent-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.02em;
    }
    
    .form-header p {
        color: var(--text-secondary);
        font-size: 1.125rem;
        font-weight: 400;
    }
    
    .form-grid {
        display: grid;
        gap: var(--spacing-lg);
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        animation: fadeInUp 0.5s ease;
        animation-fill-mode: both;
    }
    
    .form-group:nth-child(1) { animation-delay: 0.1s; }
    .form-group:nth-child(2) { animation-delay: 0.2s; }
    .form-group:nth-child(3) { animation-delay: 0.3s; }
    .form-group:nth-child(4) { animation-delay: 0.4s; }
    .form-group:nth-child(5) { animation-delay: 0.5s; }
    .form-group:nth-child(6) { animation-delay: 0.6s; }
    
    .form-label {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: var(--spacing-sm);
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: var(--spacing-xs);
    }
    
    .form-input, .form-select, .form-textarea {
        background: rgba(255, 255, 255, 0.08);
        border: 2px solid rgba(255, 255, 255, 0.15);
        border-radius: var(--border-radius);
        padding: var(--spacing-md);
        color: var(--text-primary);
        font-size: 1rem;
        transition: var(--transition);
        backdrop-filter: blur(10px);
        font-family: inherit;
    }
    
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: rgba(0, 245, 255, 0.6);
        background: rgba(255, 255, 255, 0.12);
        box-shadow: 0 0 0 3px rgba(0, 245, 255, 0.1);
        transform: translateY(-2px);
    }
    
    .form-input::placeholder, .form-textarea::placeholder {
        color: var(--text-muted);
        font-weight: 400;
    }
    
    .form-select option {
        background: rgba(102, 126, 234, 0.95);
        color: white;
        padding: var(--spacing-sm);
    }
    
    .rating-selector {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: var(--spacing-md);
        margin-top: var(--spacing-sm);
    }
    
    .rating-option {
        position: relative;
    }
    
    .rating-input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    
    .rating-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: var(--spacing-lg);
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid rgba(255, 255, 255, 0.15);
        border-radius: var(--border-radius);
        cursor: pointer;
        transition: var(--transition);
        text-align: center;
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
    }
    
    .rating-label::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left 0.6s;
    }
    
    .rating-label:hover::before {
        left: 100%;
    }
    
    .rating-emoji {
        font-size: 2rem;
        margin-bottom: var(--spacing-sm);
        transition: var(--transition);
    }
    
    .rating-text {
        color: var(--text-secondary);
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: var(--spacing-xs);
    }
    
    .rating-desc {
        color: var(--text-muted);
        font-size: 0.75rem;
        font-weight: 400;
        line-height: 1.3;
        text-transform: none;
        letter-spacing: normal;
        text-align: center;
        opacity: 0.8;
    }
    
    .rating-input:checked + .rating-label {
        border-color: rgba(0, 245, 255, 0.6);
        background: rgba(0, 245, 255, 0.1);
        transform: scale(1.05);
        box-shadow: 0 10px 25px rgba(0, 245, 255, 0.2);
    }
    
    .rating-input:checked + .rating-label .rating-emoji {
        transform: scale(1.2);
        filter: drop-shadow(0 0 10px rgba(0, 245, 255, 0.5));
    }
    
    .rating-input:checked + .rating-label .rating-text {
        color: var(--text-primary);
    }
    
    .rating-input:checked + .rating-label .rating-desc {
        color: var(--text-secondary);
        opacity: 1;
    }
    
    .rating-label:hover {
        border-color: rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.08);
        transform: translateY(-3px);
    }
    
    .activity-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--spacing-md);
        margin-top: var(--spacing-sm);
    }
    
    .activity-option {
        position: relative;
    }
    
    .activity-input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    
    .activity-label {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
        padding: var(--spacing-lg);
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid rgba(255, 255, 255, 0.15);
        border-radius: var(--border-radius);
        cursor: pointer;
        transition: var(--transition);
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
    }
    
    .activity-label::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 0;
        height: 100%;
        background: var(--accent-gradient);
        transition: width 0.3s ease;
        opacity: 0.1;
    }
    
    .activity-icon {
        width: 50px;
        height: 50px;
        background: var(--accent-gradient);
        border-radius: var(--border-radius);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        flex-shrink: 0;
        transition: var(--transition);
    }
    
    .activity-info h4 {
        color: var(--text-primary);
        margin: 0 0 var(--spacing-xs) 0;
        font-size: 1rem;
        font-weight: 600;
    }
    
    .activity-info p {
        color: var(--text-muted);
        margin: 0;
        font-size: 0.875rem;
        line-height: 1.4;
    }
    
    .activity-input:checked + .activity-label {
        border-color: rgba(0, 245, 255, 0.6);
        background: rgba(0, 245, 255, 0.1);
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 245, 255, 0.15);
    }
    
    .activity-input:checked + .activity-label::after {
        width: 4px;
    }
    
    .activity-input:checked + .activity-label .activity-icon {
        transform: scale(1.1);
        box-shadow: 0 0 20px rgba(0, 245, 255, 0.4);
    }
    
    .activity-label:hover {
        border-color: rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.08);
        transform: translateY(-2px);
    }
    
    .form-actions {
        display: flex;
        gap: var(--spacing-md);
        justify-content: center;
        margin-top: var(--spacing-xl);
        flex-wrap: wrap;
        animation: fadeInUp 0.6s ease 0.7s both;
    }
    
    .progress-indicator {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: rgba(255, 255, 255, 0.1);
        z-index: 1001;
    }
    
    .progress-bar {
        height: 100%;
        background: var(--accent-gradient);
        width: 0%;
        transition: width 0.3s ease;
    }
    
    @media (max-width: 768px) {
        .form-card {
            padding: var(--spacing-lg);
            margin: var(--spacing-md);
        }
        
        .rating-selector {
            grid-template-columns: 1fr;
        }
        
        .activity-grid {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
            align-items: center;
        }
        
        .activity-label {
            padding: var(--spacing-md);
        }
        
        .rating-label {
            padding: var(--spacing-md);
        }
    }
    
    @keyframes fadeInUp {
        from { 
            opacity: 0; 
            transform: translateY(30px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }
</style>
@endpush

@section('content')
<div class="progress-indicator">
    <div class="progress-bar" id="progressBar"></div>
</div>

<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h1><i class="fas fa-plus-circle" style="margin-right: 15px;"></i>Laporan Belajar Harian</h1>
            <p>Buat laporan pembelajaran untuk memantau progress siswa secara real-time</p>
        </div>

        <form id="reportForm" action="{{ route('teacher.reports.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <!-- Student Selection -->
                <div class="form-group">
                    <label for="student_id" class="form-label">
                        <i class="fas fa-user-graduate"></i>
                        Pilih Siswa
                    </label>
                    <select name="student_id" id="student_id" class="form-select" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->class }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Selection -->
                <div class="form-group">
                    <label for="date" class="form-label">
                        <i class="fas fa-calendar-alt"></i>
                        Tanggal Pembelajaran
                    </label>
                    <input type="date" name="report_date" id="date" class="form-input" 
                           value="{{ date('Y-m-d') }}" required>
                </div>

                <!-- Activity Selection -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-tasks"></i>
                        Aktivitas Pembelajaran
                    </label>
                    <div class="activity-grid">
                        @foreach($activities as $activity)
                            <div class="activity-option">
                                <input type="radio" name="activity_id" id="activity_{{ $activity->id }}" 
                                       value="{{ $activity->id }}" class="activity-input" required>
                                <label for="activity_{{ $activity->id }}" class="activity-label">
                                    <div class="activity-icon">
                                        <i class="{{ $activity->icon_class ?? 'fas fa-book' }}"></i>
                                    </div>
                                    <div class="activity-info">
                                        <h4>{{ $activity->name }}</h4>
                                        <p>{{ $activity->description }}</p>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Activity Description -->
                <div class="form-group">
                    <label for="activity_description" class="form-label">
                        <i class="fas fa-edit"></i>
                        Deskripsi Pembelajaran
                    </label>
                    <textarea name="activity_description" id="activity_description" rows="4" class="form-textarea" 
                              placeholder="Jelaskan detail aktivitas pembelajaran, materi yang diajarkan, dan metode yang digunakan..." required></textarea>
                </div>

                <!-- Rating Selection -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-chart-line"></i>
                        Tingkat Pemahaman Siswa
                    </label>
                    <div class="rating-selector">
                        <div class="rating-option">
                            <input type="radio" name="performance_rating" id="rating_1" value="1" class="rating-input" required>
                            <label for="rating_1" class="rating-label">
                                <div class="rating-emoji">🌱</div>
                                <div class="rating-text">Awal Berkembang</div>
                                <div class="rating-desc">Mulai memahami beberapa hal dasar dalam proses belajar</div>
                            </label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="performance_rating" id="rating_2" value="2" class="rating-input">
                            <label for="rating_2" class="rating-label">
                                <div class="rating-emoji">🌿</div>
                                <div class="rating-text">Mulai Berkembang</div>
                                <div class="rating-desc">Memahami beberapa aspek pada proses belajar</div>
                            </label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="performance_rating" id="rating_3" value="3" class="rating-input">
                            <label for="rating_3" class="rating-label">
                                <div class="rating-emoji">🌳</div>
                                <div class="rating-text">Berkembang</div>
                                <div class="rating-desc">Memahami proses belajar dan menerapkan keterampilan</div>
                            </label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="performance_rating" id="rating_4" value="4" class="rating-input">
                            <label for="rating_4" class="rating-label">
                                <div class="rating-emoji">�</div>
                                <div class="rating-text">Sangat Baik</div>
                            </label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="performance_rating" id="rating_5" value="5" class="rating-input">
                            <label for="rating_5" class="rating-label">
                                <div class="rating-emoji">🏆</div>
                                <div class="rating-text">Sangat Mahir</div>
                                <div class="rating-desc">Pemahaman menyeluruh dalam segala situasi</div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="form-group">
                    <label for="notes" class="form-label">
                        <i class="fas fa-comment-alt"></i>
                        Catatan Tambahan (Opsional)
                    </label>
                    <textarea name="notes" id="notes" rows="3" class="form-textarea" 
                              placeholder="Tambahkan catatan khusus, kesulitan yang dialami siswa, atau rekomendasi untuk pembelajaran selanjutnya..."></textarea>
                </div>

                <!-- Duration -->
                <div class="form-group">
                    <label for="duration" class="form-label">
                        <i class="fas fa-clock"></i>
                        Durasi Pembelajaran (menit)
                    </label>
                    <input type="number" name="duration" id="duration" class="form-input" 
                           min="5" max="480" value="60" placeholder="60" required>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Simpan Laporan
                </button>
                <a href="{{ route('teacher.reports.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Dashboard
                </a>
            </div>
        </form>
    </div>
</div>



@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form progress tracking
    const form = document.getElementById('reportForm');
    const progressBar = document.getElementById('progressBar');
    const formInputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    const radioGroups = ['activity_id', 'performance_rating'];
    
    // Initialize progress
    updateProgress();
    
    // Add event listeners to all form inputs
    formInputs.forEach(input => {
        input.addEventListener('change', updateProgress);
        input.addEventListener('input', updateProgress);
    });
    
    // Add event listeners to radio groups
    radioGroups.forEach(name => {
        const radios = form.querySelectorAll(`input[name="${name}"]`);
        radios.forEach(radio => {
            radio.addEventListener('change', updateProgress);
        });
    });
    
    function updateProgress() {
        let completedFields = 0;
        let totalFields = 0;
        
        // Check regular required fields
        formInputs.forEach(input => {
            if (input.type !== 'radio') {
                totalFields++;
                if (input.value.trim() !== '') {
                    completedFields++;
                }
            }
        });
        
        // Check radio groups
        radioGroups.forEach(name => {
            totalFields++;
            const checked = form.querySelector(`input[name="${name}"]:checked`);
            if (checked) {
                completedFields++;
            }
        });
        
        const progress = (completedFields / totalFields) * 100;
        progressBar.style.width = progress + '%';
        
        // Add glow effect when near completion
        if (progress > 80) {
            progressBar.style.boxShadow = '0 0 20px rgba(0, 245, 255, 0.6)';
        } else {
            progressBar.style.boxShadow = 'none';
        }
    }
    
    // Auto-save to localStorage
    function saveFormData() {
        const formData = new FormData(form);
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        
        localStorage.setItem('teacher_report_draft', JSON.stringify(data));
    }
    
    // Load saved data
    function loadFormData() {
        const saved = localStorage.getItem('teacher_report_draft');
        if (saved) {
            try {
                const data = JSON.parse(saved);
                
                Object.keys(data).forEach(key => {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input) {
                        if (input.type === 'radio') {
                            const radio = form.querySelector(`[name="${key}"][value="${data[key]}"]`);
                            if (radio) radio.checked = true;
                        } else {
                            input.value = data[key];
                        }
                    }
                });
                
                updateProgress();
                
                // Show restore notification
                showToast('Data tersimpan dipulihkan', 'info');
            } catch (e) {
                console.error('Error loading saved data:', e);
            }
        }
    }
    
    // Save data on input
    let saveTimeout;
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(saveFormData, 1000);
        });
    });
    
    // Load saved data on page load
    loadFormData();
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        submitBtn.disabled = true;
        
        // Simulate save process
        setTimeout(() => {
            // Clear saved draft
            localStorage.removeItem('teacher_report_draft');
            
            // Submit form
            form.submit();
        }, 1000);
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+S to save (prevent default and focus on submit)
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            document.querySelector('button[type="submit"]').focus();
        }
        
        // Escape to go back
        if (e.key === 'Escape') {
            const backBtn = document.querySelector('.btn-secondary');
            if (backBtn && confirm('Yakin ingin kembali? Data yang belum disimpan akan hilang.')) {
                window.location.href = backBtn.href;
            }
        }
    });
    
    // Dynamic rating feedback
    const ratingInputs = document.querySelectorAll('input[name="performance_rating"]');
    ratingInputs.forEach(input => {
        input.addEventListener('change', function() {
            const notesTextarea = document.getElementById('notes');
            const rating = parseInt(this.value);
            
            // Add suggested notes based on rating
            let suggestion = '';
            switch(rating) {
                case 1:
                    suggestion = 'Siswa mulai memahami beberapa hal dasar dalam proses belajar dan mulai menggunakan beberapa keterampilan dalam situasi tertentu. ';
                    break;
                case 2:
                    suggestion = 'Siswa memahami beberapa aspek pada proses belajar dan mulai menggunakan beberapa keterampilan dalam situasi tertentu. ';
                    break;
                case 3:
                    suggestion = 'Siswa memahami proses belajar dan menerapkan keterampilan dalam situasi tertentu. ';
                    break;
                case 4:
                    suggestion = 'Siswa menunjukkan pemahaman eksplisit dalam proses belajar dengan kemampuan tingkat tinggi pada situasi tertentu. ';
                    break;
                case 5:
                    suggestion = 'Siswa menunjukkan pemahaman menyeluruh dalam proses belajar dengan kemampuan tingkat tinggi dalam segala situasi. ';
                    break;
            }
            
            if (notesTextarea.value.trim() === '') {
                notesTextarea.value = suggestion;
                notesTextarea.focus();
            }
        });
    });
    
    // Activity selection feedback
    const activityInputs = document.querySelectorAll('input[name="activity_id"]');
    activityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const description = document.getElementById('activity_description');
            const activityName = this.closest('.activity-label').querySelector('h4').textContent;
            
            if (description.value.trim() === '') {
                description.value = `Pembelajaran ${activityName} - `;
                description.focus();
                // Move cursor to end
                description.setSelectionRange(description.value.length, description.value.length);
            }
        });
    });
    
    // Toast notification function
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas fa-${type === 'success' ? 'check' : 'info'}-circle"></i>
                <span>${message}</span>
            </div>
        `;
        
        // Add toast styles if not exist
        if (!document.querySelector('#toast-styles')) {
            const toastStyles = document.createElement('style');
            toastStyles.id = 'toast-styles';
            toastStyles.textContent = `
                .toast {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: var(--glass-bg);
                    backdrop-filter: blur(25px);
                    border: 1px solid var(--glass-border);
                    border-radius: var(--border-radius);
                    padding: var(--spacing-md);
                    color: var(--text-primary);
                    box-shadow: var(--shadow);
                    z-index: 1002;
                    animation: slideInRight 0.3s ease;
                    max-width: 300px;
                }
                .toast-content {
                    display: flex;
                    align-items: center;
                    gap: var(--spacing-sm);
                }
                .toast-success { border-left: 4px solid #00f5ff; }
                .toast-info { border-left: 4px solid #ffff00; }
                @keyframes slideInRight {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
            `;
            document.head.appendChild(toastStyles);
        }
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideInRight 0.3s ease reverse';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    // Form validation feedback
    function validateForm() {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.style.borderColor = '#ff4757';
                isValid = false;
            } else {
                field.style.borderColor = '';
            }
        });
        
        // Check radio groups
        radioGroups.forEach(name => {
            const checked = form.querySelector(`input[name="${name}"]:checked`);
            if (!checked) {
                isValid = false;
                const radioContainer = form.querySelector(`input[name="${name}"]`).closest('.form-group');
                radioContainer.style.borderColor = '#ff4757';
            }
        });
        
        return isValid;
    }
    
    // Real-time validation
    formInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.style.borderColor = '#ff4757';
                this.style.boxShadow = '0 0 0 3px rgba(255, 71, 87, 0.1)';
            } else {
                this.style.borderColor = '';
                this.style.boxShadow = '';
            }
        });
        
        input.addEventListener('focus', function() {
            this.style.borderColor = '';
            this.style.boxShadow = '';
        });
    });
});
</script>
@endpush
