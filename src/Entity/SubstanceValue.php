<?php

namespace App\Entity;

use App\Repository\SubstanceValueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubstanceValueRepository::class)]
class SubstanceValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Substance $substance = null;

    #[ORM\ManyToOne(inversedBy: 'substanceValues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MedicalProduct $medicalProduct = null;

    #[ORM\ManyToOne]
    private ?Unit $unit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $value = null;

	public function setId(?int $id): self
	{
		$this->id = $id;
		return $this;
	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubstance(): ?Substance
    {
        return $this->substance;
    }

    public function setSubstance(?Substance $substance): self
    {
        $this->substance = $substance;

        return $this;
    }

    public function getMedicalProduct(): ?MedicalProduct
    {
        return $this->medicalProduct;
    }

    public function setMedicalProduct(?MedicalProduct $medicalProduct): self
    {
        $this->medicalProduct = $medicalProduct;

        return $this;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
