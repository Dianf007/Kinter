@extends('layouts.quiz')

@section('title', 'Quiz Results')

@push('styles')
<style>
    .result-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 60px);
        padding: 40px 20px;
    }
    
    .result-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 30px;
        padding: 50px;
        max-width: 800px;
        width: 100%;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .result-card::before {
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
    
    .score-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        position: relative;
        font-size: 28px;
        font-weight: 700;
        color: white;
    }
    
    .score-pass {
        background: linear-gradient(135deg, #4caf50, #8bc34a);
    }
    
    .score-fail {
        background: linear-gradient(135deg, #f44336, #ff5722);
    }
    
    .result-title {
        color: white;
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .result-subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 18px;
        margin-bottom: 40px;
    }
    
    .result-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }
    
    .stat-item {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 15px;
        padding: 20px;
    }
    
    .stat-value {
        color: #00f5ff;
        font-size: 24px;
        font-weight: 600;
        display: block;
        margin-bottom: 5px;
    }
    
    .stat-label {
        color: rgba(255, 255, 255, 0.7);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .badges-section {
        margin-bottom: 40px;
    }
    
    .badges-grid {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
        margin-top: 20px;
    }
    
    .badge-item {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        transition: transform 0.3s ease;
    }
    
    .badge-item:hover {
        transform: scale(1.05);
    }
    
    .badge-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 10px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ffd700, #ffb347);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .badge-name {
        color: white;
        font-size: 14px;
        font-weight: 500;
    }
    
    .actions-section {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .action-btn {
        padding: 15px 30px;
        border-radius: 25px;
        border: none;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #00f5ff, #ff00ff);
        color: white;
    }
    
    .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }
    
    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }
    
    .explanations {
        text-align: left;
        margin-top: 40px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        padding: 30px;
    }
    
    .explanation-item {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .explanation-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    
    .question-text {
        color: white;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .answer-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 14px;
    }
    
    .answer-label {
        color: rgba(255, 255, 255, 0.7);
        min-width: 120px;
    }
    
    .answer-value {
        color: white;
        flex: 1;
    }
    
    .correct-answer {
        color: #4caf50;
    }
    
    .wrong-answer {
        color: #f44336;
    }
    
    @media (max-width: 768px) {
        .result-card {
            padding: 30px 20px;
            margin: 20px;
        }
        
        .result-title {
            font-size: 24px;
        }
        
        .result-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .actions-section {
            flex-direction: column;
        }
        
        .action-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="result-container">
    <div class="result-card">
        <div class="score-circle {{ $attempt->is_passed ? 'score-pass' : 'score-fail' }}">
            {{ $attempt->score }}%
        </div>
        
        <h1 class="result-title">{{ $attempt->is_passed ? 'Congratulations!' : 'Keep Trying!' }}</h1>
        <p class="result-subtitle">{{ $quiz->title }}</p>
        
        <div class="result-stats">
            <div class="stat-item">
                <span class="stat-value">{{ $attempt->score }}%</span>
                <span class="stat-label">Final Score</span>
            </div>
            <div class="stat-item">
                <span class="stat-value">{{ gmdate('i:s', $attempt->duration_seconds) }}</span>
                <span class="stat-label">Time Taken</span>
            </div>
            <div class="stat-item">
                <span class="stat-value">{{ $attempt->answers->where('is_correct', true)->count() }}/{{ $quiz->questions->count() }}</span>
                <span class="stat-label">Correct</span>
            </div>
            <div class="stat-item">
                <span class="stat-value">{{ $attempt->is_passed ? 'PASSED' : 'FAILED' }}</span>
                <span class="stat-label">Status</span>
            </div>
        </div>
        
        @if($badges->isNotEmpty())
        <div class="badges-section">
            <h3 style="color: white; margin-bottom: 20px;">🏆 Badges Earned</h3>
            <div class="badges-grid">
                @foreach($badges as $badge)
                <div class="badge-item">
                    <div class="badge-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="badge-name">{{ $badge->name }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <div class="actions-section">
            @if($attempt->is_passed)
            <a href="{{ route('quiz.certificate', $attempt) }}" class="action-btn btn-primary">
                <i class="fas fa-download"></i>
                Download Certificate
            </a>
            @endif
            
            <a href="{{ route('quiz.show', $quiz) }}" class="action-btn btn-secondary">
                <i class="fas fa-redo"></i>
                Retake Quiz
            </a>
            
            <a href="{{ route('quiz.index') }}" class="action-btn btn-secondary">
                <i class="fas fa-list"></i>
                More Quizzes
            </a>
        </div>
        
        <div class="explanations">
            <h3 style="color: white; margin-bottom: 20px;">📖 Question Review</h3>
            @foreach($quiz->questions as $question)
            <div class="explanation-item">
                <div class="question-text">{!! $question->question_text !!}</div>
                
                @php
                    $userAnswers = $attempt->answers->where('question_id', $question->id)->pluck('answer.answer_text')->toArray();
                    $correctAnswers = $question->answers->where('is_correct', true)->pluck('answer_text')->toArray();
                    $isCorrect = !empty(array_intersect($userAnswers, $correctAnswers));
                @endphp
                
                <div class="answer-row">
                    <span class="answer-label">Your Answer:</span>
                    <span class="answer-value {{ $isCorrect ? 'correct-answer' : 'wrong-answer' }}">
                        {{ implode(', ', $userAnswers) ?: 'No answer' }}
                    </span>
                </div>
                
                <div class="answer-row">
                    <span class="answer-label">Correct Answer:</span>
                    <span class="answer-value correct-answer">
                        {{ implode(', ', $correctAnswers) }}
                    </span>
                </div>
                
                @if($question->explanation)
                <div class="answer-row">
                    <span class="answer-label">Explanation:</span>
                    <span class="answer-value">{{ $question->explanation }}</span>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
