<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228125017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE survey_fill_up_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE survey_fill_up_request (id INT NOT NULL, poll_id INT NOT NULL, email VARCHAR(255) NOT NULL, hashed_token VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A52167E73C947C0F ON survey_fill_up_request (poll_id)');
        $this->addSql('ALTER TABLE survey_fill_up_request ADD CONSTRAINT FK_A52167E73C947C0F FOREIGN KEY (poll_id) REFERENCES poll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ALTER admin_can_see DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE survey_fill_up_request_id_seq CASCADE');
        $this->addSql('ALTER TABLE survey_fill_up_request DROP CONSTRAINT FK_A52167E73C947C0F');
        $this->addSql('DROP TABLE survey_fill_up_request');
        $this->addSql('ALTER TABLE "user" ALTER admin_can_see SET DEFAULT false');
    }
}
