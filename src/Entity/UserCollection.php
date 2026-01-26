<?php

namespace App\Entity;

use App\Repository\UserCollectionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserCollectionRepository::class)]
class UserCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'collections')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userCollections')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RubiksCube $rubiksCube = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $addedAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $personalNote = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $condition = null; // Neuf, Très bon, Bon, Usé

    #[ORM\Column(nullable: true)]
    private ?float $purchasePrice = null;

    public function __construct()
    {
        $this->addedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getRubiksCube(): ?RubiksCube
    {
        return $this->rubiksCube;
    }

    public function setRubiksCube(?RubiksCube $rubiksCube): static
    {
        $this->rubiksCube = $rubiksCube;

        return $this;
    }

    public function getAddedAt(): ?\DateTimeImmutable
    {
        return $this->addedAt;
    }

    public function setAddedAt(\DateTimeImmutable $addedAt): static
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    public function getPersonalNote(): ?string
    {
        return $this->personalNote;
    }

    public function setPersonalNote(?string $personalNote): static
    {
        $this->personalNote = $personalNote;

        return $this;
    }

    public function getCondition(): ?string
    {
        return $this->condition;
    }

    public function setCondition(?string $condition): static
    {
        $this->condition = $condition;

        return $this;
    }

    public function getPurchasePrice(): ?float
    {
        return $this->purchasePrice;
    }

    public function setPurchasePrice(?float $purchasePrice): static
    {
        $this->purchasePrice = $purchasePrice;

        return $this;
    }
}
