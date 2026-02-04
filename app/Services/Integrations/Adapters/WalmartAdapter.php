<?php

namespace App\Services\Integrations\Adapters;

use App\Services\Integrations\Contracts\IntegrationAdapterInterface;
use App\Services\Integrations\DTO\StandardOrder;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class WalmartAdapter implements IntegrationAdapterInterface
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    // In real impl, we would initialize OAuth client here or Auth headers
    }

    public function fetchOrders(Carbon $since): Collection
    {
        // TODO: Implement actual Walmart API call
        // GET /v3/orders?createdStartDate=$since

        // Mock response for now to prove structure
        return collect([]);
    }

    public function syncInventory(string $externalSku, int $quantity): bool
    {
        // TODO: Implement Walmart Inventory API
        // POST /v3/inventory
        return true;
    }

    public function fulfillOrder(string $externalOrderId, string $trackingNumber, string $carrier): bool
    {
        // TODO: Implement Walmart Shipping API
        // POST /v3/orders/{id}/shipping
        return true;
    }
}