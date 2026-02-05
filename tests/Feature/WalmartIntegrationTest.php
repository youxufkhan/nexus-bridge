<?php

namespace Tests\Feature;

use App\Jobs\FetchOrdersJob;
use App\Models\Agency;
use App\Models\Client;
use App\Models\IntegrationConnection;
use App\Services\Integrations\Adapters\WalmartAdapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WalmartIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
    }

    public function test_walmart_adapter_can_get_token(): void
    {
        $agency = Agency::factory()->create();
        $connection = IntegrationConnection::factory()->create([
            'agency_id' => $agency->id,
            'platform_type' => 'walmart',
            'credentials' => [
                'client_id' => 'test_id',
                'client_secret' => 'test_secret',
            ],
        ]);

        Http::fake([
            'https://marketplace.walmartapis.com/v3/token' => Http::response([
                'access_token' => 'adapter_token',
                'expires_in' => 900,
            ], 200),
        ]);

        $adapter = new WalmartAdapter($connection);
        // We need to use reflection or make getToken public for testing,
        // but fetchOrders calls it internally.

        Http::fake([
            'https://marketplace.walmartapis.com/v3/orders*' => Http::response([
                'list' => ['elements' => ['order' => []]],
            ], 200),
        ]);

        $adapter->fetchOrders(now()->subDay());

        $this->assertEquals('adapter_token', $connection->fresh()->credentials['access_token']);
    }

    public function test_fetch_orders_job_persists_walmart_orders(): void
    {
        $agency = Agency::factory()->create();
        $client = Client::factory()->create(['agency_id' => $agency->id]);
        $connection = IntegrationConnection::factory()->create([
            'agency_id' => $agency->id,
            'client_id' => $client->id,
            'platform_type' => 'walmart',
            'credentials' => [
                'access_token' => 'test_key',
                'token_expires_at' => now()->addHour()->toDateTimeString(),
            ],
        ]);

        Http::fake([
            'https://marketplace.walmartapis.com/v3/orders*' => Http::response([
                'list' => [
                    'elements' => [
                        'order' => [
                            [
                                'purchaseOrderId' => 'REF-WAL-001',
                                'orderStatus' => 'COMPLETED',
                                'orderDate' => now()->toIso8601String(),
                                'customerInfo' => [
                                    'email' => 'customer@example.com',
                                    'shippingInfo' => [
                                        'postalAddress' => [
                                            'name' => 'Jane Client',
                                            'address1' => '123 Tech Lane',
                                            'city' => 'San Jose',
                                            'state' => 'CA',
                                            'postalCode' => '95131',
                                            'country' => 'US',
                                        ],
                                    ],
                                ],
                                'orderLines' => [
                                    'orderLine' => [
                                        ['sku' => 'SKU-GENERIC', 'productName' => 'Refactored Item'],
                                    ],
                                ],
                                'orderSumary' => [
                                    'totalAmount' => ['amount' => 150.00, 'currency' => 'USD'],
                                ],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        // Dispatch the generic job
        FetchOrdersJob::dispatch($connection);

        $this->assertDatabaseHas('orders', [
            'external_order_id' => 'REF-WAL-001',
            'status' => 'COMPLETED',
            'total_amount' => 150.00,
            'currency' => 'USD',
        ]);

        $order = \App\Models\Order::where('external_order_id', 'REF-WAL-001')->first();
        $this->assertEquals(150.00, $order->total_amount);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'sku' => 'SKU-GENERIC',
            'name' => 'Refactored Item',
            'price' => 150.00,
            'quantity' => 1,
        ]);
    }

    public function test_adapter_can_sync_products(): void
    {
        $agency = Agency::factory()->create();
        $client = Client::factory()->create(['agency_id' => $agency->id]);
        $connection = IntegrationConnection::factory()->create([
            'agency_id' => $agency->id,
            'client_id' => $client->id,
            'platform_type' => 'walmart',
            'credentials' => [
                'access_token' => 'test',
                'token_expires_at' => now()->addHour()->toDateTimeString(),
            ],
        ]);

        Http::fake([
            'https://marketplace.walmartapis.com/v3/items*' => Http::response([
                'ItemResponse' => [
                    [
                        'sku' => 'WAL-ADAPT-SKU',
                        'productName' => 'Adapter Verified Product',
                        'wpid' => 'WP-ADAPT',
                    ],
                ],
                'nextCursor' => null,
            ], 200),
        ]);

        \App\Jobs\FetchProductsJob::dispatch($connection);

        $this->assertDatabaseHas('products', ['master_sku' => 'WAL-ADAPT-SKU']);
    }
}
