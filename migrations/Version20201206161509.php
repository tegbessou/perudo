<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201206161509 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, creator_id INT DEFAULT NULL, players_id INT DEFAULT NULL, number_of_players INT NOT NULL, uuid VARCHAR(255) NOT NULL, INDEX IDX_232B318C61220EA6 (creator_id), INDEX IDX_232B318CF1849495 (players_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(255) NOT NULL, bot TINYINT(1) NOT NULL, number_of_dices INT NOT NULL, dice_color VARCHAR(255) NOT NULL, dices LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', uuid VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C61220EA6 FOREIGN KEY (creator_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CF1849495 FOREIGN KEY (players_id) REFERENCES player (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C61220EA6');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CF1849495');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE player');
    }
}
