<?php

namespace App\Entity;

use App\Repository\RegistrationStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegistrationStatusRepository::class)]
class RegistrationStatus
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'registrationStatus', targetEntity: MedicalProduct::class)]
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
            $medicalProduct->setRegistrationStatus($this);
        }

        return $this;
    }

    public function removeMedicalProduct(MedicalProduct $medicalProduct): self
    {
        if ($this->medicalProducts->removeElement($medicalProduct)) {
            // set the owning side to null (unless already changed)
            if ($medicalProduct->getRegistrationStatus() === $this) {
                $medicalProduct->setRegistrationStatus(null);
            }
        }

        return $this;
    }
}
