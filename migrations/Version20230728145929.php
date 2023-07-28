<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230728145929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ad_category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, image_name VARCHAR(50) DEFAULT NULL, image_size INT UNSIGNED DEFAULT NULL, image_mime_type VARCHAR(50) DEFAULT NULL, image_original_name VARCHAR(1000) DEFAULT NULL, image_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', name VARCHAR(128) NOT NULL, slug VARCHAR(128) NOT NULL, icon VARCHAR(50) DEFAULT NULL, hidden TINYINT(1) NOT NULL, is_featured TINYINT(1) DEFAULT 0 NOT NULL, featuredorder INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_EC541411989D9B62 (slug), INDEX IDX_EC541411727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ad_category ADD CONSTRAINT FK_EC541411727ACA70 FOREIGN KEY (parent_id) REFERENCES ad_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ad ADD categories_id INT NOT NULL');
        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED58A21214B7 FOREIGN KEY (categories_id) REFERENCES ad_category (id)');
        $this->addSql('CREATE INDEX IDX_77E0ED58A21214B7 ON ad (categories_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad DROP FOREIGN KEY FK_77E0ED58A21214B7');
        $this->addSql('ALTER TABLE ad_category DROP FOREIGN KEY FK_EC541411727ACA70');
        $this->addSql('DROP TABLE ad_category');
        $this->addSql('DROP INDEX IDX_77E0ED58A21214B7 ON ad');
        $this->addSql('ALTER TABLE ad DROP categories_id');
    }
}
