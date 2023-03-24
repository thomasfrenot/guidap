<?php

namespace App\EntityListener;

use App\Entity\Activity;
use App\Entity\RecreationPark;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ActivityEntityListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * Event played on RecreationPark creation
     *
     * @param RecreationPark $recreationPark
     * @param LifecycleEventArgs $event
     */
    public function prePersist(Activity $activity, LifecycleEventArgs $event)
    {
        $activity->computeSlug($this->slugger);
    }

    /**
     * Event played on RecreationPark update
     *
     * @param RecreationPark $recreationPark
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(Activity $activity, LifecycleEventArgs $event)
    {
        $activity->computeSlug($this->slugger);
    }
}
