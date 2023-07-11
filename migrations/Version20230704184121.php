<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230704184121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_category CHANGE background background VARCHAR(255) DEFAULT \'default\'');
        $this->addSql('ALTER TABLE blog_tag ADD color VARCHAR(255) DEFAULT \'default\', ADD background VARCHAR(255) DEFAULT \'default\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_category CHANGE background background VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE blog_tag DROP color, DROP background');
    }
}
