<?php

namespace App\Entity;

use App\Repository\DopingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DopingRepository::class)]
class Doping
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'doping', targetEntity: Substance::class)]
    private Collection $substances;

    #[ORM\OneToMany(mappedBy: 'doping', targetEntity: MedicalProduct::class)]
    private Collection $medicalProducts;

    public function __construct()
    {
        $this->substances = new ArrayCollection();
        $this->medicalProducts = new ArrayCollection();
    }

	public function setId(string $id): self
	{
		$this->id = $id;
		return $this;
	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Substance>
     */
    public function getSubstances(): Collection
    {
        return $this->substances;
    }

    public function addSubstance(Substance $substance): self
    {
        if (!$this->substances->contains($substance)) {
            $this->substances->add($substance);
            $substance->setDoping($this);
        }

        return $this;
    }

    public function removeSubstance(Substance $substance): self
    {
        if ($this->substances->removeElement($substance)) {
            // set the owning side to null (unless already changed)
            if ($substance->getDoping() === $this) {
                $substance->setDoping(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MedicalProduct>
     */
    public function getMedicalProducts(): Collection
    {
        return $this->medicalProducts;
    }

    public function addMedicalProduct(MedicalProduct $medicalProduct): self
    {
        if (!$this->medicalProducts->contains($medicalProduct)) {
            $this->medicalProducts->add($medicalProduct);
            $medicalProduct->setDoping($this);
        }

        return $this;
    }

    public function removeMedicalProduct(MedicalProduct $medicalProduct): self
    {
        if ($this->medicalProducts->removeElement($medicalProduct)) {
            // set the owning side to null (unless already changed)
            if ($medicalProduct->getDoping() === $this) {
                $medicalProduct->setDoping(null);
            }
        }

        return $this;
    }
}
