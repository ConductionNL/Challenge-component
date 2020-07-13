<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use App\Repository\PitchRepository;
use Cassandra\Decimal;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A pitch is used by providers to present their solution/product/service for the tender.
 *
 * @ApiResource(
 *     attributes={"pagination_items_per_page"=30},
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true},
 *     itemOperations={
 *          "get",
 *          "put",
 *          "delete",
 *          "get_change_logs"={
 *              "path"="/pitches/{id}/change_log",
 *              "method"="get",
 *              "swagger_context" = {
 *                  "summary"="Changelogs",
 *                  "description"="Gets al the change logs for this resource"
 *              }
 *          },
 *          "get_audit_trail"={
 *              "path"="/pitches/{id}/audit_trail",
 *              "method"="get",
 *              "swagger_context" = {
 *                  "summary"="Audittrail",
 *                  "description"="Gets the audit trail for this resource"
 *              }
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass=PitchRepository::class)
 * @Gedmo\Loggable(logEntryClass="Conduction\CommonGroundBundle\Entity\ChangeLog")
 *
 * @ApiFilter(SearchFilter::class, properties={
 *     "name": "partial",
 *     "description": "partial",
 *     "submitter": "partial"
 *     })
 * @ApiFilter(DateFilter::class, strategy=DateFilter::EXCLUDE_NULL)
 * @ApiFilter(RangeFilter::class, properties={"requiredBudget"})
 */
class Pitch
{
    /**
     * @var UuidInterface The UUID identifier of this pitch.
     *
     * @example e2984465-190a-4562-829e-a8cca81aa35d
     *
     * @Assert\Uuid
     * @Groups({"read"})
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var string The name of this pitch.
     *
     * @example Glorious swimming pools by SwimmingPool Enterprise
     *
     * @Assert\NotNull
     * @Assert\Length(
     *      max = 255
     * )
     * @Gedmo\Versioned
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string The description of this pitch.
     *
     * @example Pitch made by SwimmingPool Enterprise
     *
     * @Assert\Length(
     *      max = 255
     * )
     * @Gedmo\Versioned
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string The submitter of this pitch.
     *
     * @example https://cc.zuid-drecht.nl/organizations/
     *
     * @Assert\NotNull
     * @Assert\Url
     * @Gedmo\Versioned
     * @Groups({"read", "write"})
     * @ORM\Column(type="string")
     */
    private $submitter;

    /**
     * @var string The required budget for this pitch.
     *
     * @example 150000.00
     *
     * @Gedmo\Versioned
     * @Groups({"read", "write"})
     * @ORM\Column(type="decimal", precision=8, scale=2, nullable=true)
     */
    private $requiredBudget;

    /**
     * @var array The document(s) of this tender.
     *
     * @Gedmo\Versioned
     * @Groups({"read", "write"})
     * @ORM\Column(type="array", nullable=true)
     */
    private $documents = [];

    /**
     * @Gedmo\Versioned
     * @Assert\DateTime
     * @Groups({"read", "write"})
     * @ORM\Column(type="datetime")
     */
    private $dateSubmitted;

    /**
     * @Groups({"read","write"})
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity=Tender::class, inversedBy="pitches")
     * @ORM\JoinColumn(nullable=true)
     */
    private $tender;

    /**
     * @Groups({"read","write"})
     * @MaxDepth(1)
     * @ORM\ManyToMany(targetEntity=PitchStage::class)
     */
    private $stages;

    /**
     * @Groups({"read","write"})
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity=PitchStage::class)
     */
    private $currentStage;

    /**
     * @Groups({"read","write"})
     * @MaxDepth(1)
     * @ORM\OneToMany(targetEntity=Proposal::class, mappedBy="pitch")
     */
    private $proposals;

    /**
     * @var Datetime The moment this pitch was created
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var Datetime The moment this pitch was last updated
     *
     * @Groups({"read"})
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified;

    public function __construct()
    {
        $this->stages = new ArrayCollection();
        $this->proposals = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): self
    {
        $this->id = $id;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSubmitter(): ?string
    {
        return $this->submitter;
    }

    public function setSubmitter(string $submitter): self
    {
        $this->submitter = $submitter;

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
