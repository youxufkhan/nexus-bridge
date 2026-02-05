<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WarehouseTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_warehouses(): void
    {
        $agency = \App\Models\Agency::factory()->create();
        $user = \App\Models\User::factory()->create(['agency_id' => $agency->id]);
        $warehouse = \App\Models\Warehouse::factory()->create(['agency_id' => $agency->id]);

        $response = $this->actingAs($user)->get(route('dashboard.warehouses.index'));

        $response->assertStatus(200);
        $response->assertSee($warehouse->name);
    }

    public function test_can_create_warehouse(): void
    {
        $agency = \App\Models\Agency::factory()->create();
        $user = \App\Models\User::factory()->create(['agency_id' => $agency->id]);

        $response = $this->actingAs($user)->post(route('dashboard.warehouses.store'), [
            'name' => 'New Hub',
            'location_code' => 'NH-01',
            'address' => ['city' => 'New York']
        ]);

        $response->assertRedirect(route('dashboard.warehouses.index'));
        $this->assertDatabaseHas('warehouses', ['name' => 'New Hub', 'agency_id' => $agency->id]);
    }

    public function test_cannot_access_other_agency_warehouse(): void
    {
        $agency1 = \App\Models\Agency::factory()->create();
        $user1 = \App\Models\User::factory()->create(['agency_id' => $agency1->id]);

        $agency2 = \App\Models\Agency::factory()->create();
        $warehouse2 = \App\Models\Warehouse::factory()->create(['agency_id' => $agency2->id]);

        $response = $this->actingAs($user1)->get(route('dashboard.warehouses.edit', $warehouse2));

        $response->assertStatus(403);
    }
}