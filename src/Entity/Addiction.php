<?php

namespace App\Entity;

use App\Repository\AddictionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddictionRepository::class)]
class Addiction
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'addiction', targetEntity: Substance::class)]
    private Collection $substances;

    #[ORM\OneToMany(mappedBy: 'addiction', targetEntity: MedicalProduct::class)]
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
            $substance->setAddiction($this);
        }

        return $this;
    }

    public function removeSubstance(Substance $substance): self
    {
        if ($this->substances->removeElement($substance)) {
            // set the owning side to null (unless already changed)
            if ($substance->getAddiction() === $this) {
                $substance->setAddiction(null);
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
            $medicalProduct->setAddiction($this);
        }

        return $this;
    }

    public function removeMedicalProduct(MedicalProduct $medicalProduct): self
    {
        if ($this->medicalProducts->removeElement($medicalProduct)) {
            // set the owning side to null (unless already changed)
            if ($medicalProduct->getAddiction() === $this) {
                $medicalProduct->setAddiction(null);
            }
        }

        return $this;
    }
}
