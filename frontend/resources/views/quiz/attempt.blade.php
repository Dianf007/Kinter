@extends('layouts.quiz')

@section('title', 'Quiz: '.$quiz->title)

@push('styles')
<style>
.quiz-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 40px 20px;
}

.quiz-header {
    text-align: center;
    color: white;
    margin-bottom: 40px;
}

.quiz-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 10px;
}

.quiz-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    color: rgba(255, 255, 255, 0.8);
}

.quiz-progress {
    background: rgba(255, 255, 255, 0.2);
    height: 8px;
    border-radius: 20px;
    margin: 20px 0;
    overflow: hidden;
}

.quiz-progress-bar {
    background: linear-gradient(90deg, #00f2fe 0%, #4facfe 100%);
    height: 100%;
    transition: width 0.3s ease;
}

.timer-section {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    margin-bottom: 40px;
}

.timer-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: conic-gradient(#ff6b6b 0deg, transparent 0deg);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.timer-circle::before {
    content: '';
    position: absolute;
    width: 65px;
    height: 65px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
}

.timer-text {
    position: relative;
    z-index: 2;
    font-weight: 700;
    color: #333;
    font-size: 14px;
}

.question-card {
    background: white;
    border-radius: 20px;
    padding: 40px;
    margin-bottom: 30px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    display: none;
}

.question-card.active {
    display: block;
    animation: slideIn 0.5s ease;
}

@keyframes slideIn {
    from { 
        opacity: 0; 
        transform: translateY(30px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}
}
.question-title {
    font-size: 24px;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 30px;
    line-height: 1.4;
}
.answer-option {
    background: #f7fafc;
    border: 2px solid #e2e8f0;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}
.answer-option:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border-color: #4facfe;
}
.answer-option.selected {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    border-color: #4facfe;
}
.answer-option.correct {
    background: linear-gradient(135deg, #56cc9d 0%, #6bcf7f 100%);
    animation: correctPulse 0.6s ease;
}
.answer-option.incorrect {
    background: linear-gradient(135deg, #ff6b6b 0%, #ffa8a8 100%);
    animation: shake 0.6s ease;
}
@keyframes correctPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}
.powerup-bar {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin: 30px 0;
    flex-wrap: wrap;
}
.powerup-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 15px;
    color: white;
    padding: 15px 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    min-width: 120px;
}
.powerup-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 30px rgba(102,126,234,0.4);
}
.powerup-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}
.powerup-btn .count {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #ff6b6b;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}
.navigation-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 40px;
}
.nav-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 15px;
    color: white;
    padding: 15px 30px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 120px;
}
.nav-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102,126,234,0.3);
}
.nav-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.streak-display {
    background: rgba(255,255,255,0.2);
    border-radius: 15px;
    padding: 15px;
    color: white;
    text-align: center;
    margin-bottom: 20px;
}
.score-display {
    background: linear-gradient(135deg, #56cc9d 0%, #6bcf7f 100%);
    border-radius: 15px;
    padding: 15px;
    color: white;
    text-align: center;
    margin-bottom: 20px;
    font-weight: 600;
}
</style>
@endpush

@section('content')
<div class="quiz-container">
    <div class="quiz-header">
        <h1 class="quiz-title">{{ $quiz->title }}</h1>
        <div class="quiz-meta">
            <div>Question <span id="current-question">1</span> of {{ $quiz->questions->count() }}</div>
            <div>Score: <span id="current-score">0</span></div>
        </div>
    </div>

    <div class="timer-section">
        <div class="timer-circle">
            <div class="timer-text" id="timer">{{ $quiz->time_limit ?? 30 }}:00</div>
        </div>
        <div style="flex: 1;">
            <div class="quiz-progress">
                <div class="quiz-progress-bar" id="progress-bar" style="width: 0%"></div>
            </div>
        </div>
    </div>

        <form id="quiz-form" action="{{ route('quiz.submit', $quiz) }}" method="POST">
            @csrf
            <input type="hidden" name="attempt_id" value="{{ $attempt->id }}">
            
            @foreach($quiz->questions as $index => $question)
            <div class="question-card {{ $index === 0 ? 'active' : '' }}" data-question-id="{{ $question->id }}" data-question-index="{{ $index }}">
                <div class="question-title">
                    <span class="question-number">{{ $index + 1 }}/{{ count($quiz->questions) }}</span>
                    <h3>{!! $question->question_text !!}</h3>
                </div>

                <div class="answers-container">
                    @if($question->type == 'multiple_choice' || $question->type == 'true_false')
                        @foreach($question->answers as $answer)
                        <div class="answer-option" data-answer-id="{{ $answer->id }}">
                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $answer->id }}" id="answer-{{ $answer->id }}" style="display:none;">
                            <label for="answer-{{ $answer->id }}">{{ $answer->answer_text }}</label>
                        </div>
                        @endforeach
                    @elseif($question->type == 'multi_answer')
                        @foreach($question->answers as $answer)
                        <div class="answer-option" data-answer-id="{{ $answer->id }}">
                            <input type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $answer->id }}" id="answer-{{ $answer->id }}" style="display:none;">
                            <label for="answer-{{ $answer->id }}">{{ $answer->answer_text }}</label>
                        </div>
                        @endforeach
                    @endif
                </div>

                <div class="powerup-bar">
                    <button type="button" class="powerup-btn" data-powerup="50:50">
                        🎯 50:50 <span class="count">3</span>
                    </button>
                    <button type="button" class="powerup-btn" data-powerup="hint">
                        💡 Hint <span class="count">2</span>
                    </button>
                    <button type="button" class="powerup-btn" data-powerup="skip">
                        ⏭️ Skip <span class="count">1</span>
                    </button>
                </div>

                <div class="navigation-buttons">
                    <button type="button" class="nav-btn" id="prev-btn" data-action="previous" {{ $index === 0 ? 'style=visibility:hidden' : '' }}>
                        ← Previous
                    </button>
                    <button type="button" class="nav-btn" id="next-btn" data-action="next">
                        {{ $index === count($quiz->questions) - 1 ? 'Finish Quiz' : 'Next →' }}
                    </button>
                </div>
            </div>
            @endforeach
        </form>
    </div>
</div>

@push('scripts')
<script>
// Quiz variables
let currentQuestion = 0;
let totalQuestions = {{ count($quiz->questions) }};
let totalSeconds = 600; // 10 minutes
let streak = 0;
let score = 0;
let selectedAnswers = {};

// Sound effects (will be loaded from assets)
const sounds = {
    correct: new Audio('/assets/sounds/correct.mp3'),
    incorrect: new Audio('/assets/sounds/incorrect.mp3'),
    powerup: new Audio('/assets/sounds/powerup.mp3'),
    tick: new Audio('/assets/sounds/tick.mp3')
};

// Initialize quiz
document.addEventListener('DOMContentLoaded', function() {
    updateProgress();
    startTimer();
    bindAnswerClicks();
    bindPowerupClicks();
    bindNavigationClicks();
});

// Timer logic with circular progress
function startTimer() {
    const timerEl = document.getElementById('timer');
    const timerCircle = document.querySelector('.timer-circle');
    const totalTime = 600;
    
    const interval = setInterval(() => {
        if (totalSeconds <= 0) {
            clearInterval(interval);
            submitQuiz();
            return;
        }
        
        // Update timer display
        let min = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
        let sec = String(totalSeconds % 60).padStart(2, '0');
        timerEl.textContent = min + ':' + sec;
        
        // Update circular progress
        const progress = ((totalTime - totalSeconds) / totalTime) * 360;
        timerCircle.style.background = `conic-gradient(#ff6b6b ${progress}deg, rgba(255,255,255,0.3) 0deg)`;
        
        // Warning sounds
        if (totalSeconds <= 60 && totalSeconds % 10 === 0) {
            playSound('tick');
        }
        
        totalSeconds--;
    }, 1000);
}

// Answer selection logic
function bindAnswerClicks() {
    document.querySelectorAll('.answer-option').forEach(option => {
        option.addEventListener('click', function() {
            const questionId = this.closest('.question-card').dataset.questionId;
            const answerId = this.dataset.answerId;
            const input = this.querySelector('input');
            const isMultiple = input.type === 'checkbox';
            
            if (isMultiple) {
                // Multiple choice logic
                this.classList.toggle('selected');
                input.checked = !input.checked;
            } else {
                // Single choice logic
                const allOptions = this.closest('.answers-container').querySelectorAll('.answer-option');
                allOptions.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                input.checked = true;
                
                // Auto advance after selection (optional)
                setTimeout(() => {
                    if (currentQuestion < totalQuestions - 1) {
                        nextQuestion();
                    }
                }, 1000);
            }
            
            // Animate selection
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
}

// Power-up functionality
function bindPowerupClicks() {
    document.querySelectorAll('.powerup-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const powerup = this.dataset.powerup;
            const count = parseInt(this.querySelector('.count').textContent);
            
            if (count <= 0) return;
            
            playSound('powerup');
            this.querySelector('.count').textContent = count - 1;
            
            switch(powerup) {
                case '50:50':
                    activate5050();
                    break;
                case 'hint':
                    showHint();
                    break;
                case 'skip':
                    skipQuestion();
                    break;
            }
            
            if (count - 1 <= 0) {
                this.disabled = true;
            }
        });
    });
}

// Power-up functions
function activate5050() {
    const currentCard = document.querySelector('.question-card.active');
    const wrongAnswers = Array.from(currentCard.querySelectorAll('.answer-option')).filter(option => {
        const input = option.querySelector('input');
        // This would need actual correct answer data
        return !option.dataset.correct; // placeholder
    });
    
    // Hide 2 wrong answers
    wrongAnswers.slice(0, 2).forEach(answer => {
        answer.style.opacity = '0.3';
        answer.style.pointerEvents = 'none';
    });
    
    showPowerupEffect('50:50 Activated!');
}

function showHint() {
    // Show hint modal or tooltip
    const hint = "This is a helpful hint for the current question.";
    showPowerupEffect(`💡 Hint: ${hint}`);
}

function skipQuestion() {
    showPowerupEffect('Question Skipped!');
    nextQuestion();
}

function showPowerupEffect(message) {
    // Create floating effect
    const effect = document.createElement('div');
    effect.textContent = message;
    effect.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px 30px;
        border-radius: 15px;
        font-weight: 600;
        z-index: 1000;
        animation: powerupEffect 2s ease forwards;
    `;
    
    document.body.appendChild(effect);
    setTimeout(() => effect.remove(), 2000);
}

// Add CSS for powerup effect
const style = document.createElement('style');
style.textContent = `
@keyframes powerupEffect {
    0% { opacity: 0; transform: translate(-50%, -50%) scale(0.5); }
    20% { opacity: 1; transform: translate(-50%, -50%) scale(1.1); }
    80% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
    100% { opacity: 0; transform: translate(-50%, -50%) scale(0.9); }
}
`;
document.head.appendChild(style);

// Navigation functions
function bindNavigationClicks() {
    document.querySelectorAll('.nav-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const action = this.dataset.action;
            
            if (action === 'previous') {
                previousQuestion();
            } else if (action === 'next') {
                if (currentQuestion === totalQuestions - 1) {
                    submitQuiz();
                } else {
                    nextQuestion();
                }
            }
        });
    });
}

function nextQuestion() {
    if (currentQuestion < totalQuestions - 1) {
        document.querySelector('.question-card.active').classList.remove('active');
        currentQuestion++;
        document.querySelectorAll('.question-card')[currentQuestion].classList.add('active');
        updateProgress();
    } else {
        submitQuiz();
    }
}

function previousQuestion() {
    if (currentQuestion > 0) {
        document.querySelector('.question-card.active').classList.remove('active');
        currentQuestion--;
        document.querySelectorAll('.question-card')[currentQuestion].classList.add('active');
        updateProgress();
    }
}

function updateProgress() {
    const progress = ((currentQuestion + 1) / totalQuestions) * 100;
    document.getElementById('progress-bar').style.width = progress + '%';
    
    // Update navigation buttons
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    
    if (prevBtn) prevBtn.style.visibility = currentQuestion === 0 ? 'hidden' : 'visible';
    if (nextBtn) nextBtn.textContent = currentQuestion === totalQuestions - 1 ? 'Finish Quiz' : 'Next →';
}

function submitQuiz() {
    // Add confirmation with Wayground-style modal
    if (confirm('Are you ready to submit your quiz?')) {
        document.getElementById('quiz-form').submit();
    }
}

function playSound(type) {
    if (sounds[type]) {
        sounds[type].currentTime = 0;
        sounds[type].play().catch(() => {}); // Handle autoplay restrictions
    }
}

// Answer feedback simulation (would need real data)
function showAnswerFeedback(isCorrect) {
    const currentCard = document.querySelector('.question-card.active');
    const selectedOption = currentCard.querySelector('.answer-option.selected');
    
    if (selectedOption) {
        if (isCorrect) {
            selectedOption.classList.add('correct');
            playSound('correct');
            streak++;
            score += 10;
        } else {
            selectedOption.classList.add('incorrect');
            playSound('incorrect');
            streak = 0;
        }
        
        // Update displays
        document.getElementById('streak-count').textContent = streak;
        document.getElementById('current-score').textContent = score;
    }
}
</script>
@endpush
@endsection
