<?php

namespace App\EntityListener;

use App\Entity\RecreationPark;
use App\Service\GeocodingService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RecreationParkEntityListener
{
    private $geocodingService;

    public function __construct(GeocodingService $geocodingService)
    {
        $this->geocodingService = $geocodingService;
    }

    public function prePersist(RecreationPark $recreationPark, LifecycleEventArgs $event)
    {

        $coordinates = $this->geocodingService->getCoordinates($recreationPark->getAddress() . ', ' . $recreationPark->getZipcode() . ' ' . $recreationPark->getCity());
        if (count($coordinates)) {
            $recreationPark->setLatitude($coordinates['latitude']);
            $recreationPark->setLongitude($coordinates['longitude']);
        }
    }
}
