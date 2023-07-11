<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230705140707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorites (post_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_E46960F54B89032C (post_id), INDEX IDX_E46960F5A76ED395 (user_id), PRIMARY KEY(post_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, user_id INT DEFAULT NULL, rating INT NOT NULL, details LONGTEXT DEFAULT NULL, visible TINYINT(1) NOT NULL, headline VARCHAR(128) DEFAULT NULL, slug VARCHAR(128) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_794381C6989D9B62 (slug), INDEX IDX_794381C64B89032C (post_id), INDEX IDX_794381C6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorites ADD CONSTRAINT FK_E46960F54B89032C FOREIGN KEY (post_id) REFERENCES blog_post (id)');
        $this->addSql('ALTER TABLE favorites ADD CONSTRAINT FK_E46960F5A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C64B89032C FOREIGN KEY (post_id) REFERENCES blog_post (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE blog_post ADD enablereviews TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE youtubeurl youtubeurl VARCHAR(255) DEFAULT \'#\', CHANGE externallink externallink VARCHAR(255) DEFAULT \'#\', CHANGE instagramurl instagramurl VARCHAR(255) DEFAULT \'#\', CHANGE facebookurl facebookurl VARCHAR(255) DEFAULT \'#\', CHANGE googleplusurl googleplusurl VARCHAR(255) DEFAULT \'#\', CHANGE linkedinurl linkedinurl VARCHAR(255) DEFAULT \'#\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorites DROP FOREIGN KEY FK_E46960F54B89032C');
        $this->addSql('ALTER TABLE favorites DROP FOREIGN KEY FK_E46960F5A76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C64B89032C');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('DROP TABLE favorites');
        $this->addSql('DROP TABLE review');
        $this->addSql('ALTER TABLE blog_post DROP enablereviews');
        $this->addSql('ALTER TABLE `user` CHANGE youtubeurl youtubeurl VARCHAR(255) DEFAULT NULL, CHANGE externallink externallink VARCHAR(255) DEFAULT NULL, CHANGE instagramurl instagramurl VARCHAR(255) DEFAULT NULL, CHANGE facebookurl facebookurl VARCHAR(255) DEFAULT NULL, CHANGE googleplusurl googleplusurl VARCHAR(255) DEFAULT NULL, CHANGE linkedinurl linkedinurl VARCHAR(255) DEFAULT NULL');
    }
}
