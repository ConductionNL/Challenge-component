<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\ChallengeRepository")
 */
class Challenge
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $catchPhrase;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateClose;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModified;

    /**
     * @ORM\Column(type="array")
     */
    private $subitters = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pitch", mappedBy="challenge")
     */
    private $pitches;

    public function __construct()
    {
        $this->pitches = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCatchPhrase(): ?string
    {
        return $this->catchPhrase;
    }

    public function setCatchPhrase(?string $catchPhrase): self
    {
        $this->catchPhrase = $catchPhrase;

        return $this;
    }

    public function getDateClose(): ?\DateTimeInterface
    {
        return $this->dateClose;
    }

    public function setDateClose(\DateTimeInterface $dateClose): self
    {
        $this->dateClose = $dateClose;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(?\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return $this->dateModified;
    }

    public function setDateModified(?\DateTimeInterface $dateModified): self
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    public function getSubitters(): ?array
    {
        return $this->subitters;
    }

    public function setSubitters(array $subitters): self
    {
        $this->subitters = $subitters;

        return $this;
    }

    /**
     * @return Collection|Pitch[]
     */
    public function getPitches(): Collection
    {
        return $this->pitches;
    }

    public function addPitch(Pitch $pitch): self
    {
        if (!$this->pitches->contains($pitch)) {
            $this->pitches[] = $pitch;
            $pitch->setChallenge($this);
        }

        return $this;
    }

    public function removePitch(Pitch $pitch): self
    {
        if ($this->pitches->contains($pitch)) {
            $this->pitches->removeElement($pitch);
            // set the owning side to null (unless already changed)
            if ($pitch->getChallenge() === $this) {
                $pitch->setChallenge(null);
            }
        }

        return $this;
    }
}
