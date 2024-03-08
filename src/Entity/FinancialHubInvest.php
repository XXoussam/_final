<?php

namespace App\Entity;

use App\Repository\FinancialHubInvestRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: FinancialHubInvestRepository::class)]
#[Vich\Uploadable]
class FinancialHubInvest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
//    #[Vich\UploadableField(mapping: 'FinancialHubInvest', fileNameProperty: 'image')]
//    private ?File $imageFile = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"nom is required")]
    private ?string $nomInvest = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"description is required")]
    private ?string $descriptionInvest = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getNomInvest(): ?string
    {
        return $this->nomInvest;
    }

    public function setNomInvest(string $nomInvest): static
    {
        $this->nomInvest = $nomInvest;

        return $this;
    }

    public function getDescriptionInvest(): ?string
    {
        return $this->descriptionInvest;
    }

    public function setDescriptionInvest(string $descriptionInvest): static
    {
        $this->descriptionInvest = $descriptionInvest;

        return $this;
    }

//    public function setImageFile(?File $imageFile = null): void
//    {
//        $this->imageFile = $imageFile;
//    }
//
//    public function getImageFile(): ?File
//    {
//        return $this->imageFile;
//    }


    public function __toString()
    {
        return $this->nomInvest;
    }
}
