<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class QuizController extends Controller
{
    // Daftar kuis (user/guest)
    public function index()
    {
        $quizzes = Quiz::where('is_active', true)->withCount('questions')->paginate(10);
        return view('quiz.index', compact('quizzes'));
    }

    // Detail kuis & instruksi
    public function show(Quiz $quiz)
    {
        $quiz->load('questions.answers');
        return view('quiz.show', compact('quiz'));
    }

    // Mulai attempt baru (user/guest)
    public function start(Request $request, Quiz $quiz)
    {
        // Determine user or guest
        if (auth()->check()) {
            $userId = auth()->id();
            $guestName = null;
        } else {
            $request->validate([ 'guest_name' => 'required|string|max:255' ]);
            $userId = null;
            $guestName = $request->input('guest_name');
        }
        // Create attempt
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => $userId,
            'guest_name' => $guestName,
            'class_id' => $request->user()?->class_id,
            'started_at' => now(),
        ]);
        // Redirect to attempt view
        return redirect()->route('quiz.attempt', [$quiz, $attempt]);
    }
    
    // Halaman pengerjaan attempt
    public function attempt(Quiz $quiz, QuizAttempt $attempt)
    {
        $quiz->load('questions.answers');
        return view('quiz.attempt', compact('quiz', 'attempt'));
    }

    // Submit jawaban attempt
    public function submit(Request $request, Quiz $quiz)
    {
        $data = $request->validate([
            'attempt_id' => 'required|exists:quiz_attempts,id',
            'answers' => 'required|array',
        ]);
        $attempt = QuizAttempt::findOrFail($data['attempt_id']);
        $questions = $quiz->questions()->with('answers')->get();
        $correctCount = 0;
        // Clear previous answers if any
        $attempt->answers()->delete();
        foreach ($questions as $question) {
            $qid = $question->id;
            $userAnswers = $data['answers'][$qid] ?? [];
            if (!is_array($userAnswers)) {
                $userAnswers = [$userAnswers];
            }
            $correctIds = $question->answers->where('is_correct', true)->pluck('id')->toArray();
            sort($correctIds);
            $selected = array_map('intval', $userAnswers);
            sort($selected);
            $isCorrect = ($correctIds === $selected);
            if ($isCorrect) {
                $correctCount++;
            }
            foreach ($selected as $aid) {
                QuizAttemptAnswer::create([
                    'quiz_attempt_id' => $attempt->id,
                    'question_id' => $qid,
                    'answer_id' => $aid,
                    'is_correct' => in_array($aid, $correctIds),
                    'answered_at' => now(),
                ]);
            }
        }
        $total = $questions->count();
        $score = $total > 0 ? round(($correctCount / $total) * 100) : 0;
        $attempt->update([
            'finished_at' => now(),
            'duration_seconds' => now()->diffInSeconds($attempt->started_at),
            'score' => $score,
            'is_passed' => $score >= 60,
        ]);
        return redirect()->route('quiz.result', [$quiz, $attempt]);
    }

    // Lihat hasil attempt
    public function result(Quiz $quiz, QuizAttempt $attempt)
    {
        // Load user's answers with question and correct answers
        $attempt->load(['answers.question.answers', 'answers.answer']);
        // Optionally load badges earned (if implemented)
        $badges = auth()->check() ? auth()->user()->badges : collect();
        return view('quiz.result', compact('quiz', 'attempt', 'badges'));
    }

    // Leaderboard per kelas
    public function leaderboard(Quiz $quiz)
    {
        // Logic: tampilkan leaderboard per kelas
    }

    // Generate PDF certificate
    public function certificate(QuizAttempt $attempt)
    {
        $quiz = $attempt->quiz;
        $userName = $attempt->user?->name ?? $attempt->guest_name;
        $data = compact('quiz', 'attempt', 'userName');
        $pdf = Pdf::loadView('quiz.certificate', $data)
                  ->setPaper('a4', 'landscape');
        return $pdf->download("certificate-quiz-{$attempt->id}.pdf");
    }
}
