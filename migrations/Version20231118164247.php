<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231118164247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE healthcare_facility (id VARCHAR(255) NOT NULL, full_name VARCHAR(255) NOT NULL, facility_code VARCHAR(255) NOT NULL, facility_type_code VARCHAR(255) NOT NULL, facility_type VARCHAR(255) NOT NULL, secondary_facility_type VARCHAR(255) NOT NULL, founder VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, house_number_orientation VARCHAR(255) NOT NULL, region VARCHAR(255) NOT NULL, region_code VARCHAR(255) NOT NULL, district VARCHAR(255) NOT NULL, district_code VARCHAR(255) NOT NULL, administrative_district VARCHAR(255) NOT NULL, provider_telephone VARCHAR(255) NOT NULL, provider_fax VARCHAR(255) NOT NULL, activity_started_at DATETIME NOT NULL, provider_email VARCHAR(255) NOT NULL, provider_web VARCHAR(255) NOT NULL, provider_type VARCHAR(255) NOT NULL, provider_name VARCHAR(255) NOT NULL, identification_number VARCHAR(255) NOT NULL, person_type VARCHAR(255) NOT NULL, region_code_of_domicile VARCHAR(255) NOT NULL, domicile_region VARCHAR(255) NOT NULL, district_code_of_domicile VARCHAR(255) NOT NULL, domicile_district VARCHAR(255) NOT NULL, postal_code_of_domicile VARCHAR(255) NOT NULL, domicile_city VARCHAR(255) NOT NULL, domicile_street VARCHAR(255) NOT NULL, domicile_house_number_orientation VARCHAR(255) NOT NULL, gps VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE healthcare_service (id VARCHAR(255) NOT NULL, facility_id VARCHAR(255) DEFAULT NULL, care_field VARCHAR(255) NOT NULL, care_form VARCHAR(255) NOT NULL, care_type VARCHAR(255) NOT NULL, care_extent LONGTEXT NOT NULL, bed_count INT NOT NULL, service_note LONGTEXT NOT NULL, professional_representative LONGTEXT NOT NULL, INDEX IDX_FD321567A7014910 (facility_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE healthcare_service ADD CONSTRAINT FK_FD321567A7014910 FOREIGN KEY (facility_id) REFERENCES healthcare_facility (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE healthcare_service DROP FOREIGN KEY FK_FD321567A7014910');
        $this->addSql('DROP TABLE healthcare_facility');
        $this->addSql('DROP TABLE healthcare_service');
    }
}
