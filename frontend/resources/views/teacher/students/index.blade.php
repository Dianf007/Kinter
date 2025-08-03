@extends('layouts.teacher')

@section('title', 'Kelola Siswa')

@push('styles')
<style>
    .students-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--spacing-xl) var(--spacing-md);
        animation: fadeInUp 0.6s ease;
    }
    
    .page-header {
        text-align: center;
        margin-bottom: var(--spacing-xl);
    }
    
    .page-header h1 {
        font-size: clamp(2rem, 4vw, 2.5rem);
        font-weight: 800;
        margin-bottom: var(--spacing-sm);
        background: var(--accent-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.02em;
    }
    
    .page-header p {
        color: var(--text-secondary);
        font-size: 1.125rem;
        font-weight: 400;
    }
    
    .students-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--spacing-xl);
        gap: var(--spacing-md);
        flex-wrap: wrap;
    }
    
    .add-student-btn {
        background: var(--accent-gradient);
        border: none;
        border-radius: var(--border-radius-lg);
        padding: var(--spacing-md) var(--spacing-lg);
        color: white;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: var(--spacing-sm);
        text-decoration: none;
        white-space: nowrap;
    }
    
    .add-student-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 245, 255, 0.3);
        text-decoration: none;
        color: white;
    }
    
    .students-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: var(--spacing-xl);
    }
    
    .student-card {
        background: var(--glass-bg);
        backdrop-filter: blur(25px);
        border: 1px solid var(--glass-border);
        border-radius: var(--border-radius-lg);
        padding: var(--spacing-xl);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.5s ease;
        animation-fill-mode: both;
    }
    
    .student-card:nth-child(1) { animation-delay: 0.1s; }
    .student-card:nth-child(2) { animation-delay: 0.2s; }
    .student-card:nth-child(3) { animation-delay: 0.3s; }
    .student-card:nth-child(4) { animation-delay: 0.4s; }
    .student-card:nth-child(5) { animation-delay: 0.5s; }
    .student-card:nth-child(6) { animation-delay: 0.6s; }
    
    .student-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--accent-gradient);
        background-size: 300% 100%;
        animation: gradientShift 3s ease-in-out infinite;
    }
    
    .student-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
        border-color: rgba(0, 245, 255, 0.3);
    }
    
    .student-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--accent-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--spacing-md);
        font-size: 2rem;
        color: white;
        font-weight: 700;
        box-shadow: 0 8px 20px rgba(0, 245, 255, 0.2);
    }
    
    .student-info {
        text-align: center;
        margin-bottom: var(--spacing-lg);
    }
    
    .student-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: var(--spacing-xs);
    }
    
    .student-class {
        color: var(--text-secondary);
        font-size: 1rem;
        font-weight: 500;
        margin-bottom: var(--spacing-sm);
    }
    
    .student-email {
        color: var(--text-muted);
        font-size: 0.875rem;
        font-family: var(--font-mono);
    }
    
    .student-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: var(--spacing-md);
        margin-bottom: var(--spacing-lg);
        padding: var(--spacing-md);
        background: rgba(255, 255, 255, 0.05);
        border-radius: var(--border-radius);
        backdrop-filter: blur(10px);
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: rgba(0, 245, 255, 0.9);
        display: block;
        font-family: var(--font-mono);
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-top: var(--spacing-xs);
    }
    
    .student-actions {
        display: flex;
        gap: var(--spacing-sm);
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .action-btn {
        padding: var(--spacing-sm) var(--spacing-md);
        border: none;
        border-radius: var(--border-radius);
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: var(--spacing-xs);
        min-width: 80px;
        justify-content: center;
    }
    
    .btn-view {
        background: rgba(0, 245, 255, 0.1);
        color: rgba(0, 245, 255, 0.9);
        border: 1px solid rgba(0, 245, 255, 0.3);
    }
    
    .btn-edit {
        background: rgba(255, 255, 0, 0.1);
        color: rgba(255, 255, 0, 0.9);
        border: 1px solid rgba(255, 255, 0, 0.3);
    }
    
    .btn-delete {
        background: rgba(255, 0, 127, 0.1);
        color: rgba(255, 0, 127, 0.9);
        border: 1px solid rgba(255, 0, 127, 0.3);
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .btn-view:hover {
        background: rgba(0, 245, 255, 0.2);
        box-shadow: 0 5px 15px rgba(0, 245, 255, 0.2);
        color: white;
        text-decoration: none;
    }
    
    .btn-edit:hover {
        background: rgba(255, 255, 0, 0.2);
        box-shadow: 0 5px 15px rgba(255, 255, 0, 0.2);
        color: white;
        text-decoration: none;
    }
    
    .btn-delete:hover {
        background: rgba(255, 0, 127, 0.2);
        box-shadow: 0 5px 15px rgba(255, 0, 127, 0.2);
        color: white;
    }
    
    .empty-state {
        text-align: center;
        padding: var(--spacing-xl) var(--spacing-lg);
        background: var(--glass-bg);
        backdrop-filter: blur(25px);
        border: 1px solid var(--glass-border);
        border-radius: var(--border-radius-lg);
        margin: var(--spacing-xl) 0;
    }
    
    .empty-icon {
        font-size: 4rem;
        color: var(--text-muted);
        margin-bottom: var(--spacing-lg);
    }
    
    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: var(--spacing-sm);
    }
    
    .empty-text {
        color: var(--text-secondary);
        margin-bottom: var(--spacing-lg);
    }
    
    @media (max-width: 768px) {
        .students-controls {
            flex-direction: column;
            align-items: stretch;
        }
        
        .students-grid {
            grid-template-columns: 1fr;
        }
        
        .student-stats {
            grid-template-columns: 1fr;
            gap: var(--spacing-sm);
        }
        
        .student-actions {
            flex-direction: column;
            gap: var(--spacing-xs);
        }
        
        .action-btn {
            min-width: auto;
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
<div class="students-container">
    <div class="page-header">
        <h1><i class="fas fa-user-graduate" style="margin-right: 15px;"></i>Kelola Siswa</h1>
        <p>Kelola data siswa dan pantau progress pembelajaran mereka secara real-time</p>
    </div>

    <div class="students-controls">
        <div style="display: flex; align-items: center; gap: 15px;">
            <span style="color: var(--text-secondary); font-weight: 600;">
                Total: {{ $students->count() }} siswa
            </span>
        </div>
        <a href="#" class="add-student-btn">
            <i class="fas fa-plus"></i>
            Tambah Siswa
        </a>
    </div>

    @if($students->count() > 0)
        <div class="students-grid">
            @foreach($students as $student)
            <div class="student-card">
                <div class="student-avatar">
                    {{ strtoupper(substr($student->name, 0, 2)) }}
                </div>
                
                <div class="student-info">
                    <div class="student-name">{{ $student->name }}</div>
                    <div class="student-class">Kelas {{ $student->class }}</div>
                    <div class="student-email">{{ $student->email ?? 'email@student.id' }}</div>
                </div>

                <div class="student-stats">
                    <div class="stat-item">
                        <span class="stat-value">{{ $student->daily_reports_count ?? 0 }}</span>
                        <div class="stat-label">Total Laporan</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ number_format($student->daily_reports_avg_performance_rating ?? 0, 1) }}</span>
                        <div class="stat-label">Rata-rata</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ $student->created_at ? $student->created_at->diffInDays(now()) : 0 }}</span>
                        <div class="stat-label">Hari Aktif</div>
                    </div>
                </div>

                <div class="student-actions">
                    <a href="{{ route('teacher.reports.show', $student) }}" class="action-btn btn-view">
                        <i class="fas fa-chart-line"></i>
                        Progress
                    </a>
                    <a href="#" class="action-btn btn-edit">
                        <i class="fas fa-edit"></i>
                        Edit
                    </a>
                    <button class="action-btn btn-delete" onclick="confirmDelete('{{ $student->name }}')">
                        <i class="fas fa-trash"></i>
                        Hapus
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="empty-title">Belum Ada Siswa</div>
            <div class="empty-text">
                Mulai dengan menambahkan siswa pertama untuk dapat membuat laporan pembelajaran yang terstruktur dan mendalam.
            </div>
            <a href="#" class="add-student-btn">
                <i class="fas fa-plus"></i>
                Tambah Siswa Pertama
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate cards on scroll
    const observeCards = () => {
        const cards = document.querySelectorAll('.student-card');
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }, index * 100);
                    }
                });
            },
            { threshold: 0.1 }
        );
        
        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });
    };
    
    // Initialize animations
    observeCards();
    
    // Hover effects for student cards
    const studentCards = document.querySelectorAll('.student-card');
    studentCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
            this.style.zIndex = '10';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.zIndex = '1';
        });
    });
    
    // Statistics counter animation
    const animateCounters = () => {
        const counters = document.querySelectorAll('.stat-value');
        
        counters.forEach(counter => {
            const target = parseInt(counter.textContent) || parseFloat(counter.textContent) || 0;
            const increment = target / 50;
            let current = 0;
            
            const updateCounter = () => {
                if (current < target) {
                    current += increment;
                    if (Number.isInteger(target)) {
                        counter.textContent = Math.ceil(current);
                    } else {
                        counter.textContent = current.toFixed(1);
                    }
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target.toString();
                }
            };
            
            // Start animation when element is visible
            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            updateCounter();
                            observer.unobserve(entry.target);
                        }
                    });
                },
                { threshold: 0.5 }
            );
            
            observer.observe(counter.closest('.student-card'));
        });
    };
    
    // Initialize counter animation
    animateCounters();
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+N for new student
        if (e.ctrlKey && e.key === 'n') {
            e.preventDefault();
            const addBtn = document.querySelector('.add-student-btn');
            if (addBtn) {
                addBtn.click();
            }
        }
        
        // Escape to scroll to top
        if (e.key === 'Escape') {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
});

// Delete confirmation function
function confirmDelete(studentName) {
    if (confirm(`Apakah Anda yakin ingin menghapus siswa ${studentName}? Tindakan ini tidak dapat dibatalkan.`)) {
        // Here you would implement the actual delete functionality
        console.log('Delete student functionality would be implemented here');
        
        // Show success notification
        alert('Siswa berhasil dihapus');
    }
}
</script>
@endpush

@endsection
