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
                'student_id' => 'PK001',
                'name' => 'Himawari no Youna',
                'class' => '2',
                'email' => 'himawari@student.id',
                'teacher_id' => 1
            ],
            [
                'student_id' => 'PK020',
                'name' => 'Achmad Azzam Ramadhan',
                'class' => '5', 
                'email' => 'azzam@pondokkoding.com',
                'teacher_id' => 1
            ],
            [
                'student_id' => 'PK002',
                'name' => 'Keanu Dian A.',
                'class' => '2',
                'email' => 'keanu@pondokkoding.com',
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
