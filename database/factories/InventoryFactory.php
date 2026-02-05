<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
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
            'product_id' => \App\Models\Product::factory(),
            'warehouse_id' => \App\Models\Warehouse::factory(),
            'quantity_on_hand' => $this->faker->numberBetween(0, 1000),
            'quantity_reserved' => $this->faker->numberBetween(0, 100),
        ];
    }
}