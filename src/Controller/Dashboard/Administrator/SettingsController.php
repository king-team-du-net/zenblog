<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Controller\Dashboard\Administrator;

use App\Controller\Controller;
use App\Entity\Setting;
use App\Entity\User;
use App\Twig\TwigSettingExtension;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted(User::ADMINISTRATOR)]
class SettingsController extends Controller
{
    #[Route(path: '/%website_dashboard_path%/administrator/settings/layout', name: 'dashboard_administrator_settings_layout', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function layout(Request $request, EntityManagerInterface $em, TranslatorInterface $translator, TwigSettingExtension $services): Response
    {
        $appLayoutSettings = $em->getRepository("App\Entity\AppLayoutSettings")->find(1);
        if (!$appLayoutSettings) {
            $services->redirectToReferer('index');
            $this->addFlash('error', $translator->trans('setting.layout_loaded'));
        }
        $form = $this->createForm(AppLayoutSettingsType::class, $appLayoutSettings)->handleRequest($request);

        if ($form->isSubmitted()) {
            /** @var Setting $settings */
            $settings = $request->request->all()['app_layout_settings'];
            if (!\array_key_exists('app_locales', (array) $settings)) {
                $form->get('app_locales')->addError(new \Symfony\Component\Form\FormError($translator->trans('setting.app_locales')));
            } else {
                if (!\in_array($settings['default_locale'], $settings['app_locales'], true)) {
                    $form->get('default_locale')->addError(new \Symfony\Component\Form\FormError($translator->trans('setting.default_locale')));
                }
            }

            if ($form->isValid()) {
                $em->persist($appLayoutSettings);
                $em->flush();

                $services->setSetting('maintenance_mode_custom_message', $settings['maintenance_mode_custom_message']);
                $services->setSetting('date_format', $settings['date_format']);
                $services->setSetting('date_format_simple', $settings['date_format_simple']);
                $services->setSetting('date_format_date_only', $settings['date_format_date_only']);
                $services->setSetting('date_timezone', $settings['date_timezone']);
                $services->setSetting('date_timezone', $settings['date_timezone']);
                $services->setSetting('default_locale', $settings['default_locale']);
                $services->setSetting('app_locales', \in_array('app_locales', (array) $settings, true) ? $settings['app_locales'] : '');
                $services->setSetting('website_name', $settings['website_name']);
                $services->setSetting('website_slug', $settings['website_slug']);
                $services->setSetting('website_url', $settings['website_url']);
                $services->setSetting('website_root_url', $settings['website_root_url']);
                $services->setSetting('website_description_en', $settings['website_description_en']);
                $services->setSetting('website_description_fr', $settings['website_description_fr']);
                $services->setSetting('website_description_es', $settings['website_description_es']);
                $services->setSetting('website_description_ar', $settings['website_description_ar']);
                $services->setSetting('website_description_de', $settings['website_description_de']);
                $services->setSetting('website_description_pt', $settings['website_description_pt']);
                $services->setSetting('website_keywords_en', $settings['website_keywords_en']);
                $services->setSetting('website_keywords_fr', $settings['website_keywords_fr']);
                $services->setSetting('website_keywords_es', $settings['website_keywords_es']);
                $services->setSetting('website_keywords_ar', $settings['website_keywords_ar']);
                $services->setSetting('website_keywords_de', $settings['website_keywords_de']);
                $services->setSetting('website_keywords_pt', $settings['website_keywords_pt']);
                $services->setSetting('website_contact_phone', $settings['website_contact_phone']);
                $services->setSetting('website_contact_fax', $settings['website_contact_fax']);
                $services->setSetting('website_contact_address', $settings['website_contact_address']);
                $services->setSetting('facebook_url', $settings['facebook_url']);
                $services->setSetting('instagram_url', $settings['instagram_url']);
                $services->setSetting('youtube_url', $settings['youtube_url']);
                $services->setSetting('twitter_url', $settings['twitter_url']);
                $services->setSetting('app_layout', $settings['app_layout']);
                $services->setSetting('app_theme', $settings['app_theme']);
                $services->setSetting('primary_color', $settings['primary_color']);
                $services->setSetting('custom_css', $settings['custom_css']);
                $services->setSetting('google_analytics_code', $settings['google_analytics_code']);
                $services->setSetting('show_back_to_top_button', $settings['show_back_to_top_button']);
                $services->setSetting('show_terms_of_service_page', $settings['show_terms_of_service_page']);
                $services->setSetting('terms_of_service_page_slug', $settings['terms_of_service_page_slug']);
                $services->setSetting('show_privacy_policy_page', $settings['show_privacy_policy_page']);
                $services->setSetting('privacy_policy_page_slug', $settings['privacy_policy_page_slug']);
                $services->setSetting('show_cookie_policy_page', $settings['show_cookie_policy_page']);
                $services->setSetting('cookie_policy_page_slug', $settings['cookie_policy_page_slug']);
                $services->setSetting('show_cookie_policy_bar', $settings['show_cookie_policy_bar']);
                $services->setSetting('show_gdpr_compliance_page', $settings['show_gdpr_compliance_page']);
                $services->setSetting('gdpr_compliance_page_slug', $settings['gdpr_compliance_page_slug']);

                $services->updateEnv('APP_ENV', $settings['app_environment']);
                $services->updateEnv('APP_DEBUG', $settings['app_debug']);
                $services->updateEnv('APP_SECRET', $settings['app_secret']);
                $services->updateEnv('MAINTENANCE_MODE', $settings['maintenance_mode']);
                $services->updateEnv('DATE_FORMAT', $settings['date_format']);
                $services->updateEnv('DATE_FORMAT_SIMPLE', $settings['date_format_simple']);
                $services->updateEnv('DATE_FORMAT_DATE_ONLY', $settings['date_format_date_only']);
                $services->updateEnv('DATE_TIMEZONE', $settings['date_timezone']);
                $services->updateEnv('DEFAULT_LOCALE', $settings['default_locale']);
                $services->updateEnv('APP_LOCALES', implode('|', $settings['app_locales']).'|');

                $this->addFlash('success', $translator->trans('setting.updated_successfully'));
            } else {
                $this->addFlash('error', $translator->trans('content.invalid_data'));
            }
        } else {
            $form->get('app_environment')->setData($services->getEnv('APP_ENV'));
            $form->get('app_debug')->setData($services->getEnv('APP_DEBUG'));
            $form->get('app_secret')->setData($services->getEnv('APP_SECRET'));
            $form->get('maintenance_mode')->setData($services->getEnv('MAINTENANCE_MODE'));
            $form->get('date_format')->setData($services->getEnv('DATE_FORMAT'));
            $form->get('date_format_simple')->setData($services->getEnv('DATE_FORMAT_SIMPLE'));
            $form->get('date_format_date_only')->setData($services->getEnv('DATE_FORMAT_DATE_ONLY'));
            $form->get('date_timezone')->setData($services->getEnv('DATE_TIMEZONE'));
            $form->get('default_locale')->setData($services->getEnv('DEFAULT_LOCALE'));
            $form->get('app_locales')->setData(array_filter(explode('|', $services->getEnv('APP_LOCALES'))));
            $form->get('maintenance_mode_custom_message')->setData($services->getSetting('maintenance_mode_custom_message'));
            $form->get('website_name')->setData($services->getSetting('website_name'));
            $form->get('website_slug')->setData($services->getSetting('website_slug'));
            $form->get('website_url')->setData($services->getSetting('website_url'));
            $form->get('website_root_url')->setData($services->getSetting('website_root_url'));
            $form->get('website_description_en')->setData($services->getSetting('website_description_en'));
            $form->get('website_description_fr')->setData($services->getSetting('website_description_fr'));
            $form->get('website_description_es')->setData($services->getSetting('website_description_es'));
            $form->get('website_description_ar')->setData($services->getSetting('website_description_ar'));
            $form->get('website_description_de')->setData($services->getSetting('website_description_de'));
            $form->get('website_description_pt')->setData($services->getSetting('website_description_pt'));
            $form->get('website_keywords_en')->setData($services->getSetting('website_keywords_en'));
            $form->get('website_keywords_fr')->setData($services->getSetting('website_keywords_fr'));
            $form->get('website_keywords_es')->setData($services->getSetting('website_keywords_es'));
            $form->get('website_keywords_ar')->setData($services->getSetting('website_keywords_ar'));
            $form->get('website_keywords_de')->setData($services->getSetting('website_keywords_de'));
            $form->get('website_keywords_pt')->setData($services->getSetting('website_keywords_pt'));
            $form->get('website_contact_phone')->setData($services->getSetting('website_contact_phone'));
            $form->get('website_contact_fax')->setData($services->getSetting('website_contact_fax'));
            $form->get('website_contact_address')->setData($services->getSetting('website_contact_address'));
            $form->get('facebook_url')->setData($services->getSetting('facebook_url'));
            $form->get('instagram_url')->setData($services->getSetting('instagram_url'));
            $form->get('youtube_url')->setData($services->getSetting('youtube_url'));
            $form->get('twitter_url')->setData($services->getSetting('twitter_url'));
            $form->get('app_layout')->setData($services->getSetting('app_layout'));
            $form->get('app_theme')->setData($services->getSetting('app_theme'));
            $form->get('primary_color')->setData($services->getSetting('primary_color'));
            $form->get('custom_css')->setData($services->getSetting('custom_css'));
            $form->get('google_analytics_code')->setData($services->getSetting('google_analytics_code'));
            $form->get('show_back_to_top_button')->setData($services->getSetting('show_back_to_top_button'));
            $form->get('show_terms_of_service_page')->setData($services->getSetting('show_terms_of_service_page'));
            $form->get('terms_of_service_page_slug')->setData($services->getSetting('terms_of_service_page_slug'));
            $form->get('show_privacy_policy_page')->setData($services->getSetting('show_privacy_policy_page'));
            $form->get('privacy_policy_page_slug')->setData($services->getSetting('privacy_policy_page_slug'));
            $form->get('show_cookie_policy_page')->setData($services->getSetting('show_cookie_policy_page'));
            $form->get('cookie_policy_page_slug')->setData($services->getSetting('cookie_policy_page_slug'));
            $form->get('show_cookie_policy_bar')->setData($services->getSetting('show_cookie_policy_bar'));
            $form->get('show_gdpr_compliance_page')->setData($services->getSetting('show_gdpr_compliance_page'));
            $form->get('gdpr_compliance_page_slug')->setData($services->getSetting('gdpr_compliance_page_slug'));
        }

        return $this->render('dashboard/administrator/settings/layout.html.twig', compact('form', 'services'));
    }

    #[Route(path: '/%website_dashboard_path%/administrator/settings/blog', name: 'dashboard_administrator_settings_blog', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function blog(Request $request, EntityManagerInterface $em, TranslatorInterface $translator, TwigSettingExtension $services): Response
    {
        $form = $this->createFormBuilder()
            ->add('blog_posts_per_page', TextType::class, [
                'required' => true,
                'label' => 'label.blog_posts_per_page',
                'attr' => ['class' => 'touchspin-integer'],
            ])
            ->add('blog_comments_enabled', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'label' => 'label.blog_comments_enabled',
                'choices' => ['label.no' => 'no', 'label.facebook_comments' => 'facebook', 'label.disqus_comments' => 'disqus'],
                'label_attr' => ['class' => 'radio-custom radio-inline'],
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('facebook_app_id', TextType::class, [
                'required' => false,
                'label' => 'label.facebook_app_id',
                'attr' => ['class' => 'form-control'],
                'help' => 'help.facebook_app_id',
            ])
            ->add('disqus_subdomain', TextType::class, [
                'required' => false,
                'label' => 'label.disqus_subdomain',
                'attr' => ['class' => 'form-control'],
                'help' => 'help.disqus_subdomain',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'label.save',
                'attr' => ['class' => 'mt-2'],
            ])
            ->getForm()
        ;
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /** @var Setting $settings */
                $settings = $form->getData();
                $services->setSetting('blog_posts_per_page', $settings['blog_posts_per_page']);
                $services->setSetting('blog_comments_enabled', $settings['blog_comments_enabled']);
                $services->setSetting('facebook_app_id', $settings['facebook_app_id']);
                $services->setSetting('disqus_subdomain', $settings['disqus_subdomain']);
                $this->addFlash('success', $translator->trans('setting.updated_successfully'));
            } else {
                $this->addFlash('error', $translator->trans('content.invalid_data'));
            }
        } else {
            $form->get('blog_posts_per_page')->setData($services->getSetting('blog_posts_per_page'));
            $form->get('blog_comments_enabled')->setData($services->getSetting('blog_comments_enabled'));
            $form->get('facebook_app_id')->setData($services->getSetting('facebook_app_id'));
            $form->get('disqus_subdomain')->setData($services->getSetting('disqus_subdomain'));
        }

        return $this->render('dashboard/administrator/settings/blog.html.twig', compact('form', 'services'));
    }

    #[Route(path: '/%website_dashboard_path%/administrator/settings/google-recaptcha', name: 'dashboard_administrator_settings_google_recaptcha', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function googleRecaptcha(Request $request, EntityManagerInterface $em, TranslatorInterface $translator, TwigSettingExtension $services): Response
    {
        $form = $this->createFormBuilder()
            ->add('google_recaptcha_enabled', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'label' => 'label.google_recaptcha_enabled',
                'choices' => ['label.yes' => 'yes', 'label.no' => 'no'],
                'label_attr' => ['class' => 'radio-custom radio-inline'],
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('google_recaptcha_site_key', TextType::class, [
                'required' => false,
                'label' => 'label.google_recaptcha_site_key',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('google_recaptcha_secret_key', TextType::class, [
                'required' => false,
                'label' => 'label.google_recaptcha_secret_key',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'label.save',
                'attr' => ['class' => 'mt-2'],
            ])
            ->getForm()
        ;
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /** @var Setting $settings */
                $settings = $form->getData();
                $services->setSetting('google_recaptcha_enabled', $settings['google_recaptcha_enabled']);
                $services->setSetting('google_recaptcha_site_key', $settings['google_recaptcha_site_key']);
                $services->setSetting('google_recaptcha_secret_key', $settings['google_recaptcha_secret_key']);

                $services->updateEnv('EWZ_RECAPTCHA_SITE_KEY', $settings['google_recaptcha_site_key']);
                $services->updateEnv('EWZ_RECAPTCHA_SECRET', $settings['google_recaptcha_secret_key']);

                $this->addFlash('success', $translator->trans('setting.updated_successfully'));
            } else {
                $this->addFlash('error', $translator->trans('content.invalid_data'));
            }
        } else {
            $form->get('google_recaptcha_enabled')->setData($services->getSetting('google_recaptcha_enabled'));
            $form->get('google_recaptcha_site_key')->setData($services->getSetting('google_recaptcha_site_key'));
            $form->get('google_recaptcha_secret_key')->setData($services->getSetting('google_recaptcha_secret_key'));
        }

        return $this->render('dashboard/administrator/settings/google-recaptcha.html.twig', compact('form', 'services'));
    }

    #[Route(path: '/%website_dashboard_path%/administrator/settings/google-maps', name: 'dashboard_administrator_settings_google_maps', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function googleMaps(Request $request, EntityManagerInterface $em, TranslatorInterface $translator, TwigSettingExtension $services): Response
    {
        $form = $this->createFormBuilder()
            ->add('google_maps_api_key', TextType::class, [
                'required' => false,
                'label' => 'label.google_maps_api_key',
                'attr' => ['class' => 'form-control'],
                'help' => 'help.google_maps_api_key',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'label.save',
                'attr' => ['class' => 'mt-2'],
            ])
            ->getForm()
        ;
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /** @var Setting $settings */
                $settings = $form->getData();
                $services->updateEnv('GOOGLE_MAPS_API_KEY', $settings['google_maps_api_key']);

                $this->addFlash('success', $translator->trans('setting.updated_successfully'));
            } else {
                $this->addFlash('error', $translator->trans('content.invalid_data'));
            }
        } else {
            $form->get('google_maps_api_key')->setData($services->getEnv('GOOGLE_MAPS_API_KEY'));
        }

        return $this->render('dashboard/administrator/settings/google-maps.html.twig', compact('form', 'services'));
    }

    #[Route(path: '/%website_dashboard_path%/administrator/settings/mail-server', name: 'dashboard_administrator_settings_mail_server', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function mailServer(Request $request, EntityManagerInterface $em, TranslatorInterface $translator, TwigSettingExtension $services): Response
    {
        if ('1' === $services->getEnv('DEMO_MODE')) {
            $this->addFlash('error', $translator->trans('setting.mail-server_mode_demo_disabled', [], 'javascript'));

            return $this->redirectToRoute('dashboard_index');
        }

        $form = $this->createFormBuilder()
            ->add('mail_server_transport', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'label' => 'label.mail_server_transport',
                'choices' => ['label.smtp' => 'smtp', 'label.gmail' => 'gmail', 'label.sendmail' => 'sendmail'],
                'label_attr' => ['class' => 'radio-custom radio-inline'],
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('mail_server_host', TextType::class, [
                'required' => true,
                'label' => 'label.mail_server_host',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('mail_server_port', TextType::class, [
                'required' => false,
                'label' => 'label.mail_server_port',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('mail_server_encryption', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'label' => 'label.mail_server_encryption',
                'choices' => ['label.null' => null, 'label.ssl' => 'ssl', 'label.tls' => 'tls'],
                'label_attr' => ['class' => 'radio-custom radio-inline'],
            ])
            ->add('mail_server_username', TextType::class, [
                'required' => false,
                'label' => 'label.mail_server_username',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('mail_server_password', TextType::class, [
                'required' => false,
                'label' => 'label.mail_server_password',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('website_no_reply_email', TextType::class, [
                'required' => true,
                'label' => 'label.website_no_reply_email',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(),
                ],
                'help' => 'help.website_no_reply_email',
            ])
            ->add('website_contact_email', TextType::class, [
                'required' => true,
                'label' => 'label.website_contact_email',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(),
                ],
                'help' => 'help.website_contact_email',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'label.save',
                'attr' => ['class' => 'mt-2'],
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /** @var Setting $settings */
                $settings = $form->getData();
                $services->setSetting('mail_server_transport', $settings['mail_server_transport']);
                $services->setSetting('mail_server_host', rawurlencode($settings['mail_server_host']));
                $services->setSetting('mail_server_port', $settings['mail_server_port']);
                $services->setSetting('mail_server_encryption', $settings['mail_server_encryption']);
                $services->setSetting('mail_server_username', rawurlencode($settings['mail_server_username']));
                $services->setSetting('mail_server_password', rawurlencode($settings['mail_server_password']));
                $services->setSetting('website_contact_email', $settings['website_contact_email']);
                $services->setSetting('website_no_reply_email', $settings['website_no_reply_email']);

                $dsnUrl = $settings['mail_server_transport'].'://';

                if (\mb_strlen($settings['mail_server_username'])) {
                    $dsnUrl .= rawurlencode($settings['mail_server_username']);
                }
                if (\mb_strlen($settings['mail_server_password'])) {
                    $dsnUrl .= ':'.rawurlencode($settings['mail_server_password']);
                }
                if (\mb_strlen($settings['mail_server_host'])) {
                    $dsnUrl .= '@'.$settings['mail_server_host'];
                }
                if (\mb_strlen($settings['mail_server_port'])) {
                    $dsnUrl .= ':'.$settings['mail_server_port'];
                }
                if (\mb_strlen($settings['mail_server_encryption'])) {
                    $dsnUrl .= '/?encryption='.$settings['mail_server_encryption'];
                }
                if ('gmail' === $settings['mail_server_transport']) {
                    if (\mb_strlen($settings['mail_server_encryption'])) {
                        $dsnUrl .= '&auth_mode=oauth';
                    } else {
                        $dsnUrl .= '?auth_mode=oauth';
                    }
                }
                $services->updateEnv('MAILER_URL', $dsnUrl);

                $this->addFlash('success', $translator->trans('setting.updated_successfully'));
            } else {
                $this->addFlash('error', $translator->trans('content.invalid_data'));
            }
        } else {
            $form->get('mail_server_transport')->setData($services->getSetting('mail_server_transport'));
            $form->get('mail_server_host')->setData(rawurldecode($services->getSetting('mail_server_host')));
            $form->get('mail_server_port')->setData($services->getSetting('mail_server_port'));
            $form->get('mail_server_encryption')->setData($services->getSetting('mail_server_encryption'));
            $form->get('mail_server_username')->setData(rawurldecode($services->getSetting('mail_server_username')));
            $form->get('mail_server_password')->setData(rawurldecode($services->getSetting('mail_server_password')));
            $form->get('website_contact_email')->setData($services->getSetting('website_contact_email'));
            $form->get('website_no_reply_email')->setData($services->getSetting('website_no_reply_email'));
        }

        return $this->render('dashboard/administrator/settings/mail-server.html.twig', compact('form', 'services'));
    }

    #[Route(path: '/%website_dashboard_path%/administrator/settings/mail-server/test', name: 'dashboard_administrator_settings_mail_server_test', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function mailServerTest(Request $request, MailerInterface $mailer, TranslatorInterface $translator, TwigSettingExtension $services): Response
    {
        $email = (new TemplatedEmail())
            ->from(new Address(
                $services->getSetting('website_no_reply_email'),
                $services->getSetting('website_name'),
            ))
            ->to(new Address($request->query->get('email')))
            ->subject($translator->trans('setting.mailer_send_test_subject'))
            ->htmlTemplate('dashboard/administrator/settings/mail-server-test-email.html.twig')
            // ->context()
        ;

        try {
            $result = $mailer->send($email);
            if (0 === $result) {
                $this->addFlash('danger', $translator->trans('setting.mailer_send_test_danger'));
            } else {
                $this->addFlash('success', $translator->trans('setting.mailer_send_test_success').' '.$request->request->get('email'));
            }
        } catch (\Exception $e) {
            $this->addFlash('danger', $translator->trans('setting.mailer_send_test_danger'));
        }

        return $this->redirectToRoute('dashboard_administrator_settings_mail_server');
    }

    #[Route(path: '/%website_dashboard_path%/administrator/settings/newsletter', name: 'dashboard_administrator_settings_newsletter', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function newsletter(Request $request, EntityManagerInterface $em, TranslatorInterface $translator, TwigSettingExtension $services): Response
    {
        $form = $this->createFormBuilder()
            ->add('newsletter_enabled', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'label' => 'label.newsletter_enabled',
                'choices' => ['label.yes' => 'yes', 'label.no' => 'no'],
                'label_attr' => ['class' => 'radio-custom radio-inline'],
                'help' => 'help.newsletter_enabled',
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('mailchimp_api_key', TextType::class, [
                'required' => false,
                'label' => 'label.mailchimp_api_key',
                'attr' => ['class' => 'form-control'],
                'help' => 'help.mailchimp_api_key',
            ])
            ->add('mailchimp_list_id', TextType::class, [
                'required' => false,
                'label' => 'label.mailchimp_list_id',
                'attr' => ['class' => 'form-control'],
                'help' => 'help.mailchimp_list_id',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'label.save',
                'attr' => ['class' => 'mt-2'],
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /** @var Setting $settings */
                $settings = $form->getData();
                $services->setSetting('newsletter_enabled', $settings['newsletter_enabled']);
                $services->setSetting('mailchimp_api_key', $settings['mailchimp_api_key']);
                $services->setSetting('mailchimp_list_id', $settings['mailchimp_list_id']);
                $services->updateEnv('NEWSLETTER_ENABLED', $settings['newsletter_enabled']);
                $services->updateEnv('MAILCHIMP_API_KEY', $settings['mailchimp_api_key']);
                $services->updateEnv('MAILCHIMP_LIST_ID', $settings['mailchimp_list_id']);
                $this->addFlash('success', $translator->trans('setting.updated_successfully'));
            } else {
                $this->addFlash('error', $translator->trans('content.invalid_data'));
            }
        } else {
            $form->get('newsletter_enabled')->setData($services->getSetting('newsletter_enabled'));
            $form->get('mailchimp_api_key')->setData($services->getSetting('mailchimp_api_key'));
            $form->get('mailchimp_list_id')->setData($services->getSetting('mailchimp_list_id'));
        }

        return $this->render('dashboard/administrator/settings/newsletter.html.twig', compact('form', 'services'));
    }
}
