<?php

namespace App\Services\Integrations\DTO;

use Carbon\Carbon;

class StandardOrder
{
    public function __construct(
        public string $externalId,
        public string $externalStatus,
        public Carbon $placedAt,
        public array $customer, // ['name' => string, 'email' => string, 'phone' => ?string]
        public array $shippingAddress, // ['address1', 'city', 'state', 'zip', 'country']
        /** @var \App\Services\Integrations\DTO\StandardOrderItem[] */
        public array $items,
        public array $financials // ['total' => float, 'currency' => string]
    ) {}

    public static function make(array $data): self
    {
        return new self(
            $data['externalId'],
            $data['externalStatus'],
            $data['placedAt'],
            $data['customer'],
            $data['shippingAddress'],
            $data['items'] ?? [],
            $data['financials']
        );
    }
}
