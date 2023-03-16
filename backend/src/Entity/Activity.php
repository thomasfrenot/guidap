<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActivityRepository::class)
 */
class Activity
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=RecreationPark::class, mappedBy="activities")
     */
    private $recreationParks;

    public function __construct()
    {
        $this->recreationParks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, RecreationPark>
     */
    public function getRecreationParks(): Collection
    {
        return $this->recreationParks;
    }

    public function addRecreationPark(RecreationPark $recreationPark): self
    {
        if (!$this->recreationParks->contains($recreationPark)) {
            $this->recreationParks[] = $recreationPark;
            $recreationPark->addActivity($this);
        }

        return $this;
    }

    public function removeRecreationPark(RecreationPark $recreationPark): self
    {
        if ($this->recreationParks->removeElement($recreationPark)) {
            $recreationPark->removeActivity($this);
        }

        return $this;
    }
}
