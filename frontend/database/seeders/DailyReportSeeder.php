<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DailyReport;
use App\Models\Student;
use App\Models\Activity;
use Carbon\Carbon;

class DailyReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $activities = Activity::all();
        
        if ($students->isEmpty() || $activities->isEmpty()) {
            $this->command->info('Please run StudentSeeder and ActivitySeeder first');
            return;
        }
        
        $descriptions = [
            'Scratch' => [
                'Siswa belajar membuat animasi karakter dengan block programming',
                'Latihan membuat game sederhana menggunakan sprite dan backdrop',
                'Praktek membuat cerita interaktif dengan dialog dan suara',
                'Belajar konsep loop dan kondisi dalam pemrograman visual',
                'Membuat proyek animasi dengan musik dan efek suara'
            ],
            'Code.org' => [
                'Menyelesaikan tahap Hour of Code dengan karakter Minecraft',
                'Belajar algoritma dasar melalui puzzle programming',
                'Praktek debugging dan problem solving',
                'Membuat pola dan sequence menggunakan block code',
                'Latihan membuat fungsi dan parameter'
            ],
            'Wayground Quiz' => [
                'Mengerjakan kuis logika dan matematika dasar',
                'Menyelesaikan soal pemahaman algoritma',
                'Praktek soal computational thinking',
                'Kuis tentang konsep programming fundamental',
                'Evaluasi pemahaman konsep abstraksi dan dekomposisi'
            ],
            'Robot Programming' => [
                'Belajar menggerakkan robot dengan perintah dasar',
                'Praktek sensor dan actuator pada robot',
                'Membuat program navigasi sederhana',
                'Latihan koordinasi dan timing movement',
                'Praktek problem solving dengan robot challenges'
            ]
        ];
        
        $notes = [
            'Siswa sangat antusias dan aktif bertanya',
            'Perlu bimbingan lebih dalam memahami konsep loop',
            'Menunjukkan kreativitas tinggi dalam membuat proyek',
            'Mampu bekerja sama dengan baik dalam tim',
            'Kesulitan di awal tapi mulai memahami setelah praktek',
            'Sangat cepat menangkap materi dan membantu teman',
            'Perlu latihan lebih untuk meningkatkan pemahaman',
            'Menunjukkan progress yang konsisten',
            'Tertarik untuk mengembangkan proyek lebih lanjut',
            'Butuh motivasi tambahan untuk fokus'
        ];
        
        // Generate reports for the last 30 days
        for ($i = 0; $i < 50; $i++) {
            $student = $students->random();
            $activity = $activities->random();
            $randomDate = Carbon::now()->subDays(rand(0, 30));
            
            // Get random description based on activity
            $activityDescriptions = $descriptions[$activity->name] ?? ['Aktivitas pembelajaran umum'];
            $description = $activityDescriptions[array_rand($activityDescriptions)];
            
            DailyReport::create([
                'teacher_id' => 1,
                'student_id' => $student->id,
                'activity_id' => $activity->id,
                'activity_description' => $description,
                'performance_rating' => rand(2, 5), // Rating 2-5 untuk variasi
                'notes' => rand(0, 10) > 6 ? $notes[array_rand($notes)] : null,
                'report_date' => $randomDate->format('Y-m-d'),
                'duration' => rand(30, 120), // 30-120 menit
                'created_at' => $randomDate,
                'updated_at' => $randomDate
            ]);
        }
        
        $this->command->info('Created 50 sample daily reports');
    }
}
