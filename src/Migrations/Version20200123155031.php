<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200123155031 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('ALTER TABLE movie ADD COLUMN number_of_voters INTEGER DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__movie AS SELECT id, title, url, release_date, synopsys, director, rating, user_id FROM movie');
        $this->addSql('DROP TABLE movie');
        $this->addSql('CREATE TABLE movie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, release_date DATE DEFAULT NULL, synopsys VARCHAR(255) NOT NULL, director VARCHAR(255) NOT NULL, rating INTEGER DEFAULT NULL, user_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO movie (id, title, url, release_date, synopsys, director, rating, user_id) SELECT id, title, url, release_date, synopsys, director, rating, user_id FROM __temp__movie');
        $this->addSql('DROP TABLE __temp__movie');
    }
}
