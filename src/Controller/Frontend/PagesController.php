<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Controller\Frontend;

use App\Entity\Contact;
use App\Entity\Page;
use App\Entity\User;
use App\Form\ContactType;
use App\Repository\UserRepository;
use App\Service\ContactService;
use App\Twig\TwigSettingExtension;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '/page')]
class PagesController extends AbstractController
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    #[Route(path: '/contact', name: 'contact', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function contact(
        Request $request,
        ContactService $contactService,
        TwigSettingExtension $setting
    ): Response {
        $contact = new Contact();

        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();
            $contact
                ->setFullName($user->getFullName())
                ->setEmail($user->getEmail())
            ;
        }

        $form = $this->createForm(ContactType::class, $contact);

        if ('no' === $setting->getSetting('google_recaptcha_enabled')) {
            $form->remove('recaptcha');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $contactService->persistContact($contact);

            $this->addFlash('success', $this->translator->trans('flash_success.contact_successfully'));

            return $this->redirectToRoute('contact');
        }

        return $this->render('frontend/pages/contact.html.twig', compact('form', 'contact'));
    }

    #[Route(path: '/access-denied', name: 'access_denied', methods: [Request::METHOD_GET])]
    public function accessDenied(): Response
    {
        return $this->render('frontend/pages/access-denied.html.twig');
    }

    #[Route(path: '/about', name: 'about', methods: [Request::METHOD_GET])]
    public function about(UserRepository $userRepository): Response
    {
        return $this->render('frontend/pages/about.html.twig', [
            'users' => $userRepository->findTeam(3),
        ]);
    }

    #[Route(path: '/{slug}', name: 'page', methods: [Request::METHOD_GET])]
    public function pages(Page $page): Response
    {
        if (!$page) {
            $this->addFlash('danger', $this->translator->trans('flash_danger.page'));

            return $this->redirectToRoute('homepage');
        }

        return $this->render('frontend/pages/page.html.twig', compact('page'));
    }
}
