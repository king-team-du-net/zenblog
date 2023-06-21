<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UpdateAvatarType;
use App\Form\UpdateProfileType;
use App\Form\UpdatePasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Interface\UserProfile\UpdateAvatarInterface;
use App\Interface\UserProfile\UpdateProfileInterface;
use App\Interface\UserProfile\UpdatePasswordInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Logout\LogoutUrlGenerator;

#[IsGranted(User::DEFAULT)]
#[Route('/profile', name: 'profile_')]
class ProfileController extends Controller
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {
    }

    #[Route('/update-profile', name: 'update_profile', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function updateProfile(Request $request, UpdateProfileInterface $updateProfile): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(UpdateProfileType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updateProfile($user);

            $this->addFlash('success', $this->translator->trans('flash_success.update_profile_successfully'));

            return $this->redirectToRoute('profile_update_profile');
        }

        return $this->render('profile/update_profile.html.twig', compact('user', 'form'));
    }

    #[Route('/update-avatar', name: 'update_avatar', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function updateAvatar(Request $request, UpdateAvatarInterface $updateAvatar): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(UpdateAvatarType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updateAvatar($user);

            $this->addFlash('success', $this->translator->trans('flash_success.update_avatar_successfully'));

            return $this->redirectToRoute('profile_update_avatar');
        }

        return $this->render('profile/update_avatar.html.twig', compact('user', 'form'));
    }

    #[Route('/update-password', name: 'update_password', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function updatePassword(Request $request, UpdatePasswordInterface $updatePassword, LogoutUrlGenerator $logoutUrlGenerator): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(UpdatePasswordType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updatePassword($user);

            $this->addFlash('success', $this->translator->trans('flash_success.update_password_successfully'));

            //return $this->redirectToRoute('profile_update_password');

            return $this->redirect($logoutUrlGenerator->getLogoutPath());
        }

        return $this->render('profile/update_password.html.twig', compact('user', 'form'));
    }
}
