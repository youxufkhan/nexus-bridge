<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Agency;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use App\Jobs\FetchOrdersJob;

class IntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_integration_connection()
    {
        Queue::fake();

        $agency = Agency::factory()->create();
        $user = User::factory()->create(['agency_id' => $agency->id]);
        $client = Client::factory()->create(['agency_id' => $agency->id]);

        $response = $this->actingAs($user)->post(route('dashboard.integrations.store'), [
            'client_id' => $client->id,
            'platform_type' => 'walmart',
            'api_key' => 'secret-key-123',
            'client_id_key' => 'client-id-456',
        ]);

        $response->assertRedirect(route('dashboard.integrations.index'));

        $this->assertDatabaseHas('integration_connections', [
            'agency_id' => $agency->id,
            'client_id' => $client->id,
            'platform_type' => 'walmart',
        ]);

        Queue::assertPushed(FetchOrdersJob::class);
    }
}