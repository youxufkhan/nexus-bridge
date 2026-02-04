<?php

namespace App\Services\Integrations;

use App\Models\IntegrationConnection;
use App\Services\Integrations\Contracts\IntegrationAdapterInterface;
use App\Services\Integrations\Adapters\WalmartAdapter;
use Exception;

class IntegrationManager
{
    public function make(IntegrationConnection $connection): IntegrationAdapterInterface
    {
        return match ($connection->platform_type) {
                'walmart' => new WalmartAdapter($connection->credentials ?? []),
                default => throw new Exception("Platform [{$connection->platform_type}] is not supported."),
            };
    }
}