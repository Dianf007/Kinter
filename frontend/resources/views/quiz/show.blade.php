@extends('layouts.quiz')

@section('title', $quiz->title)

@push('styles')
<style>
    .quiz-preview {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 60px);
        padding: 40px 20px;
    }
    
    .preview-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 30px;
        padding: 50px;
        max-width: 600px;
        width: 100%;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .preview-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #00f5ff, #ff00ff, #ffff00, #00f5ff);
        background-size: 300% 100%;
        animation: gradient 3s ease-in-out infinite;
    }
    
    @keyframes gradient {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    .quiz-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        font-size: 36px;
        color: white;
    }
    
    .quiz-title {
        color: white;
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 20px;
        line-height: 1.3;
    }
    
    .quiz-description {
        color: rgba(255, 255, 255, 0.8);
        font-size: 16px;
        line-height: 1.6;
        margin-bottom: 40px;
    }
    
    .quiz-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }
    
    .info-item {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 15px;
        padding: 20px;
        text-align: center;
    }
    
    .info-value {
        color: #00f5ff;
        font-size: 24px;
        font-weight: 600;
        display: block;
        margin-bottom: 5px;
    }
    
    .info-label {
        color: rgba(255, 255, 255, 0.7);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .start-form {
        margin-bottom: 30px;
    }
    
    .guest-input {
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 15px 20px;
        width: 100%;
        color: white;
        font-size: 16px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    
    .guest-input:focus {
        outline: none;
        border-color: #00f5ff;
        background: rgba(255, 255, 255, 0.15);
        box-shadow: 0 0 20px rgba(0, 245, 255, 0.3);
    }
    
    .guest-input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }
    
    .start-button {
        background: linear-gradient(135deg, #00f5ff, #ff00ff);
        border: none;
        color: white;
        padding: 18px 40px;
        border-radius: 25px;
        font-size: 18px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .start-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.6s;
    }
    
    .start-button:hover::before {
        left: 100%;
    }
    
    .start-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 245, 255, 0.3);
    }
    
    .back-link {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        font-size: 14px;
        transition: color 0.3s ease;
    }
    
    .back-link:hover {
        color: #00f5ff;
    }
    
    @media (max-width: 768px) {
        .preview-card {
            padding: 30px 20px;
            margin: 20px;
        }
        
        .quiz-title {
            font-size: 24px;
        }
        
        .quiz-info {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .start-button {
            width: 100%;
            padding: 16px;
        }
    }
</style>
@endpush

@section('content')
<div class="quiz-preview">
    <div class="preview-card">
        <div class="quiz-icon">
            <i class="fas fa-brain"></i>
        </div>
        
        <h1 class="quiz-title">{{ $quiz->title }}</h1>
        
        @if($quiz->description)
        <p class="quiz-description">{{ $quiz->description }}</p>
        @endif
        
        <div class="quiz-info">
            <div class="info-item">
                <span class="info-value">{{ $quiz->questions()->count() }}</span>
                <span class="info-label">Questions</span>
            </div>
            <div class="info-item">
                <span class="info-value">{{ $quiz->time_limit ?? 30 }}</span>
                <span class="info-label">Minutes</span>
            </div>
            <div class="info-item">
                <span class="info-value">{{ $quiz->difficulty ?? 'Medium' }}</span>
                <span class="info-label">Difficulty</span>
            </div>
        </div>
        
        @if(auth()->check())
            <form action="{{ route('quiz.start', $quiz) }}" method="POST" class="start-form">
                @csrf
                <button type="submit" class="start-button">
                    <i class="fas fa-play"></i>
                    Start Quiz
                </button>
            </form>
        @else
            <form action="{{ route('quiz.start', $quiz) }}" method="POST" class="start-form">
                @csrf
                <input type="text" 
                       class="guest-input" 
                       name="guest_name" 
                       placeholder="Enter your name to continue"
                       required
                       maxlength="50">
                <button type="submit" class="start-button">
                    <i class="fas fa-play"></i>
                    Start Quiz
                </button>
            </form>
        @endif
        
        <a href="{{ route('quiz.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Back to Quiz List
        </a>
    </div>
</div>
@endsection
