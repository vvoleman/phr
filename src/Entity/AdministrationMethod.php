<?php

namespace App\Entity;

use App\Repository\AdministrationMethodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdministrationMethodRepository::class)]
class AdministrationMethod
{
    #[ORM\Id]
    #[ORM\Column]
    private string $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $nameEn;

    #[ORM\Column(length: 255)]
    private string $edqmCode;

    #[ORM\OneToMany(mappedBy: 'administrationMethod', targetEntity: MedicalProduct::class)]
    private Collection $medicalProducts;

    public function __construct()
    {
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

    public function getNameEn(): ?string
    {
        return $this->nameEn;
    }

    public function setNameEn(string $nameEn): self
    {
        $this->nameEn = $nameEn;

        return $this;
    }

    public function getEdqmCode(): ?string
    {
        return $this->edqmCode;
    }

    public function setEdqmCode(string $edqmCode): self
    {
        $this->edqmCode = $edqmCode;

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
            $medicalProduct->setAdministrationMethod($this);
        }

        return $this;
    }

    public function removeMedicalProduct(MedicalProduct $medicalProduct): self
    {
        if ($this->medicalProducts->removeElement($medicalProduct)) {
            // set the owning side to null (unless already changed)
            if ($medicalProduct->getAdministrationMethod() === $this) {
                $medicalProduct->setAdministrationMethod(null);
            }
        }

        return $this;
    }
}
