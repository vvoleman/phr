<?php

namespace App\Entity\NRPZS;

use App\Entity\ISerializable;
use App\Repository\HealthcareServiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HealthcareServiceRepository::class)]
class HealthcareService implements ISerializable
{
	#[ORM\Id]
	#[ORM\Column(name: "id", type: "string")]
	private string $id;

	#[ORM\Column(name: "care_field", type: "string")]
	private ?string $careField = null;

	#[ORM\Column(name: "care_form", type: "string")]
	private ?string $careForm = null;

	#[ORM\Column(name: "care_type", type: "string")]
	private ?string $careType = null;

	#[ORM\Column(name: "care_extent", type: "text")]
	private ?string $careExtent = null;

	#[ORM\Column(name: "bed_count", type: "integer")]
	private ?string $bedCount = null;

	#[ORM\Column(name: "service_note", type: "text")]
	private ?string $serviceNote = null;

	#[ORM\Column(name: "professional_representative", type: "text")]
	private ?string $professionalRepresentative = null;

	#[ORM\ManyToOne(inversedBy: 'services')]
	private HealthcareFacility $facility;

	public function getId(): string
	{
		return $this->id;
	}

	public function setId(string $id): HealthcareService
	{
		$this->id = $id;
		return $this;
	}

	public function getCareField(): string
	{
		return $this->careField;
	}

	public function setCareField(string $careField): HealthcareService
	{
		$this->careField = $careField;
		return $this;
	}

	public function getCareForm(): ?string
	{
		return $this->careForm;
	}

	public function setCareForm(?string $careForm): HealthcareService
	{
		$this->careForm = $careForm;
		return $this;
	}

	public function getCareType(): ?string
	{
		return $this->careType;
	}

	public function setCareType(?string $careType): HealthcareService
	{
		$this->careType = $careType;
		return $this;
	}

	public function getCareExtent(): ?string
	{
		return $this->careExtent;
	}

	public function setCareExtent(?string $careExtent): HealthcareService
	{
		$this->careExtent = $careExtent;
		return $this;
	}

	public function getBedCount(): ?string
	{
		return $this->bedCount;
	}

	public function setBedCount(?string $bedCount): HealthcareService
	{
		$this->bedCount = $bedCount;
		return $this;
	}

	public function getServiceNote(): ?string
	{
		return $this->serviceNote;
	}

	public function setServiceNote(?string $serviceNote): HealthcareService
	{
		$this->serviceNote = $serviceNote;
		return $this;
	}

	public function getProfessionalRepresentative(): ?string
	{
		return $this->professionalRepresentative;
	}

	public function setProfessionalRepresentative(?string $professionalRepresentative): HealthcareService
	{
		$this->professionalRepresentative = $professionalRepresentative;
		return $this;
	}

	public function getFacility(): HealthcareFacility
	{
		return $this->facility;
	}

	public function setFacility(?HealthcareFacility $facility): HealthcareService
	{
		$this->facility = $facility;
		return $this;
	}

	public function serialize( ): array
	{
		return [
			'id' => $this->id,
			'careField' => $this->careField,
			'careForm' => $this->careForm,
			'careType' => $this->careType,
			'careExtent' => $this->careExtent,
			'bedCount' => $this->bedCount,
			'serviceNote' => $this->serviceNote,
			'professionalRepresentative' => $this->professionalRepresentative,
			'facilityId' => $this->facility->getId(),
		];
	}
}
