<?php

namespace Database\Seeders;

use App\Models\JenisPembayaran;
use App\Models\Kelas;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Pos;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(1)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // for ($i = 1; $i < 7; $i++) {
        //     Kelas::create([
        //         'nama_kelas' => $i,
        //     ]);
        // }

        // TahunAjaran::create([
        //     'tahun_awal' => 2024,
        //     'tahun_akhir' => 2025,
        //     'status' => "Aktif"
        // ]);

        // Siswa::factory(100)->create();

        // Pos::factory(2)->create();

        // JenisPembayaran::factory(2)->create();
    }
}
