<?php

namespace App\Jobs;

use App\Models\IntegrationConnection;
use App\Services\Integrations\IntegrationManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FetchOrdersJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public IntegrationConnection $integrationConnection
    ) {}

    public function handle(IntegrationManager $manager): void
    {
        \Illuminate\Support\Facades\Log::info("SYNC_START: Fetching orders for {$this->integrationConnection->platform_type} (Connection #{$this->integrationConnection->id})");

        try {
            // 1. Resolve Adapter
            $adapter = $manager->make($this->integrationConnection);

            // 2. Determine Sync Time (Default to 30 days ago if never synced)
            $lastSync = isset($this->integrationConnection->settings['last_sync_time'])
                ? \Carbon\Carbon::parse($this->integrationConnection->settings['last_sync_time'])
                : now()->subDays(30);

            // 3. Fetch Orders
            $orders = $adapter->fetchOrders($lastSync);
            $count = count($orders);
            \Illuminate\Support\Facades\Log::info("SYNC_PROGRESS: Found {$count} orders on {$this->integrationConnection->platform_type}");

            // 4. Persist Orders
            foreach ($orders as $dto) {
                $order = \App\Models\Order::updateOrCreate(
                    [
                        'agency_id' => $this->integrationConnection->agency_id,
                        'client_id' => $this->integrationConnection->client_id,
                        'integration_connection_id' => $this->integrationConnection->id,
                        'external_order_id' => $dto->externalId,
                    ],
                    [
                        'status' => $dto->externalStatus,
                        'total_amount' => $dto->financials['total'] ?? 0,
                        'total_tax' => $dto->financials['tax'] ?? 0,
                        'total_shipping' => $dto->financials['shipping'] ?? 0,
                        'currency' => $dto->financials['currency'] ?? 'USD',
                        'customer_data' => $dto->customer,
                        'financials' => $dto->financials,
                    ]
                );

                // Persist Items
                foreach ($dto->items as $itemData) {
                    $itemDto = \App\Services\Integrations\DTO\StandardOrderItem::make($itemData);

                    $order->items()->updateOrCreate(
                        ['sku' => $itemDto->sku],
                        [
                            'name' => $itemDto->name,
                            'quantity' => $itemDto->quantity,
                            'price' => $itemDto->price,
                            'tax' => $itemDto->tax,
                            'shipping_charge' => $itemDto->shipping,
                            'tracking_number' => $itemDto->trackingNumber,
                            'carrier' => $itemDto->carrier,
                            'raw' => $itemDto->raw,
                        ]
                    );
                }
            }

            // 5. Update Last Sync Time
            $settings = $this->integrationConnection->settings ?? [];
            $settings['last_sync_time'] = now()->toIso8601String();
            $this->integrationConnection->update(['settings' => $settings]);

            \Illuminate\Support\Facades\Log::info("SYNC_COMPLETE: Successfully processed {$count} orders for Cluster: {$this->integrationConnection->client->name}");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("SYNC_ERROR: Order fetch failed for Connection #{$this->integrationConnection->id}: ".$e->getMessage());
            throw $e;
        }
    }
}
