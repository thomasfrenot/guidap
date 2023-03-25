<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\RecreationParkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RecreationParkRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class RecreationPark
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
     * @ORM\ManyToMany(targetEntity=Activity::class, inversedBy="recreationParks" ,cascade={"persist"})
     * @Groups("recreation_park:read")
     */
    private $activities;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"recreation_park:read", "recreation_park:create"})
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups("recreation_park:read")
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"recreation_park:read", "recreation_park:create"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"recreation_park:read", "recreation_park:create"})
     * @Assert\NotBlank()
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"recreation_park:read", "recreation_park:create"})
     * @Assert\NotBlank()
     */
    private $city;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"recreation_park:read", "recreation_park:create"})
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Length(5)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"recreation_park:read", "recreation_park:create"})
     * @Assert\NotBlank()
     * @Assert\Url()
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("recreation_park:read")
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("recreation_park:read")
     */
    private $longitude;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities[] = $activity;
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        $this->activities->removeElement($activity);

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(int $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
}
