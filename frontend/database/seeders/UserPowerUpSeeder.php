<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserPowerUpSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        $powerUp = DB::table('power_ups')->first();
        if ($user && $powerUp) {
            DB::table('user_power_ups')->insert([
                [
                    'user_id' => $user->id,
                    'power_up_id' => $powerUp->id,
                    'quantity' => 3,
                    'source' => 'bonus',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
