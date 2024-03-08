<?php

namespace App\Entity;

use App\Repository\RemboursementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RemboursementRepository::class)]
class Remboursement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Mois = null;

    #[ORM\Column]
    private ?int $MontantPaye = null;

    #[ORM\Column(length: 255)]
    private ?string $Periode_Retard = null;

    #[ORM\ManyToOne(inversedBy: 'remboursements')]
    private ?Pret $rmb = null;

    #[ORM\Column]
    private ?bool $Status = null;






    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMois(): ?string
    {
        return $this->Mois;
    }

    public function setMois(string $Mois): static
    {
        $this->Mois = $Mois;

        return $this;
    }

    public function getMontantPaye(): ?int
    {
        return $this->MontantPaye;
    }

    public function setMontantPaye(int $MontantPaye): static
    {
        $this->MontantPaye = $MontantPaye;

        return $this;
    }

    public function getPeriodeRetard(): ?string
    {
        return $this->Periode_Retard;
    }

    public function setPeriodeRetard(string $Periode_Retard): static
    {
        $this->Periode_Retard = $Periode_Retard;

        return $this;
    }

    public function getRmb(): ?Pret
    {
        return $this->rmb;
    }

    public function setRmb(?Pret $rmb): static
    {
        $this->rmb = $rmb;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->Status;
    }

    public function setStatus(bool $Status): static
    {
        $this->Status = $Status;

        return $this;
    }






}
