<?php
namespace App\Services;

use App\Repositories\CityRepository;

class AddressValidationService
{
    private $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function validate(array $addresses): bool
    {
        foreach ($addresses as $address) {
            if (!$this->cityRepository->exists($address)) {
                return false;
            }
        }
        return true;
    }
}
