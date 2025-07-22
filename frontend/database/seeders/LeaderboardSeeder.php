<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LeaderboardSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        $quiz = DB::table('quizzes')->first();
        $class = DB::table('courses')->first();
        if ($user && $quiz && $class) {
            DB::table('leaderboard')->insert([
                [
                    'class_id' => $class->id,
                    'user_id' => $user->id,
                    'quiz_id' => $quiz->id,
                    'score' => 90,
                    'rank' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
