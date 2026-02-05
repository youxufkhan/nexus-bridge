<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_inventory(): void
    {
        $agency = \App\Models\Agency::factory()->create();
        $user = \App\Models\User::factory()->create(['agency_id' => $agency->id]);
        $client = \App\Models\Client::factory()->create(['agency_id' => $agency->id]);
        $product = \App\Models\Product::factory()->create([
            'agency_id' => $agency->id,
            'client_id' => $client->id,
            'name' => 'Test Product'
        ]);

        $response = $this->actingAs($user)->get(route('dashboard.inventories.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Product');
    }

    public function test_can_adjust_inventory(): void
    {
        $agency = \App\Models\Agency::factory()->create();
        $user = \App\Models\User::factory()->create(['agency_id' => $agency->id]);
        $warehouse = \App\Models\Warehouse::factory()->create(['agency_id' => $agency->id]);
        $client = \App\Models\Client::factory()->create(['agency_id' => $agency->id]);
        $product = \App\Models\Product::factory()->create([
            'agency_id' => $agency->id,
            'client_id' => $client->id
        ]);

        $response = $this->actingAs($user)->post(route('dashboard.inventories.update', $product), [
            'adjustments' => [
                [
                    'warehouse_id' => $warehouse->id,
                    'quantity_on_hand' => 150
                ]
            ]
        ]);

        $response->assertRedirect(route('dashboard.inventories.index'));
        $this->assertDatabaseHas('inventories', [
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'quantity_on_hand' => 150
        ]);
    }
}