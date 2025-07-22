<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PowerUpSeeder extends Seeder
{
    public function run()
    {
        DB::table('power_ups')->insert([
            [
                'name' => '50:50',
                'description' => 'Menghilangkan dua opsi jawaban salah',
                'type' => 'standard',
                'icon' => 'powerup-5050.png',
                'price_points' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tambah Waktu',
                'description' => 'Menambah waktu pengerjaan soal',
                'type' => 'standard',
                'icon' => 'powerup-timer.png',
                'price_points' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hint',
                'description' => 'Memberikan petunjuk untuk soal',
                'type' => 'special',
                'icon' => 'powerup-hint.png',
                'price_points' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Skip',
                'description' => 'Lewati soal tanpa mengurangi skor',
                'type' => 'special',
                'icon' => 'powerup-skip.png',
                'price_points' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
