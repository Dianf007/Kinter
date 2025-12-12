<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\School;
class SubjectSeeder extends Seeder
{
    public function run()
    {
        $school1 = School::first();
        $school2 = School::skip(1)->first();
        Subject::create(['name' => 'Matematika', 'school_id' => $school1->id]);
        Subject::create(['name' => 'Bahasa Inggris', 'school_id' => $school1->id]);
        Subject::create(['name' => 'IPA', 'school_id' => $school2->id]);
    }
}
