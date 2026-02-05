<?php

namespace App\Services\Integrations\Adapters;

use App\Models\IntegrationConnection;
use App\Services\Integrations\Contracts\IntegrationAdapterInterface;
use App\Services\Integrations\DTO\StandardOrder;
use App\Services\Integrations\DTO\StandardProduct;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WalmartAdapter implements IntegrationAdapterInterface
{
    private string $baseUrl = 'https://marketplace.walmartapis.com/v3';

    public function __construct(protected IntegrationConnection $connection) {}

    /**
     * Get or refresh OAuth token.
     */
    protected function getToken(bool $forceRefresh = false): string
    {
        $credentials = $this->connection->credentials;

        if (! $forceRefresh && ! empty($credentials['access_token']) && ! empty($credentials['token_expires_at'])) {
            if (Carbon::now()->lt(Carbon::parse($credentials['token_expires_at']))) {
                return $credentials['access_token'];
            }
        }

        $clientId = $credentials['client_id'] ?? null;
        $clientSecret = $credentials['client_secret'] ?? null;

        if (! $clientId || ! $clientSecret) {
            throw new \Exception('Walmart credentials (client_id or client_secret) are missing.');
        }

        $correlationId = (string) Str::uuid();

        $response = Http::withHeaders([
            'Authorization' => 'Basic '.base64_encode($clientId.':'.$clientSecret),
            'WM_QOS.CORRELATION_ID' => $correlationId,
            'WM_SVC.NAME' => 'Walmart Marketplace',
            'Accept' => 'application/json',
        ])->asForm()->post($this->baseUrl.'/token', [
            'grant_type' => 'client_credentials',
        ]);

        if ($response->failed()) {
            Log::error('Walmart Token Error', ['status' => $response->status(), 'body' => $response->json()]);
            throw new \Exception('Failed to retrieve Walmart access token.');
        }

        $data = $response->json();
        $accessToken = $data['access_token'];

        $expiresIn = $data['expires_in'] ?? 900;

        $credentials['access_token'] = $accessToken;
        $credentials['token_expires_at'] = Carbon::now()->addSeconds($expiresIn - 60)->toDateTimeString();

        $this->connection->update(['credentials' => $credentials]);

        return $accessToken;
    }

    public function fetchProducts(): Collection
    {
        $accessToken = $this->getToken();
        $cursor = '*';
        /** @var Collection<int, StandardProduct> $allProducts */
        $allProducts = collect();
        $pages = 0;

        do {
            $correlationId = (string) Str::uuid();
            $response = Http::withHeaders([
                'WM_SEC.ACCESS_TOKEN' => $accessToken,
                'WM_QOS.CORRELATION_ID' => $correlationId,
                'WM_SVC.NAME' => 'Walmart Marketplace',
                'Accept' => 'application/json',
            ])->get($this->baseUrl.'/items', [
                'limit' => 200,
                'nextCursor' => $cursor,
            ]);

            if ($response->status() === 401) {
                $accessToken = $this->getToken(true);

                continue; // Retry current page
            }

            if ($response->failed()) {
                break;
            }

            $items = $response->json('ItemResponse') ?? [];
            if (empty($items)) {
                break;
            }

            foreach ($items as $item) {
                if (empty($item['sku'])) {
                    continue;
                }

                $allProducts->push(StandardProduct::make([
                    'externalId' => $item['wpid'] ?? $item['sku'],
                    'externalSku' => $item['sku'],
                    'name' => $item['productName'] ?? 'Walmart Product '.$item['sku'],
                    'upc' => $item['upc'] ?? null,
                    'gtin' => $item['gtin'] ?? null,
                    'price' => $item['price']['amount'] ?? 0,
                    'currency' => $item['price']['currency'] ?? 'USD',
                    'status' => $item['publishedStatus'] ?? 'active',
                    'category' => $item['productType'] ?? null,
                    'raw' => $item,
                ]));
            }

            $pages++;
            $cursor = $response->json('nextCursor');
        } while ($cursor && $cursor !== '*' && $pages < 100);

        return $allProducts;
    }

    public function fetchOrders(Carbon $since): Collection
    {
        $accessToken = $this->getToken();
        $correlationId = (string) Str::uuid();

        $response = Http::withHeaders([
            'WM_SEC.ACCESS_TOKEN' => $accessToken,
            'WM_QOS.CORRELATION_ID' => $correlationId,
            'WM_SVC.NAME' => 'Walmart Marketplace',
            'Accept' => 'application/json',
        ])->get($this->baseUrl.'/orders', [
            'createdStartDate' => $since->toIso8601String(),
            'limit' => 200,
        ]);

        if ($response->status() === 401) {
            $accessToken = $this->getToken(true);
            $response = Http::withHeaders([
                'WM_SEC.ACCESS_TOKEN' => $accessToken,
                'WM_QOS.CORRELATION_ID' => $correlationId,
                'WM_SVC.NAME' => 'Walmart Marketplace',
                'Accept' => 'application/json',
            ])->get($this->baseUrl.'/orders', [
                'createdStartDate' => $since->toIso8601String(),
                'limit' => 200,
            ]);
        }

        if ($response->failed()) {
            Log::error('Walmart Orders Sync Error', ['status' => $response->status(), 'body' => $response->json()]);

            return collect([]);
        }

        $rawOrders = $response->json('list.elements.order') ?? [];

        return collect($rawOrders)->map(function ($order) {
            $shippingInfo = $order['shippingInfo'] ?? [];
            $postalAddress = $shippingInfo['postalAddress'] ?? [];

            // Aggregate line items and calculate financials manually since summary is missing
            $totalAmount = 0;
            $totalTax = 0;
            $totalShipping = 0;

            $items = array_map(function ($line) use (&$totalAmount, &$totalTax, &$totalShipping) {
                $item = $line['item'] ?? [];
                $charges = $line['charges']['charge'] ?? [];

                $price = 0;
                $tax = 0;
                $shipping = 0;

                foreach ($charges as $charge) {
                    $amount = (float) ($charge['chargeAmount']['amount'] ?? 0);
                    $chargeTax = (float) ($charge['tax']['taxAmount']['amount'] ?? ($charge['taxAndOtherFees']['taxAndOtherFeesAmount']['amount'] ?? 0));

                    if (($charge['chargeType'] ?? '') === 'PRODUCT') {
                        $price += $amount;
                    } elseif (($charge['chargeType'] ?? '') === 'SHIPPING') {
                        $shipping += $amount;
                    }

                    $tax += $chargeTax;
                }

                $qty = (int) ($line['orderLineQuantity']['amount'] ?? 1);
                $totalAmount += ($price * $qty) + ($shipping * $qty) + ($tax * $qty);
                $totalTax += ($tax * $qty);
                $totalShipping += ($shipping * $qty);

                return [
                    'sku' => $item['sku'] ?? 'UNKNOWN',
                    'name' => $item['productName'] ?? '',
                    'quantity' => $qty,
                    'price' => $price,
                    'tax' => $tax,
                    'shipping' => $shipping,
                    'trackingNumber' => $line['orderLineStatuses']['orderLineStatus'][0]['trackingInfo']['trackingNumber'] ?? null,
                    'carrier' => $line['orderLineStatuses']['orderLineStatus'][0]['trackingInfo']['carrierName']['carrier'] ?? ($line['orderLineStatuses']['orderLineStatus'][0]['trackingInfo']['carrierName']['otherCarrier'] ?? null),
                    'raw' => $line,
                ];
            }, $order['orderLines']['orderLine'] ?? []);

            return StandardOrder::make([
                'externalId' => $order['purchaseOrderId'],
                'externalStatus' => $order['orderStatus'] ?? 'OPEN',
                'placedAt' => isset($order['orderDate']) ? Carbon::createFromTimestampMs($order['orderDate']) : now(),
                'customer' => [
                    'name' => $postalAddress['name'] ?? 'Unknown',
                    'email' => $order['customerEmailId'] ?? null,
                    'phone' => $shippingInfo['phone'] ?? null,
                ],
                'shippingAddress' => [
                    'address1' => $postalAddress['address1'] ?? '',
                    'city' => $postalAddress['city'] ?? '',
                    'state' => $postalAddress['state'] ?? '',
                    'zip' => $postalAddress['postalCode'] ?? '',
                    'country' => $postalAddress['country'] ?? 'USA',
                ],
                'items' => $items,
                'financials' => [
                    'total' => (float) $totalAmount,
                    'currency' => 'USD',
                    'tax' => (float) $totalTax,
                    'shipping' => (float) $totalShipping,
                ],
            ]);
        });
    }

    public function syncInventory(string $externalSku, int $quantity): bool
    {
        // Future implementation
        return true;
    }

    public function fulfillOrder(string $externalOrderId, string $trackingNumber, string $carrier): bool
    {
        // Future implementation
        return true;
    }
}
