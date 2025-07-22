@extends('layouts.quiz')

@section('title', 'Quiz List')

@push('styles')
<style>
    .quiz-grid {
        padding: 40px 20px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .quiz-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 30px;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .quiz-card::before {
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
    
    .quiz-card:hover::before {
        transform: scaleX(1);
    }
    
    .quiz-card:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
    }
    
    .quiz-title {
        color: white;
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 15px;
        line-height: 1.3;
    }
    
    .quiz-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .quiz-questions {
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .quiz-difficulty {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .difficulty-easy { background: rgba(76, 175, 80, 0.3); color: #4caf50; }
    .difficulty-medium { background: rgba(255, 152, 0, 0.3); color: #ff9800; }
    .difficulty-hard { background: rgba(244, 67, 54, 0.3); color: #f44336; }
    
    .quiz-description {
        color: rgba(255, 255, 255, 0.7);
        font-size: 14px;
        line-height: 1.5;
        margin-bottom: 20px;
    }
    
    .quiz-stats {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.6);
    }
    
    .quiz-header {
        text-align: center;
        padding: 60px 20px 40px;
        color: white;
    }
    
    .quiz-header h1 {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 15px;
        background: linear-gradient(135deg, #00f5ff, #ff00ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .quiz-header p {
        font-size: 18px;
        color: rgba(255, 255, 255, 0.8);
        max-width: 600px;
        margin: 0 auto;
    }
    
    @media (max-width: 768px) {
        .quiz-grid {
            grid-template-columns: 1fr;
            padding: 20px;
            gap: 20px;
        }
        
        .quiz-header h1 {
            font-size: 32px;
        }
        
        .quiz-header {
            padding: 40px 20px 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="quiz-header">
    <h1>Quiz Collection</h1>
    <p>Challenge yourself with our interactive quizzes. Test your knowledge and compete with others!</p>
</div>

<div class="quiz-grid">
    @foreach($quizzes as $quiz)
    <div class="quiz-card" onclick="location.href='{{ route('quiz.show', $quiz) }}'">
        <div class="quiz-title">{{ $quiz->title }}</div>
        
        <div class="quiz-meta">
            <div class="quiz-questions">
                <i class="fas fa-question-circle"></i>
                {{ $quiz->questions_count }} Questions
            </div>
            <div class="quiz-difficulty difficulty-{{ strtolower($quiz->difficulty ?? 'medium') }}">
                {{ $quiz->difficulty ?? 'Medium' }}
            </div>
        </div>
        
        @if($quiz->description)
        <div class="quiz-description">
            {{ Str::limit($quiz->description, 120) }}
        </div>
        @endif
        
        <div class="quiz-stats">
            <span>
                <i class="fas fa-clock"></i>
                {{ $quiz->time_limit ?? 30 }} min
            </span>
            <span>
                <i class="fas fa-users"></i>
                {{ rand(10, 100) }} taken
            </span>
        </div>
    </div>
    @endforeach
</div>

@if($quizzes->hasPages())
<div style="display: flex; justify-content: center; padding: 40px 20px;">
    <div style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(15px); border-radius: 15px; padding: 20px;">
        {{ $quizzes->links() }}
    </div>
</div>
@endif
</section>
@endsection
