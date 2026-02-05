<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Client;
use App\Models\IntegrationConnection;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesAndAgenciesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Sample Agency
        $agency = Agency::updateOrCreate(['name' => 'Nexus Demo HQ']);

        // 2. Create Superadmin
        User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Global Admin',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'agency_id' => null,
            ]
        );

        // 3. Create Agency Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Agency Manager',
                'password' => Hash::make('password'),
                'role' => 'agency_admin',
                'agency_id' => $agency->id,
            ]
        );

        // 4. Create Sample Portfolio Data
        $client = Client::updateOrCreate(
            ['code' => 'ACME-001'],
            ['name' => 'Acme Global Logistics', 'agency_id' => $agency->id]
        );

        $connection = IntegrationConnection::updateOrCreate(
            ['client_id' => $client->id, 'platform_type' => 'walmart'],
            [
                'agency_id' => $agency->id,
                'credentials' => ['api_key' => 'verified_token_xyz'],
                'settings' => ['last_sync_time' => now()],
            ]
        );

        Product::updateOrCreate(
            ['master_sku' => 'NEXUS-PRD-001'],
            [
                'agency_id' => $agency->id,
                'client_id' => $client->id,
                'name' => 'Enterprise Ingestion Node',
                'dimensions' => ['h' => 10, 'w' => 5],
                'weight' => ['val' => 2, 'unit' => 'kg'],
            ]
        );

        Order::updateOrCreate(
            ['external_order_id' => 'ORD-NEXUS-2025-01'],
            [
                'agency_id' => $agency->id,
                'client_id' => $client->id,
                'integration_connection_id' => $connection->id,
                'status' => 'pending',
                'financials' => ['total' => 450.00, 'currency' => 'USD'],
                'customer_data' => ['name' => 'John Wick', 'email' => 'john@continental.com'],
            ]
        );
    }
}
