<?php

namespace App\Entity;

use App\Repository\ProductDocumentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductDocumentRepository::class)]
class ProductDocument
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fileName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $leafletDecisionDate = null;

    #[ORM\OneToOne(mappedBy: 'document', cascade: ['persist', 'remove'])]
    private ?MedicalProduct $medicalProduct = null;

	public function setId(string $id): self
	{
		$this->id = $id;
		return $this;
	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getLeafletDecisionDate(): ?\DateTimeInterface
    {
        return $this->leafletDecisionDate;
    }

    public function setLeafletDecisionDate(?\DateTimeInterface $leafletDecisionDate): self
    {
        $this->leafletDecisionDate = $leafletDecisionDate;

        return $this;
    }

    public function getMedicalProduct(): ?MedicalProduct
    {
        return $this->medicalProduct;
    }

    public function setMedicalProduct(MedicalProduct $medicalProduct): self
    {
        // set the owning side of the relation if necessary
        if ($medicalProduct->getDocument() !== $this) {
            $medicalProduct->setDocument($this);
        }

        $this->medicalProduct = $medicalProduct;

        return $this;
    }
}
