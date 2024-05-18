<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240513112650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dictionary DROP FOREIGN KEY FK_1FA0E52663977652');
        $this->addSql('DROP INDEX IDX_1FA0E52663977652 ON dictionary');
        $this->addSql('ALTER TABLE dictionary DROP mot_id');
        $this->addSql('ALTER TABLE scores ADD user_id INT DEFAULT NULL, ADD word_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE scores ADD CONSTRAINT FK_750375EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE scores ADD CONSTRAINT FK_750375EE357438D FOREIGN KEY (word_id) REFERENCES dictionary (id)');
        $this->addSql('CREATE INDEX IDX_750375EA76ED395 ON scores (user_id)');
        $this->addSql('CREATE INDEX IDX_750375EE357438D ON scores (word_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649FB0F374D');
        $this->addSql('DROP INDEX IDX_8D93D649FB0F374D ON user');
        $this->addSql('ALTER TABLE user DROP scores_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dictionary ADD mot_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dictionary ADD CONSTRAINT FK_1FA0E52663977652 FOREIGN KEY (mot_id) REFERENCES scores (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_1FA0E52663977652 ON dictionary (mot_id)');
        $this->addSql('ALTER TABLE user ADD scores_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649FB0F374D FOREIGN KEY (scores_id) REFERENCES scores (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8D93D649FB0F374D ON user (scores_id)');
        $this->addSql('ALTER TABLE scores DROP FOREIGN KEY FK_750375EA76ED395');
        $this->addSql('ALTER TABLE scores DROP FOREIGN KEY FK_750375EE357438D');
        $this->addSql('DROP INDEX IDX_750375EA76ED395 ON scores');
        $this->addSql('DROP INDEX IDX_750375EE357438D ON scores');
        $this->addSql('ALTER TABLE scores DROP user_id, DROP word_id');
    }
}
