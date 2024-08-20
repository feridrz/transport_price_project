<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\PricingService;
use Mockery;
use App\Repositories\VehicleRepository;

class PricingServiceTest extends TestCase
{
    public function test_calculate_prices()
    {
        $distance = 100; // 100 km

        // Mock the VehicleRepository
        $vehicleRepository = Mockery::mock(VehicleRepository::class);
        $vehicleRepository->shouldReceive('getAll')
            ->andReturn([
                ['number' => 0, 'cost_km' => 1, 'minimum' => 50],
                ['number' => 1, 'cost_km' => 2, 'minimum' => 200]
            ]);

        // Create the PricingService with the mocked VehicleRepository
        $pricingService = new PricingService($vehicleRepository);

        // Calculate prices
        $prices = $pricingService->calculatePrices($distance);

        // Assert that the prices are calculated correctly
        $this->assertEquals([
            ['vehicle_type' => 0, 'price' => 100], // 100 km * 1 cost/km
            ['vehicle_type' => 1, 'price' => 200]  // 100 km * 2 cost/km but minimum price is 200
        ], $prices);
    }
}
