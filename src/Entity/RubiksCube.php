<?php

namespace App\Entity;

use App\Repository\RubiksCubeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RubiksCubeRepository::class)]
class RubiksCube
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $type = null; // 2x2, 3x3, 4x4, Pyraminx, Megaminx, etc.

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $brand = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $releaseYear = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $difficulty = null; // Débutant, Intermédiaire, Expert

    #[ORM\OneToMany(mappedBy: 'rubiksCube', targetEntity: UserCollection::class, orphanRemoval: true)]
    private Collection $userCollections;

    #[ORM\OneToMany(mappedBy: 'rubiksCube', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'rubiksCube', targetEntity: Rating::class, orphanRemoval: true)]
    private Collection $ratings;

    public function __construct()
    {
        $this->userCollections = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getReleaseYear(): ?int
    {
        return $this->releaseYear;
    }

    public function setReleaseYear(?int $releaseYear): static
    {
        $this->releaseYear = $releaseYear;

        return $this;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(?string $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getUserCollections(): Collection
    {
        return $this->userCollections;
    }

    public function addUserCollection(UserCollection $userCollection): static
    {
        if (!$this->userCollections->contains($userCollection)) {
            $this->userCollections->add($userCollection);
            $userCollection->setRubiksCube($this);
        }

        return $this;
    }

    public function removeUserCollection(UserCollection $userCollection): static
    {
        if ($this->userCollections->removeElement($userCollection)) {
            if ($userCollection->getRubiksCube() === $this) {
                $userCollection->setRubiksCube(null);
            }
        }

        return $this;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setRubiksCube($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            if ($comment->getRubiksCube() === $this) {
                $comment->setRubiksCube(null);
            }
        }

        return $this;
    }

    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): static
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setRubiksCube($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): static
    {
        if ($this->ratings->removeElement($rating)) {
            if ($rating->getRubiksCube() === $this) {
                $rating->setRubiksCube(null);
            }
        }

        return $this;
    }

    public function getAverageRating(): float
    {
        if ($this->ratings->isEmpty()) {
            return 0;
        }

        $sum = 0;
        foreach ($this->ratings as $rating) {
            $sum += $rating->getRating();
        }

        return round($sum / $this->ratings->count(), 1);
    }
}
