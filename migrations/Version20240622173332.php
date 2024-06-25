<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 *
 * @SuppressWarnings(PHPMD)
 */
final class Version20240622173332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_stats (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, player_id INTEGER NOT NULL, start DATETIME NOT NULL, stop DATETIME DEFAULT NULL, round INTEGER DEFAULT NULL, CONSTRAINT FK_65741E2599E6F5DF FOREIGN KEY (player_id) REFERENCES player_stats (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_65741E2599E6F5DF ON game_stats (player_id)');
        $this->addSql('CREATE TABLE player_stats (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, balance FLOAT DEFAULT NULL, winning FLOAT DEFAULT NULL, bet INTEGER DEFAULT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE game_stats');
        $this->addSql('DROP TABLE player_stats');
    }
}
