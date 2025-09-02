<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le champs nom est requis")]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: "le champs nom doit contenir au moins {{limit}} caractères.",
        maxMessage: "le champs nom ne doit pas dépasser {{limit}} caractères."
    )]
    private ?string $name = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank(message: "Le champs nom est requis")]
    #[Assert\Length(
        min: 2,
        max: 10,
        minMessage: "le champs code postale doit contenir au moins {{limit}} caractères.",
        maxMessage: "le champs code postale ne doit pas dépasser {{limit}} caractères."
    )]
    private ?string $postalCode = null;

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

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }
}
