<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class DistanceService
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function calculateTotalDistance(array $addresses): float
    {
        $cacheKey = 'distance_' . md5(json_encode($addresses));

        return Cache::remember($cacheKey, 3600, function() use ($addresses) {
            $totalDistance = 0;

            for ($i = 0; $i < count($addresses) - 1; $i++) {
                $origin = "{$addresses[$i]['city']},{$addresses[$i]['country']}";
                $destination = "{$addresses[$i + 1]['city']},{$addresses[$i + 1]['country']}";

                try {
                    $response = $this->client->get("https://maps.googleapis.com/maps/api/directions/json", [
                        'query' => [
                            'origin' => $origin,
                            'destination' => $destination,
                            'key' => env('GOOGLE_MAPS_API_KEY'),
                        ],
                    ]);

                    $data = json_decode($response->getBody(), true);
                    if (!isset($data['routes'][0]['legs'][0]['distance']['value'])) {
                        throw new \Exception("Invalid response from Google Maps API");
                    }
                    $distanceMeters = $data['routes'][0]['legs'][0]['distance']['value'];
                    $totalDistance += $distanceMeters / 1000; //meters to km
                } catch (\Exception $e) {
                    \Log::error("Failed to calculate distance: " . $e->getMessage());
                    throw new \RuntimeException("Distance calculation failed");
                }
            }

            return $totalDistance;
        });
    }

}
