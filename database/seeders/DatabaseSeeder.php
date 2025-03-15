<?php

namespace Database\Seeders;

use App\Models\Kelas;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\TahunPelajaran;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Kelas::create([
        //     'nama_kelas' => 1,
        // ]);

        TahunPelajaran::create([
            'tahun_awal' => 2024,
            'tahun_akhir' => 2025,
            'status' => "Aktif"
        ]);
    }
}
