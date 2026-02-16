<?php

namespace App\Console\Commands;

use App\Models\Student;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestStudent extends Command
{
    protected $signature = 'app:create-student
                            {--name= : Student name}
                            {--email= : Student email}
                            {--password= : Student password (default: password123)}
                            {--student-id= : Student ID (auto-generated if not provided)}
                            {--class= : Class (default: Grade 5A)}
                            {--gender= : Gender (L/P, default: L)}
                            {--birth-date= : Birth date (YYYY-MM-DD, default: 2015-06-15)}
                            {--phone= : Phone number}
                            {--address= : Address}
                            {--test : Create test students if no arguments provided}';
    
    protected $description = 'Create a student account';

    public function handle()
    {
        // If test flag, create test students
        if ($this->option('test')) {
            return $this->createTestStudents();
        }

        // Interactive or argument-based creation
        $name = $this->option('name') ?? $this->ask('Student name');
        $email = $this->option('email') ?? $this->ask('Student email');
        $password = $this->option('password') ?? 'password123';
        $studentId = $this->option('student-id');
        $class = $this->option('class') ?? 'Grade 5A';
        $gender = $this->option('gender') ?? 'L';
        $birthDate = $this->option('birth-date') ?? '2015-06-15';
        $phone = $this->option('phone') ?? '';
        $address = $this->option('address') ?? '';

        // Check if student already exists
        if (Student::where('email', $email)->exists()) {
            $this->error("Student with email {$email} already exists!");
            return 1;
        }

        // Auto-generate student ID if not provided
        if (!$studentId) {
            $lastStudent = Student::orderBy('id', 'desc')->first();
            $number = str_pad(($lastStudent?->id ?? 0) + 1, 3, '0', STR_PAD_LEFT);
            $studentId = 'STU' . $number;
        }

        // Create student
        $student = Student::create([
            'name' => $name,
            'email' => $email,
            'student_id' => $studentId,
            'student_code' => strtoupper(substr($name, 0, 2)) . $studentId,
            'password' => Hash::make($password),
            'class' => $class,
            'gender' => $gender,
            'birth_date' => $birthDate,
            'phone' => $phone,
            'address' => $address,
            'teacher_id' => 1, // Default teacher
        ]);

        $this->info('✓ Student created successfully!');
        $this->table(
            ['Name', 'Email', 'Password', 'Student ID'],
            [
                [$name, $email, $password, $studentId],
            ]
        );
        
        return 0;
    }

    private function createTestStudents()
    {
        // Check if students already exist
        $testStudents = [
            ['John Doe', 'student@example.com'],
            ['Jane Smith', 'jane@example.com'],
        ];

        $created = [];
        foreach ($testStudents as [$name, $email]) {
            if (Student::where('email', $email)->exists()) {
                $this->line("⚠ {$email} already exists");
                continue;
            }

            Student::create([
                'name' => $name,
                'email' => $email,
                'student_id' => 'STU' . str_pad(Student::count() + 1, 3, '0', STR_PAD_LEFT),
                'student_code' => strtoupper(substr($name, 0, 2)) . str_pad(Student::count() + 1, 3, '0', STR_PAD_LEFT),
                'password' => Hash::make('password123'),
                'class' => 'Grade 5A',
                'gender' => $name === 'Jane Smith' ? 'P' : 'L',
                'birth_date' => $name === 'John Doe' ? '2015-06-15' : '2015-08-20',
                'phone' => '081234567890',
                'address' => 'School Address',
                'teacher_id' => 1,
            ]);

            $created[] = [$name, $email, 'password123'];
        }

        if (count($created) > 0) {
            $this->info('✓ Test students created successfully!');
            $this->table(['Name', 'Email', 'Password'], $created);
        } else {
            $this->info('All test students already exist');
        }

        return 0;
    }
}
