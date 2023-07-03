<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230703114434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_category ADD color VARCHAR(255) DEFAULT NULL, ADD background VARCHAR(255) DEFAULT NULL, CHANGE hidden hidden TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE blog_post CHANGE hidden hidden TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_category DROP color, DROP background, CHANGE hidden hidden TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE blog_post CHANGE hidden hidden TINYINT(1) DEFAULT 0 NOT NULL');
    }
}
