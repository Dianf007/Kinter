<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::updateOrCreate([
            'username' => 'adminUA',
        ], [
            'password' => Hash::make('Pondokkoding!23'),
            'role' => 'ultraadmin',
        ]);
    }
}
