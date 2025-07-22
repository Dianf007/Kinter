<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadgeSeeder extends Seeder
{
    public function run()
    {
        DB::table('badges')->insert([
            [
                'name' => 'Quiz Master',
                'description' => 'Menyelesaikan 10 kuis dengan skor di atas 80%',
                'icon' => 'badge-quizmaster.png',
                'criteria' => json_encode(['quizzes_completed' => 10, 'min_score' => 80]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Power User',
                'description' => 'Menggunakan semua power-up dalam satu kuis',
                'icon' => 'badge-poweruser.png',
                'criteria' => json_encode(['all_powerups_used' => true]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
