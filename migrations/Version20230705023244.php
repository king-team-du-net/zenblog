<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230705023244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD youtubeurl VARCHAR(255) DEFAULT NULL, ADD youtube_url VARCHAR(255) DEFAULT NULL, ADD externallink VARCHAR(255) DEFAULT NULL, ADD phonenumber VARCHAR(50) DEFAULT NULL, ADD twitter_url VARCHAR(255) DEFAULT NULL, ADD instagram_url VARCHAR(255) DEFAULT NULL, ADD facebook_url VARCHAR(255) DEFAULT NULL, ADD googleplus_url VARCHAR(255) DEFAULT NULL, ADD linkedin_url VARCHAR(255) DEFAULT NULL, ADD facebook_profile_picture VARCHAR(1000) DEFAULT NULL, ADD facebook_id VARCHAR(255) DEFAULT NULL, ADD facebook_access_token VARCHAR(255) DEFAULT NULL, ADD google_id VARCHAR(255) DEFAULT NULL, ADD google_access_tokenn VARCHAR(255) DEFAULT NULL, ADD api_key VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649C912ED9D ON user (api_key)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_8D93D649C912ED9D ON `user`');
        $this->addSql('ALTER TABLE `user` DROP youtubeurl, DROP youtube_url, DROP externallink, DROP phonenumber, DROP twitter_url, DROP instagram_url, DROP facebook_url, DROP googleplus_url, DROP linkedin_url, DROP facebook_profile_picture, DROP facebook_id, DROP facebook_access_token, DROP google_id, DROP google_access_tokenn, DROP api_key');
    }
}