<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeocodingService
{
    const ENDPOINT = 'mapbox.places';

    private $apiGeocoding;
    private $client;

    public function __construct(
        $apiGeocoding,
        HttpClientInterface $client
    )
    {
        //todo: meilleurs moyen de récupérer l'env API_GEOCODING ??
        $this->apiGeocoding = $apiGeocoding;
        $this->client = $client;
    }

    /**
     * @param string $search address search for geocoding
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getCoordinates($search) :array
    {
        $geocodingUrl = 'https://api.mapbox.com/geocoding/v5/'.self::ENDPOINT.'/'.urlencode($search).'.json?access_token='.$this->apiGeocoding;
        $client = HttpClient::create();
        $response = $client->request('GET', $geocodingUrl);

        $content = json_decode($response->getContent());
        if (200 === $response->getStatusCode() && count($content->features)) {
            if (count($content->features[0]->geometry->coordinates)) {
                return [
                    'latitude' => $content->features[0]->geometry->coordinates[0],
                    'longitude' => $content->features[0]->geometry->coordinates[1],
                ];
            }
        }

        return [];
    }
}
