<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\GuestReport;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GuestReport>
 */
class GuestReportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GuestReport::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'contact' => $this->faker->phoneNumber(),
            'location' => $this->faker->address(), // Kunci 'location' ini harus cocok dengan nama kolom di migrasi Anda
            'description' => $this->faker->paragraph(2),
            'photo' => null,
            'report_status' => 'Menunggu Verifikasi',
            'fire_status' => 'Sedang Terjadi',
        ];
    }
}