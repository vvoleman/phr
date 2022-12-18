<?php

namespace App\Entity;

use App\Repository\SubstanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubstanceRepository::class)]
class Substance implements ISerializable
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\ManyToOne(inversedBy: 'substances')]
    private ?Source $source = null;

    #[ORM\Column(length: 255)]
    private ?string $nameInn = null;

    #[ORM\Column(length: 255)]
    private ?string $nameEn = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'substances')]
    private ?Addiction $addiction = null;

    #[ORM\ManyToOne(inversedBy: 'substances')]
    private ?Doping $doping = null;

    #[ORM\ManyToMany(targetEntity: MedicalProduct::class, mappedBy: 'substances')]
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

    public function getSource(): ?Source
    {
        return $this->source;
    }

    public function setSource(?Source $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getNameInn(): ?string
    {
        return $this->nameInn;
    }

    public function setNameInn(string $nameInn): self
    {
        $this->nameInn = $nameInn;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddiction(): ?Addiction
    {
        return $this->addiction;
    }

    public function setAddiction(?Addiction $addiction): self
    {
        $this->addiction = $addiction;

        return $this;
    }

    public function getDoping(): ?Doping
    {
        return $this->doping;
    }

    public function setDoping(?Doping $doping): self
    {
        $this->doping = $doping;

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
            $medicalProduct->addSubstance($this);
        }

        return $this;
    }

    public function removeMedicalProduct(MedicalProduct $medicalProduct): self
    {
        if ($this->medicalProducts->removeElement($medicalProduct)) {
            $medicalProduct->removeSubstance($this);
        }

        return $this;
    }

	public function serialize(): array
	{
		return [
			'id' => $this->id,
			'name' => [
				'inn' => $this->nameInn,
				'en' => $this->nameEn,
				'cs' => $this->name,
			],
			'addiction' => $this->addiction?->getName(),
			'doping' => $this->doping?->getName(),
		];
	}
}
