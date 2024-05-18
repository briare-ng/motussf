<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240509083018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dictionary ADD mot_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dictionary ADD CONSTRAINT FK_1FA0E52663977652 FOREIGN KEY (mot_id) REFERENCES scores (id)');
        $this->addSql('CREATE INDEX IDX_1FA0E52663977652 ON dictionary (mot_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dictionary DROP FOREIGN KEY FK_1FA0E52663977652');
        $this->addSql('DROP INDEX IDX_1FA0E52663977652 ON dictionary');
        $this->addSql('ALTER TABLE dictionary DROP mot_id');
    }
}
