<?php

namespace App\Repositories;

use MongoDB\Client;

class CityRepository
{
    private $collection;

    public function __construct(Client $client)
    {
        $this->collection = $client->interview->cities;
    }

    public function exists(array $address): bool
    {
        $city = $this->collection->findOne([
            'name' => $address['city'],
            'zipCode' => $address['zip'],
            'country' => $address['country'],
        ]);

        return $city !== null;
    }
}
