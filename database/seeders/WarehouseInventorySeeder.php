<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agency;
use App\Models\User;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Inventory;
use App\Models\Client;

class WarehouseInventorySeeder extends Seeder
{
    public function run(): void
    {
        $agency = Agency::first() ?? Agency::factory()->create(['name' => 'Demo Agency']);
        $warehouse = Warehouse::factory()->create([
            'agency_id' => $agency->id,
            'name' => 'Main Distribution Center',
            'location_code' => 'MDC-01'
        ]);

        $client = Client::first() ?? Client::factory()->create(['agency_id' => $agency->id]);

        $product = Product::factory()->create([
            'agency_id' => $agency->id,
            'client_id' => $client->id,
            'name' => 'Quantum Processor X1',
            'master_sku' => 'QP-X1'
        ]);

        Inventory::factory()->create([
            'agency_id' => $agency->id,
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'quantity_on_hand' => 500
        ]);
    }
}