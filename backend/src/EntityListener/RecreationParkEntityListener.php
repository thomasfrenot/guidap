<?php

namespace App\EntityListener;

use App\Entity\RecreationPark;
use App\Service\GeocodingService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecreationParkEntityListener
{
    private $geocodingService;
    private $slugger;

    public function __construct(GeocodingService $geocodingService, SluggerInterface $slugger)
    {
        $this->geocodingService = $geocodingService;
        $this->slugger = $slugger;
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
        $recreationPark->computeSlug($this->slugger);
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
        $recreationPark->computeSlug($this->slugger);
    }
}
