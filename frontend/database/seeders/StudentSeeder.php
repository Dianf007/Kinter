<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        $schools = \App\Models\School::all();
        foreach ($schools as $school) {
            for ($i = 1; $i <= 20; $i++) {
                Student::create([
                    'school_id' => $school->id,
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'student_id' => $school->id . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'class' => $faker->randomElement(['1A','1B','2A','2B','3A','3B','4A','4B','5A','5B','6A','6B']),
                    'teacher_id' => 1, // atau random jika ada data guru
                    'avatar' => null
                ]);
            }
        }
    }
}
