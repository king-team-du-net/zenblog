<?php

namespace App\Controller;

use App\Entity\Page;
use App\Entity\User;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\AppServices;
use App\Service\ContactService;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: '/page')]
class PagesController extends AbstractController
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    #[Route(path: '/contact', name: 'contact_index', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function contactIndex(
        Request $request,
        ContactService $contactService,
        AppServices $services
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

        if ('no' == $services->getSetting('google_recaptcha_enabled')) {
            $form->remove('recaptcha');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $contactService->persistContact($contact);

            $this->addFlash('success', $this->translator->trans('Your message has been successfully sent, thank you.'));

            return $this->redirectToRoute('contact_index');
        }

        return $this->render('pages/contact.html.twig', compact('form', 'contact'));
    }

    #[Route(path: '/access-denied', name: 'access_denied', methods: [Request::METHOD_GET])]
    public function accessDenied(): Response
    {
        return $this->render('pages/access-denied.html.twig');
    }

    #[Route(path: '/about', name: 'about_index', methods: [Request::METHOD_GET])]
    public function aboutIndex(UserRepository $userRepository): Response
    {
        return $this->render('pages/about.html.twig', [
            'users' => $userRepository->findUsers(User::ADMIN, User::ADMINISTRATOR, User::EDITOR),
        ]);
    }

    #[Route(path: '/{slug}', name: 'page', methods: [Request::METHOD_GET])]
    public function pages(Page $page): Response
    {
        if (!$page) {
            $this->addFlash('danger', $this->translator->trans('The page can not be found'));

            return $this->redirectToRoute('blog_index');
        }

        return $this->render('pages/page.html.twig', compact('page'));
    }
}
