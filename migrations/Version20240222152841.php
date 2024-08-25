<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240222152841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE poll_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE survey_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE answer (id INT NOT NULL, poll_id INT DEFAULT NULL, question VARCHAR(255) NOT NULL, response INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DADD4A253C947C0F ON answer (poll_id)');
        $this->addSql('CREATE TABLE poll (id INT NOT NULL, survey_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, count_fill_up INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_84BCFA45B3FE509D ON poll (survey_id)');
        $this->addSql('CREATE TABLE survey (id INT NOT NULL, creator_id INT NOT NULL, count INT NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AD5F9BFC61220EA6 ON survey (creator_id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A253C947C0F FOREIGN KEY (poll_id) REFERENCES poll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE poll ADD CONSTRAINT FK_84BCFA45B3FE509D FOREIGN KEY (survey_id) REFERENCES survey (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE survey ADD CONSTRAINT FK_AD5F9BFC61220EA6 FOREIGN KEY (creator_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE answer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE poll_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE survey_id_seq CASCADE');
        $this->addSql('ALTER TABLE answer DROP CONSTRAINT FK_DADD4A253C947C0F');
        $this->addSql('ALTER TABLE poll DROP CONSTRAINT FK_84BCFA45B3FE509D');
        $this->addSql('ALTER TABLE survey DROP CONSTRAINT FK_AD5F9BFC61220EA6');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE poll');
        $this->addSql('DROP TABLE survey');
    }
}
