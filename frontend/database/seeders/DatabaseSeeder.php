<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->call([
            CategorySeeder::class,
            TagSeeder::class,
            PortfolioSeeder::class,
            PostSeeder::class,
            QuizSeeder::class,
            PowerUpSeeder::class,
            BadgeSeeder::class,
            UserPointsSeeder::class,
            UserPowerUpSeeder::class,
            UserBadgeSeeder::class,
            LeaderboardSeeder::class,
        ]);
    }
}
