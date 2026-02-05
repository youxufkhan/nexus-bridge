<?php

namespace App\Services\Integrations\DTO;

class StandardProduct
{
    public function __construct(
        public string $externalId,
        public string $externalSku,
        public string $name,
        public ?string $upc = null,
        public ?string $gtin = null,
        public ?float $price = null,
        public string $currency = 'USD',
        public string $status = 'active',
        public ?string $category = null,
        public array $dimensions = [], // ['length' => float, 'width' => float, 'height' => float, 'unit' => string]
        public array $weight = [], // ['value' => float, 'unit' => string]
        public array $raw = [] // Original payload for debugging
    ) {}

    public static function make(array $data): self
    {
        return new self(
            $data['externalId'],
            $data['externalSku'],
            $data['name'],
            $data['upc'] ?? null,
            $data['gtin'] ?? null,
            (float) ($data['price'] ?? 0),
            $data['currency'] ?? 'USD',
            $data['status'] ?? 'active',
            $data['category'] ?? null,
            $data['dimensions'] ?? [],
            $data['weight'] ?? [],
            $data['raw'] ?? []
        );
    }
}
