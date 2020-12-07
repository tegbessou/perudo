<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201207155432 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE bet');
        $this->addSql('ALTER TABLE player ADD my_turn TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bet (id INT AUTO_INCREMENT NOT NULL, game_id INT DEFAULT NULL, player_id INT DEFAULT NULL, dice_number INT NOT NULL, dice_value INT NOT NULL, INDEX IDX_FBF0EC9B99E6F5DF (player_id), INDEX IDX_FBF0EC9BE48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT FK_FBF0EC9B99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT FK_FBF0EC9BE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE player DROP my_turn');
    }
}
