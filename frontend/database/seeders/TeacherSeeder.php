<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\School;
class TeacherSeeder extends Seeder
{
    public function run()
    {
        $school1 = School::first();
        $school2 = School::skip(1)->first();
        Teacher::create(['name' => 'Budi', 'email' => 'budi@alpha.sch.id', 'phone' => '0811111111', 'school_id' => $school1->id]);
        Teacher::create(['name' => 'Ani', 'email' => 'ani@beta.sch.id', 'phone' => '0822222222', 'school_id' => $school2->id]);
    }
}
