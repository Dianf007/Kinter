<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\School;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Room;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoAcademySeeder extends Seeder
{
    public function run(): void
    {
        $schools = School::query()->orderBy('id')->get();
        if ($schools->isEmpty()) {
            // This should not happen as SchoolSeeder runs first
            return;
        }

        // Kelas
        foreach ($schools as $school) {
            Classroom::firstOrCreate(['name' => 'Kelas 1A', 'school_id' => $school->id]);
            Classroom::firstOrCreate(['name' => 'Kelas 2A', 'school_id' => $school->id]);
            Classroom::firstOrCreate(['name' => 'Kelas 3A', 'school_id' => $school->id]);
        }

        // Ruang
        foreach ($schools as $school) {
            Room::firstOrCreate(['name' => 'Ruang 101', 'school_id' => $school->id]);
            Room::firstOrCreate(['name' => 'Ruang 102', 'school_id' => $school->id]);
        }

        // Mapel
        foreach ($schools as $school) {
            Subject::firstOrCreate(['name' => 'Matematika', 'school_id' => $school->id]);
            Subject::firstOrCreate(['name' => 'Bahasa Inggris', 'school_id' => $school->id]);
            Subject::firstOrCreate(['name' => 'Scratch', 'school_id' => $school->id]);
        }

        // Guru (table teachers untuk jadwal/courses)
        $teacherSeeds = [
            ['name' => 'Budi Santoso', 'email' => 'budi.guru@demo.id', 'phone' => '0811111111'],
            ['name' => 'Ani Wijaya', 'email' => 'ani.guru@demo.id', 'phone' => '0822222222'],
            ['name' => 'Siti Rahma', 'email' => 'siti.guru@demo.id', 'phone' => '0833333333'],
            ['name' => 'Rudi Hartono', 'email' => 'rudi.guru@demo.id', 'phone' => '0844444444'],
        ];

        foreach ($schools as $idx => $school) {
            foreach ($teacherSeeds as $t) {
                Teacher::updateOrCreate(
                    ['email' => $t['email'] . '.' . $school->id],
                    [
                        'name' => $t['name'],
                        'email' => $t['email'] . '.' . $school->id,
                        'phone' => $t['phone'],
                        'school_id' => $school->id,
                    ]
                );
            }
        }

        // Siswa (table students untuk teacher portal: butuh teacher_id -> users.id)
        // Pastikan ada minimal 1 user sebagai guru portal (teacher_id=1 biasanya ada dari DatabaseSeeder)
        $teacherUserId = DB::table('users')->orderBy('id')->value('id');
        if (!$teacherUserId) {
            // jika tidak ada user sama sekali, lewati seeding siswa
            return;
        }

        $studentSeeds = [
            ['student_id' => 'PK001', 'name' => 'Himawari no Youna', 'class' => '1A', 'email' => 'himawari@student.id'],
            ['student_id' => 'PK002', 'name' => 'Keanu Dian A.', 'class' => '1A', 'email' => 'keanu@student.id'],
            ['student_id' => 'PK003', 'name' => 'Achmad Azzam Ramadhan', 'class' => '2A', 'email' => 'azzam@student.id'],
            ['student_id' => 'PK004', 'name' => 'Dewi Lestari', 'class' => '2A', 'email' => 'dewi@student.id'],
            ['student_id' => 'PK005', 'name' => 'Andi Firmansyah', 'class' => '3A', 'email' => 'andi@student.id'],
            ['student_id' => 'PK006', 'name' => 'Maya Kusuma', 'class' => '3A', 'email' => 'maya@student.id'],
        ];

        $studentIds = [];
        foreach ($studentSeeds as $s) {
            $student = Student::updateOrCreate(
                ['student_id' => $s['student_id']],
                [
                    'name' => $s['name'],
                    'class' => $s['class'],
                    'email' => $s['email'],
                    'teacher_id' => $teacherUserId,
                ]
            );
            $studentIds[] = $student->id;
        }

        // Enroll siswa ke classroom lewat pivot student_classroom
        $allClassrooms = Classroom::query()->orderBy('id')->get();
        if ($allClassrooms->isNotEmpty() && !empty($studentIds)) {
            foreach ($studentIds as $i => $studentId) {
                $classroom = $allClassrooms[$i % $allClassrooms->count()];
                DB::table('student_classroom')->updateOrInsert(
                    ['student_id' => $studentId, 'classroom_id' => $classroom->id],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }
}
