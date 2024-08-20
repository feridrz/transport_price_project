<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\DistanceService;
use GuzzleHttp\Client;
use Mockery;

class DistanceServiceTest extends TestCase
{
    public function test_calculate_total_distance()
    {
        $addresses = [
            ['city' => 'Berlin', 'country' => 'DE'],
            ['city' => 'Hamburg', 'country' => 'DE']
        ];

        // Mock the Guzzle Client
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('get')
            ->andReturn(new \GuzzleHttp\Psr7\Response(200, [], json_encode([
                'routes' => [
                    [
                        'legs' => [
                            ['distance' => ['value' => 100000]] // 100 km
                        ]
                    ]
                ]
            ])));

        // Create the DistanceService with the mocked Client
        $distanceService = new DistanceService($client);

        // Assert that the calculated total distance is correct
        $this->assertEquals(100, $distanceService->calculateTotalDistance($addresses));
    }
}
