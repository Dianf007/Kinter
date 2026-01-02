<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        $schools = School::query()->orderBy('id')->get();
        // Sekolah sudah dibuat oleh SchoolSeeder, jangan duplikat lagi
        if ($schools->isEmpty()) {
            // Jika belum ada sekolah sama sekali, maka sesuatu error
            $this->command->info('Schools not found! Running SchoolSeeder first...');
            $this->call(SchoolSeeder::class);
            $schools = School::query()->orderBy('id')->get();
        }

        // Ultraadmin (pemilik)
        $ultra = Admin::updateOrCreate(
            ['username' => 'adminUA'],
            ['password' => Hash::make('Pondokkoding!23'), 'role' => 'ultraadmin', 'school_id' => null]
        );

        // Superadmin (kepala sekolah) bisa handle > 1 sekolah
        $super1 = Admin::updateOrCreate(
            ['username' => 'superadmin1'],
            ['password' => Hash::make('Pondokkoding!23'), 'role' => 'superadmin', 'school_id' => null]
        );
        $super2 = Admin::updateOrCreate(
            ['username' => 'superadmin2'],
            ['password' => Hash::make('Pondokkoding!23'), 'role' => 'superadmin', 'school_id' => null]
        );

        // Assign sekolah ke superadmin
        $schoolIds = $schools->pluck('id')->values();
        $super1->managedSchools()->sync($schoolIds->slice(0, min(2, $schoolIds->count()))->all());
        if ($schoolIds->count() > 2) {
            $super2->managedSchools()->sync($schoolIds->slice(1, 2)->all());
        } else {
            $super2->managedSchools()->sync($schoolIds->all());
        }

        // Admin sekolah (1 admin = 1 sekolah)
        foreach ($schools as $idx => $school) {
            Admin::updateOrCreate(
                ['username' => 'admin' . ($idx + 1)],
                [
                    'password' => Hash::make('Pondokkoding!23'),
                    'role' => 'admin',
                    'school_id' => $school->id,
                ]
            );
        }

        // Pastikan ultraadmin tidak punya assignment khusus (tetap bisa manage semua via role)
        $ultra->managedSchools()->sync([]);
    }
}
