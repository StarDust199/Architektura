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

            $this->addSql('INSERT INTO `users` (`id`, `username`, `password`, `email`, `nickname`, `reset_token`, `reset_token_expires_at`) VALUES (1, "markopolo", "zaq1@WSX", "markopolo@kanbala.com", "MarkoKurczePolo", NULL, NULL)');
            $this->addSql('INSERT INTO `users` (`id`, `username`, `password`, `email`, `nickname`, `reset_token`, `reset_token_expires_at`) VALUES (2, "xxwojoxx", "zaq1@WSX", "xxwojoxx@kanbanal.com", "HighEloKoxWoj", NULL, NULL)');
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
            $this->addSql('INSERT INTO `board_members` (`id`, `user_id_id`, `board_id_id`) VALUES (1, 1, 2)');
            $this->addSql('INSERT INTO `board_members` (`id`, `user_id_id`, `board_id_id`) VALUES (2, 2, 1)');
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
            $this->addSql('INSERT INTO `boards` (`id`, `user_id_id`, `title`, `created_at`) VALUES (1, 2, "Kanbanal Project", "2024-05-08 10:37:21")');
            $this->addSql('INSERT INTO `boards` (`id`, `user_id_id`, `title`, `created_at`) VALUES (2, 1, "Evil Kanbanal Project", "2024-05-08 10:43:42")');
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

            $this->addSql('INSERT INTO `chat_messages` (`id`, `board_id_id`, `user_id_id`, `content`, `created_at`) VALUES (1, 2, 1, "Zly kanbanal jest jak, dzialam XD", "2024-05-08 11:44:42")');
            $this->addSql('INSERT INTO `chat_messages` (`id`, `board_id_id`, `user_id_id`, `content`, `created_at`) VALUES (2, 1, 2, "Tutaj zaczyna sie moje imperium", "2024-05-08 11:21:37")');
        }
        //Status 1 - TODO, 2 - IN PROGRESS, 3 - DONE
        if (!$schema->hasTable('tasks')) {
            $this->addSql('CREATE TABLE tasks (
                id INT AUTO_INCREMENT NOT NULL, 
                user_id_id INT DEFAULT NULL, 
                board_id_id INT DEFAULT NULL, 
                title VARCHAR(255) NOT NULL, 
                description TINYTEXT NOT NULL, 
                status INT NOT NULL, 
                INDEX IDX_505865979D86650F (user_id_id), 
                INDEX IDX_50586597DDF9797C (board_id_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('INSERT INTO `tasks` (`id`, `user_id_id`, `board_id_id`, `title`, `description`, `status`) VALUES (1, 1, 2, "Naprawic kanbanal", "No ogolnie to zrob tak zeby dzialalo", 3)');
            $this->addSql('INSERT INTO `tasks` (`id`, `user_id_id`, `board_id_id`, `title`, `description`, `status`) VALUES (2, 2, 1, "Naprawic kanbanal ale inny", "Klejone tasma ale jest", 2)');
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
