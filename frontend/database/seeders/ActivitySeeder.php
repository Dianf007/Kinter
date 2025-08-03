<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = [
            [
                'name' => 'Code.org',
                'type' => 'code_org',
                'description' => 'Pembelajaran coding dengan platform Code.org',
                'color' => '#00d4aa'
            ],
            [
                'name' => 'Scratch',
                'type' => 'scratch',
                'description' => 'Membuat project dengan Scratch programming',
                'color' => '#ff6600'
            ],
            [
                'name' => 'Quiz Wayground',
                'type' => 'quiz',
                'description' => 'Mengerjakan quiz di platform Wayground',
                'color' => '#667eea'
            ],
            [
                'name' => 'Membaca',
                'type' => 'reading',
                'description' => 'Kegiatan membaca buku atau artikel',
                'color' => '#48bb78'
            ],
            [
                'name' => 'Matematika',
                'type' => 'math',
                'description' => 'Latihan soal dan konsep matematika',
                'color' => '#ed8936'
            ],
            [
                'name' => 'Sains',
                'type' => 'science',
                'description' => 'Eksperimen dan teori sains',
                'color' => '#38b2ac'
            ],
            [
                'name' => 'Seni & Kreativitas',
                'type' => 'art',
                'description' => 'Kegiatan seni dan kreativitas',
                'color' => '#e53e3e'
            ],
            [
                'name' => 'Musik',
                'type' => 'music',
                'description' => 'Pembelajaran musik dan instrumen',
                'color' => '#9f7aea'
            ],
            [
                'name' => 'Olahraga',
                'type' => 'physical',
                'description' => 'Aktivitas fisik dan olahraga',
                'color' => '#f56565'
            ],
            [
                'name' => 'Komputer',
                'type' => 'computer',
                'description' => 'Pembelajaran teknik penggunaan komputer',
                'color' => '#9f7aea'
            ],
            [
                'name' => 'Lainnya',
                'type' => 'other',
                'description' => 'Aktivitas pembelajaran lainnya',
                'color' => '#a0aec0'
            ]
        ];

        foreach ($activities as $activity) {
            \App\Models\Activity::create($activity);
        }
    }
}
