<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260123000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create initial database schema for Rubiks Cube Collection';
    }

    public function up(Schema $schema): void
    {
        // User table
        $this->addSql('CREATE TABLE `user` (
            id INT AUTO_INCREMENT NOT NULL,
            email VARCHAR(180) NOT NULL,
            roles JSON NOT NULL,
            password VARCHAR(255) NOT NULL,
            username VARCHAR(100) NOT NULL,
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // RubiksCube table
        $this->addSql('CREATE TABLE rubiks_cube (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(255) NOT NULL,
            type VARCHAR(100) NOT NULL,
            description LONGTEXT DEFAULT NULL,
            brand VARCHAR(100) DEFAULT NULL,
            image_url VARCHAR(255) DEFAULT NULL,
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            release_year INT DEFAULT NULL,
            difficulty VARCHAR(50) DEFAULT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // UserCollection table
        $this->addSql('CREATE TABLE user_collection (
            id INT AUTO_INCREMENT NOT NULL,
            user_id INT NOT NULL,
            rubiks_cube_id INT NOT NULL,
            added_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            personal_note LONGTEXT DEFAULT NULL,
            `condition` VARCHAR(50) DEFAULT NULL,
            purchase_price DOUBLE PRECISION DEFAULT NULL,
            INDEX IDX_4FFF3C2BA76ED395 (user_id),
            INDEX IDX_4FFF3C2B8B8E8428 (rubiks_cube_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Comment table
        $this->addSql('CREATE TABLE comment (
            id INT AUTO_INCREMENT NOT NULL,
            user_id INT NOT NULL,
            rubiks_cube_id INT NOT NULL,
            content LONGTEXT NOT NULL,
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            INDEX IDX_9474526CA76ED395 (user_id),
            INDEX IDX_9474526C8B8E8428 (rubiks_cube_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Rating table
        $this->addSql('CREATE TABLE rating (
            id INT AUTO_INCREMENT NOT NULL,
            user_id INT NOT NULL,
            rubiks_cube_id INT NOT NULL,
            rating INT NOT NULL,
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            INDEX IDX_D8892622A76ED395 (user_id),
            INDEX IDX_D88926228B8E8428 (rubiks_cube_id),
            UNIQUE INDEX user_rubikscube_unique (user_id, rubiks_cube_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Foreign keys
        $this->addSql('ALTER TABLE user_collection ADD CONSTRAINT FK_4FFF3C2BA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_collection ADD CONSTRAINT FK_4FFF3C2B8B8E8428 FOREIGN KEY (rubiks_cube_id) REFERENCES rubiks_cube (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C8B8E8428 FOREIGN KEY (rubiks_cube_id) REFERENCES rubiks_cube (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926228B8E8428 FOREIGN KEY (rubiks_cube_id) REFERENCES rubiks_cube (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_collection DROP FOREIGN KEY FK_4FFF3C2BA76ED395');
        $this->addSql('ALTER TABLE user_collection DROP FOREIGN KEY FK_4FFF3C2B8B8E8428');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C8B8E8428');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622A76ED395');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926228B8E8428');

        $this->addSql('DROP TABLE user_collection');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE rubiks_cube');
        $this->addSql('DROP TABLE `user`');
    }
}
