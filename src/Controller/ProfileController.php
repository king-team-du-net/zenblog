<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UpdateAvatarType;
use App\Form\UpdatePasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Interface\UserProfile\UpdateAvatarInterface;
use App\Interface\UserProfile\UpdatePasswordInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::DEFAULT)]
#[Route('/profile', name: 'profile_')]
class ProfileController extends Controller
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {
    }

    #[Route('/update-avatar', name: 'update_avatar', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function updateAvatar(Request $request, UpdateAvatarInterface $updateAvatar): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(UpdateAvatarType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updateAvatar($user);

            $this->addFlash('success', $this->translator->trans('Your avatar has been changed successfully.'));

            return $this->redirectToRoute('profile_update_avatar');
        }

        return $this->render('profile/update_avatar.html.twig', compact('user', 'form'));
    }

    #[Route('/update-password', name: 'update_password', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function updatePassword(Request $request, UpdatePasswordInterface $updatePassword): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(UpdatePasswordType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updatePassword($user);

            $this->addFlash('success', $this->translator->trans('Your password has been changed successfully.'));

            return $this->redirectToRoute('profile_update_password');
        }

        return $this->render('profile/update_password.html.twig', compact('user', 'form'));
    }
}
