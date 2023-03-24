<?php

namespace App\Entity\UZIS;

use App\Repository\UZIS\DiagnoseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiagnoseRepository::class)]
class Diagnose
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'diagnoses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DiagnoseGroup $parent = null;

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

    public function getParent(): ?DiagnoseGroup
    {
        return $this->parent;
    }

    public function setParent(?DiagnoseGroup $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

	public function toArray(): array
	{
		return [
			"id" => $this->getId(),
			"name" => $this->getName(),
			"parent" => [
				"id" => $this->getParent()->getId(),
				"name" => $this->getParent()->getName(),
			]
		];
	}
}
