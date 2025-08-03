@extends('layouts.teacher')

@section('title', 'Detail Laporan - ' . $student->name)

@push('styles')
<style>
    .report-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl) var(--spacing-md);
        animation: fadeInUp 0.6s ease;
    }
    
    .report-header {
        background: var(--glass-bg);
        backdrop-filter: blur(25px);
        border: 1px solid var(--glass-border);
        border-radius: var(--border-radius-lg);
        padding: var(--spacing-xl);
        margin-bottom: var(--spacing-xl);
        position: relative;
        overflow: hidden;
    }
    
    .report-header::before {
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
    
    .student-profile {
        display: flex;
        align-items: center;
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-lg);
    }
    
    .student-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: var(--accent-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: white;
        font-weight: 700;
        box-shadow: 0 10px 30px rgba(0, 245, 255, 0.3);
    }
    
    .student-info h1 {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: var(--spacing-xs);
    }
    
    .student-meta {
        display: flex;
        gap: var(--spacing-lg);
        color: var(--text-secondary);
        font-size: 1rem;
    }
    
    .report-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
    }
    
    .stat-card {
        background: var(--glass-bg);
        backdrop-filter: blur(25px);
        border: 1px solid var(--glass-border);
        border-radius: var(--border-radius-lg);
        padding: var(--spacing-lg);
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--accent-gradient);
    }
    
    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        background: var(--accent-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: var(--spacing-sm);
        font-family: var(--font-mono);
    }
    
    .stat-label {
        color: var(--text-secondary);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.875rem;
    }
    
    .report-actions {
        display: flex;
        gap: var(--spacing-md);
        justify-content: center;
        margin-bottom: var(--spacing-xl);
        flex-wrap: wrap;
    }
    
    .action-btn {
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        border-radius: var(--border-radius-lg);
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: var(--spacing-sm);
        min-width: 150px;
        justify-content: center;
    }
    
    .btn-primary {
        background: var(--accent-gradient);
        color: white;
    }
    
    .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: var(--text-primary);
        border: 1px solid var(--glass-border);
    }
    
    .btn-success {
        background: linear-gradient(135deg, #00ff88, #00cc6a);
        color: white;
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #ffaa00, #ff8800);
        color: white;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        text-decoration: none;
        color: inherit;
    }
    
    .reports-timeline {
        background: var(--glass-bg);
        backdrop-filter: blur(25px);
        border: 1px solid var(--glass-border);
        border-radius: var(--border-radius-lg);
        padding: var(--spacing-xl);
        position: relative;
    }
    
    .timeline-header {
        text-align: center;
        margin-bottom: var(--spacing-xl);
    }
    
    .timeline-header h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: var(--spacing-sm);
    }
    
    .timeline-item {
        display: flex;
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
        position: relative;
        padding-left: var(--spacing-xl);
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 12px;
        top: 0;
        bottom: -30px;
        width: 2px;
        background: linear-gradient(180deg, rgba(0, 245, 255, 0.8), rgba(255, 0, 255, 0.8));
    }
    
    .timeline-item:last-child::before {
        bottom: 0;
    }
    
    .timeline-dot {
        position: absolute;
        left: 0;
        top: 20px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--accent-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.75rem;
        font-weight: 700;
        box-shadow: 0 0 0 4px rgba(0, 245, 255, 0.2);
    }
    
    .timeline-content {
        flex: 1;
        background: rgba(255, 255, 255, 0.05);
        border-radius: var(--border-radius);
        padding: var(--spacing-lg);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .timeline-date {
        color: var(--text-muted);
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: var(--spacing-sm);
        font-family: var(--font-mono);
    }
    
    .timeline-activity {
        color: rgba(0, 245, 255, 0.9);
        font-weight: 600;
        margin-bottom: var(--spacing-sm);
        display: flex;
        align-items: center;
        gap: var(--spacing-sm);
    }
    
    .timeline-description {
        color: var(--text-secondary);
        line-height: 1.6;
        margin-bottom: var(--spacing-md);
    }
    
    .timeline-rating {
        display: flex;
        align-items: center;
        gap: var(--spacing-sm);
        margin-bottom: var(--spacing-sm);
    }
    
    .rating-stars {
        display: flex;
        gap: 2px;
    }
    
    .star {
        color: #ffd700;
        font-size: 1rem;
    }
    
    .star.empty {
        color: rgba(255, 255, 255, 0.2);
    }
    
    .timeline-notes {
        background: rgba(255, 255, 255, 0.05);
        border-radius: var(--border-radius);
        padding: var(--spacing-md);
        color: var(--text-muted);
        font-style: italic;
        margin-top: var(--spacing-sm);
        border-left: 4px solid rgba(0, 245, 255, 0.5);
    }
    
    .empty-reports {
        text-align: center;
        padding: var(--spacing-xl);
        color: var(--text-muted);
    }
    
    .empty-reports i {
        font-size: 4rem;
        margin-bottom: var(--spacing-lg);
        opacity: 0.5;
    }
    
    .filter-controls {
        display: flex;
        gap: var(--spacing-md);
        margin-bottom: var(--spacing-lg);
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .filter-btn {
        padding: var(--spacing-sm) var(--spacing-md);
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid var(--glass-border);
        border-radius: var(--border-radius);
        color: var(--text-secondary);
        cursor: pointer;
        transition: var(--transition);
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .filter-btn.active,
    .filter-btn:hover {
        background: var(--accent-gradient);
        color: white;
        border-color: transparent;
    }
    
    /* Export Modal Styles */
    .export-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(10px);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        animation: fadeIn 0.3s ease;
    }
    
    .export-modal.show {
        display: flex;
    }
    
    .modal-content {
        background: var(--glass-bg);
        backdrop-filter: blur(25px);
        border: 1px solid var(--glass-border);
        border-radius: var(--border-radius-lg);
        padding: var(--spacing-xl);
        max-width: 500px;
        width: 90%;
        animation: slideInUp 0.3s ease;
    }
    
    .modal-header {
        text-align: center;
        margin-bottom: var(--spacing-lg);
    }
    
    .modal-header h3 {
        color: var(--text-primary);
        margin-bottom: var(--spacing-sm);
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    .modal-header p {
        color: var(--text-secondary);
        font-size: 0.95rem;
    }
    
    .export-options {
        display: grid;
        gap: var(--spacing-md);
        margin-bottom: var(--spacing-lg);
    }
    
    .export-option {
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid var(--glass-border);
        border-radius: var(--border-radius);
        padding: var(--spacing-lg);
        cursor: pointer;
        transition: var(--transition);
        position: relative;
    }
    
    .export-option:hover {
        border-color: rgba(0, 245, 255, 0.5);
        background: rgba(0, 245, 255, 0.1);
    }
    
    .export-option.selected {
        border-color: rgba(0, 245, 255, 0.8);
        background: rgba(0, 245, 255, 0.15);
    }
    
    .option-title {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: var(--spacing-xs);
        display: flex;
        align-items: center;
        gap: var(--spacing-sm);
    }
    
    .option-desc {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-bottom: var(--spacing-md);
    }
    
    .date-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--spacing-md);
    }
    
    .date-input-group {
        display: flex;
        flex-direction: column;
    }
    
    .date-input-group label {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-bottom: var(--spacing-xs);
        font-weight: 600;
    }
    
    .date-input {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid var(--glass-border);
        border-radius: var(--border-radius);
        padding: var(--spacing-sm);
        color: var(--text-primary);
        font-size: 0.95rem;
    }
    
    .date-input:focus {
        outline: none;
        border-color: rgba(0, 245, 255, 0.6);
        background: rgba(255, 255, 255, 0.12);
    }
    
    .modal-actions {
        display: flex;
        gap: var(--spacing-md);
        justify-content: flex-end;
    }
    
    .modal-btn {
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        border-radius: var(--border-radius);
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        min-width: 100px;
    }
    
    .btn-cancel {
        background: rgba(255, 255, 255, 0.1);
        color: var(--text-secondary);
        border: 1px solid var(--glass-border);
    }
    
    .btn-export {
        background: var(--accent-gradient);
        color: white;
    }
    
    .modal-btn:hover {
        transform: translateY(-2px);
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideInUp {
        from { 
            opacity: 0; 
            transform: translateY(30px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }
    
    @media (max-width: 768px) {
        .student-profile {
            flex-direction: column;
            text-align: center;
        }
        
        .student-meta {
            flex-direction: column;
            gap: var(--spacing-sm);
        }
        
        .report-actions {
            flex-direction: column;
            align-items: center;
        }
        
        .timeline-item {
            padding-left: var(--spacing-lg);
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
    
    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
</style>
@endpush

@section('content')
<div class="report-container">
    <!-- Student Profile Header -->
    <div class="report-header">
        <div class="student-profile">
            <div class="student-avatar">
                {{ strtoupper(substr($student->name, 0, 2)) }}
            </div>
            <div class="student-info">
                <h1>{{ $student->name }}</h1>
                <div class="student-meta">
                    <span><i class="fas fa-id-card"></i> {{ $student->student_id }}</span>
                    <span><i class="fas fa-school"></i> Kelas {{ $student->class }}</span>
                    <span><i class="fas fa-envelope"></i> {{ $student->email }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="report-stats">
        <div class="stat-card">
            <div class="stat-value">{{ $reports->count() }}</div>
            <div class="stat-label">Total Laporan</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ number_format($averageRating, 1) }}</div>
            <div class="stat-label">Rata-rata Tingkat Pemahaman</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $totalActivities }}</div>
            <div class="stat-label">Aktivitas</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $reports->sum('duration') }}</div>
            <div class="stat-label">Total Menit</div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="report-actions">
        <button class="action-btn btn-success" onclick="showExportModal()">
            <i class="fas fa-file-pdf"></i>
            Export PDF
        </button>
        <button class="action-btn btn-warning" onclick="sendToParent()">
            <i class="fas fa-paper-plane"></i>
            Kirim ke Wali Murid
        </button>
        <a href="{{ route('teacher.reports.create') }}?student={{ $student->id }}" class="action-btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Laporan
        </a>
        <a href="{{ route('teacher.students.index') }}" class="action-btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    <!-- Reports Timeline -->
    <div class="reports-timeline">
        <div class="timeline-header">
            <h2><i class="fas fa-clock"></i> Riwayat Pembelajaran</h2>
            <p style="color: var(--text-secondary);">Timeline aktivitas pembelajaran {{ $student->name }}</p>
        </div>

        <!-- Filter Controls -->
        <div class="filter-controls">
            <button class="filter-btn active" data-filter="all">Semua</button>
            <button class="filter-btn" data-filter="week">Minggu Ini</button>
            <button class="filter-btn" data-filter="month">Bulan Ini</button>
            <button class="filter-btn" data-filter="rating-5">🏆 Sangat Mahir</button>
            <button class="filter-btn" data-filter="rating-4">⭐ Mahir</button>
        </div>

        @if($reports->count() > 0)
            @foreach($reports as $index => $report)
            <div class="timeline-item" data-date="{{ $report->report_date }}" data-rating="{{ $report->performance_rating }}">
                <div class="timeline-dot">{{ $index + 1 }}</div>
                <div class="timeline-content">
                    <div class="timeline-date">
                        <i class="fas fa-calendar"></i>
                        {{ \Carbon\Carbon::parse($report->report_date)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                    </div>
                    
                    <div class="timeline-activity">
                        <i class="{{ $report->activity->icon_class ?? 'fas fa-book' }}"></i>
                        {{ $report->activity->name }}
                        @if($report->duration)
                            <span style="margin-left: auto; color: var(--text-muted); font-size: 0.875rem;">
                                <i class="fas fa-clock"></i> {{ $report->duration }} menit
                            </span>
                        @endif
                    </div>
                    
                    <div class="timeline-description">
                        {{ $report->activity_description }}
                    </div>
                    
                    <div class="timeline-rating">
                        <span style="color: var(--text-secondary); font-weight: 600;">Tingkat Pemahaman:</span>
                        <div class="rating-display">
                            @php
                                $ratingTexts = [
                                    1 => 'Awal Berkembang',
                                    2 => 'Mulai Berkembang', 
                                    3 => 'Berkembang',
                                    4 => 'Mahir',
                                    5 => 'Sangat Mahir'
                                ];
                                $ratingEmojis = [
                                    1 => '🌱',
                                    2 => '🌿',
                                    3 => '🌳', 
                                    4 => '⭐',
                                    5 => '🏆'
                                ];
                                $ratingColors = [
                                    1 => '#ff6b6b',
                                    2 => '#ffa726',
                                    3 => '#66bb6a', 
                                    4 => '#42a5f5',
                                    5 => '#ab47bc'
                                ];
                            @endphp
                            <span style="display: inline-flex; align-items: center; gap: 8px; padding: 4px 12px; background: {{ $ratingColors[$report->performance_rating] }}20; border-radius: 20px; border: 1px solid {{ $ratingColors[$report->performance_rating] }}40;">
                                <span style="font-size: 1.1em;">{{ $ratingEmojis[$report->performance_rating] }}</span>
                                <span style="color: {{ $ratingColors[$report->performance_rating] }}; font-weight: 600; font-size: 0.875rem;">
                                    {{ $ratingTexts[$report->performance_rating] }}
                                </span>
                            </span>
                        </div>
                    </div>
                    
                    @if($report->notes)
                    <div class="timeline-notes">
                        <i class="fas fa-comment"></i>
                        {{ $report->notes }}
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-reports">
                <i class="fas fa-file-alt"></i>
                <h3>Belum Ada Laporan</h3>
                <p>Siswa ini belum memiliki laporan pembelajaran</p>
                <a href="{{ route('teacher.reports.create') }}?student={{ $student->id }}" class="action-btn btn-primary" style="margin-top: 20px;">
                    <i class="fas fa-plus"></i>
                    Buat Laporan Pertama
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Export Modal -->
<div id="exportModal" class="export-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-file-pdf"></i> Export Laporan PDF</h3>
            <p>Pilih jenis laporan yang ingin dicetak</p>
        </div>
        
        <div class="export-options">
            <!-- Weekly Export Option -->
            <div class="export-option" data-type="weekly">
                <div class="option-title">
                    <i class="fas fa-calendar-week"></i>
                    Laporan Mingguan
                </div>
                <div class="option-desc">
                    Cetak laporan untuk periode minggu tertentu
                </div>
                <div class="date-inputs" style="display: none;">
                    <div class="date-input-group">
                        <label>Tanggal Mulai</label>
                        <input type="date" class="date-input" id="weeklyStartDate">
                    </div>
                    <div class="date-input-group">
                        <label>Tanggal Selesai</label>
                        <input type="date" class="date-input" id="weeklyEndDate">
                    </div>
                </div>
            </div>
            
            <!-- Monthly Export Option -->
            <div class="export-option" data-type="monthly">
                <div class="option-title">
                    <i class="fas fa-calendar-alt"></i>
                    Laporan Bulanan
                </div>
                <div class="option-desc">
                    Cetak laporan untuk bulan tertentu
                </div>
                <div class="date-inputs" style="display: none;">
                    <div class="date-input-group">
                        <label>Bulan & Tahun</label>
                        <input type="month" class="date-input" id="monthlyDate">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal-actions">
            <button class="modal-btn btn-cancel" onclick="closeExportModal()">
                <i class="fas fa-times"></i> Batal
            </button>
            <button class="modal-btn btn-export" onclick="processExport()">
                <i class="fas fa-download"></i> Export PDF
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize export modal
    initializeExportModal();
    
    // Filter functionality
    const filterBtns = document.querySelectorAll('.filter-btn');
    const timelineItems = document.querySelectorAll('.timeline-item');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            const today = new Date();
            
            timelineItems.forEach(item => {
                const itemDate = new Date(item.dataset.date);
                const itemRating = parseInt(item.dataset.rating);
                let show = true;
                
                switch(filter) {
                    case 'week':
                        const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                        show = itemDate >= weekAgo;
                        break;
                    case 'month':
                        const monthAgo = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
                        show = itemDate >= monthAgo;
                        break;
                    case 'rating-5':
                        show = itemRating === 5;
                        break;
                    case 'rating-4':
                        show = itemRating === 4;
                        break;
                    case 'all':
                    default:
                        show = true;
                        break;
                }
                
                if (show) {
                    item.style.display = 'flex';
                    item.style.animation = 'fadeInUp 0.5s ease';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
    
    // Animate statistics on scroll
    const statValues = document.querySelectorAll('.stat-value');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = entry.target;
                const finalValue = target.textContent;
                const isFloat = finalValue.includes('.');
                const numericValue = parseFloat(finalValue) || 0;
                
                let current = 0;
                const increment = numericValue / 30;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= numericValue) {
                        current = numericValue;
                        clearInterval(timer);
                    }
                    
                    if (isFloat) {
                        target.textContent = current.toFixed(1);
                    } else {
                        target.textContent = Math.floor(current);
                    }
                }, 50);
                
                observer.unobserve(target);
            }
        });
    }, { threshold: 0.5 });
    
    statValues.forEach(stat => observer.observe(stat));
});

// Export Modal Functions
function initializeExportModal() {
    const exportOptions = document.querySelectorAll('.export-option');
    const today = new Date();
    
    // Set default dates
    const weeklyStartDate = document.getElementById('weeklyStartDate');
    const weeklyEndDate = document.getElementById('weeklyEndDate');
    const monthlyDate = document.getElementById('monthlyDate');
    
    // Set current week as default
    const startOfWeek = new Date(today.setDate(today.getDate() - today.getDay()));
    const endOfWeek = new Date(today.setDate(today.getDate() - today.getDay() + 6));
    
    weeklyStartDate.value = startOfWeek.toISOString().split('T')[0];
    weeklyEndDate.value = endOfWeek.toISOString().split('T')[0];
    
    // Set current month as default
    const currentMonth = new Date().toISOString().slice(0, 7);
    monthlyDate.value = currentMonth;
    
    // Handle option selection
    exportOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selection from all options
            exportOptions.forEach(opt => {
                opt.classList.remove('selected');
                opt.querySelector('.date-inputs').style.display = 'none';
            });
            
            // Select current option
            this.classList.add('selected');
            this.querySelector('.date-inputs').style.display = 'grid';
        });
    });
    
    // Handle weekly start date change
    weeklyStartDate.addEventListener('change', function() {
        const startDate = new Date(this.value);
        const endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + 6);
        weeklyEndDate.value = endDate.toISOString().split('T')[0];
    });
}

function showExportModal() {
    const modal = document.getElementById('exportModal');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeExportModal() {
    const modal = document.getElementById('exportModal');
    modal.classList.remove('show');
    document.body.style.overflow = 'auto';
    
    // Reset selections
    const exportOptions = document.querySelectorAll('.export-option');
    exportOptions.forEach(option => {
        option.classList.remove('selected');
        option.querySelector('.date-inputs').style.display = 'none';
    });
}

function processExport() {
    const selectedOption = document.querySelector('.export-option.selected');
    
    if (!selectedOption) {
        showToast('Silakan pilih jenis laporan terlebih dahulu', 'error');
        return;
    }
    
    const exportType = selectedOption.dataset.type;
    let startDate, endDate, period;
    
    if (exportType === 'weekly') {
        startDate = document.getElementById('weeklyStartDate').value;
        endDate = document.getElementById('weeklyEndDate').value;
        
        if (!startDate || !endDate) {
            showToast('Silakan isi tanggal mulai dan selesai', 'error');
            return;
        }
        
        period = `${formatDate(startDate)} - ${formatDate(endDate)}`;
    } else if (exportType === 'monthly') {
        const monthlyDateValue = document.getElementById('monthlyDate').value;
        
        if (!monthlyDateValue) {
            showToast('Silakan pilih bulan dan tahun', 'error');
            return;
        }
        
        const [year, month] = monthlyDateValue.split('-');
        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        period = `${monthNames[parseInt(month) - 1]} ${year}`;
        
        // Set start and end date for the month
        startDate = `${year}-${month}-01`;
        const lastDay = new Date(year, month, 0).getDate();
        endDate = `${year}-${month}-${lastDay.toString().padStart(2, '0')}`;
    }
    
    // Close modal and start export
    closeExportModal();
    exportPDF(exportType, startDate, endDate, period);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
}

// Export PDF function
function exportPDF(exportType = 'all', startDate = null, endDate = null, period = null) {
    const studentName = "{{ $student->name }}";
    const studentClass = "{{ $student->class }}";
    
    // Show loading toast
    showToast('Memproses PDF...', 'info');
    
    // Prepare export data
    const exportData = {
        type: exportType,
        start_date: startDate,
        end_date: endDate,
        period: period,
        student_id: {{ $student->id }}
    };
    
    // Create temporary download link
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/teacher/reports/{{ $student->id }}/export-pdf';
    form.style.display = 'none';
    
    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    form.appendChild(csrfInput);
    
    // Add export data
    Object.keys(exportData).forEach(key => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = exportData[key];
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    
    // Submit form
    form.submit();
    
    // Clean up
    setTimeout(() => {
        document.body.removeChild(form);
        showToast('PDF berhasil diunduh!', 'success');
    }, 1000);
}

// Send to parent function
function sendToParent() {
    const studentName = "{{ $student->name }}";
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    
    // Show confirm dialog
    if (!confirm(`Kirim laporan ${studentName} ke wali murid melalui email?`)) {
        return;
    }
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
    btn.disabled = true;
    
    // Simulate email sending (replace with actual implementation)
    setTimeout(() => {
        // Here you would make an AJAX call to send email
        fetch(`/teacher/reports/{{ $student->id }}/send-email`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Email berhasil dikirim ke wali murid!', 'success');
            } else {
                showToast('Gagal mengirim email', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Fitur email sedang dalam pengembangan', 'info');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }, 3000);
}

// Toast notification function
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'}-circle"></i>
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
            .toast-success { border-left: 4px solid #00ff88; }
            .toast-error { border-left: 4px solid #ff4757; }
            .toast-info { border-left: 4px solid #ffaa00; }
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
    }, 4000);
}
</script>
@endpush
@endsection
