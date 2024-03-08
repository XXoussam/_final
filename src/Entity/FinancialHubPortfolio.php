<?php

namespace App\Entity;

use App\Repository\FinancialHubPortfolioRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FinancialHubPortfolioRepository::class)]
class FinancialHubPortfolio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"nom Portfolio is required")]
    private ?string $nomPortfolio = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?FinancialHubInvest $FHIid = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"montant is required")]
    #[Assert\Positive(message:"il faut que montantToInvest est positive")]
    private ?int $montantToInvest = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPortfolio(): ?string
    {
        return $this->nomPortfolio;
    }

    public function setNomPortfolio(string $nomPortfolio): static
    {
        $this->nomPortfolio = $nomPortfolio;

        return $this;
    }

    public function getFHIid(): ?FinancialHubInvest
    {
        return $this->FHIid;
    }

    public function setFHIid(?FinancialHubInvest $FHIid): static
    {
        $this->FHIid = $FHIid;

        return $this;
    }

    public function getMontantToInvest(): ?int
    {
        return $this->montantToInvest;
    }

    public function setMontantToInvest(int $montantToInvest): static
    {
        $this->montantToInvest = $montantToInvest;

        return $this;
    }
}
