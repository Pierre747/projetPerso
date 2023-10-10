<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231010130259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit ADD owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC277E3C61F9 FOREIGN KEY (owner_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC277E3C61F9 ON produit (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC277E3C61F9');
        $this->addSql('DROP INDEX IDX_29A5EC277E3C61F9 ON produit');
        $this->addSql('ALTER TABLE produit DROP owner_id');
    }
}
