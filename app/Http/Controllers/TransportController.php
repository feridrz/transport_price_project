<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculatePriceRequest;
use App\Services\AddressValidationService;
use App\Services\DistanceService;
use App\Services\PricingService;
use Illuminate\Http\JsonResponse;

class TransportController extends Controller
{

    public function calculatePrice(
        CalculatePriceRequest $request,
        AddressValidationService $addressValidationService,
        DistanceService $distanceService,
        PricingService $pricingService
    ): JsonResponse {
        $addresses = $request->input('addresses');

        // Validate against the database
        if (!$addressValidationService->validate($addresses)) {
            return $this->errorResponse('One or more addresses not found in database', 404);
        }

        try {
            // total distance
            $distance = $distanceService->calculateTotalDistance($addresses);
        } catch (\RuntimeException $e) {
            return $this->errorResponse('Distance calculation failed', 500);
        }
        // prices
        $prices = $pricingService->calculatePrices($distance);

        return $this->successResponse($prices);
    }


}
