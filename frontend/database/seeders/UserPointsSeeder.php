<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserPointsSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        if ($user) {
            DB::table('user_points')->insert([
                [
                    'user_id' => $user->id,
                    'points' => 200,
                    'source' => 'initial bonus',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
