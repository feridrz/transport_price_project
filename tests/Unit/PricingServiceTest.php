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
        $distance = 100;

        $vehicleRepository = Mockery::mock(VehicleRepository::class);
        $vehicleRepository->shouldReceive('getAll')
            ->andReturn([
                ['number' => 0, 'cost_km' => 1, 'minimum' => 50],
                ['number' => 1, 'cost_km' => 2, 'minimum' => 200]
            ]);

        $pricingService = new PricingService($vehicleRepository);

        $prices = $pricingService->calculatePrices($distance);

        $this->assertEquals([
            ['vehicle_type' => 0, 'price' => 100],
            ['vehicle_type' => 1, 'price' => 200]
        ], $prices);
    }
}
