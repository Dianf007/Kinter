@extends('layouts.teacher')

@section('title', 'Dashboard Laporan Guru')

@push('styles')
<style>
    .dashboard-container {
        padding: var(--spacing-xl) var(--spacing-md);
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .header-section {
        text-align: center;
        margin-bottom: var(--spacing-xl);
        animation: fadeInUp 0.6s ease;
    }
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .header-section h1 {
        font-size: clamp(2rem, 5vw, 3rem);
        font-weight: 800;
        margin-bottom: var(--spacing-sm);
        background: var(--accent-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.02em;
    }
    
    .header-section p {
        font-size: 1.125rem;
        color: var(--text-secondary);
        max-width: 600px;
        margin: 0 auto;
        font-weight: 400;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
    }
    
    .stat-card {
        background: var(--glass-bg);
        backdrop-filter: blur(25px);
        border: 1px solid var(--glass-border);
        border-radius: var(--border-radius-lg);
        padding: var(--spacing-xl);
        text-align: center;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease;
        animation-fill-mode: both;
    }
    
    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--accent-gradient);
        transform: scaleX(0);
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .stat-card:hover::before {
        transform: scaleX(1);
    }
    
    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        background: rgba(255, 255, 255, 0.15);
        box-shadow: var(--shadow-hover);
    }
    
    .stat-icon {
        width: 80px;
        height: 80px;
        background: var(--accent-gradient);
        border-radius: var(--border-radius);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--spacing-md);
        font-size: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .stat-icon::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.6s;
    }
    
    .stat-card:hover .stat-icon::after {
        left: 100%;
    }
    
    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: var(--spacing-xs);
        font-family: 'JetBrains Mono', monospace;
        letter-spacing: -0.02em;
    }
    
    .stat-label {
        color: var(--text-secondary);
        font-size: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .main-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: var(--spacing-xl);
        margin-bottom: var(--spacing-xl);
        align-items: start;
    }
    
    .reports-section, .students-section {
        background: var(--glass-bg);
        backdrop-filter: blur(25px);
        border: 1px solid var(--glass-border);
        border-radius: var(--border-radius-lg);
        padding: var(--spacing-xl);
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease 0.4s both;
    }
    
    .students-section {
        animation-delay: 0.5s;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--spacing-lg);
        padding-bottom: var(--spacing-md);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .section-title {
        color: var(--text-primary);
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: var(--spacing-sm);
    }
    
    .report-item {
        background: rgba(255, 255, 255, 0.05);
        border-radius: var(--border-radius);
        padding: var(--spacing-md);
        margin-bottom: var(--spacing-md);
        border-left: 4px solid transparent;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }
    
    .report-item::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.05), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s;
    }
    
    .report-item:hover::after {
        transform: translateX(100%);
    }
    
    .report-item:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateX(8px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .report-rating-1 { border-left-color: #ef4444; }
    .report-rating-2 { border-left-color: #f59e0b; }
    .report-rating-3 { border-left-color: #10b981; }
    .report-rating-4 { border-left-color: #3b82f6; }
    .report-rating-5 { border-left-color: #8b5cf6; }
    
    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--spacing-sm);
    }
    
    .report-student {
        color: var(--text-primary);
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .report-activity {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin-bottom: var(--spacing-xs);
        display: flex;
        align-items: center;
        gap: var(--spacing-xs);
    }
    
    .report-description {
        color: var(--text-muted);
        font-size: 0.875rem;
        line-height: 1.5;
    }
    
    .rating-badge {
        display: inline-flex;
        align-items: center;
        gap: var(--spacing-xs);
        padding: var(--spacing-xs) var(--spacing-sm);
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }
    
    .rating-1 { background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3); }
    .rating-2 { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
    .rating-3 { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
    .rating-4 { background: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
    .rating-5 { background: rgba(139, 92, 246, 0.2); color: #a78bfa; border: 1px solid rgba(139, 92, 246, 0.3); }
    
    .student-item {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
        padding: var(--spacing-md);
        background: rgba(255, 255, 255, 0.05);
        border-radius: var(--border-radius);
        margin-bottom: var(--spacing-md);
        transition: var(--transition);
        border: 1px solid transparent;
    }
    
    .student-item:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
        border-color: rgba(255, 255, 255, 0.1);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .student-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.2);
        transition: var(--transition);
    }
    
    .student-item:hover .student-avatar {
        border-color: rgba(0, 245, 255, 0.5);
        transform: scale(1.05);
    }
    
    .student-info h4 {
        color: var(--text-primary);
        margin: 0 0 var(--spacing-xs) 0;
        font-size: 1rem;
        font-weight: 600;
    }
    
    .student-info p {
        color: var(--text-muted);
        margin: 0;
        font-size: 0.875rem;
    }
    
    .action-buttons {
        display: flex;
        gap: var(--spacing-md);
        justify-content: center;
        margin-top: var(--spacing-xl);
        flex-wrap: wrap;
    }
    
    .empty-state {
        text-align: center;
        color: var(--text-secondary);
        padding: var(--spacing-xl);
        animation: fadeIn 0.6s ease;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: var(--spacing-lg);
        opacity: 0.5;
        background: var(--accent-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .empty-state h3 {
        font-size: 1.5rem;
        margin-bottom: var(--spacing-sm);
        color: var(--text-primary);
    }
    
    .empty-state p {
        font-size: 1rem;
        opacity: 0.8;
        max-width: 400px;
        margin: 0 auto;
        line-height: 1.6;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @media (max-width: 768px) {
        .main-content {
            grid-template-columns: 1fr;
            gap: var(--spacing-lg);
        }
        
        .dashboard-container {
            padding: var(--spacing-lg) var(--spacing-sm);
        }
        
        .action-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .section-header {
            flex-direction: column;
            gap: var(--spacing-sm);
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <div class="header-section">
        <h1>📚 Dashboard Laporan Guru</h1>
        <p>Kelola laporan pembelajaran harian siswa dengan mudah dan efektif</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="stat-value">{{ $weeklyStats['total_reports'] ?? 0 }}</div>
            <div class="stat-label">Laporan Minggu Ini</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-value">{{ number_format($weeklyStats['average_rating'] ?? 0, 1) }}</div>
            <div class="stat-label">Rata-rata Nilai</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value">{{ $weeklyStats['active_students'] ?? 0 }}</div>
            <div class="stat-label">Siswa Aktif</div>
        </div>
    </div>

    <div class="main-content">
        <div class="reports-section">
            <div class="section-header">
                <h2 class="section-title">📋 Laporan Hari Ini</h2>
                <a href="{{ route('teacher.reports.create') }}" class="btn-primary">
                    <i class="fas fa-plus"></i> Tambah Laporan
                </a>
            </div>

            @forelse($todayReports as $report)
            <div class="report-item report-rating-{{ $report->performance_rating }}">
                <div class="report-header">
                    <div class="report-student">{{ $report->student->name }}</div>
                    <div class="rating-badge rating-{{ $report->performance_rating }}">
                        {{ $report->rating_emoji }} {{ $report->rating_text }}
                    </div>
                </div>
                <div class="report-activity">
                    <i class="{{ $report->activity->icon_class }}"></i>
                    {{ $report->activity->name }}
                </div>
                <div class="report-description">{{ $report->activity_description }}</div>
            </div>
            @empty
            <div style="text-align: center; color: rgba(255,255,255,0.7); padding: 40px;">
                <i class="fas fa-clipboard" style="font-size: 48px; margin-bottom: 20px; opacity: 0.5;"></i>
                <p>Belum ada laporan hari ini</p>
                <a href="{{ route('teacher.reports.create') }}" class="btn-primary" style="margin-top: 15px;">
                    <i class="fas fa-plus"></i> Buat Laporan Pertama
                </a>
            </div>
            @endforelse
        </div>

        <div class="students-section">
            <div class="section-header">
                <h2 class="section-title">👥 Siswa Terbaru</h2>
                <a href="{{ route('teacher.students.index') }}" style="color: #00f5ff; text-decoration: none;">
                    Lihat Semua
                </a>
            </div>

            @forelse($recentStudents as $student)
            <div class="student-item">
                <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}" class="student-avatar">
                <div class="student-info">
                    <h4>{{ $student->name }}</h4>
                    <p>{{ $student->class }} • {{ $student->daily_reports_count ?? 0 }} laporan</p>
                </div>
            </div>
            @empty
            <div style="text-align: center; color: rgba(255,255,255,0.7); padding: 40px;">
                <i class="fas fa-user-plus" style="font-size: 48px; margin-bottom: 20px; opacity: 0.5;"></i>
                <p>Belum ada data siswa</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="action-buttons">
        <a href="{{ route('teacher.reports.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Buat Laporan Baru
        </a>
        <a href="{{ route('teacher.students.index') }}" class="btn-secondary">
            <i class="fas fa-users"></i> Kelola Siswa
        </a>
        <a href="#" class="btn-secondary">
            <i class="fas fa-chart-line"></i> Lihat Analytics
        </a>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate stats on page load
    animateStats();
    
    // Add interactive hover effects
    addInteractiveEffects();
    
    // Show success message if redirected after creating report
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif
});

function animateStats() {
    const statValues = document.querySelectorAll('.stat-value');
    
    statValues.forEach(stat => {
        const finalValue = parseInt(stat.textContent) || parseFloat(stat.textContent) || 0;
        let currentValue = 0;
        const increment = finalValue / 50;
        const isFloat = stat.textContent.includes('.');
        
        const animation = setInterval(() => {
            currentValue += increment;
            if (currentValue >= finalValue) {
                currentValue = finalValue;
                clearInterval(animation);
            }
            
            if (isFloat) {
                stat.textContent = currentValue.toFixed(1);
            } else {
                stat.textContent = Math.floor(currentValue);
            }
        }, 30);
    });
}

function addInteractiveEffects() {
    // Add click effect to report items
    const reportItems = document.querySelectorAll('.report-item');
    reportItems.forEach(item => {
        item.addEventListener('click', function() {
            this.style.transform = 'scale(0.98) translateX(8px)';
            setTimeout(() => {
                this.style.transform = 'translateX(8px)';
            }, 150);
        });
    });
    
    // Add pulse effect to stat cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            const icon = this.querySelector('.stat-icon');
            icon.style.animation = 'pulse 0.6s ease-in-out';
            setTimeout(() => {
                icon.style.animation = '';
            }, 600);
        });
    });
    
    // Add smooth reveal for empty states
    const emptyStates = document.querySelectorAll('.empty-state');
    emptyStates.forEach(state => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '0';
                    entry.target.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        entry.target.style.transition = 'all 0.6s ease';
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, 100);
                }
            });
        });
        observer.observe(state);
    });
}

// Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + N for new report
    if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
        e.preventDefault();
        window.location.href = '{{ route('teacher.reports.create') }}';
    }
    
    // Ctrl/Cmd + S for students page
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        window.location.href = '{{ route('teacher.students.index') }}';
    }
});

// Add real-time clock
function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('id-ID', { 
        hour: '2-digit', 
        minute: '2-digit',
        hour12: false 
    });
    const dateString = now.toLocaleDateString('id-ID', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    
    // Add clock to header if not exists
    let clockElement = document.getElementById('live-clock');
    if (!clockElement) {
        clockElement = document.createElement('div');
        clockElement.id = 'live-clock';
        clockElement.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius);
            padding: var(--spacing-sm);
            color: var(--text-primary);
            font-size: 0.875rem;
            font-weight: 500;
            z-index: 999;
            transition: var(--transition);
        `;
        document.body.appendChild(clockElement);
    }
    
    clockElement.innerHTML = `
        <div style="font-weight: 600; margin-bottom: 2px;">${timeString}</div>
        <div style="font-size: 0.75rem; opacity: 0.8;">${dateString}</div>
    `;
}

// Update clock every second
setInterval(updateClock, 1000);
updateClock();
</script>
@endpush
@endsection
