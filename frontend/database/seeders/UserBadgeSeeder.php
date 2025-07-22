<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserBadgeSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        $badge = DB::table('badges')->first();
        if ($user && $badge) {
            DB::table('user_badges')->insert([
                [
                    'user_id' => $user->id,
                    'badge_id' => $badge->id,
                    'awarded_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
