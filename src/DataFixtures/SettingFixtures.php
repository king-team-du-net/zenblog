<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Setting;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class SettingFixtures extends Fixture
{
    use FakerTrait;

    public function __construct(private readonly ParameterBagInterface $params)
    {
    }

    /**
     * @param EntityManagerInterface $manager
     */
    public function load(ObjectManager $manager): void
    {
        /** @var string $content */
        $content = $this->faker()->realText(236);

        $settings = [
            1 => [
                'name' => 'website_name',
                'value' => $this->params->get('website_name'),
            ],
            2 => [
                'name' => 'website_slug',
                'value' => $this->params->get('website_slug'),
            ],
            3 => [
                'name' => 'website_root_url',
                'value' => $this->params->get('website_root_url'),
            ],
            4 => [
                'name' => 'website_url',
                'value' => 'https://127.0.0.1:8000',
            ],
            5 => [
                'name' => 'website_contact_email',
                'value' => $this->params->get('website_contact_email'),
            ],
            6 => [
                'name' => 'website_contact_phone',
                'value' => $this->params->get('website_contact_phone'),
            ],
            7 => [
                'name' => 'website_contact_fax',
                'value' => $this->params->get('website_contact_fax'),
            ],
            8 => [
                'name' => 'website_contact_address',
                'value' => $this->params->get('website_contact_address'),
            ],
            9 => [
                'name' => 'website_sav',
                'value' => $this->params->get('website_sav'),
            ],
            10 => [
                'name' => 'website_no_reply_email',
                'value' => $this->params->get('website_no_reply_email'),
            ],
            11 => [
                'name' => 'website_dashboard_path',
                'value' => $this->params->get('website_dashboard_path'),
            ],
            12 => [
                'name' => 'blog_posts_per_page',
                'value' => '4',
            ],
            13 => [
                'name' => 'blog_comments_per_page',
                'value' => '4',
            ],
            14 => [
                'name' => 'custom_css',
                'value' => '',
            ],
            15 => [
                'name' => 'show_back_to_top_button',
                'value' => 'yes',
            ],
            16 => [
                'name' => 'show_cookie_policy_bar',
                'value' => 'yes',
            ],
            17 => [
                'name' => 'primary_color',
                'value' => '#2163e8;',
            ],
            18 => [
                'name' => 'facebook_url',
                'value' => 'https://www.facebook.com',
            ],
            19 => [
                'name' => 'instagram_url',
                'value' => 'https://www.instagram.com',
            ],
            20 => [
                'name' => 'youtube_url',
                'value' => 'https://www.youtube.com',
            ],
            21 => [
                'name' => 'twitter_url',
                'value' => 'https://www.twitter.com',
            ],
            22 => [
                'name' => 'google_plus_url',
                'value' => 'https://www.google.com',
            ],
            23 => [
                'name' => 'linkedin_url',
                'value' => '#',
            ],
            24 => [
                'name' => 'google_analytics_code',
                'value' => '',
            ],
            25 => [
                'name' => 'google_recaptcha_enabled',
                'value' => 'no',
            ],
            26 => [
                'name' => 'google_recaptcha_site_key',
                'value' => '',
            ],
            27 => [
                'name' => 'google_recaptcha_secret_key',
                'value' => '',
            ],
            28 => [
                'name' => 'social_login_facebook_enabled',
                'value' => 'no',
            ],
            29 => [
                'name' => 'social_login_facebook_id',
                'value' => '',
            ],
            30 => [
                'name' => 'social_login_facebook_secret',
                'value' => '',
            ],
            31 => [
                'name' => 'social_login_google_id',
                'value' => '',
            ],
            32 => [
                'name' => 'social_login_google_secret',
                'value' => '',
            ],
            33 => [
                'name' => 'social_login_google_enabled',
                'value' => 'no',
            ],
            34 => [
                'name' => 'app_environment',
                'value' => 'dev',
            ],
            35 => [
                'name' => 'maintenance_mode',
                'value' => '0',
            ],
            36 => [
                'name' => 'app_theme',
                'value' => 'light',
            ],
            37 => [
                'name' => 'app_layout',
                'value' => 'container',
            ],
            38 => [
                'name' => 'users_can_register',
                'value' => 'yes',
            ],
            39 => [
                'name' => 'footer_about',
                'value' => $content,
            ],
            40 => [
                'name' => 'show_about_page',
                'value' => 'yes',
            ],
            41 => [
                'name' => 'about_page_slug',
                'value' => 'about-us',
            ],
            42 => [
                'name' => 'mail_server_transport',
                'value' => '',
            ],
            43 => [
                'name' => 'mail_server_host',
                'value' => '',
            ],
            44 => [
                'name' => 'mail_server_port',
                'value' => null,
            ],
            45 => [
                'name' => 'mail_server_encryption',
                'value' => null,
            ],
            46 => [
                'name' => 'mail_server_auth_mode',
                'value' => null,
            ],
            47 => [
                'name' => 'mail_server_username',
                'value' => '',
            ],
            48 => [
                'name' => 'mail_server_password',
                'value' => '',
            ],
            49 => [
                'name' => 'homepage_products_number',
                'value' => '8',
            ],
            50 => [
                'name' => 'homepage_categories_number',
                'value' => '8',
            ],
            51 => [
                'name' => 'homepage_posts_number',
                'value' => '8',
            ],
            52 => [
                'name' => 'homepage_show_search_box',
                'value' => 'no',
            ],
            53 => [
                'name' => 'homepage_show_call_to_action',
                'value' => 'yes',
            ],
            54 => [
                'name' => 'show_terms_of_service_page',
                'value' => 'yes',
            ],
            55 => [
                'name' => 'terms_of_service_page_content',
                'value' => 'terms_of_service_page_content',
            ],
            56 => [
                'name' => 'show_privacy_policy_page',
                'value' => 'yes',
            ],
            57 => [
                'name' => 'privacy_policy_page_content',
                'value' => 'privacy_policy_page_content',
            ],
            58 => [
                'name' => 'show_cookie_policy_page',
                'value' => 'yes',
            ],
            59 => [
                'name' => 'cookie_policy_page_content',
                'value' => 'yes',
            ],
            60 => [
                'name' => 'show_gdpr_compliance_page',
                'value' => 'yes',
            ],
            61 => [
                'name' => 'gdpr_compliance_page_content',
                'value' => 'gdpr_compliance_page_content',
            ],
            62 => [
                'name' => 'terms_of_service_page_slug',
                'value' => 'terms-of-service',
            ],
            63 => [
                'name' => 'privacy_policy_page_slug',
                'value' => 'privacy-policy',
            ],
            64 => [
                'name' => 'cookie_policy_page_slug',
                'value' => 'cookie-policy',
            ],
            65 => [
                'name' => 'gdpr_compliance_page_slug',
                'value' => 'gdpr-compliance',
            ],
            66 => [
                'name' => 'blog_comments_enabled',
                'value' => 'no',
            ],
            67 => [
                'name' => 'facebook_app_id',
                'value' => '',
            ],
            68 => [
                'name' => 'disqus_subdomain',
                'value' => '',
            ],
            69 => [
                'name' => 'newsletter_enabled',
                'value' => 'no',
            ],
            70 => [
                'name' => 'mailchimp_api_key',
                'value' => '',
            ],
            71 => [
                'name' => 'mailchimp_list_id',
                'value' => '',
            ],
            72 => [
                'name' => 'website_description_en',
                'value' => '',
            ],
            73 => [
                'name' => 'website_keywords_en',
                'value' => '',
            ],
            74 => [
                'name' => 'website_description_fr',
                'value' => '',
            ],
            75 => [
                'name' => 'website_keywords_fr',
                'value' => '',
            ],
            76 => [
                'name' => 'website_description_es',
                'value' => '',
            ],
            77 => [
                'name' => 'website_keywords_es',
                'value' => '',
            ],
            78 => [
                'name' => 'website_description_ar',
                'value' => '',
            ],
            79 => [
                'name' => 'website_keywords_ar',
                'value' => '',
            ],
            80 => [
                'name' => 'website_app_version',
                'value' => '1.04',
            ],
            81 => [
                'name' => 'website_google_iframe',
                'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.9663095343008!2d-74.00425878428698!3d40.74076684379132!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259bf5c1654f3%3A0xc80f9cfce5383d5d!2sGoogle!5e0!3m2!1sen!2sin!4v1586000412513!5m2!1sen!2sin',
            ]
        ];

        foreach ($settings as $key => $label) {
            $setting = (new Setting());
            $setting
                ->setName($label['name'])
                ->setValue($label['value'])
            ;
            $manager->persist($setting);
        }

        $manager->flush();
    }
}
