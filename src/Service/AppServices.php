<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Twig\Environment;
use App\Entity\Setting;
use Psr\Cache\CacheItemPoolInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/** @method User getUser() */
final class AppServices
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AuthorizationCheckerInterface $authChecker,
        private RequestStack $requestStack,
        private KernelInterface $kernel,
        private CacheItemPoolInterface $cache,
        private UrlGeneratorInterface $router,
        private Security $security,
        private MailerInterface $mailer,
        private TranslatorInterface $translator,
        private ParameterBagInterface $params,
        private Environment $templating,
        private UrlMatcherInterface $urlMatcherInterface
    ) {
    }

    // Redirects to the referer page when available, if not, redirects to the dashboard index
    /*public function redirectToReferer(mixed $alt = null): RedirectResponse
    {
        if ($this->requestStack->getCurrentRequest()->headers->get('referer')) {
            return new RedirectResponse($this->requestStack->getCurrentRequest()->headers->get('referer'));
        } else {
            if ($alt) {
                if ($this->authChecker->isGranted(Administrator::ADMINISTRATOR)) {
                    return new RedirectResponse($this->router->generate('dashboard_administrator_' . $alt));
                } elseif ($this->authChecker->isGranted(User::MODERATOR)) {
                    return new RedirectResponse($this->router->generate('dashboard_moderator_' . $alt));
                } elseif ($this->authChecker->isGranted(User::DEFAULT)) {
                    return new RedirectResponse($this->router->generate('dashboard_user_' . $alt));
                } else {
                    return new RedirectResponse($this->router->generate($alt));
                }
            } else {
                return new RedirectResponse($this->router->generate('dashboard_index'));
            }
        }
    }*/

    /*
    // Generates a random string iwth a specified length
    public function generateReference(int $length): string
    {
        $reference = implode('', [
            bin2hex(random_bytes(2)),
            bin2hex(random_bytes(2)),
            bin2hex(chr((ord(random_bytes(1)) & 0x0F) | 0x40)) . bin2hex(random_bytes(1)),
            bin2hex(chr((ord(random_bytes(1)) & 0x3F) | 0x80)) . bin2hex(random_bytes(1)),
            bin2hex(random_bytes(2)),
        ]);

        return strlen($reference) > $length ? substr($reference, 0, $length) : $reference;
    }
    */

    // Gets a setting from the cache / db
    public function getSetting(string $name)
    {
        $settingcache = $this->cache->getItem('settings_' . $name);

        if ($settingcache->isHit()) {
            return $settingcache->get();
        }

        /** @var Setting $setting */
        $setting = $this->entityManager->getRepository(Setting::class)->findOneByName($name);

        if (!$setting) {
            return null;
        }

        $settingcache->set($setting->getValue());
        $this->cache->save($settingcache);

        return $setting ? ($setting->getValue()) : (null);
    }

    // Sets a setting from the cache / db
    public function setSetting(string $name, string $value): int
    {
        /** @var Setting $setting  */
        $setting = $this->entityManager->getRepository(Setting::class)->findOneByKey($name);

        if ($setting) {
            $setting->setValue($value);
            $this->entityManager->flush();
            $settingcache = $this->cache->getItem('settings_' . $name);
            $settingcache->set($value);
            $this->cache->save($settingcache);

            if ('website_name' == $name || 'website_no_reply_email' == $name || 'website_root_url' == $name) {
                $this->updateEnv(strtoupper($name), $value);
            }

            return 1;
        } else {
            return 0;
        }
    }

    // Updates the .env name with the choosen value
    public function updateEnv(string $name, string $value): void
    {
        if (0 == strlen($name)) {
            return;
        }

        $envFile = $this->kernel->getProjectDir() . '/.env';
        $lines = file($envFile);
        $newLines = [];

        foreach ($lines as $line) {
            preg_match('/' . $name . '=/i', $line, $matches);
            if (!count($matches)) {
                $newLines[] = $line;
                continue;
            }
            $newLine = trim($name) . '=' . trim($value) . "\n";
            $newLines[] = $newLine;
        }

        $newContent = implode('', $newLines);
        file_put_contents($envFile, $newContent);
    }

    // Gets the value with the entered name from the .env file
    public function getEnv(string $name)
    {
        if (0 == strlen($name)) {
            return;
        }
        $envFile = $this->kernel->getProjectDir() . '/.env';
        $lines = file($envFile);
        foreach ($lines as $line) {
            preg_match('/' . $name . '=/i', $line, $matches);

            if (!count($matches)) {
                continue;
            }

            return trim(explode('=', $line, 2)[1]);
        }

        return null;
    }

    // Shows the soft deleted entities for ROLE_ADMINISTRATOR
    /*
    public function disableSofDeleteFilterForAdmin(
        EntityManagerInterface $entityManager,
        AuthorizationCheckerInterface $authChecker
    ): void {
        $entityManager->getFilters()->enable('softdeleteable');

        if ($authChecker->isGranted(Administrator::ADMINISTRATOR)) {
            $entityManager->getFilters()->disable('softdeleteable');
        }
    }

    // Returns the layout settings entity to be used in the twig templates
    public function getAppLayoutSettings(): ?AppLayoutSettings
    {
        /** @var AppLayoutSettings $appLayoutSetting /
        $appLayoutSetting = $this->entityManager->getRepository(AppLayoutSettings::class)->find(1);

        return $appLayoutSetting;
    }
    */

    /*
    // Get route name from path
    public function getRouteName($path = null)
    {
        try {
            if ($path) {
                return $this->urlMatcherInterface->match($path)['_route'];
            } else {
                return $this->urlMatcherInterface->match(
                    $this->requestStack->getCurrentRequest()->getPathInfo()
                )['_route'];
            }
        } catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
            return null;
        }
    }

    // Checks if string ends with string
    public function endsWith($haystack, $needle): bool
    {
        $length = strlen($needle);
        if (!$length) {
            return true;
        }

        return substr($haystack, -$length) === $needle;
    }

    // Changes the link locale
    public function changeLinkLocale(string $newLocale, string $link): string
    {
        if ('categories_dropdown' == $link || 'footer_categories_section' == $link) {
            return $link;
        }

        if (false !== strpos($link, '/en/')) {
            return str_replace('/en/', '/' . $newLocale . '/', $link);
        } elseif (false !== strpos($link, '/fr/')) {
            return str_replace('/fr/', '/' . $newLocale . '/', $link);
        } elseif (false !== strpos($link, '/ar/')) {
            return str_replace('/ar/', '/' . $newLocale . '/', $link);
        } elseif (false !== strpos($link, '/es/')) {
            return str_replace('/es/', '/' . $newLocale . '/', $link);
        } elseif (false !== strpos($link, '/pt/')) {
            return str_replace('/pt/', '/' . $newLocale . '/', $link);
        } elseif (false !== strpos($link, '/de/')) {
            return str_replace('/de/', '/' . $newLocale . '/', $link);
        } elseif ($this->endsWith($link, '/en')) {
            return str_replace('/en', '/' . $newLocale, $link);
        } elseif ($this->endsWith($link, '/fr')) {
            return str_replace('/fr', '/' . $newLocale, $link);
        } elseif ($this->endsWith($link, '/ar')) {
            return str_replace('/ar', '/' . $newLocale, $link);
        } elseif ($this->endsWith($link, '/es')) {
            return str_replace('/es', '/' . $newLocale, $link);
        } elseif ($this->endsWith($link, '/pt')) {
            return str_replace('/pt', '/' . $newLocale, $link);
        } elseif ($this->endsWith($link, '/de')) {
            return str_replace('/de', '/' . $newLocale, $link);
        }

        return 'x';
    }
    */

    /*
    // Returns the pages after applying the specified search criterias
    public function getPages($criterias): QueryBuilder
    {
        $this->disableSofDeleteFilterForAdmin($this->entityManager, $this->authChecker);
        /** @var string $slug /
        $slug = array_key_exists('slug', $criterias) ? $criterias['slug'] : 'all';

        return $this->entityManager->getRepository(Page::class)->getPages($slug);
    }

    // Returns the menus (header and footer)
    public function getMenus($criterias): QueryBuilder
    {
        /** @var string $slug /
        $slug = array_key_exists('slug', $criterias) ? $criterias['slug'] : 'all';
        /** @var QueryBuilder $menus /
        $menus = $this->entityManager->getRepository(Menu::class)->getMenus($slug);

        return $menus;
    }
    */
}
