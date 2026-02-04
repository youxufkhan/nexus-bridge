<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IntegrationConnection>
 */
class IntegrationConnectionFactory extends Factory
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
            'client_id' => \App\Models\Client::factory(),
            'platform_type' => 'walmart',
            'credentials' => ['api_key' => 'test'],
            'settings' => [],
        ];
    }
}