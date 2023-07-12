<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230711212115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_layout_setting (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE homepage_hero_settings (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) DEFAULT NULL, paragraph LONGTEXT DEFAULT NULL, content LONGTEXT DEFAULT NULL, show_search_box TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_post ADD isonhomepageslider_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01D376C51EF FOREIGN KEY (isonhomepageslider_id) REFERENCES homepage_hero_settings (id)');
        $this->addSql('CREATE INDEX IDX_BA5AE01D376C51EF ON blog_post (isonhomepageslider_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01D376C51EF');
        $this->addSql('DROP TABLE app_layout_setting');
        $this->addSql('DROP TABLE homepage_hero_settings');
        $this->addSql('DROP INDEX IDX_BA5AE01D376C51EF ON blog_post');
        $this->addSql('ALTER TABLE blog_post DROP isonhomepageslider_id');
    }
}
