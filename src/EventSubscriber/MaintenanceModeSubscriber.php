<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use Twig\Environment;
use App\Service\AppServices;
use App\Twig\TwigSettingExtension;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

final class MaintenanceModeSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ParameterBagInterface $params,
        private readonly Security $security,
        private readonly TranslatorInterface $translator,
        private readonly Environment $templating,
        private readonly TwigSettingExtension $setting
    ) {
    }

    public function onKernelController(ControllerEvent $event): void
    {
        try {
            if ('1' == $this->params->get('maintenance_mode') && !$this->security->isGranted(User::ADMINISTRATOR)) {
                $event->setController(
                    function () {
                        return new Response($this->templating->render('pages/maintenance-mode.html.twig', ['customMessage' => $this->setting->getSetting('maintenance_mode_custom_message')], Response::HTTP_SERVICE_UNAVAILABLE));
                    });
            }
        } catch (AuthenticationCredentialsNotFoundException $e) {
        }
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
