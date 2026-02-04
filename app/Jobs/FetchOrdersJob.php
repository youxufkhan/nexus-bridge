<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\IntegrationConnection;
use App\Services\Integrations\IntegrationManager;

class FetchOrdersJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public IntegrationConnection $integrationConnection
        )
    {
    }

    public function handle(IntegrationManager $manager): void
    {
        // 1. Resolve Adapter
        $adapter = $manager->make($this->integrationConnection);

        // 2. Determine Sync Time (Default to 30 days ago if never synced)
        $lastSync = isset($this->integrationConnection->settings['last_sync_time'])
            ?\Carbon\Carbon::parse($this->integrationConnection->settings['last_sync_time'])
            : now()->subDays(30);

        // 3. Fetch Orders
        $orders = $adapter->fetchOrders($lastSync);

        // 4. Persist Orders
        foreach ($orders as $dto) {
            \App\Models\Order::updateOrCreate(
            [
                'agency_id' => $this->integrationConnection->agency_id,
                'client_id' => $this->integrationConnection->client_id,
                'integration_connection_id' => $this->integrationConnection->id,
                'external_order_id' => $dto->externalId,
            ],
            [
                'status' => $dto->externalStatus,
                'customer_data' => $dto->customer,
                'financials' => $dto->financials,
                // TODO: Map items properly in a separate table or JSON column
            ]
            );
        }

        // 5. Update Last Sync Time
        $settings = $this->integrationConnection->settings ?? [];
        $settings['last_sync_time'] = now()->toIso8601String();
        $this->integrationConnection->update(['settings' => $settings]);
    }
}