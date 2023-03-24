<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @ORM\Entity(repositoryClass=ActivityRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Activity
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("recreation_park:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("recreation_park:read")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups("recreation_park:read")
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity=RecreationPark::class, mappedBy="activities")
     */
    private $recreationParks;

    public function __construct()
    {
        $this->recreationParks = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function computeSlug(SluggerInterface $slugger)
    {
        if (!$this->slug || '-' === $this->slug) {
            $this->slug = (string) $slugger->slug((string) $this)->lower();
        }
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
