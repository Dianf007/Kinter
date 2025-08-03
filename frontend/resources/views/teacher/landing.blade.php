@extends('layouts.teacher')

@section('title', 'Portal Guru - Laporan Pembelajaran')

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .hero-section {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        animation: float 20s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(1deg); }
    }
    
    .hero-content {
        text-align: center;
        max-width: 900px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }
    
    .hero-icon {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #00f5ff, #ff00ff);
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 40px;
        font-size: 60px;
        color: white;
        animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .hero-title {
        color: white;
        font-size: 56px;
        font-weight: 700;
        margin-bottom: 25px;
        line-height: 1.2;
        background: linear-gradient(135deg, #00f5ff, #ff00ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .hero-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 22px;
        margin-bottom: 50px;
        line-height: 1.6;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        margin-bottom: 60px;
    }
    
    .feature-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 25px;
        padding: 35px;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #00f5ff, #ff00ff);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .feature-card:hover::before {
        transform: scaleX(1);
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
        background: rgba(255, 255, 255, 0.15);
    }
    
    .feature-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        font-size: 36px;
        color: white;
    }
    
    .feature-title {
        color: white;
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .feature-description {
        color: rgba(255, 255, 255, 0.8);
        font-size: 16px;
        line-height: 1.5;
    }
    
    .cta-section {
        text-align: center;
    }
    
    .cta-buttons {
        display: flex;
        gap: 25px;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .cta-btn {
        padding: 20px 40px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        font-size: 18px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        min-width: 200px;
        justify-content: center;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #00f5ff, #ff00ff);
        color: white;
        box-shadow: 0 15px 35px rgba(0, 245, 255, 0.3);
    }
    
    .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }
    
    .cta-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }
    
    .stats-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 30px;
        margin: 80px 0;
    }
    
    .stat-card {
        text-align: center;
        padding: 30px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .stat-number {
        color: #00f5ff;
        font-size: 48px;
        font-weight: 700;
        display: block;
        margin-bottom: 10px;
    }
    
    .stat-label {
        color: rgba(255, 255, 255, 0.8);
        font-size: 16px;
        font-weight: 500;
    }
    
    .testimonial-section {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 30px;
        padding: 50px;
        margin: 60px 0;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .testimonial-text {
        color: rgba(255, 255, 255, 0.9);
        font-size: 20px;
        font-style: italic;
        line-height: 1.6;
        margin-bottom: 30px;
    }
    
    .testimonial-author {
        color: white;
        font-weight: 600;
        font-size: 16px;
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 40px;
        }
        
        .hero-subtitle {
            font-size: 18px;
        }
        
        .features-grid {
            grid-template-columns: 1fr;
        }
        
        .cta-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .stats-section {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .testimonial-section {
            padding: 30px 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="hero-section">
    <div class="hero-content">
        <div class="hero-icon">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        
        <h1 class="hero-title">Portal Guru Digital</h1>
        <p class="hero-subtitle">
            Kelola laporan pembelajaran harian siswa dengan mudah. Pantau progress belajar coding, quiz, dan aktivitas pembelajaran lainnya dalam satu platform yang intuitif.
        </p>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3 class="feature-title">Laporan Harian</h3>
                <p class="feature-description">
                    Input dan kelola laporan pembelajaran siswa setiap hari dengan sistem rating yang mudah dipahami
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="feature-title">Analytics Progress</h3>
                <p class="feature-description">
                    Lihat progress dan statistik pembelajaran siswa dalam dashboard yang informatif
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-code"></i>
                </div>
                <h3 class="feature-title">Multi Platform</h3>
                <p class="feature-description">
                    Mendukung berbagai platform pembelajaran: Code.org, Scratch, Quiz, dan aktivitas lainnya
                </p>
            </div>
        </div>

        <div class="stats-section">
            <div class="stat-card">
                <span class="stat-number">5</span>
                <span class="stat-label">Level Rating</span>
            </div>
            <div class="stat-card">
                <span class="stat-number">10+</span>
                <span class="stat-label">Jenis Aktivitas</span>
            </div>
            <div class="stat-card">
                <span class="stat-number">∞</span>
                <span class="stat-label">Siswa</span>
            </div>
        </div>

        <div class="testimonial-section">
            <p class="testimonial-text">
                "Platform ini sangat membantu saya dalam memantau progress belajar coding siswa. Interface yang user-friendly dan sistem rating yang jelas membuat pelaporan menjadi efisien."
            </p>
            <p class="testimonial-author">- Pak Budi, Guru Informatika</p>
        </div>

        <div class="cta-section">
            <div class="cta-buttons">
                <a href="{{ route('teacher.reports.index') }}" class="cta-btn btn-primary">
                    <i class="fas fa-rocket"></i>
                    Mulai Sekarang
                </a>
                <a href="#demo" class="cta-btn btn-secondary">
                    <i class="fas fa-play"></i>
                    Lihat Demo
                </a>
            </div>
            
            <div style="margin-top: 40px; color: rgba(255,255,255,0.7); font-size: 14px;">
                <p>✨ Gratis untuk guru dan tidak ada batasan siswa</p>
            </div>
        </div>
    </div>
</div>
@endsection
