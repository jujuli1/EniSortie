<?php

namespace App\Service;

use App\Entity\City;

class LocationApiService
{
    private readonly string $baseUrl ;
    public function __construct() {
        $this->baseUrl = "https://api-adresse.data.gouv.fr/search/";
    }
    /**
     * Search locations by city entity
     */
    public function searchLocationsByCityEntity(City $city): array
    {
        // request to the api
        $url = $this->baseUrl . "?q=" . urlencode($city->getName()) . "&postcode=" . $city->getPostalCode() . "&limit=10";

        $data = json_decode(file_get_contents($url), true);
        $results = [];

        if (!empty($data['features'])) {
            foreach ($data['features'] as $feature) {
                $results[] = [
                    'name' => $feature['properties']['label'], // "10 rue de Paris, Rennes"
                    'street' => $feature['properties']['name'] ?? null,
                    'latitude' => $feature['geometry']['coordinates'][1],
                    'longitude' => $feature['geometry']['coordinates'][0],
                ];
            }
        }

        return $results;
    }
}
