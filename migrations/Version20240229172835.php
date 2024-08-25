<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229172835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE poll ADD survey_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE poll DROP survey');
        $this->addSql('ALTER TABLE poll ADD CONSTRAINT FK_84BCFA45B3FE509D FOREIGN KEY (survey_id) REFERENCES survey (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_84BCFA45B3FE509D ON poll (survey_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE poll DROP CONSTRAINT FK_84BCFA45B3FE509D');
        $this->addSql('DROP INDEX IDX_84BCFA45B3FE509D');
        $this->addSql('ALTER TABLE poll ADD survey VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE poll DROP survey_id');
    }
}
