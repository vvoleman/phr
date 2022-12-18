<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221217132528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE addiction (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE administration_method (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, name_en VARCHAR(255) NOT NULL, edqm_code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, name_en VARCHAR(255) NOT NULL, edqm_code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dispensing (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE doping (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE indication_group (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medical_product (id VARCHAR(255) NOT NULL, form_id VARCHAR(255) DEFAULT NULL, administration_method_id VARCHAR(255) DEFAULT NULL, wrapping_id VARCHAR(255) DEFAULT NULL, country_holder_id VARCHAR(255) DEFAULT NULL, registration_status_id VARCHAR(255) DEFAULT NULL, indication_group_id VARCHAR(255) DEFAULT NULL, dispensing_id VARCHAR(255) DEFAULT NULL, addiction_id VARCHAR(255) DEFAULT NULL, doping_id VARCHAR(255) DEFAULT NULL, document_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, strength VARCHAR(255) NOT NULL, packaging VARCHAR(255) NOT NULL, addition VARCHAR(255) NOT NULL, registration_holder VARCHAR(255) NOT NULL, recently_delivered TINYINT(1) NOT NULL, expiration_hours INT DEFAULT NULL, INDEX IDX_9548E4635FF69B7D (form_id), INDEX IDX_9548E46315BADB29 (administration_method_id), INDEX IDX_9548E463730A3343 (wrapping_id), INDEX IDX_9548E4636B0D67B0 (country_holder_id), INDEX IDX_9548E46388ABFED8 (registration_status_id), INDEX IDX_9548E463C24E3835 (indication_group_id), INDEX IDX_9548E463DD51E94B (dispensing_id), INDEX IDX_9548E46330C0E13B (addiction_id), INDEX IDX_9548E463342DE21C (doping_id), UNIQUE INDEX UNIQ_9548E463C33F7837 (document_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medical_product_substance (medical_product_id VARCHAR(255) NOT NULL, substance_id VARCHAR(255) NOT NULL, INDEX IDX_B39F85536EC54F96 (medical_product_id), INDEX IDX_B39F8553C707E018 (substance_id), PRIMARY KEY(medical_product_id, substance_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_document (id VARCHAR(255) NOT NULL, file_name VARCHAR(255) NOT NULL, leaflet_decision_date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_form (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, name_en VARCHAR(255) NOT NULL, name_lat VARCHAR(255) NOT NULL, is_cannabis TINYINT(1) NOT NULL, edqm_code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registration_status (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE source (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE substance (id VARCHAR(255) NOT NULL, source_id VARCHAR(255) DEFAULT NULL, addiction_id VARCHAR(255) DEFAULT NULL, doping_id VARCHAR(255) DEFAULT NULL, name_inn VARCHAR(255) NOT NULL, name_en VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_E481CB19953C1C61 (source_id), INDEX IDX_E481CB1930C0E13B (addiction_id), INDEX IDX_E481CB19342DE21C (doping_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unit (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wrapping (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, name_en VARCHAR(255) NOT NULL, edqm_code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE medical_product ADD CONSTRAINT FK_9548E4635FF69B7D FOREIGN KEY (form_id) REFERENCES product_form (id)');
        $this->addSql('ALTER TABLE medical_product ADD CONSTRAINT FK_9548E46315BADB29 FOREIGN KEY (administration_method_id) REFERENCES administration_method (id)');
        $this->addSql('ALTER TABLE medical_product ADD CONSTRAINT FK_9548E463730A3343 FOREIGN KEY (wrapping_id) REFERENCES wrapping (id)');
        $this->addSql('ALTER TABLE medical_product ADD CONSTRAINT FK_9548E4636B0D67B0 FOREIGN KEY (country_holder_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE medical_product ADD CONSTRAINT FK_9548E46388ABFED8 FOREIGN KEY (registration_status_id) REFERENCES registration_status (id)');
        $this->addSql('ALTER TABLE medical_product ADD CONSTRAINT FK_9548E463C24E3835 FOREIGN KEY (indication_group_id) REFERENCES indication_group (id)');
        $this->addSql('ALTER TABLE medical_product ADD CONSTRAINT FK_9548E463DD51E94B FOREIGN KEY (dispensing_id) REFERENCES dispensing (id)');
        $this->addSql('ALTER TABLE medical_product ADD CONSTRAINT FK_9548E46330C0E13B FOREIGN KEY (addiction_id) REFERENCES addiction (id)');
        $this->addSql('ALTER TABLE medical_product ADD CONSTRAINT FK_9548E463342DE21C FOREIGN KEY (doping_id) REFERENCES doping (id)');
        $this->addSql('ALTER TABLE medical_product ADD CONSTRAINT FK_9548E463C33F7837 FOREIGN KEY (document_id) REFERENCES product_document (id)');
        $this->addSql('ALTER TABLE medical_product_substance ADD CONSTRAINT FK_B39F85536EC54F96 FOREIGN KEY (medical_product_id) REFERENCES medical_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medical_product_substance ADD CONSTRAINT FK_B39F8553C707E018 FOREIGN KEY (substance_id) REFERENCES substance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE substance ADD CONSTRAINT FK_E481CB19953C1C61 FOREIGN KEY (source_id) REFERENCES source (id)');
        $this->addSql('ALTER TABLE substance ADD CONSTRAINT FK_E481CB1930C0E13B FOREIGN KEY (addiction_id) REFERENCES addiction (id)');
        $this->addSql('ALTER TABLE substance ADD CONSTRAINT FK_E481CB19342DE21C FOREIGN KEY (doping_id) REFERENCES doping (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medical_product DROP FOREIGN KEY FK_9548E4635FF69B7D');
        $this->addSql('ALTER TABLE medical_product DROP FOREIGN KEY FK_9548E46315BADB29');
        $this->addSql('ALTER TABLE medical_product DROP FOREIGN KEY FK_9548E463730A3343');
        $this->addSql('ALTER TABLE medical_product DROP FOREIGN KEY FK_9548E4636B0D67B0');
        $this->addSql('ALTER TABLE medical_product DROP FOREIGN KEY FK_9548E46388ABFED8');
        $this->addSql('ALTER TABLE medical_product DROP FOREIGN KEY FK_9548E463C24E3835');
        $this->addSql('ALTER TABLE medical_product DROP FOREIGN KEY FK_9548E463DD51E94B');
        $this->addSql('ALTER TABLE medical_product DROP FOREIGN KEY FK_9548E46330C0E13B');
        $this->addSql('ALTER TABLE medical_product DROP FOREIGN KEY FK_9548E463342DE21C');
        $this->addSql('ALTER TABLE medical_product DROP FOREIGN KEY FK_9548E463C33F7837');
        $this->addSql('ALTER TABLE medical_product_substance DROP FOREIGN KEY FK_B39F85536EC54F96');
        $this->addSql('ALTER TABLE medical_product_substance DROP FOREIGN KEY FK_B39F8553C707E018');
        $this->addSql('ALTER TABLE substance DROP FOREIGN KEY FK_E481CB19953C1C61');
        $this->addSql('ALTER TABLE substance DROP FOREIGN KEY FK_E481CB1930C0E13B');
        $this->addSql('ALTER TABLE substance DROP FOREIGN KEY FK_E481CB19342DE21C');
        $this->addSql('DROP TABLE addiction');
        $this->addSql('DROP TABLE administration_method');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE dispensing');
        $this->addSql('DROP TABLE doping');
        $this->addSql('DROP TABLE indication_group');
        $this->addSql('DROP TABLE medical_product');
        $this->addSql('DROP TABLE medical_product_substance');
        $this->addSql('DROP TABLE product_document');
        $this->addSql('DROP TABLE product_form');
        $this->addSql('DROP TABLE registration_status');
        $this->addSql('DROP TABLE source');
        $this->addSql('DROP TABLE substance');
        $this->addSql('DROP TABLE unit');
        $this->addSql('DROP TABLE wrapping');
    }
}
