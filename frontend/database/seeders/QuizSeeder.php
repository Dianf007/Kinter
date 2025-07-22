<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class QuizSeeder extends Seeder
{
    public function run()
    {
        // Create a teacher user
        $teacher = User::factory()->create([
            'name' => 'Teacher Quiz',
            'email' => 'teacher_quiz@example.com',
            'password' => bcrypt('password'),
            'role' => 'teacher',
        ]);

        // Create a quiz
        $quizId = DB::table('quizzes')->insertGetId([
            'title' => 'Contoh Kuis Wayground',
            'description' => 'Kuis demo untuk fitur Wayground',
            'teacher_id' => $teacher->id,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create questions
        $questionId1 = DB::table('questions')->insertGetId([
            'quiz_id' => $quizId,
            'type' => 'multiple_choice',
            'question_text' => 'Apa ibu kota Indonesia?',
            'explanation' => 'Ibu kota Indonesia adalah Jakarta.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $questionId2 = DB::table('questions')->insertGetId([
            'quiz_id' => $quizId,
            'type' => 'true_false',
            'question_text' => 'Matahari terbit dari barat.',
            'explanation' => 'Matahari terbit dari timur.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create answers for question 1
        DB::table('answers')->insert([
            [
                'question_id' => $questionId1,
                'answer_text' => 'Jakarta',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => $questionId1,
                'answer_text' => 'Bandung',
                'is_correct' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => $questionId1,
                'answer_text' => 'Surabaya',
                'is_correct' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        // Create answers for question 2
        DB::table('answers')->insert([
            [
                'question_id' => $questionId2,
                'answer_text' => 'Benar',
                'is_correct' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => $questionId2,
                'answer_text' => 'Salah',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
