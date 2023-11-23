<?php

namespace App\Entity\NRPZS;

use App\Entity\ISerializable;
use App\Repository\HealthcareFacilityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HealthcareFacilityRepository::class)]
class HealthcareFacility implements ISerializable
{
	#[ORM\Id]
	#[ORM\Column(name: "id", type: "string")]
	private string $id;

	#[ORM\Column(name: "full_name", type: "string")]
	private string $fullName;

	#[ORM\Column(name: "facility_code", type: "string")]
	private string $facilityCode;

	#[ORM\Column(name: "facility_type_code", type: "string")]
	private string $facilityTypeCode;

	#[ORM\Column(name: "facility_type", type: "string")]
	private string $facilityType;

	#[ORM\Column(name: "secondary_facility_type", type: "string")]
	private ?string $secondaryFacilityType = null;

	#[ORM\Column(name: "founder", type: "string")]
	private string $founder;

	#[ORM\Column(name: "city", type: "string")]
	private string $city;

	#[ORM\Column(name: "postal_code", type: "string")]
	private string $postalCode;

	#[ORM\Column(name: "street", type: "string")]
	private string $street;

	#[ORM\Column(name: "house_number_orientation", type: "string")]
	private string $houseNumberOrientation;

	#[ORM\Column(name: "region", type: "string")]
	private string $region;

	#[ORM\Column(name: "region_code", type: "string")]
	private string $regionCode;

	#[ORM\Column(name: "district", type: "string")]
	private string $district;

	#[ORM\Column(name: "district_code", type: "string")]
	private string $districtCode;

	#[ORM\Column(name: "administrative_district", type: "string")]
	private ?string $administrativeDistrict = null;

	#[ORM\Column(name: "provider_telephone", type: "string")]
	private ?string $providerTelephone = null;

	#[ORM\Column(name: "provider_fax", type: "string")]
	private ?string $providerFax = null;

	#[ORM\Column(name: "activity_started_at", type: "datetime")]
	private ?\DateTimeInterface $activityStartedAt = null;

	#[ORM\Column(name: "provider_email", type: "string")]
	private ?string $providerEmail = null;

	#[ORM\Column(name: "provider_web", type: "string")]
	private ?string $providerWeb = null;

	#[ORM\Column(name: "provider_type", type: "string")]
	private string $providerType;

	#[ORM\Column(name: "provider_name", type: "string")]
	private string $providerName;

	#[ORM\Column(name: "identification_number", type: "string")]
	private string $identificationNumber;

	#[ORM\Column(name: "person_type", type: "string")]
	private string $personType;

	#[ORM\Column(name: "region_code_of_domicile", type: "string")]
	private ?string $regionCodeOfDomicile = null;

	#[ORM\Column(name: "domicile_region", type: "string")]
	private ?string $domicileRegion = null;

	#[ORM\Column(name: "district_code_of_domicile", type: "string")]
	private ?string $districtCodeOfDomicile = null;

	#[ORM\Column(name: "domicile_district", type: "string")]
	private ?string $domicileDistrict = null;

	#[ORM\Column(name: "postal_code_of_domicile", type: "string")]
	private ?string $postalCodeOfDomicile = null;

	#[ORM\Column(name: "domicile_city", type: "string")]
	private ?string $domicileCity = null;

	#[ORM\Column(name: "domicile_street", type: "string")]
	private ?string $domicileStreet = null;

	#[ORM\Column(name: "domicile_house_number_orientation", type: "string")]
	private ?string $domicileHouseNumberOrientation = null;

	#[ORM\Column(name: "gps", type: "string")]
	private ?string $gps = null;

	#[ORM\OneToMany(mappedBy: 'facility', targetEntity: HealthcareService::class)]
	private Collection $services;

	public function getServices(): Collection
	{
		return $this->services;
	}

	public function setServices(Collection $services): HealthcareFacility
	{
		$this->services = $services;
		return $this;
	}

	public function addService(HealthcareService $service): self
	{
		if (!$this->services->contains($service)) {
			$this->services->add($service);
			$service->setFacility($this);
		}

		return $this;
	}

	public function removeFacility(HealthcareService $service): self
	{
		if ($this->services->removeElement($service)) {
			// set the owning side to null (unless already changed)
			if ($service->getFacility() === $this) {
				$service->setFacility(null);
			}
		}

		return $this;
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function setId(string $id): HealthcareFacility
	{
		$this->id = $id;
		return $this;
	}

	public function getFullName(): string
	{
		return $this->fullName;
	}

	public function setFullName(string $fullName): HealthcareFacility
	{
		$this->fullName = $fullName;
		return $this;
	}

	public function getFacilityCode(): string
	{
		return $this->facilityCode;
	}

	public function setFacilityCode(string $facilityCode): HealthcareFacility
	{
		$this->facilityCode = $facilityCode;
		return $this;
	}

	public function getFacilityTypeCode(): string
	{
		return $this->facilityTypeCode;
	}

	public function setFacilityTypeCode(string $facilityTypeCode): HealthcareFacility
	{
		$this->facilityTypeCode = $facilityTypeCode;
		return $this;
	}

	public function getFacilityType(): string
	{
		return $this->facilityType;
	}

	public function setFacilityType(string $facilityType): HealthcareFacility
	{
		$this->facilityType = $facilityType;
		return $this;
	}

	public function getSecondaryFacilityType(): ?string
	{
		return $this->secondaryFacilityType;
	}

	public function setSecondaryFacilityType(?string $secondaryFacilityType): HealthcareFacility
	{
		$this->secondaryFacilityType = $secondaryFacilityType;
		return $this;
	}

	public function getFounder(): string
	{
		return $this->founder;
	}

	public function setFounder(string $founder): HealthcareFacility
	{
		$this->founder = $founder;
		return $this;
	}

	public function getCity(): string
	{
		return $this->city;
	}

	public function setCity(string $city): HealthcareFacility
	{
		$this->city = $city;
		return $this;
	}

	public function getPostalCode(): string
	{
		return $this->postalCode;
	}

	public function setPostalCode(string $postalCode): HealthcareFacility
	{
		$this->postalCode = $postalCode;
		return $this;
	}

	public function getStreet(): string
	{
		return $this->street;
	}

	public function setStreet(string $street): HealthcareFacility
	{
		$this->street = $street;
		return $this;
	}

	public function getHouseNumberOrientation(): string
	{
		return $this->houseNumberOrientation;
	}

	public function setHouseNumberOrientation(string $houseNumberOrientation): HealthcareFacility
	{
		$this->houseNumberOrientation = $houseNumberOrientation;
		return $this;
	}

	public function getRegion(): string
	{
		return $this->region;
	}

	public function setRegion(string $region): HealthcareFacility
	{
		$this->region = $region;
		return $this;
	}

	public function getRegionCode(): string
	{
		return $this->regionCode;
	}

	public function setRegionCode(string $regionCode): HealthcareFacility
	{
		$this->regionCode = $regionCode;
		return $this;
	}

	public function getDistrict(): string
	{
		return $this->district;
	}

	public function setDistrict(string $district): HealthcareFacility
	{
		$this->district = $district;
		return $this;
	}

	public function getDistrictCode(): string
	{
		return $this->districtCode;
	}

	public function setDistrictCode(string $districtCode): HealthcareFacility
	{
		$this->districtCode = $districtCode;
		return $this;
	}

	public function getAdministrativeDistrict(): ?string
	{
		return $this->administrativeDistrict;
	}

	public function setAdministrativeDistrict(?string $administrativeDistrict): HealthcareFacility
	{
		$this->administrativeDistrict = $administrativeDistrict;
		return $this;
	}

	public function getProviderTelephone(): ?string
	{
		return $this->providerTelephone;
	}

	public function setProviderTelephone(?string $providerTelephone): HealthcareFacility
	{
		$this->providerTelephone = $providerTelephone;
		return $this;
	}

	public function getProviderFax(): ?string
	{
		return $this->providerFax;
	}

	public function setProviderFax(?string $providerFax): HealthcareFacility
	{
		$this->providerFax = $providerFax;
		return $this;
	}

	public function getActivityStartedAt(): ?\DateTimeInterface
	{
		return $this->activityStartedAt;
	}

	public function setActivityStartedAt(?\DateTimeInterface $activityStartedAt): HealthcareFacility
	{
		$this->activityStartedAt = $activityStartedAt;
		return $this;
	}

	public function getProviderEmail(): ?string
	{
		return $this->providerEmail;
	}

	public function setProviderEmail(?string $providerEmail): HealthcareFacility
	{
		$this->providerEmail = $providerEmail;
		return $this;
	}

	public function getProviderWeb(): ?string
	{
		return $this->providerWeb;
	}

	public function setProviderWeb(?string $providerWeb): HealthcareFacility
	{
		$this->providerWeb = $providerWeb;
		return $this;
	}

	public function getProviderType(): string
	{
		return $this->providerType;
	}

	public function setProviderType(string $providerType): HealthcareFacility
	{
		$this->providerType = $providerType;
		return $this;
	}

	public function getProviderName(): string
	{
		return $this->providerName;
	}

	public function setProviderName(string $providerName): HealthcareFacility
	{
		$this->providerName = $providerName;
		return $this;
	}

	public function getIdentificationNumber(): string
	{
		return $this->identificationNumber;
	}

	public function setIdentificationNumber(string $identificationNumber): HealthcareFacility
	{
		$this->identificationNumber = $identificationNumber;
		return $this;
	}

	public function getPersonType(): string
	{
		return $this->personType;
	}

	public function setPersonType(string $personType): HealthcareFacility
	{
		$this->personType = $personType;
		return $this;
	}

	public function getRegionCodeOfDomicile(): ?string
	{
		return $this->regionCodeOfDomicile;
	}

	public function setRegionCodeOfDomicile(?string $regionCodeOfDomicile): HealthcareFacility
	{
		$this->regionCodeOfDomicile = $regionCodeOfDomicile;
		return $this;
	}

	public function getDomicileRegion(): ?string
	{
		return $this->domicileRegion;
	}

	public function setDomicileRegion(?string $domicileRegion): HealthcareFacility
	{
		$this->domicileRegion = $domicileRegion;
		return $this;
	}

	public function getDistrictCodeOfDomicile(): ?string
	{
		return $this->districtCodeOfDomicile;
	}

	public function setDistrictCodeOfDomicile(?string $districtCodeOfDomicile): HealthcareFacility
	{
		$this->districtCodeOfDomicile = $districtCodeOfDomicile;
		return $this;
	}

	public function getDomicileDistrict(): ?string
	{
		return $this->domicileDistrict;
	}

	public function setDomicileDistrict(?string $domicileDistrict): HealthcareFacility
	{
		$this->domicileDistrict = $domicileDistrict;
		return $this;
	}

	public function getPostalCodeOfDomicile(): ?string
	{
		return $this->postalCodeOfDomicile;
	}

	public function setPostalCodeOfDomicile(?string $postalCodeOfDomicile): HealthcareFacility
	{
		$this->postalCodeOfDomicile = $postalCodeOfDomicile;
		return $this;
	}

	public function getDomicileCity(): ?string
	{
		return $this->domicileCity;
	}

	public function setDomicileCity(?string $domicileCity): HealthcareFacility
	{
		$this->domicileCity = $domicileCity;
		return $this;
	}

	public function getDomicileStreet(): ?string
	{
		return $this->domicileStreet;
	}

	public function setDomicileStreet(?string $domicileStreet): HealthcareFacility
	{
		$this->domicileStreet = $domicileStreet;
		return $this;
	}

	public function getDomicileHouseNumberOrientation(): ?string
	{
		return $this->domicileHouseNumberOrientation;
	}

	public function setDomicileHouseNumberOrientation(?string $domicileHouseNumberOrientation): HealthcareFacility
	{
		$this->domicileHouseNumberOrientation = $domicileHouseNumberOrientation;
		return $this;
	}

	public function getGps(): ?string
	{
		return $this->gps;
	}

	public function setGps(?string $gps): HealthcareFacility
	{
		$this->gps = $gps;
		return $this;
	}

	public function serialize(): array
	{
		return [
			'id' => $this->getId(),
			'full_name' => $this->getFullName(),
			'facility_code' => $this->getFacilityCode(),
			'facility_type_code' => $this->getFacilityTypeCode(),
			'facility_type' => $this->getFacilityType(),
			'secondary_facility_type' => $this->getSecondaryFacilityType(),
			'founder' => $this->getFounder(),
			'city' => $this->getCity(),
			'postal_code' => $this->getPostalCode(),
			'street' => $this->getStreet(),
			'house_number_orientation' => $this->getHouseNumberOrientation(),
			'region' => $this->getRegion(),
			'region_code' => $this->getRegionCode(),
			'district' => $this->getDistrict(),
			'district_code' => $this->getDistrictCode(),
			'administrative_district' => $this->getAdministrativeDistrict(),
			'provider_telephone' => $this->getProviderTelephone(),
			'provider_fax' => $this->getProviderFax(),
			'activity_started_at' => $this->getActivityStartedAt(),
			'provider_email' => $this->getProviderEmail(),
			'provider_web' => $this->getProviderWeb(),
			'provider_type' => $this->getProviderType(),
			'provider_name' => $this->getProviderName(),
			'identification_number' => $this->getIdentificationNumber(),
			'person_type' => $this->getPersonType(),
			'region_code_of_domicile' => $this->getRegionCodeOfDomicile(),
			'domicile_region' => $this->getDomicileRegion(),
			'district_code_of_domicile' => $this->getDistrictCodeOfDomicile(),
			'domicile_district' => $this->getDomicileDistrict(),
			'postal_code_of_domicile' => $this->getPostalCodeOfDomicile(),
			'domicile_city' => $this->getDomicileCity(),
			'domicile_street' => $this->getDomicileStreet(),
			'domicile_house_number_orientation' => $this->getDomicileHouseNumberOrientation(),
			'gps' => $this->getGps(),
			'services' => array_map(fn(HealthcareService $product) => $product->serialize(), $this->services->toArray()),
		];
	}
}
