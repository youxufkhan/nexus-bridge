<?php

namespace App\Jobs;

use App\Models\IntegrationConnection;
use App\Models\PlatformProductMap;
use App\Models\Product;
use App\Services\Integrations\IntegrationManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class FetchProductsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public IntegrationConnection $integrationConnection) {}

    public function handle(IntegrationManager $manager): void
    {
        Log::info("SYNC_START: Fetching catalog for {$this->integrationConnection->platform_type} (Connection #{$this->integrationConnection->id})");

        try {
            $adapter = $manager->make($this->integrationConnection);
            $products = $adapter->fetchProducts();
            $count = count($products);

            Log::info("SYNC_PROGRESS: Found {$count} products on {$this->integrationConnection->platform_type}");

            foreach ($products as $dto) {
                // 1. Update or Create Master Product
                $product = Product::updateOrCreate(
                    [
                        'agency_id' => $this->integrationConnection->agency_id,
                        'client_id' => $this->integrationConnection->client_id,
                        'master_sku' => $dto->externalSku,
                    ],
                    [
                        'name' => $dto->name,
                        'upc' => $dto->upc,
                        'gtin' => $dto->gtin,
                        'base_price' => $dto->price,
                        'base_currency' => $dto->currency,
                        'status' => $dto->status,
                        'category' => $dto->category,
                        'dimensions' => $dto->dimensions,
                        'weight' => $dto->weight,
                    ]
                );

                // 2. Update or Create Platform Mapping
                PlatformProductMap::updateOrCreate(
                    [
                        'integration_connection_id' => $this->integrationConnection->id,
                        'external_sku' => $dto->externalSku,
                    ],
                    [
                        'agency_id' => $this->integrationConnection->agency_id,
                        'product_id' => $product->id,
                        'external_id' => $dto->externalId,
                    ]
                );
            }

            // Update telemetry
            $settings = $this->integrationConnection->settings ?? [];
            $settings['last_product_sync'] = now()->toIso8601String();
            $this->integrationConnection->update(['settings' => $settings]);

            Log::info("SYNC_COMPLETE: Successfully processed {$count} products for Cluster: {$this->integrationConnection->client->name}");

        } catch (\Exception $e) {
            Log::error("SYNC_ERROR: Product fetch failed for Connection #{$this->integrationConnection->id}: ".$e->getMessage());
            throw $e;
        }
    }
}
