<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Client;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseInventorySeeder extends Seeder
{
    public function run(): void
    {
        $agency = Agency::first() ?? Agency::factory()->create(['name' => 'Demo Agency']);

        $warehouse = Warehouse::firstOrCreate(
        ['location_code' => 'MDC-01'],
        [
            'agency_id' => $agency->id,
            'name' => 'Main Distribution Center',
        ]
        );

        $client = Client::firstOrCreate(
        ['agency_id' => $agency->id],
        ['name' => 'Default Client', 'code' => 'DEF-001']
        );

        $product = Product::firstOrCreate(
        ['master_sku' => 'QP-X1'],
        [
            'agency_id' => $agency->id,
            'client_id' => $client->id,
            'name' => 'Quantum Processor X1',
        ]
        );

        Inventory::updateOrCreate(
        [
            'agency_id' => $agency->id,
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
        ],
        ['quantity_on_hand' => 500]
        );
    }
}