<?php

namespace App\Entity;

use App\Repository\ProductFormRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductFormRepository::class)]
class ProductForm
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $nameEn = null;

    #[ORM\Column(length: 255)]
    private ?string $nameLat = null;

    #[ORM\Column]
    private ?bool $isCannabis = null;

    #[ORM\Column(length: 255)]
    private ?string $edqmCode = null;

    #[ORM\OneToMany(mappedBy: 'form', targetEntity: MedicalProduct::class)]
    private Collection $medicalProducts;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $shortName = null;

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

    public function getNameLat(): ?string
    {
        return $this->nameLat;
    }

    public function setNameLat(string $nameLat): self
    {
        $this->nameLat = $nameLat;

        return $this;
    }

    public function isIsCannabis(): ?bool
    {
        return $this->isCannabis;
    }

    public function setIsCannabis(bool $isCannabis): self
    {
        $this->isCannabis = $isCannabis;

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
            $medicalProduct->setForm($this);
        }

        return $this;
    }

    public function removeMedicalProduct(MedicalProduct $medicalProduct): self
    {
        if ($this->medicalProducts->removeElement($medicalProduct)) {
            // set the owning side to null (unless already changed)
            if ($medicalProduct->getForm() === $this) {
                $medicalProduct->setForm(null);
            }
        }

        return $this;
    }

	public function serialize(): array
         	{
         		return [
         			'id' => $this->id,
         			'name' => $this->name,
					'short_name' => $this->shortName
         		];
         	}

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(string|null $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }
}
