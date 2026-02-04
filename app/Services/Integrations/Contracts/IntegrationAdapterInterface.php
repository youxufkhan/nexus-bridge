<?php

namespace App\Services\Integrations\Contracts;

use Illuminate\Support\Collection;
use Carbon\Carbon;

interface IntegrationAdapterInterface
{
    /**
     * Fetch orders created or updated since the given timestamp.
     *
     * @param Carbon $since
     * @return Collection<int, \App\Services\Integrations\DTO\StandardOrder>
     */
    public function fetchOrders(Carbon $since): Collection;

    /**
     * Sync inventory for a specific SKU.
     *
     * @param string $externalSku
     * @param int $quantity
     * @return bool
     */
    public function syncInventory(string $externalSku, int $quantity): bool;

    /**
     * Update fulfillment status on the external platform.
     *
     * @param string $externalOrderId
     * @param string $trackingNumber
     * @param string $carrier
     * @return bool
     */
    public function fulfillOrder(string $externalOrderId, string $trackingNumber, string $carrier): bool;
}