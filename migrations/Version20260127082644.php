<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260127082644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, is_read TINYINT NOT NULL, is_replied TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE comment CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE comment RENAME INDEX idx_9474526c8b8e8428 TO IDX_9474526C69058F5B');
        $this->addSql('ALTER TABLE rating CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE rating RENAME INDEX idx_d88926228b8e8428 TO IDX_D889262269058F5B');
        $this->addSql('ALTER TABLE rubiks_cube CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user_collection CHANGE added_at added_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user_collection RENAME INDEX idx_4fff3c2ba76ed395 TO IDX_5B2AA3DEA76ED395');
        $this->addSql('ALTER TABLE user_collection RENAME INDEX idx_4fff3c2b8b8e8428 TO IDX_5B2AA3DE69058F5B');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE comment CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE comment RENAME INDEX idx_9474526c69058f5b TO IDX_9474526C8B8E8428');
        $this->addSql('ALTER TABLE rating CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE rating RENAME INDEX idx_d889262269058f5b TO IDX_D88926228B8E8428');
        $this->addSql('ALTER TABLE rubiks_cube CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE `user` CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user_collection CHANGE added_at added_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user_collection RENAME INDEX idx_5b2aa3de69058f5b TO IDX_4FFF3C2B8B8E8428');
        $this->addSql('ALTER TABLE user_collection RENAME INDEX idx_5b2aa3dea76ed395 TO IDX_4FFF3C2BA76ED395');
    }
}
