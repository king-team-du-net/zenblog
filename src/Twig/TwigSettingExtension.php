<?php

namespace App\Twig;

use App\Entity\User;
use Twig\TwigFunction;
use App\Entity\Setting;
use App\Repository\SettingRepository;
use Psr\Cache\CacheItemPoolInterface;
use Twig\Extension\AbstractExtension;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class TwigSettingExtension extends AbstractExtension
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AuthorizationCheckerInterface $authChecker,
        private readonly RequestStack $requestStack,
        private readonly UrlGeneratorInterface $router,
        private readonly UrlMatcherInterface $urlMatcherInterface,
        private readonly ParameterBagInterface $params,
        private readonly TranslatorInterface $translator,
        private readonly CacheItemPoolInterface $cache,
        private readonly KernelInterface $kernel
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getSetting', [$this, 'getSetting']),
            new TwigFunction('setSetting', [$this, 'setSetting']),
            new TwigFunction('updateEnv', [$this, 'updateEnv']),
            new TwigFunction('getEnv', [$this, 'getEnv']),
            new TwigFunction('generateReference', [$this, 'generateReference']),
            new TwigFunction('endsWith', [$this, 'endsWith']),
            new TwigFunction('changeLinkLocale', [$this, 'changeLinkLocale']),
            new TwigFunction('disableSofDeleteFilterForAdmin', [$this, 'disableSofDeleteFilterForAdmin']),
            new TwigFunction('redirectToReferer', [$this, 'redirectToReferer']),
            //new TwigFunction('getAppLayoutSettings', [$this, 'getAppLayoutSettings']),
            new TwigFunction('getRouteName', [$this, 'getRouteName']),
            new TwigFunction('getPages', [$this, 'getPages']),
        ];
    }

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
        /** @var Setting $setting */
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

    // Shows the soft deleted entities for ROLE_ADMINISTRATOR
    public function disableSofDeleteFilterForAdmin(EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authChecker): void
    {
        $entityManager->getFilters()->enable('softdeleteable');
        if ($authChecker->isGranted("ROLE_ADMINISTRATOR")) {
            $entityManager->getFilters()->disable('softdeleteable');
        }
    }

    // Redirects to the referer page when available, if not, redirects to the dashboard index
    public function redirectToReferer($alt = null): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        if ($this->requestStack->getCurrentRequest()->headers->get('referer')) {
            return new RedirectResponse($this->requestStack->getCurrentRequest()->headers->get('referer'));
        } else {
            if ($alt) {
                if ($this->authChecker->isGranted(User::ADMINISTRATOR)) {
                    return new RedirectResponse($this->router->generate('dashboard_administrator_' . $alt));
                } elseif ($this->authChecker->isGranted(User::ADMIN)) {
                    return new RedirectResponse($this->router->generate('dashboard_admin_' . $alt));
                } elseif ($this->authChecker->isGranted(User::EDITOR)) {
                    return new RedirectResponse($this->router->generate('dashboard_editor_' . $alt));
                } elseif ($this->authChecker->isGranted(User::DEFAULT)) {
                    return new RedirectResponse($this->router->generate('dashboard_user_' . $alt));
                } else {
                    return new RedirectResponse($this->router->generate($alt));
                }
            } else {
                return new RedirectResponse($this->router->generate('dashboard_index'));
            }
        }
    }

    // Returns the layout settings entity to be used in the twig templates
    /*public function getAppLayoutSettings()
    {
        $appLayoutSettings = $this->entityManager->getRepository("App\Entity\AppLayoutSettings")->find(1);
        return $appLayoutSettings;
    }*/

    // Get route name from path
    public function getRouteName($path = null)
    {
        try {
            if ($path) {
                return $this->urlMatcherInterface->match($path)['_route'];
            } else {
                return $this->urlMatcherInterface->match($this->requestStack->getCurrentRequest()->getPathInfo())['_route'];
            }
        } catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
            return null;
        }
    }

    // Returns the pages after applying the specified search criterias
    public function getPages($criterias)
    {
        $this->disableSofDeleteFilterForAdmin($this->entityManager, $this->authChecker);
        $slug = array_key_exists('slug', $criterias) ? $criterias['slug'] : "all";

        return $this->entityManager->getRepository("App\Entity\Page")->getPages($slug);
    }
}
