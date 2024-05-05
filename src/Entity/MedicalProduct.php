<?php

namespace App\Entity;

use App\Repository\MedicalProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedicalProductRepository::class)]
class MedicalProduct implements ISerializable
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $strength = null;

    #[ORM\ManyToOne(inversedBy: 'medicalProducts')]
    private ?ProductForm $form = null;

    #[ORM\Column(length: 255)]
    private ?string $packaging = null;

    #[ORM\ManyToOne(inversedBy: 'medicalProducts')]
    private ?AdministrationMethod $administrationMethod = null;

    #[ORM\Column(length: 255)]
    private ?string $addition = null;

    #[ORM\ManyToOne(inversedBy: 'medicalProducts')]
    private ?Wrapping $wrapping = null;

    #[ORM\Column(length: 255)]
    private ?string $registrationHolder = null;

    #[ORM\ManyToOne(inversedBy: 'medicalProducts')]
    private ?Country $countryHolder = null;

    #[ORM\ManyToOne(inversedBy: 'medicalProducts')]
    private ?RegistrationStatus $registrationStatus = null;

    #[ORM\ManyToOne(inversedBy: 'medicalProducts')]
    private ?IndicationGroup $indicationGroup = null;

    #[ORM\ManyToMany(targetEntity: Substance::class, inversedBy: 'medicalProducts')]
    private Collection $substances;

    #[ORM\ManyToOne(inversedBy: 'medicalProducts')]
    private ?Dispensing $dispensing = null;

    #[ORM\ManyToOne(inversedBy: 'medicalProducts')]
    private ?Addiction $addiction = null;

    #[ORM\ManyToOne(inversedBy: 'medicalProducts')]
    private ?Doping $doping = null;

    #[ORM\Column]
    private ?bool $recentlyDelivered = null;

    #[ORM\Column(nullable: true)]
    private ?int $expirationHours = null;

    #[ORM\OneToOne(inversedBy: 'medicalProduct', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?ProductDocument $document = null;

    public function __construct()
    {
        $this->substances = new ArrayCollection();
    }

	public function setId(string $id): self
	{
		$this->id = $id;
		return $this;
	}

    public function getId(): ?string
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

    public function getStrength(): ?string
    {
        return $this->strength;
    }

    public function setStrength(string $strength): self
    {
        $this->strength = $strength;

        return $this;
    }

    public function getForm(): ?ProductForm
    {
        return $this->form;
    }

    public function setForm(?ProductForm $form): self
    {
        $this->form = $form;

        return $this;
    }

    public function getPackaging(): ?string
    {
        return $this->packaging;
    }

    public function setPackaging(string $packaging): self
    {
        $this->packaging = $packaging;

        return $this;
    }

    public function getAdministrationMethod(): ?AdministrationMethod
    {
        return $this->administrationMethod;
    }

    public function setAdministrationMethod(?AdministrationMethod $administrationMethod): self
    {
        $this->administrationMethod = $administrationMethod;

        return $this;
    }

    public function getAddition(): ?string
    {
        return $this->addition;
    }

    public function setAddition(string $addition): self
    {
        $this->addition = $addition;

        return $this;
    }

    public function getWrapping(): ?Wrapping
    {
        return $this->wrapping;
    }

    public function setWrapping(?Wrapping $wrapping): self
    {
        $this->wrapping = $wrapping;

        return $this;
    }

    public function getRegistrationHolder(): ?string
    {
        return $this->registrationHolder;
    }

    public function setRegistrationHolder(string $registrationHolder): self
    {
        $this->registrationHolder = $registrationHolder;

        return $this;
    }

    public function getCountryHolder(): ?Country
    {
        return $this->countryHolder;
    }

    public function setCountryHolder(?Country $countryHolder): self
    {
        $this->countryHolder = $countryHolder;

        return $this;
    }

    public function getRegistrationStatus(): ?RegistrationStatus
    {
        return $this->registrationStatus;
    }

    public function setRegistrationStatus(?RegistrationStatus $registrationStatus): self
    {
        $this->registrationStatus = $registrationStatus;

        return $this;
    }

    public function getIndicationGroup(): ?IndicationGroup
    {
        return $this->indicationGroup;
    }

    public function setIndicationGroup(?IndicationGroup $indicationGroup): self
    {
        $this->indicationGroup = $indicationGroup;

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
        }

        return $this;
    }

    public function removeSubstance(Substance $substance): self
    {
        $this->substances->removeElement($substance);

        return $this;
    }

    public function getDispensing(): ?Dispensing
    {
        return $this->dispensing;
    }

    public function setDispensing(?Dispensing $dispensing): self
    {
        $this->dispensing = $dispensing;

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

    public function isRecentlyDelivered(): ?bool
    {
        return $this->recentlyDelivered;
    }

    public function setRecentlyDelivered(bool $recentlyDelivered): self
    {
        $this->recentlyDelivered = $recentlyDelivered;

        return $this;
    }

    public function getExpirationHours(): ?int
    {
        return $this->expirationHours;
    }

    public function setExpirationHours(?int $expirationHours): self
    {
        $this->expirationHours = $expirationHours;

        return $this;
    }

    public function getDocument(): ?ProductDocument
    {
        return $this->document;
    }

    public function setDocument(ProductDocument $document): self
    {
        $this->document = $document;

        return $this;
    }

	public function serialize(): array
	{
		$substances = [];
		foreach ($this->getSubstances() as $substance) {
			$substances[] = [
				'id' => $substance->getId(),
				'name' => $substance->getName(),
				'strength' => $this->strength,
			];
		}

		return [
			'id' => $this->id,
			'name' => $this->name,
			'strength' => $this->strength,
			'packaging' => [
				'form' => $this->form->serialize(),
				'packaging' => $this->packaging,
			],
			'addition' => $this->addition,
			'registrationHolder' => $this->registrationHolder,
			'recentlyDelivered' => $this->recentlyDelivered,
			'expirationHours' => $this->expirationHours,
			'country' => $this->countryHolder?->getEdqmCode(),
			'substances' => $substances,
		];
	}

	private function getSubstanceAmount() {
		$strengths = explode('/', $this->strength);
		return array_map(function ($strength) {
			return [
				$strength => $this->id
				];
		}, $strengths);
	}

	private function getUnit($strength) {
		$strength = str_replace(',', '.', $strength);
		preg_match('/^([\d\.]+)(\w+)$/', $strength, $matches);

		if (count($matches) !== 3) {
			return null;
		}
		$value = floatval($matches[1]); // Convert the matched value to float
		$unit = $matches[2];

		return [
			'value' => $value,
			'unit' => $unit,
		];
		//split into number and unit
	}
}
