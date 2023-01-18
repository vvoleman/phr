<?php

namespace App\Entity\UZIS;

use App\Repository\UZIS\DiagnoseGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiagnoseGroupRepository::class)]
class DiagnoseGroup
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: Diagnose::class)]
    private Collection $diagnoses;

    public function __construct()
    {
        $this->diagnoses = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

	public function setId(string $id): self
	{
		$this->id = $id;
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

    /**
     * @return Collection<int, Diagnose>
     */
    public function getDiagnoses(): Collection
    {
        return $this->diagnoses;
    }

    public function addDiagnosis(Diagnose $diagnosis): self
    {
        if (!$this->diagnoses->contains($diagnosis)) {
            $this->diagnoses->add($diagnosis);
            $diagnosis->setParent($this);
        }

        return $this;
    }

    public function removeDiagnosis(Diagnose $diagnosis): self
    {
        if ($this->diagnoses->removeElement($diagnosis)) {
            // set the owning side to null (unless already changed)
            if ($diagnosis->getParent() === $this) {
                $diagnosis->setParent(null);
            }
        }

        return $this;
    }

}
