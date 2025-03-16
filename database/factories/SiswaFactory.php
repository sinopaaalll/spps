<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nis' => $this->faker->unique()->numerify('######'), // 6 digit angka unik
            'nama' => $this->faker->name(),
            'jk' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date(),
            'alamat' => $this->faker->address(),
            'nama_ibu' => $this->faker->name('female'),
            'nama_ayah' => $this->faker->name('male'),
            'nama_wali' => $this->faker->optional()->name(),
            'telp_ortu' => $this->faker->optional()->phoneNumber(),
            'status' => $this->faker->randomElement(['Aktif', 'Tidak Aktif']),
            'kelas_id' => \App\Models\Kelas::inRandomOrder()->first()->id ?? 1, // Ambil random id kelas
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
