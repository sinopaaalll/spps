<?php

namespace Database\Factories;

use App\Models\Pos;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JenisPembayaran>
 */
class JenisPembayaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tipe' => $this->faker->randomElement(['bulanan', 'bebas']),
            'pos_id' => Pos::inRandomOrder()->first()->id ?? Pos::factory(),
            'tahun_ajaran_id' => TahunAjaran::inRandomOrder()->first()->id ?? TahunAjaran::factory(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
