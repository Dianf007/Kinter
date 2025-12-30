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
            // 2 Siswa statis (anak Anda)
            Student::create([
                'school_id' => $school->id,
                'name' => 'Himawari no youna',
                'email' => 'youna@pondokkoding.com',
                'student_id' => $school->id . '0001',
                'student_code' => 'bungaMatahari1',
                'class' => '2',
                'teacher_id' => 1,
                'avatar' => null
            ]);

            Student::create([
                'school_id' => $school->id,
                'name' => 'Achmad Azzam Ramadhan',
                'email' => 'azzam@pondokkoding.com',
                'student_id' => $school->id . '0002',
                'student_code' => 'PK20AAR',
                'class' => '5',
                'teacher_id' => 1,
                'avatar' => null
            ]);

            
            Student::create([
                'school_id' => $school->id,
                'name' => 'Daffa Arfama Lesmatano', // Ubah sesuai nama anak
                'email' => 'daffa@pondokkoding.com',
                'student_id' => $school->id . '0003',
                'student_code' => 'PK33DAL',
                'class' => '8',
                'teacher_id' => 1,
                'avatar' => null
            ]);
            
            Student::create([
                'school_id' => $school->id,
                'name' => 'Hanif Aqil Muzakki', // Ubah sesuai nama anak
                'email' => 'aqil@pondokkoding.com',
                'student_id' => $school->id . '0004',
                'student_code' => 'PK39HAM',
                'class' => '8',
                'teacher_id' => 1,
                'avatar' => null
            ]);

            Student::create([
                'school_id' => $school->id,
                'name' => 'Rifqi Akmal Syarifuddin', // Ubah sesuai nama anak
                'email' => 'akmal@pondokkoding.com',
                'student_id' => $school->id . '0005',
                'student_code' => 'PK40RAS',
                'class' => '8',
                'teacher_id' => 1,
                'avatar' => null
            ]);

            Student::create([
                'school_id' => $school->id,
                'name' => 'Mezaluna', // Ubah sesuai nama anak
                'email' => 'luna@pondokkoding.com',
                'student_id' => $school->id . '0006',
                'student_code' => 'PK41MSA',
                'class' => '5',
                'teacher_id' => 1,
                'avatar' => null
            ]); 

            Student::create([
                'school_id' => $school->id,
                'name' => 'Heaven Aa Koesindra Handy', // Ubah sesuai nama anak
                'email' => 'heaven@pondokkoding.com',
                'student_id' => $school->id . '0007',
                'student_code' => 'PK46HKH',
                'class' => '5',
                'teacher_id' => 1,
                'avatar' => null
            ]); 

            Student::create([
                'school_id' => $school->id,
                'name' => 'vidya almaika anidya agda', // Ubah sesuai nama anak
                'email' => 'vidya@pondokkoding.com',
                'student_id' => $school->id . '0008',
                'student_code' => 'PK47VAA',
                'class' => '5',
                'teacher_id' => 1,
                'avatar' => null
            ]);

            // 10 Siswa random
            for ($i = 9; $i <= 12; $i++) {
                Student::create([
                    'school_id' => $school->id,
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'student_id' => $school->id . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'class' => $faker->randomElement(['1A','1B','2A','2B','3A','3B','4A','4B','5A','5B','6A','6B']),
                    'teacher_id' => 1,
                    'avatar' => null
                ]);
            }
        }
    }
}
