<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Classroom;
use App\Models\School;
class ClassroomSeeder extends Seeder
{
    public function run()
    {
        $school1 = School::first();
        $school2 = School::skip(1)->first();
        Classroom::create(['name' => 'Kelas 1A', 'school_id' => $school1->id]);
        Classroom::create(['name' => 'Kelas 2A', 'school_id' => $school1->id]);
        Classroom::create(['name' => 'Kelas 1B', 'school_id' => $school2->id]);
    }
}
