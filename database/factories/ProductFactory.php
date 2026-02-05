<?php

namespace Database\Factories;

use App\Models\Agency;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'agency_id' => Agency::factory(),
            'client_id' => Client::factory(),
            'name' => $this->faker->words(3, true),
            'master_sku' => $this->faker->unique()->bothify('SKU-#####'),
            'dimensions' => [
                'length' => $this->faker->numberBetween(10, 100),
                'width' => $this->faker->numberBetween(10, 100),
                'height' => $this->faker->numberBetween(10, 100),
                'unit' => 'cm',
            ],
            'weight' => [
                'value' => $this->faker->numberBetween(1, 10),
                'unit' => 'kg',
            ],
        ];
    }
}
