<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'agency_id' => \App\Models\Agency::factory(),
            'name' => $this->faker->company(),
            'code' => $this->faker->unique()->slug(),
            'is_active' => true,
        ];
    }
}
