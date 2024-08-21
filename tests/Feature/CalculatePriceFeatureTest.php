<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class CalculatePriceFeatureTest extends TestCase
{
    protected $validApiKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validApiKey = env('API_KEY');
    }

    public function test_calculate_price_with_invalid_api_key()
    {
        $response = $this->makePostRequestWithApiKey('invalid-api-key', $this->validAddresses());

        $response->assertStatus(401)
            ->assertJson(['error' => 'Unauthorized']);
    }

    public function test_successful_response_for_valid_request()
    {
        $addresses = $this->validAddresses();

        Http::fake([
            env('GOOGLE_MAPS_URL') . '/*' => Http::response([
                'routes' => [
                    [
                        'legs' => [
                            [
                                'distance' => ['value' => 15000],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->makePostRequestWithApiKey($this->validApiKey, $addresses);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'vehicle_type',
                        'price',
                    ],
                ],
            ]);
    }

    private function makePostRequestWithApiKey(string $apiKey, array $addresses)
    {
        return $this->json('POST', '/api/calculate-price', [
            'addresses' => $addresses
        ], ['Authorization' => 'Bearer ' . $apiKey]);
    }

    private function validAddresses(): array
    {
        return [
            ['country' => 'DE', 'zip' => '10115', 'city' => 'Berlin'],
            ['country' => 'DE', 'zip' => '20095', 'city' => 'Hamburg'],
        ];
    }
}
