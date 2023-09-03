<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230903124511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create band table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE band_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE band (
          id INT NOT NULL,
          name VARCHAR(255) NOT NULL,
          origin VARCHAR(255) NOT NULL,
          city VARCHAR(255) NOT NULL,
          start_year INT NOT NULL,
          separation_year INT DEFAULT NULL,
          founders VARCHAR(255) DEFAULT NULL,
          members INT DEFAULT NULL,
          musical_current VARCHAR(255) DEFAULT NULL,
          presentation TEXT NOT NULL,
          PRIMARY KEY(id)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE band_id_seq CASCADE');
        $this->addSql('DROP TABLE band');
    }
}
