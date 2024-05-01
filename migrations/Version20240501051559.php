<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240501051559_Combined extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Create tables only if they don't exist
        if (!$schema->hasTable('users')) {
            $this->addSql('CREATE TABLE users (
                id INT AUTO_INCREMENT NOT NULL, 
                username VARCHAR(255) NOT NULL, 
                password VARCHAR(255) NOT NULL, 
                email VARCHAR(255) NOT NULL, 
                nickname VARCHAR(255) NOT NULL, 
                reset_token VARCHAR(255) DEFAULT NULL, 
                reset_token_expires_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        if (!$schema->hasTable('board_members')) {
            $this->addSql('CREATE TABLE board_members (
                id INT AUTO_INCREMENT NOT NULL, 
                user_id_id INT DEFAULT NULL, 
                board_id_id INT DEFAULT NULL, 
                INDEX IDX_DBEFAF09D86650F (user_id_id), 
                INDEX IDX_DBEFAF0DDF9797C (board_id_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        if (!$schema->hasTable('boards')) {
            $this->addSql('CREATE TABLE boards (
                id INT AUTO_INCREMENT NOT NULL, 
                user_id_id INT DEFAULT NULL, 
                title VARCHAR(255) NOT NULL, 
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
                INDEX IDX_F3EE4D139D86650F (user_id_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        if (!$schema->hasTable('chat_messages')) {
            $this->addSql('CREATE TABLE chat_messages (
                id INT AUTO_INCREMENT NOT NULL, 
                board_id_id INT DEFAULT NULL, 
                user_id_id INT DEFAULT NULL, 
                content LONGTEXT NOT NULL, 
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
                INDEX IDX_EF20C9A6DDF9797C (board_id_id), 
                INDEX IDX_EF20C9A69D86650F (user_id_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        if (!$schema->hasTable('tasks')) {
            $this->addSql('CREATE TABLE tasks (
                id INT AUTO_INCREMENT NOT NULL, 
                user_id_id INT DEFAULT NULL, 
                board_id_id INT DEFAULT NULL, 
                title VARCHAR(255) NOT NULL, 
                description TINYTEXT NOT NULL, 
                status VARCHAR(255) NOT NULL, 
                INDEX IDX_505865979D86650F (user_id_id), 
                INDEX IDX_50586597DDF9797C (board_id_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        // Add foreign key constraints
        if (!$schema->hasTable('board_members')) {
            $this->addSql('ALTER TABLE board_members ADD CONSTRAINT FK_DBEFAF09D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
            $this->addSql('ALTER TABLE board_members ADD CONSTRAINT FK_DBEFAF0DDF9797C FOREIGN KEY (board_id_id) REFERENCES boards (id)');
        }

        if (!$schema->hasTable('boards')) {
            $this->addSql('ALTER TABLE boards ADD CONSTRAINT FK_F3EE4D139D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        }

        if (!$schema->hasTable('chat_messages')) {
            $this->addSql('ALTER TABLE chat_messages ADD CONSTRAINT FK_EF20C9A6DDF9797C FOREIGN KEY (board_id_id) REFERENCES boards (id)');
            $this->addSql('ALTER TABLE chat_messages ADD CONSTRAINT FK_EF20C9A69D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        }

        if (!$schema->hasTable('tasks')) {
            $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_505865979D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
            $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597DDF9797C FOREIGN KEY (board_id_id) REFERENCES boards (id)');
        }
    }

    public function down(Schema $schema): void
    {
        // Drop tables
        $this->addSql('DROP TABLE IF EXISTS tasks');
        $this->addSql('DROP TABLE IF EXISTS chat_messages');
        $this->addSql('DROP TABLE IF EXISTS boards');
        $this->addSql('DROP TABLE IF EXISTS board_members');
        $this->addSql('DROP TABLE IF EXISTS users');
    }
}
