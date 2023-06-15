<?php

namespace App\Twig;

use Twig\TwigFunction;
use App\Entity\Setting;
use App\Repository\SettingRepository;
use Psr\Cache\CacheItemPoolInterface;
use Twig\Extension\AbstractExtension;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

final class TwigSettingExtension extends AbstractExtension
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
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
}
