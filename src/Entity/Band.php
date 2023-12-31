<?php

namespace App\Entity;

use App\Repository\BandRepository;
use App\Validator\StartDateBeforeEndDate;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BandRepository::class)]
class Band
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $name = "";

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $origin = "";

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $city = "";

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\Range(min: 1900, max: 2100)]
    private int $startYear;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Assert\Range(min: 1900, max: 2100)]
    #[StartDateBeforeEndDate]
    private ?int $separationYear = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $founders = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $members = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $musicalCurrent = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    private string $presentation = "";

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getOrigin(): string
    {
        return $this->origin;
    }

    public function setOrigin(string $origin): static
    {
        $this->origin = $origin;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getStartYear(): int
    {
        return $this->startYear;
    }

    public function setStartYear(int $startYear): static
    {
        $this->startYear = $startYear;

        return $this;
    }

    public function getSeparationYear(): ?int
    {
        return $this->separationYear;
    }

    public function setSeparationYear(?int $separationYear): static
    {
        $this->separationYear = $separationYear;

        return $this;
    }

    public function getFounders(): ?string
    {
        return $this->founders;
    }

    public function setFounders(?string $founders): static
    {
        $this->founders = $founders;

        return $this;
    }

    public function getMembers(): ?int
    {
        return $this->members;
    }

    public function setMembers(?int $members): static
    {
        $this->members = $members;

        return $this;
    }

    public function getMusicalCurrent(): ?string
    {
        return $this->musicalCurrent;
    }

    public function setMusicalCurrent(?string $musicalCurrent): static
    {
        $this->musicalCurrent = $musicalCurrent;

        return $this;
    }

    public function getPresentation(): string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): static
    {
        $this->presentation = $presentation;

        return $this;
    }
}
