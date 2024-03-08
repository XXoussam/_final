<?php

namespace App\Entity;

use App\Repository\PretRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use phpDocumentor\Reflection\Types\Boolean;

#[ORM\Entity(repositoryClass: PretRepository::class)]

class Pret
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\Positive(message:"montant can't be negative")]
    private ?int $Montant = null;

    #[ORM\Column]
    private ?string $Interet = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"preiode is required")]
    private ?int $Periode = null;

    #[ORM\Column(length: 255)]
    private ?string $Type = null;
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"descriotion is required")]
    private ?string $description = null;


    #[ORM\Column]
    private ?bool $Status = null;

    #[ORM\Column]
    private ?bool $accepte = null;

    #[ORM\Column]
    private ?bool $refuse = null;

    #[ORM\OneToMany(mappedBy: 'rmb', targetEntity: Remboursement::class)]
    private Collection $remboursements;


    #[ORM\ManyToOne(inversedBy: 'prets')]
    private ?Client $PretId = null;

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setRemboursements(Collection $remboursements): void
    {
        $this->remboursements = $remboursements;
    }

    public function getPretId(): ?Client
    {
        return $this->PretId;
    }

    public function setPretId(?Client $PretId): void
    {
        $this->PretId = $PretId;
    }

    public function __construct()
    {
        $this->remboursements = new ArrayCollection();
    }





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->Montant;
    }

    public function setMontant(int $Montant): static
    {
        $this->Montant = $Montant;

        return $this;
    }

    public function getInteret(): ?string
    {
        return $this->Interet;
    }

    public function setInteret(string $Interet): static
    {
        $this->Interet = $Interet;

        return $this;
    }

    public function getPeriode(): ?int
    {
        return $this->Periode;
    }

    public function setPeriode(int $Periode): static
    {
        $this->Periode = $Periode;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): static
    {
        $this->Type = $Type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }


    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function isAccepte(): ?bool
    {
        return $this->accepte;
    }

    public function setAccepte(bool $accepte): static
    {
        $this->accepte = $accepte;

        return $this;
    }

    public function isRefuse(): ?bool
    {
        return $this->refuse;
    }

    public function setRefuse(bool $refuse): static
    {
        $this->refuse = $refuse;

        return $this;
    }


    public function __toString()
    {
        if ($this->isAccepte())
            return (string) $this->getId();
        else return "";
    }

    /**
     * @return Collection<int, Remboursement>
     */
    public function getRemboursements(): Collection
    {

            return $this->remboursements;

    }

    public function addRemboursement(Remboursement $remboursement): static
    {
        if (!$this->remboursements->contains($remboursement)) {
            $this->remboursements->add($remboursement);
            $remboursement->setRmb($this);
        }

        return $this;
    }

    public function removeRemboursement(Remboursement $remboursement): static
    {
        if ($this->remboursements->removeElement($remboursement)) {
            // set the owning side to null (unless already changed)
            if ($remboursement->getRmb() === $this) {
                $remboursement->setRmb(null);
            }
        }

        return $this;
    }
public function calcule()
{
    $this->Interet=($this->Montant *0.005 * $this->Periode)/100;
}

}
