<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use MongoDB\Client as MongoClient;

class CalculatePriceFeatureTest extends TestCase
{


    public function test_calculate_price_with_invalid_api_key()
    {
        $response = $this->json('POST', '/api/calculate-price', [
            'addresses' => [
                ['country' => 'DE', 'zip' => '10115', 'city' => 'Berlin'],
                ['country' => 'DE', 'zip' => '20095', 'city' => 'Hamburg']
            ]
        ], ['Authorization' => 'Bearer invalid-api-key']);

        $response->assertStatus(401)
            ->assertJson(['error' => 'Unauthorized']);
    }

    public function test_calculate_price_with_invalid_data()
    {
        $response = $this->json('POST', '/api/calculate-price', [
            'addresses' => [
                ['country' => '', 'zip' => '', 'city' => '']
            ]
        ], ['Authorization' => 'Bearer ' . env('API_KEY')]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'addresses',
                    'addresses.0.country',
                    'addresses.0.zip',
                    'addresses.0.city'
                ]
            ]);
    }


}
