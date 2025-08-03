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
        $students = [
            [
                'student_id' => 'STU001',
                'name' => 'Ahmad Rizki',
                'class' => '5A',
                'email' => 'ahmad.rizki@student.id',
                'teacher_id' => 1
            ],
            [
                'student_id' => 'STU002',
                'name' => 'Siti Nurhaliza',
                'class' => '5A', 
                'email' => 'siti.nurhaliza@student.id',
                'teacher_id' => 1
            ],
            [
                'student_id' => 'STU003',
                'name' => 'Budi Santoso',
                'class' => '5B',
                'email' => 'budi.santoso@student.id',
                'teacher_id' => 1
            ],
            [
                'student_id' => 'STU004',
                'name' => 'Dewi Lestari',
                'class' => '5B',
                'email' => 'dewi.lestari@student.id',
                'teacher_id' => 1
            ],
            [
                'student_id' => 'STU005',
                'name' => 'Andi Firmansyah',
                'class' => '5C',
                'email' => 'andi.firmansyah@student.id',
                'teacher_id' => 1
            ],
            [
                'student_id' => 'STU006',
                'name' => 'Maya Kusuma',
                'class' => '5C',
                'email' => 'maya.kusuma@student.id',
                'teacher_id' => 1
            ],
            [
                'student_id' => 'STU007',
                'name' => 'Rio Pratama',
                'class' => '6A',
                'email' => 'rio.pratama@student.id',
                'teacher_id' => 1
            ],
            [
                'student_id' => 'STU008',
                'name' => 'Indira Sari',
                'class' => '6A',
                'email' => 'indira.sari@student.id',
                'teacher_id' => 1
            ]
        ];

        foreach ($students as $student) {
            Student::create($student);
        }
    }
}
