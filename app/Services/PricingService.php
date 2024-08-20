<?php

namespace App\Services;

use App\Repositories\VehicleRepository;

class PricingService
{
    private $vehicleRepository;

    public function __construct(VehicleRepository $vehicleRepository)
    {
        $this->vehicleRepository = $vehicleRepository;
    }

    public function calculatePrices(float $distance): array
    {
        $vehicleTypes = $this->vehicleRepository->getAll();
        $prices = [];

        foreach ($vehicleTypes as $vehicle) {
            $price = $distance * $vehicle['cost_km'];
            if ($price < $vehicle['minimum']) {
                $price = $vehicle['minimum'];
            }

            $prices[] = [
                'vehicle_type' => $vehicle['number'],
                'price' => $price,
            ];
        }

        return $prices;
    }
}
