<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230525164110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE country_tax CHANGE tax tax NUMERIC(16, 2) NOT NULL');
        $this->addSql('ALTER TABLE coupon CHANGE discount discount NUMERIC(16, 2) NOT NULL');
        $this->addSql('ALTER TABLE product CHANGE price price NUMERIC(16, 2) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE country_tax CHANGE tax tax DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE product CHANGE price price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE coupon CHANGE discount discount DOUBLE PRECISION NOT NULL');
    }
}
