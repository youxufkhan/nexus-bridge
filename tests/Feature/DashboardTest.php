<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\Client;
use App\Models\IntegrationConnection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_loads_clients_page()
    {
        $agency = Agency::factory()->create();
        $user = User::factory()->create(['agency_id' => $agency->id]);
        $client = Client::factory()->create(['agency_id' => $agency->id]);

        $response = $this->actingAs($user)->get(route('dashboard.clients.index'));

        $response->assertStatus(200);
        $response->assertSee($client->name);
    }

    public function test_it_loads_orders_page()
    {
        $agency = Agency::factory()->create();
        $user = User::factory()->create(['agency_id' => $agency->id]);
        $client = Client::factory()->create(['agency_id' => $agency->id]);
        $connection = IntegrationConnection::factory()->create([
            'agency_id' => $agency->id,
            'client_id' => $client->id,
        ]);

        $order = \App\Models\Order::create([
            'agency_id' => $agency->id,
            'client_id' => $client->id,
            'integration_connection_id' => $connection->id,
            'external_order_id' => 'ORD-TEST',
            'status' => 'pending',
            'financials' => ['total' => 100],
            'customer_data' => ['name' => 'Test User'],
        ]);

        $response = $this->actingAs($user)->get(route('dashboard.orders.index'));

        $response->assertStatus(200);
        $response->assertSee('ORD-TEST');
    }
}
