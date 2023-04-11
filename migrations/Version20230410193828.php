<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230410193828 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE substance_value (id INT AUTO_INCREMENT NOT NULL, substance_id VARCHAR(255) NOT NULL, medical_product_id VARCHAR(255) NOT NULL, unit_id VARCHAR(255) DEFAULT NULL, value VARCHAR(255) DEFAULT NULL, INDEX IDX_D65D9713C707E018 (substance_id), INDEX IDX_D65D97136EC54F96 (medical_product_id), INDEX IDX_D65D9713F8BD700D (unit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE substance_value ADD CONSTRAINT FK_D65D9713C707E018 FOREIGN KEY (substance_id) REFERENCES substance (id)');
        $this->addSql('ALTER TABLE substance_value ADD CONSTRAINT FK_D65D97136EC54F96 FOREIGN KEY (medical_product_id) REFERENCES medical_product (id)');
        $this->addSql('ALTER TABLE substance_value ADD CONSTRAINT FK_D65D9713F8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE substance_value DROP FOREIGN KEY FK_D65D9713C707E018');
        $this->addSql('ALTER TABLE substance_value DROP FOREIGN KEY FK_D65D97136EC54F96');
        $this->addSql('ALTER TABLE substance_value DROP FOREIGN KEY FK_D65D9713F8BD700D');
        $this->addSql('DROP TABLE substance_value');
    }
}
