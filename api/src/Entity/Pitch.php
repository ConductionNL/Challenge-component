<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PitchRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=PitchRepository::class)
 */
class Pitch
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="array")
     */
    private $submitters = [];

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2, nullable=true)
     */
    private $requiredBudget;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $documents = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateSubmitted;

    /**
     * @ORM\ManyToOne(targetEntity=Tender::class, inversedBy="pitches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tender;

    /**
     * @ORM\ManyToMany(targetEntity=PitchStage::class)
     */
    private $stages;

    /**
     * @ORM\ManyToOne(targetEntity=PitchStage::class)
     */
    private $currentStage;

    /**
     * @ORM\OneToMany(targetEntity=Proposal::class, mappedBy="pitch")
     */
    private $proposals;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    public function __construct()
    {
        $this->stages = new ArrayCollection();
        $this->proposals = new ArrayCollection();
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

    public function getSubmitters(): ?array
    {
        return $this->submitters;
    }

    public function setSubmitters(array $submitters): self
    {
        $this->submitters = $submitters;

        return $this;
    }

    public function getRequiredBudget(): ?string
    {
        return $this->requiredBudget;
    }

    public function setRequiredBudget(?string $requiredBudget): self
    {
        $this->requiredBudget = $requiredBudget;

        return $this;
    }

    public function getDocuments(): ?array
    {
        return $this->documents;
    }

    public function setDocuments(?array $documents): self
    {
        $this->documents = $documents;

        return $this;
    }

    public function getDateSubmitted(): ?\DateTimeInterface
    {
        return $this->dateSubmitted;
    }

    public function setDateSubmitted(\DateTimeInterface $dateSubmitted): self
    {
        $this->dateSubmitted = $dateSubmitted;

        return $this;
    }

    public function getTender(): ?Tender
    {
        return $this->tender;
    }

    public function setTender(?Tender $tender): self
    {
        $this->tender = $tender;

        return $this;
    }

    /**
     * @return Collection|PitchStage[]
     */
    public function getStages(): Collection
    {
        return $this->stages;
    }

    public function addStage(PitchStage $stage): self
    {
        if (!$this->stages->contains($stage)) {
            $this->stages[] = $stage;
        }

        return $this;
    }

    public function removeStage(PitchStage $stage): self
    {
        if ($this->stages->contains($stage)) {
            $this->stages->removeElement($stage);
        }

        return $this;
    }

    public function getCurrentStage(): ?PitchStage
    {
        return $this->currentStage;
    }

    public function setCurrentStage(?PitchStage $currentStage): self
    {
        $this->currentStage = $currentStage;

        return $this;
    }

    /**
     * @return Collection|Proposal[]
     */
    public function getProposals(): Collection
    {
        return $this->proposals;
    }

    public function addProposal(Proposal $proposal): self
    {
        if (!$this->proposals->contains($proposal)) {
            $this->proposals[] = $proposal;
            $proposal->setPitch($this);
        }

        return $this;
    }

    public function removeProposal(Proposal $proposal): self
    {
        if ($this->proposals->contains($proposal)) {
            $this->proposals->removeElement($proposal);
            // set the owning side to null (unless already changed)
            if ($proposal->getPitch() === $this) {
                $proposal->setPitch(null);
            }
        }

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }
}
