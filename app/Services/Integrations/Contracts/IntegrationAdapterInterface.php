<?php

namespace App\Services\Integrations\Contracts;

use Carbon\Carbon;
use Illuminate\Support\Collection;

interface IntegrationAdapterInterface
{
    /**
     * Fetch orders created or updated since the given timestamp.
     *
     * @param  Carbon  $since
     * @return Collection<int, \App\Services\Integrations\DTO\StandardOrder>
     */
    /**
     * Fetch products from the external platform.
     *
     * @return Collection<int, \App\Services\Integrations\DTO\StandardProduct>
     */
    public function fetchProducts(): Collection;

    public function fetchOrders(Carbon $since): Collection;

    /**
     * Sync inventory for a specific SKU.
     */
    public function syncInventory(string $externalSku, int $quantity): bool;

    /**
     * Update fulfillment status on the external platform.
     */
    public function fulfillOrder(string $externalOrderId, string $trackingNumber, string $carrier): bool;
}
