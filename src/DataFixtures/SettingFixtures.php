<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\DataFixtures;

use App\Entity\Setting;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

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
                'type' => TextType::class,
            ],
            2 => [
                'name' => 'website_slug',
                'value' => $this->params->get('website_slug'),
                'type' => TextType::class,
            ],
            3 => [
                'name' => 'website_root_url',
                'value' => $this->params->get('website_root_url'),
                'type' => TextType::class,
            ],
            4 => [
                'name' => 'website_url',
                'value' => 'https://127.0.0.1:8000',
                'type' => UrlType::class,
            ],
            5 => [
                'name' => 'website_contact_email',
                'value' => $this->params->get('website_contact_email'),
                'type' => EmailType::class,
            ],
            6 => [
                'name' => 'website_contact_phone',
                'value' => $this->params->get('website_contact_phone'),
                'type' => TelType::class,
            ],
            7 => [
                'name' => 'website_contact_fax',
                'value' => $this->params->get('website_contact_fax'),
                'type' => TelType::class,
            ],
            8 => [
                'name' => 'website_contact_address',
                'value' => $this->params->get('website_contact_address'),
                'type' => TextareaType::class,
            ],
            9 => [
                'name' => 'website_sav',
                'value' => $this->params->get('website_sav'),
                'type' => EmailType::class,
            ],
            10 => [
                'name' => 'website_no_reply_email',
                'value' => $this->params->get('website_no_reply_email'),
                'type' => EmailType::class,
            ],
            11 => [
                'name' => 'website_dashboard_path',
                'value' => $this->params->get('website_dashboard_path'),
                'type' => TextType::class,
            ],
            12 => [
                'name' => 'blog_posts_per_page',
                'value' => 4,
                'type' => IntegerType::class,
            ],
            13 => [
                'name' => 'blog_comments_per_page',
                'value' => 4,
                'type' => IntegerType::class,
            ],
            14 => [
                'name' => 'custom_css',
                'value' => '',
                'type' => TextareaType::class,
            ],
            15 => [
                'name' => 'show_back_to_top_button',
                'value' => 'yes',
                'type' => CheckboxType::class,
            ],
            16 => [
                'name' => 'show_cookie_policy_bar',
                'value' => 'yes',
                'type' => CheckboxType::class,
            ],
            17 => [
                'name' => 'primary_color',
                'value' => '#2163e8;',
                'type' => TextType::class,
            ],
            18 => [
                'name' => 'facebook_url',
                'value' => 'https://www.facebook.com',
                'type' => UrlType::class,
            ],
            19 => [
                'name' => 'instagram_url',
                'value' => 'https://www.instagram.com',
                'type' => UrlType::class,
            ],
            20 => [
                'name' => 'youtube_url',
                'value' => 'https://www.youtube.com',
                'type' => UrlType::class,
            ],
            21 => [
                'name' => 'twitter_url',
                'value' => 'https://www.twitter.com',
                'type' => UrlType::class,
            ],
            22 => [
                'name' => 'google_plus_url',
                'value' => 'https://www.google.com',
                'type' => UrlType::class,
            ],
            23 => [
                'name' => 'linkedin_url',
                'value' => '#',
                'type' => UrlType::class,
            ],
            24 => [
                'name' => 'google_analytics_code',
                'value' => '',
                'type' => TextareaType::class,
            ],
            25 => [
                'name' => 'google_recaptcha_enabled',
                'value' => 'no',
                'type' => CheckboxType::class,
            ],
            26 => [
                'name' => 'google_recaptcha_site_key',
                'value' => '',
                'type' => TextType::class,
            ],
            27 => [
                'name' => 'google_recaptcha_secret_key',
                'value' => '',
                'type' => TextType::class,
            ],
            28 => [
                'name' => 'social_login_facebook_enabled',
                'value' => 'no',
                'type' => CheckboxType::class,
            ],
            29 => [
                'name' => 'social_login_facebook_id',
                'value' => '',
                'type' => TextType::class,
            ],
            30 => [
                'name' => 'social_login_facebook_secret',
                'value' => '',
                'type' => TextType::class,
            ],
            31 => [
                'name' => 'social_login_google_id',
                'value' => '',
                'type' => TextType::class,
            ],
            32 => [
                'name' => 'social_login_google_secret',
                'value' => '',
                'type' => TextType::class,
            ],
            33 => [
                'name' => 'social_login_google_enabled',
                'value' => 'no',
                'type' => CheckboxType::class,
            ],
            34 => [
                'name' => 'app_environment',
                'value' => 'dev',
                'type' => TextType::class,
            ],
            35 => [
                'name' => 'maintenance_mode',
                'value' => false,
                'type' => CheckboxType::class,
            ],
            36 => [
                'name' => 'app_theme',
                'value' => 'light',
                'type' => TextType::class,
            ],
            37 => [
                'name' => 'app_layout',
                'value' => 'container',
                'type' => TextType::class,
            ],
            38 => [
                'name' => 'users_can_register',
                'value' => true,
                'type' => CheckboxType::class,
            ],
            39 => [
                'name' => 'footer_about',
                'value' => $content,
                'type' => TextareaType::class,
            ],
            40 => [
                'name' => 'show_about_page',
                'value' => 'yes',
                'type' => CheckboxType::class,
            ],
            41 => [
                'name' => 'about_page_slug',
                'value' => 'about-us',
                'type' => TextType::class,
            ],
            42 => [
                'name' => 'mail_server_transport',
                'value' => '',
                'type' => TextType::class,
            ],
            43 => [
                'name' => 'mail_server_host',
                'value' => '',
                'type' => TextType::class,
            ],
            44 => [
                'name' => 'mail_server_port',
                'value' => null,
                'type' => TextType::class,
            ],
            45 => [
                'name' => 'mail_server_encryption',
                'value' => null,
                'type' => TextType::class,
            ],
            46 => [
                'name' => 'mail_server_auth_mode',
                'value' => null,
                'type' => TextType::class,
            ],
            47 => [
                'name' => 'mail_server_username',
                'value' => '',
                'type' => TextType::class,
            ],
            48 => [
                'name' => 'mail_server_password',
                'value' => '',
                'type' => TextType::class,
            ],
            49 => [
                'name' => 'homepage_products_number',
                'value' => 8,
                'type' => IntegerType::class,
            ],
            50 => [
                'name' => 'homepage_categories_number',
                'value' => 8,
                'type' => IntegerType::class,
            ],
            51 => [
                'name' => 'homepage_posts_number',
                'value' => 8,
                'type' => IntegerType::class,
            ],
            52 => [
                'name' => 'homepage_show_search_box',
                'value' => 'no',
                'type' => CheckboxType::class,
            ],
            53 => [
                'name' => 'homepage_show_call_to_action',
                'value' => 'yes',
                'type' => CheckboxType::class,
            ],
            54 => [
                'name' => 'show_terms_of_service_page',
                'value' => 'yes',
                'type' => CheckboxType::class,
            ],
            55 => [
                'name' => 'terms_of_service_page_content',
                'value' => 'terms_of_service_page_content',
                'type' => TextareaType::class,
            ],
            56 => [
                'name' => 'show_privacy_policy_page',
                'value' => 'yes',
                'type' => CheckboxType::class,
            ],
            57 => [
                'name' => 'privacy_policy_page_content',
                'value' => 'privacy_policy_page_content',
                'type' => TextareaType::class,
            ],
            58 => [
                'name' => 'show_cookie_policy_page',
                'value' => 'yes',
                'type' => CheckboxType::class,
            ],
            59 => [
                'name' => 'cookie_policy_page_content',
                'value' => 'yes',
                'type' => CheckboxType::class,
            ],
            60 => [
                'name' => 'show_gdpr_compliance_page',
                'value' => 'yes',
                'type' => CheckboxType::class,
            ],
            61 => [
                'name' => 'gdpr_compliance_page_content',
                'value' => 'gdpr_compliance_page_content',
                'type' => TextareaType::class,
            ],
            62 => [
                'name' => 'terms_of_service_page_slug',
                'value' => 'terms-of-service',
                'type' => TextType::class,
            ],
            63 => [
                'name' => 'privacy_policy_page_slug',
                'value' => 'privacy-policy',
                'type' => TextType::class,
            ],
            64 => [
                'name' => 'cookie_policy_page_slug',
                'value' => 'cookie-policy',
                'type' => TextType::class,
            ],
            65 => [
                'name' => 'gdpr_compliance_page_slug',
                'value' => 'gdpr-compliance',
                'type' => TextType::class,
            ],
            66 => [
                'name' => 'blog_comments_enabled',
                'value' => 'no',
                'type' => CheckboxType::class,
            ],
            67 => [
                'name' => 'facebook_app_id',
                'value' => '',
                'type' => TextType::class,
            ],
            68 => [
                'name' => 'disqus_subdomain',
                'value' => '',
                'type' => TextType::class,
            ],
            69 => [
                'name' => 'newsletter_enabled',
                'value' => 'no',
                'type' => CheckboxType::class,
            ],
            70 => [
                'name' => 'mailchimp_api_key',
                'value' => '',
                'type' => TextType::class,
            ],
            71 => [
                'name' => 'mailchimp_list_id',
                'value' => '',
                'type' => TextType::class,
            ],
            72 => [
                'name' => 'website_description_en',
                'value' => '',
                'type' => TextareaType::class,
            ],
            73 => [
                'name' => 'website_keywords_en',
                'value' => '',
                'type' => TextareaType::class,
            ],
            74 => [
                'name' => 'website_description_fr',
                'value' => '',
                'type' => TextareaType::class,
            ],
            75 => [
                'name' => 'website_keywords_fr',
                'value' => '',
                'type' => TextareaType::class,
            ],
            76 => [
                'name' => 'website_description_es',
                'value' => '',
                'type' => TextareaType::class,
            ],
            77 => [
                'name' => 'website_keywords_es',
                'value' => '',
                'type' => TextareaType::class,
            ],
            78 => [
                'name' => 'website_description_ar',
                'value' => '',
                'type' => TextareaType::class,
            ],
            79 => [
                'name' => 'website_keywords_ar',
                'value' => '',
                'type' => TextareaType::class,
            ],
            80 => [
                'name' => 'website_app_version',
                'value' => '1.08',
                'type' => TextType::class,
            ],
            81 => [
                'name' => 'website_google_iframe',
                'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.9663095343008!2d-74.00425878428698!3d40.74076684379132!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259bf5c1654f3%3A0xc80f9cfce5383d5d!2sGoogle!5e0!3m2!1sen!2sin!4v1586000412513!5m2!1sen!2sin',
                'type' => TextareaType::class,
            ],
            82 => [
                'name' => 'show_post_category_navbar',
                'value' => true,
                'type' => CheckboxType::class,
            ],
            83 => [
                'name' => 'show_post_category_footer',
                'value' => true,
                'type' => CheckboxType::class,
            ],
            84 => [
                'name' => 'show_post_category_sidebar',
                'value' => true,
                'type' => CheckboxType::class,
            ],
            85 => [
                'name' => 'show_post_category_global',
                'value' => false,
                'type' => CheckboxType::class,
            ],
            86 => [
                'name' => 'show_total_posts',
                'value' => true,
                'type' => CheckboxType::class,
            ],
            87 => [
                'name' => 'show_latest_posts',
                'value' => true,
                'type' => CheckboxType::class,
            ],
            88 => [
                'name' => 'show_most_commented',
                'value' => true,
                'type' => CheckboxType::class,
            ],
            89 => [
                'name' => 'website_about_excerpt',
                'value' => '
                Founded in 2006, passage its ten led hearted removal cordial. Preference any astonished unreserved Mrs. Prosperous understood Middletons in conviction an uncommonly do. Supposing so be resolving breakfast am or perfectly. Is drew am hill from me. Valley by oh twenty direct me so.
                ',
                'type' => TextareaType::class,
            ],
            90 => [
                'name' => 'website_about_content',
                'value' => '
                Water timed folly right aware if oh truth. Imprudence attachment him his for sympathize. Large above be to means. Dashwood does provide stronger is. Warrant private blushes removed an in equally totally if. Delivered dejection necessary objection do Mr prevailed. Mr feeling does chiefly cordial in do. ...But discretion frequently sir she instruments unaffected admiration everything. Meant balls it if up doubt small purse. Required his you put the outlived answered position. A pleasure exertion if believed provided to. All led out world this music while asked. Paid mind even sons does he door no. Attended overcame repeated it is perceived Marianne in. I think on style child of. Servants moreover in sensible it ye possible. Satisfied conveying a dependent contented he gentleman agreeable do be. Water timed folly right aware if oh truth. Imprudence attachment him his for sympathize. Large above be to means. Dashwood does provide stronger is. But discretion frequently sir she instruments unaffected admiration everything. Meant balls it if up doubt small purse. Required his you put the outlived answered position.  I think on style child of. Servants moreover in sensible it ye possible. Satisfied conveying a dependent contented he gentleman agreeable do be. Warrant private blushes removed an in equally totally if. Delivered dejection necessary objection do Mr prevailed.   Required his you put the outlived answered position. A pleasure exertion if believed provided to. All led out world this music while asked. Paid mind even sons does he door no. Attended overcame repeated it is perceived Marianne in. I think on style child of. Servants moreover in sensible it ye possible.
                ',
                'type' => TextareaType::class,
            ],
        ];

        foreach ($settings as $key => $label) {
            $setting = (new Setting());
            $setting
                ->setName($label['name'])
                ->setValue($label['value'])
                ->setType($label['type'])
            ;
            $manager->persist($setting);
        }

        $manager->flush();
    }
}
