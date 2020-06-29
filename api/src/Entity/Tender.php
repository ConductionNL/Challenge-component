<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TenderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TenderRepository::class)
 */
class Tender
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
     * @ORM\ManyToMany(targetEntity=Group::class)
     */
    private $tenderGroups;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2, nullable=true)
     */
    private $budget;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $documents = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $kind;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $selectionCritera;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $catchPhrase;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateClose;

    /**
     * @ORM\ManyToMany(targetEntity=TenderStage::class)
     */
    private $stages;

    /**
     * @ORM\ManyToOne(targetEntity=TenderStage::class)
     */
    private $currentStage;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="tender")
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity=Entry::class, mappedBy="tender", orphanRemoval=true)
     */
    private $entries;

    /**
     * @ORM\OneToMany(targetEntity=Pitch::class, mappedBy="tender")
     */
    private $pitches;

    /**
     * @ORM\OneToMany(targetEntity=Proposal::class, mappedBy="tender")
     */
    private $proposals;

    /**
     * @ORM\OneToOne(targetEntity=Deal::class, inversedBy="tender", cascade={"persist", "remove"})
     */
    private $deal;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modified;

    public function __construct()
    {
        $this->stages = new ArrayCollection();
        $this->entries = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->tenderGroups = new ArrayCollection();
        $this->pitches = new ArrayCollection();
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

    /**
     * @return Collection|Group[]
     */
    public function getTenderGroups(): Collection
    {
        return $this->tenderGroups;
    }

    public function addTenderGroup(Group $tenderGroup): self
    {
        if (!$this->tenderGroups->contains($tenderGroup)) {
            $this->tenderGroups[] = $tenderGroup;
        }

        return $this;
    }

    public function removeTenderGroup(Group $tenderGroup): self
    {
        if ($this->tenderGroups->contains($tenderGroup)) {
            $this->tenderGroups->removeElement($tenderGroup);
        }

        return $this;
    }

    public function getBudget(): ?string
    {
        return $this->budget;
    }

    public function setBudget(?string $budget): self
    {
        $this->budget = $budget;

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

    public function getKind(): ?string
    {
        return $this->kind;
    }

    public function setKind(?string $kind): self
    {
        $this->kind = $kind;

        return $this;
    }

    public function getSelectionCritera(): ?string
    {
        return $this->selectionCritera;
    }

    public function setSelectionCritera(?string $selectionCritera): self
    {
        $this->selectionCritera = $selectionCritera;

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

    public function setDateClose(?\DateTimeInterface $dateClose): self
    {
        $this->dateClose = $dateClose;

        return $this;
    }

    /**
     * @return Collection|TenderStage[]
     */
    public function getStages(): Collection
    {
        return $this->stages;
    }

    public function addStage(TenderStage $stage): self
    {
        if (!$this->stages->contains($stage)) {
            $this->stages[] = $stage;
        }

        return $this;
    }

    public function removeStage(TenderStage $stage): self
    {
        if ($this->stages->contains($stage)) {
            $this->stages->removeElement($stage);
        }

        return $this;
    }

    public function getCurrentStage(): ?TenderStage
    {
        return $this->currentStage;
    }

    public function setCurrentStage(?TenderStage $currentStage): self
    {
        $this->currentStage = $currentStage;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setTender($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getTender() === $this) {
                $question->setTender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Entry[]
     */
    public function getEntries(): Collection
    {
        return $this->entries;
    }

    public function addEntry(Entry $entry): self
    {
        if (!$this->entries->contains($entry)) {
            $this->entries[] = $entry;
            $entry->setTender($this);
        }

        return $this;
    }

    public function removeEntry(Entry $entry): self
    {
        if ($this->entries->contains($entry)) {
            $this->entries->removeElement($entry);
            // set the owning side to null (unless already changed)
            if ($entry->getTender() === $this) {
                $entry->setTender(null);
            }
        }

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
            $pitch->setTender($this);
        }

        return $this;
    }

    public function removePitch(Pitch $pitch): self
    {
        if ($this->pitches->contains($pitch)) {
            $this->pitches->removeElement($pitch);
            // set the owning side to null (unless already changed)
            if ($pitch->getTender() === $this) {
                $pitch->setTender(null);
            }
        }

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
            $proposal->setTender($this);
        }

        return $this;
    }

    public function removeProposal(Proposal $proposal): self
    {
        if ($this->proposals->contains($proposal)) {
            $this->proposals->removeElement($proposal);
            // set the owning side to null (unless already changed)
            if ($proposal->getTender() === $this) {
                $proposal->setTender(null);
            }
        }

        return $this;
    }

    public function getDeal(): ?Deal
    {
        return $this->deal;
    }

    public function setDeal(?Deal $deal): self
    {
        $this->deal = $deal;

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

    public function getModified(): ?\DateTimeInterface
    {
        return $this->modified;
    }

    public function setModified(\DateTimeInterface $modified): self
    {
        $this->modified = $modified;

        return $this;
    }
}
