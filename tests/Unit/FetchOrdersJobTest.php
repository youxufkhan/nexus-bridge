<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Jobs\FetchOrdersJob;
use App\Models\IntegrationConnection;
use App\Models\Order;
use App\Services\Integrations\IntegrationManager;
use App\Services\Integrations\Contracts\IntegrationAdapterInterface;
use App\Services\Integrations\DTO\StandardOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Carbon\Carbon;

class FetchOrdersJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_fetches_and_persists_orders()
    {
        // 1. Setup Data
        $connection = IntegrationConnection::factory()->create([
            'platform_type' => 'walmart',
            'settings' => ['last_sync_time' => '2023-01-01 00:00:00'],
        ]);

        // 2. Mock Adapter
        $dto = new StandardOrder(
            'ORD-123',
            'shipped',
            now(),
        ['name' => 'John Doe'],
        ['address' => '123 Main St'],
        [],
        ['total' => 100.00]
            );

        $adapterMock = Mockery::mock(IntegrationAdapterInterface::class);
        $adapterMock->shouldReceive('fetchOrders')
            ->once()
            ->andReturn(collect([$dto]));

        // 3. Mock Manager
        $managerMock = Mockery::mock(IntegrationManager::class);
        $managerMock->shouldReceive('make')
            ->with(Mockery::on(fn($arg) => $arg->id === $connection->id))
            ->once()
            ->andReturn($adapterMock);

        // 4. Run Job
        $job = new FetchOrdersJob($connection);
        $job->handle($managerMock);

        // 5. Assertions
        $this->assertDatabaseHas('orders', [
            'external_order_id' => 'ORD-123',
            'agency_id' => $connection->agency_id,
            'client_id' => $connection->client_id,
            'status' => 'shipped',
        ]);

        $connection->refresh();
        $this->assertNotEquals('2023-01-01 00:00:00', $connection->settings['last_sync_time']);
    }
}