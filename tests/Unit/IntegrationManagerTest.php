<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\IntegrationConnection;
use App\Services\Integrations\IntegrationManager;
use App\Services\Integrations\Adapters\WalmartAdapter;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IntegrationManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_resolves_walmart_adapter()
    {
        $connection = IntegrationConnection::factory()->create([
            'platform_type' => 'walmart',
            'credentials' => ['api_key' => '123'],
        ]);

        $manager = new IntegrationManager();
        $adapter = $manager->make($connection);

        $this->assertInstanceOf(WalmartAdapter::class , $adapter);
    }

    public function test_it_throws_exception_for_unknown_platform()
    {
        $this->expectException(\Exception::class);

        $connection = IntegrationConnection::factory()->create([
            'platform_type' => 'unknown_platform',
        ]);

        $manager = new IntegrationManager();
        $manager->make($connection);
    }
}