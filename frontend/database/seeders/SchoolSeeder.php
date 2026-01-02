<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\School;
class SchoolSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah sekolah sudah ada
        if (School::count() > 0) {
            return;
        }
        
        School::create([
            'name' => 'Pondok Koding Mojokerto',
            'address' => "Perumahan D'Garden City Blok N.05, Sidonganti, Ngingasrembyong, Kec. Sooko, Kabupaten Mojokerto, Jawa Timur",
            'phone' => '089667668888',
        ]);
        School::create([
            'name' => 'Pondok Koding Surabaya',
            'address' => 'Jl. Melati No. 2',
            'phone' => '081234567890',
        ]);
    }
}
