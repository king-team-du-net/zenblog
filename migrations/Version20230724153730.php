<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230724153730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_post CHANGE published_at published_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE homepage_hero_settings ADD custom_background_name VARCHAR(50) DEFAULT NULL, ADD custom_background_size INT DEFAULT NULL, ADD custom_background_mime_type VARCHAR(50) DEFAULT NULL, ADD custom_background_original_name VARCHAR(1000) DEFAULT NULL, ADD custom_background_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_post CHANGE published_at published_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE homepage_hero_settings DROP custom_background_name, DROP custom_background_size, DROP custom_background_mime_type, DROP custom_background_original_name, DROP custom_background_dimensions');
    }
}
