<?php

namespace App\Entity;

use App\Repository\SourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SourceRepository::class)]
class Source
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'source', targetEntity: Substance::class)]
    private Collection $substances;

    public function __construct()
    {
        $this->substances = new ArrayCollection();
    }

	public function setId(?string $id): Source
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
            $substance->setSource($this);
        }

        return $this;
    }

    public function removeSubstance(Substance $substance): self
    {
        if ($this->substances->removeElement($substance)) {
            // set the owning side to null (unless already changed)
            if ($substance->getSource() === $this) {
                $substance->setSource(null);
            }
        }

        return $this;
    }
}
