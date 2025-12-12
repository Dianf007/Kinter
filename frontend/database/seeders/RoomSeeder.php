<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\School;
class RoomSeeder extends Seeder
{
    public function run()
    {
        $school1 = School::first();
        $school2 = School::skip(1)->first();
        Room::create(['name' => 'Ruang 101', 'school_id' => $school1->id]);
        Room::create(['name' => 'Ruang 102', 'school_id' => $school1->id]);
        Room::create(['name' => 'Ruang 201', 'school_id' => $school2->id]);
    }
}
