<?php

namespace App\Entity;

use App\Repository\CarteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarteRepository::class)]
class Carte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique:true)]
    #[assert\Length(min:12,minMessage:"Au minimum 12 caractères")]
    private ?string $numC = null;

    #[ORM\Column]
    private ?float $plafond = null;

    #[ORM\ManyToOne(inversedBy: 'num_c')]
    private ?Account $compte = null;

    public function getCompte(): ?Account
    {
        return $this->compte;
    }

    public function setCompte(?Account $compte): void
    {
        $this->compte = $compte;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumC(): ?string
    {
        return $this->numC;
    }

    public function setNumC(string $numC): static
    {
        $this->numC = $numC;

        return $this;
    }

    public function getPlafond(): ?float
    {
        return $this->plafond;
    }

    public function setPlafond(float $plafond): static
    {
        $this->plafond = $plafond;

        return $this;
    }
}
