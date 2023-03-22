<?php

namespace App\EntityListener;

use App\Entity\RecreationPark;
use App\Service\GeocodingService;
use Doctrine\ORM\Event\LifecycleEventArgs;

class RecreationParkEntityListener
{
    private $geocodingService;

    public function __construct(GeocodingService $geocodingService)
    {
        $this->geocodingService = $geocodingService;
    }

    /**
     * Event played on RecreationPark creation
     *
     * @param RecreationPark $recreationPark
     * @param LifecycleEventArgs $event
     */
    public function prePersist(RecreationPark $recreationPark, LifecycleEventArgs $event)
    {
        $coordinates = $this->geocodingService->getCoordinates($recreationPark->getAddress() . ', ' . $recreationPark->getZipcode() . ' ' . $recreationPark->getCity());
        if (count($coordinates)) {
            $recreationPark->setLatitude($coordinates['latitude']);
            $recreationPark->setLongitude($coordinates['longitude']);
        }
    }

    /**
     * Event played on RecreationPark update
     *
     * @param RecreationPark $recreationPark
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(RecreationPark $recreationPark, LifecycleEventArgs $event)
    {
        $coordinates = $this->geocodingService->getCoordinates($recreationPark->getAddress() . ', ' . $recreationPark->getZipcode() . ' ' . $recreationPark->getCity());
        if (count($coordinates)) {
            $recreationPark->setLatitude($coordinates['latitude']);
            $recreationPark->setLongitude($coordinates['longitude']);
        }
    }
}
