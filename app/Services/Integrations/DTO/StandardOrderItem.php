<?php

namespace App\Services\Integrations\DTO;

class StandardOrderItem
{
    public function __construct(
        public string $sku,
        public string $name,
        public int $quantity,
        public float $price,
        public float $tax = 0.0,
        public float $shipping = 0.0,
        public ?string $trackingNumber = null,
        public ?string $carrier = null,
        public array $raw = []
    ) {}

    public static function make(array $data): self
    {
        return new self(
            $data['sku'],
            $data['name'] ?? '',
            $data['quantity'] ?? 1,
            $data['price'] ?? 0.0,
            $data['tax'] ?? 0.0,
            $data['shipping'] ?? 0.0,
            $data['trackingNumber'] ?? null,
            $data['carrier'] ?? null,
            $data['raw'] ?? []
        );
    }
}
