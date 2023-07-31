<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230730143701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ad (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, isonhomepageslider_id INT DEFAULT NULL, ad_category_id INT NOT NULL, content LONGTEXT NOT NULL, excerpt LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, rooms INT NOT NULL, enablereviews TINYINT(1) NOT NULL, cover VARCHAR(255) NOT NULL, title VARCHAR(128) NOT NULL, slug VARCHAR(128) NOT NULL, is_featured TINYINT(1) DEFAULT 0 NOT NULL, is_portfolio TINYINT(1) DEFAULT 0 NOT NULL, is_published TINYINT(1) DEFAULT 0 NOT NULL, reference VARCHAR(255) NOT NULL, views INT DEFAULT NULL, tags VARCHAR(500) DEFAULT NULL, youtubeurl VARCHAR(255) DEFAULT \'#\', externallink VARCHAR(255) DEFAULT \'#\', phonenumber VARCHAR(50) DEFAULT NULL, email VARCHAR(180) DEFAULT NULL, twitterurl VARCHAR(255) DEFAULT \'#\', instagramurl VARCHAR(255) DEFAULT \'#\', facebookurl VARCHAR(255) DEFAULT \'#\', googleplusurl VARCHAR(255) DEFAULT \'#\', linkedinurl VARCHAR(255) DEFAULT \'#\', artists VARCHAR(500) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_77E0ED58989D9B62 (slug), UNIQUE INDEX UNIQ_77E0ED58AEA34913 (reference), INDEX IDX_77E0ED58F675F31B (author_id), INDEX IDX_77E0ED58376C51EF (isonhomepageslider_id), INDEX IDX_77E0ED58390D4E23 (ad_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorites (ad_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_E46960F54F34D596 (ad_id), INDEX IDX_E46960F5A76ED395 (user_id), PRIMARY KEY(ad_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ad_category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, image_name VARCHAR(50) DEFAULT NULL, image_size INT UNSIGNED DEFAULT NULL, image_mime_type VARCHAR(50) DEFAULT NULL, image_original_name VARCHAR(1000) DEFAULT NULL, image_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', name VARCHAR(128) NOT NULL, slug VARCHAR(128) NOT NULL, color VARCHAR(255) DEFAULT \'primary\', background VARCHAR(255) DEFAULT \'primary\', icon VARCHAR(50) DEFAULT NULL, hidden TINYINT(1) NOT NULL, is_featured TINYINT(1) DEFAULT 0 NOT NULL, featuredorder INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_EC541411989D9B62 (slug), INDEX IDX_EC541411727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_layout_setting (id INT AUTO_INCREMENT NOT NULL, logo_name VARCHAR(255) DEFAULT NULL, logo_size INT DEFAULT NULL, logo_mime_type VARCHAR(255) DEFAULT NULL, logo_original_name VARCHAR(1000) DEFAULT NULL, logo_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', favicon_name VARCHAR(255) DEFAULT NULL, favicon_size INT DEFAULT NULL, favicon_mime_type VARCHAR(255) DEFAULT NULL, favicon_original_name VARCHAR(1000) DEFAULT NULL, favicon_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', og_image_name VARCHAR(255) DEFAULT NULL, og_image_size INT DEFAULT NULL, og_image_mime_type VARCHAR(255) DEFAULT NULL, og_image_original_name VARCHAR(1000) DEFAULT NULL, og_image_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attachment (id INT AUTO_INCREMENT NOT NULL, attachment_name VARCHAR(255) NOT NULL, attachment_size INT UNSIGNED NOT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_category (id INT AUTO_INCREMENT NOT NULL, number_of_posts INT UNSIGNED NOT NULL, name VARCHAR(128) NOT NULL, slug VARCHAR(128) NOT NULL, color VARCHAR(255) DEFAULT \'primary\', background VARCHAR(255) DEFAULT \'primary\', icon VARCHAR(50) DEFAULT NULL, hidden TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_72113DE6989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_post (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, author_id INT NOT NULL, title VARCHAR(128) NOT NULL, slug VARCHAR(128) NOT NULL, readtime INT DEFAULT NULL, cover VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, deleted_at DATETIME DEFAULT NULL, excerpt LONGTEXT NOT NULL, hidden TINYINT(1) NOT NULL, published_at DATETIME DEFAULT NULL, state VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, views INT DEFAULT NULL, UNIQUE INDEX UNIQ_BA5AE01D989D9B62 (slug), INDEX IDX_BA5AE01D12469DE2 (category_id), INDEX IDX_BA5AE01DF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_post_tag (post_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_2E931ED74B89032C (post_id), INDEX IDX_2E931ED7BAD26311 (tag_id), PRIMARY KEY(post_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_post_like (post_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_65D6AA5C4B89032C (post_id), INDEX IDX_65D6AA5CA76ED395 (user_id), PRIMARY KEY(post_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_6EC39895E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, booker_id INT NOT NULL, ad_id INT NOT NULL, comment LONGTEXT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_E00CEDDE8B7E4006 (booker_id), INDEX IDX_E00CEDDE4F34D596 (ad_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, post_id INT DEFAULT NULL, ad_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, state VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, published_at DATETIME NOT NULL, ip VARCHAR(46) DEFAULT NULL, is_approved TINYINT(1) NOT NULL, is_rgpd TINYINT(1) DEFAULT 0 NOT NULL, rating INT NOT NULL, INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526C4B89032C (post_id), INDEX IDX_9474526C4F34D596 (ad_id), INDEX IDX_9474526C727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, fullname VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, subject VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, is_send TINYINT(1) NOT NULL, ip VARCHAR(46) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content (id INT AUTO_INCREMENT NOT NULL, attachment_id INT DEFAULT NULL, user_id INT DEFAULT NULL, title VARCHAR(128) NOT NULL, slug VARCHAR(128) NOT NULL, content LONGTEXT NOT NULL, excerpt LONGTEXT NOT NULL, views INT DEFAULT NULL, is_online TINYINT(1) DEFAULT 0 NOT NULL, published_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_FEC530A9989D9B62 (slug), INDEX IDX_FEC530A9464E68B (attachment_id), INDEX IDX_FEC530A9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE homepage_hero_settings (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) DEFAULT NULL, paragraph LONGTEXT DEFAULT NULL, content LONGTEXT DEFAULT NULL, custom_background_name VARCHAR(50) DEFAULT NULL, custom_background_size INT DEFAULT NULL, custom_background_mime_type VARCHAR(50) DEFAULT NULL, custom_background_original_name VARCHAR(1000) DEFAULT NULL, custom_background_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', show_search_box TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE login_attempt (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_8C11C1BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, ad_id INT DEFAULT NULL, post_id INT DEFAULT NULL, discr VARCHAR(255) NOT NULL, filename VARCHAR(255) DEFAULT NULL, alt VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, INDEX IDX_6A2CA10C4F34D596 (ad_id), INDEX IDX_6A2CA10C4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, header VARCHAR(128) DEFAULT NULL, name VARCHAR(128) NOT NULL, slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_7D053A93989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_element (id INT AUTO_INCREMENT NOT NULL, menu_id INT DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, custom_link VARCHAR(255) DEFAULT NULL, position INT NOT NULL, label VARCHAR(128) NOT NULL, slug VARCHAR(128) NOT NULL, icon VARCHAR(50) DEFAULT NULL, UNIQUE INDEX UNIQ_C99B4387989D9B62 (slug), INDEX IDX_C99B4387CCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(128) NOT NULL, slug VARCHAR(128) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_140AB620989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, expired_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', token BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, ad_id INT DEFAULT NULL, user_id INT DEFAULT NULL, rating INT NOT NULL, details LONGTEXT DEFAULT NULL, visible TINYINT(1) NOT NULL, headline VARCHAR(128) DEFAULT NULL, slug VARCHAR(128) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_794381C6989D9B62 (slug), INDEX IDX_794381C64F34D596 (ad_id), INDEX IDX_794381C6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE revision (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, target_id INT NOT NULL, content LONGTEXT NOT NULL, status INT DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_6D6315CCF675F31B (author_id), INDEX IDX_6D6315CC158E0B66 (target_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_user (role_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_332CA4DDD60322AC (role_id), INDEX IDX_332CA4DDA76ED395 (user_id), PRIMARY KEY(role_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value LONGTEXT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_9F74B8985E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, nickname VARCHAR(30) NOT NULL, slug VARCHAR(30) NOT NULL, firstname VARCHAR(20) DEFAULT NULL, lastname VARCHAR(20) DEFAULT NULL, email VARCHAR(180) NOT NULL, about LONGTEXT DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, team TINYINT(1) DEFAULT 0 NOT NULL, youtubeurl VARCHAR(255) DEFAULT \'#\', externallink VARCHAR(255) DEFAULT \'#\', phonenumber VARCHAR(50) DEFAULT NULL, twitterurl VARCHAR(255) DEFAULT \'#\', instagramurl VARCHAR(255) DEFAULT \'#\', facebookurl VARCHAR(255) DEFAULT \'#\', googleplusurl VARCHAR(255) DEFAULT \'#\', linkedinurl VARCHAR(255) DEFAULT \'#\', artists VARCHAR(500) DEFAULT NULL, last_login DATETIME DEFAULT NULL, last_login_ip VARCHAR(255) DEFAULT NULL, banned_at DATETIME DEFAULT NULL, suspended TINYINT(1) NOT NULL, is_verified TINYINT(1) NOT NULL, registration_token BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', registration_token_life_time DATETIME NOT NULL, facebook_profile_picture VARCHAR(1000) DEFAULT NULL, facebook_id VARCHAR(255) DEFAULT NULL, facebook_access_token VARCHAR(255) DEFAULT NULL, google_id VARCHAR(255) DEFAULT NULL, google_access_tokenn VARCHAR(255) DEFAULT NULL, api_key VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649A188FE64 (nickname), UNIQUE INDEX UNIQ_8D93D649989D9B62 (slug), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649C912ED9D (api_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED58F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED58376C51EF FOREIGN KEY (isonhomepageslider_id) REFERENCES homepage_hero_settings (id)');
        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED58390D4E23 FOREIGN KEY (ad_category_id) REFERENCES ad_category (id)');
        $this->addSql('ALTER TABLE favorites ADD CONSTRAINT FK_E46960F54F34D596 FOREIGN KEY (ad_id) REFERENCES ad (id)');
        $this->addSql('ALTER TABLE favorites ADD CONSTRAINT FK_E46960F5A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE ad_category ADD CONSTRAINT FK_EC541411727ACA70 FOREIGN KEY (parent_id) REFERENCES ad_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01D12469DE2 FOREIGN KEY (category_id) REFERENCES blog_category (id)');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01DF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE blog_post_tag ADD CONSTRAINT FK_2E931ED74B89032C FOREIGN KEY (post_id) REFERENCES blog_post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blog_post_tag ADD CONSTRAINT FK_2E931ED7BAD26311 FOREIGN KEY (tag_id) REFERENCES blog_tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_post_like ADD CONSTRAINT FK_65D6AA5C4B89032C FOREIGN KEY (post_id) REFERENCES blog_post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_post_like ADD CONSTRAINT FK_65D6AA5CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE8B7E4006 FOREIGN KEY (booker_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE4F34D596 FOREIGN KEY (ad_id) REFERENCES ad (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES blog_post (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4F34D596 FOREIGN KEY (ad_id) REFERENCES ad (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C727ACA70 FOREIGN KEY (parent_id) REFERENCES comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9464E68B FOREIGN KEY (attachment_id) REFERENCES attachment (id)');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE login_attempt ADD CONSTRAINT FK_8C11C1BA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C4F34D596 FOREIGN KEY (ad_id) REFERENCES ad (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C4B89032C FOREIGN KEY (post_id) REFERENCES blog_post (id)');
        $this->addSql('ALTER TABLE menu_element ADD CONSTRAINT FK_C99B4387CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C64F34D596 FOREIGN KEY (ad_id) REFERENCES ad (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE revision ADD CONSTRAINT FK_6D6315CCF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE revision ADD CONSTRAINT FK_6D6315CC158E0B66 FOREIGN KEY (target_id) REFERENCES content (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad DROP FOREIGN KEY FK_77E0ED58F675F31B');
        $this->addSql('ALTER TABLE ad DROP FOREIGN KEY FK_77E0ED58376C51EF');
        $this->addSql('ALTER TABLE ad DROP FOREIGN KEY FK_77E0ED58390D4E23');
        $this->addSql('ALTER TABLE favorites DROP FOREIGN KEY FK_E46960F54F34D596');
        $this->addSql('ALTER TABLE favorites DROP FOREIGN KEY FK_E46960F5A76ED395');
        $this->addSql('ALTER TABLE ad_category DROP FOREIGN KEY FK_EC541411727ACA70');
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01D12469DE2');
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01DF675F31B');
        $this->addSql('ALTER TABLE blog_post_tag DROP FOREIGN KEY FK_2E931ED74B89032C');
        $this->addSql('ALTER TABLE blog_post_tag DROP FOREIGN KEY FK_2E931ED7BAD26311');
        $this->addSql('ALTER TABLE user_post_like DROP FOREIGN KEY FK_65D6AA5C4B89032C');
        $this->addSql('ALTER TABLE user_post_like DROP FOREIGN KEY FK_65D6AA5CA76ED395');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE8B7E4006');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE4F34D596');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4F34D596');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C727ACA70');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9464E68B');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9A76ED395');
        $this->addSql('ALTER TABLE login_attempt DROP FOREIGN KEY FK_8C11C1BA76ED395');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C4F34D596');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C4B89032C');
        $this->addSql('ALTER TABLE menu_element DROP FOREIGN KEY FK_C99B4387CCD7E912');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C64F34D596');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE revision DROP FOREIGN KEY FK_6D6315CCF675F31B');
        $this->addSql('ALTER TABLE revision DROP FOREIGN KEY FK_6D6315CC158E0B66');
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDD60322AC');
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDA76ED395');
        $this->addSql('DROP TABLE ad');
        $this->addSql('DROP TABLE favorites');
        $this->addSql('DROP TABLE ad_category');
        $this->addSql('DROP TABLE app_layout_setting');
        $this->addSql('DROP TABLE attachment');
        $this->addSql('DROP TABLE blog_category');
        $this->addSql('DROP TABLE blog_post');
        $this->addSql('DROP TABLE blog_post_tag');
        $this->addSql('DROP TABLE user_post_like');
        $this->addSql('DROP TABLE blog_tag');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE content');
        $this->addSql('DROP TABLE homepage_hero_settings');
        $this->addSql('DROP TABLE login_attempt');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE menu_element');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE revision');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_user');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
