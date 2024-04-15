<?php declare(strict_types=1);

namespace App\Service\NRPZs\Syncers;

use App\Entity\NRPZS\HealthcareFacility;
use App\Service\AbstractSyncer;
use Doctrine\ORM\EntityRepository;

class HealthcareFacilitySyncer extends AbstractSyncer
{

    private const FILENAME = 'data.csv';

    protected function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(HealthcareFacility::class);
    }

    /**
     * @inheritDoc
     */
    protected function handleRow(array $row, EntityRepository $repository): string
    {
        $sql = "
        INSERT INTO healthcare_facility (id, full_name, facility_code, facility_type_code, facility_type, secondary_facility_type, founder, city, postal_code, street, house_number_orientation, region, region_code, district, district_code, administrative_district, provider_telephone, provider_fax, activity_started_at, provider_email, provider_web, provider_type, provider_name, identification_number, person_type, region_code_of_domicile, domicile_region, district_code_of_domicile, domicile_district, postal_code_of_domicile, domicile_city, domicile_street, domicile_house_number_orientation, gps)
        VALUES (%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s, %s,%s, %s, %s, %s)
        ON DUPLICATE KEY UPDATE
                                full_name = VALUES(full_name),
                                facility_code = VALUES(facility_code),
                                facility_type_code = VALUES(facility_type_code),
                                facility_type = VALUES(facility_type),
                                secondary_facility_type = VALUES(secondary_facility_type),
                                founder = VALUES(founder),
                                city = VALUES(city),
                                postal_code = VALUES(postal_code),
                                street = VALUES(street),
                                house_number_orientation = VALUES(house_number_orientation),
                                region = VALUES(region),
                                region_code = VALUES(region_code),
                                district = VALUES(district),
                                district_code = VALUES(district_code),
                                administrative_district = VALUES(administrative_district),
                                provider_telephone = VALUES(provider_telephone),
                                provider_fax = VALUES(provider_fax),
                                activity_started_at = VALUES(activity_started_at),
                                provider_email = VALUES(provider_email),
                                provider_web = VALUES(provider_web),
                                provider_type = VALUES(provider_type),
                                provider_name = VALUES(provider_name),
                                identification_number = VALUES(identification_number),
                                person_type = VALUES(person_type),
                                region_code_of_domicile = VALUES(region_code_of_domicile),
                                domicile_region = VALUES(domicile_region),
                                district_code_of_domicile = VALUES(district_code_of_domicile),
                                domicile_district = VALUES(domicile_district),
                                postal_code_of_domicile = VALUES(postal_code_of_domicile),
                                domicile_city = VALUES(domicile_city),
                                domicile_street = VALUES(domicile_street),
                                domicile_house_number_orientation = VALUES(domicile_house_number_orientation),
                                gps = VALUES(gps);
        ";

        $result = sprintf(
            $sql,
            $this->getOrNull($row['ZdravotnickeZarizeniId'], true),
            $this->getOrNull($row['NazevCely'], true),
            $this->getOrNull($row['ZdravotnickeZarizeniKod'], true),
            $this->getOrNull($row['DruhZarizeniKod'], true),
            $this->getOrNull($row['DruhZarizeni'], true),
            $this->getOrNull($row['DruhZarizeniSekundarni'], true),
            $this->getOrNull($row['Zrizovatel'], true),
            $this->getOrNull($row['Obec'], true),
            $this->getOrNull($row['Psc'], true),
            $this->getOrNull($row['Ulice'], true),
            $this->getOrNull($row['CisloDomovniOrientacni'], true),
            $this->getOrNull($row['Kraj'], true),
            $this->getOrNull($row['KrajKod'], true),
            $this->getOrNull($row['Okres'], true),
            $this->getOrNull($row['OkresKod'], true),
            $this->getOrNull($row['SpravniObvod'], true),
            $this->getOrNull($row['PoskytovatelTelefon'], true),
            $this->getOrNull($row['PoskytovatelFax'], true),
            $this->getOrNull($row['DatumZahajeniCinnosti'], true),
            $this->getOrNull($row['PoskytovatelEmail'], true),
            $this->getOrNull($row['PoskytovatelWeb'], true),
            $this->getOrNull($row['DruhPoskytovatele'], true),
            $this->getOrNull($row['PoskytovatelNazev'], true),
            $this->getOrNull($row['Ico'], true),
            $this->getOrNull($row['TypOsoby'], true),
            $this->getOrNull($row['KrajKodSidlo'], true),
            $this->getOrNull($row['KrajSidlo'], true),
            $this->getOrNull($row['OkresKodSidlo'], true),
            $this->getOrNull($row['OkresSidlo'], true),
            $this->getOrNull($row['PscSidlo'], true),
            $this->getOrNull($row['ObecSidlo'], true),
            $this->getOrNull($row['UliceSidlo'], true),
            $this->getOrNull($row['CisloDomovniOrientacniSidlo'], true),
            $this->getOrNull($row['GPS'], true)
        );

        $result .= sprintf("
            INSERT INTO healthcare_service (id, facility_id, care_field, care_form, care_type, care_extent, bed_count, service_note, professional_representative)
            VALUES (%s, %s, %s, %s, %s, %s, %d, %s, %s)
            ON DUPLICATE KEY UPDATE
                                    facility_id = VALUES(facility_id),
                                    care_field = VALUES(care_field),
                                    care_form = VALUES(care_form),
                                    care_type = VALUES(care_type),
                                    care_extent = VALUES(care_extent),
                                    bed_count = VALUES(bed_count),
                                    service_note = VALUES(service_note),
                                    professional_representative = VALUES(professional_representative);
        ",
            $this->getOrNull(hash('sha256', $row['ZdravotnickeZarizeniId'].$row['OborPece']), true),
            $this->getOrNull($row['ZdravotnickeZarizeniId'], true),
            $this->getOrNull($row['OborPece'], true),
            $this->getOrNull($row['FormaPece'], true),
            $this->getOrNull($row['DruhPece'], true),
            $this->getOrNull($row['RozsahPece'], true),
            $this->getOrNull($row['PocetLuzek'] ?? 0) ?? 0,
            $this->getOrNull($row['PoznamkaKeSluzbe'], true),
            $this->getOrNull($row['OdbornyZastupce'], true)
        );

        return $result;

		$facility = $repository->find($row['ZdravotnickeZarizeniId']);

		if ($facility === null) {
            $facility = new HealthcareFacility();
            $facility->setId($row['ZdravotnickeZarizeniId']);

            $this->entityManager->persist($facility);
        }

		/** @var HealthcareFacility $facility */

		$facility->setId($row['ZdravotnickeZarizeniId']);
		$facility->setFullName($row['NazevCely']);
		$facility->setFacilityCode($row['ZdravotnickeZarizeniKod']);
		$facility->setFacilityTypeCode($row['DruhZarizeniKod']);
		$facility->setFacilityType($row['DruhZarizeni']);
		$facility->setSecondaryFacilityType($row['DruhZarizeniSekundarni']);
		$facility->setFounder($row['Zrizovatel']);
		$facility->setCity($row['Obec']);
		$facility->setPostalCode($row['Psc']);
		$facility->setStreet($row['Ulice']);
		$facility->setHouseNumberOrientation($row['CisloDomovniOrientacni']);
		$facility->setRegion($row['Kraj']);
		$facility->setRegionCode($row['KrajKod']);
		$facility->setDistrict($row['Okres']);
		$facility->setDistrictCode($row['OkresKod']);
		$facility->setAdministrativeDistrict($row['SpravniObvod']);
		$facility->setProviderTelephone($row['PoskytovatelTelefon']);
		$facility->setProviderFax($row['PoskytovatelFax']);

		$startedAt = empty($row['DatumZahajeniCinnosti']) ? null : new \DateTime($row['DatumZahajeniCinnosti']);
		$facility->setActivityStartedAt($startedAt);

		$facility->setProviderEmail($row['PoskytovatelEmail']);
		$facility->setProviderWeb($row['PoskytovatelWeb']);
		$facility->setProviderType($row['DruhPoskytovatele']);
		$facility->setProviderName($row['PoskytovatelNazev']);
		$facility->setIdentificationNumber($row['Ico']);
		$facility->setPersonType($row['TypOsoby']);
		$facility->setRegionCodeOfDomicile($row['KrajKodSidlo']);
		$facility->setDomicileRegion($row['KrajSidlo']);
		$facility->setDistrictCodeOfDomicile($row['OkresKodSidlo']);
		$facility->setDomicileDistrict($row['OkresSidlo']);
		$facility->setPostalCodeOfDomicile($row['PscSidlo']);
		$facility->setDomicileCity($row['ObecSidlo']);
		$facility->setDomicileStreet($row['UliceSidlo']);
		$facility->setDomicileHouseNumberOrientation($row['CisloDomovniOrientacniSidlo']);
		$facility->setGps($row['GPS']);
	}

    protected function getFilename(): string
    {
        return self::FILENAME;
    }
}
