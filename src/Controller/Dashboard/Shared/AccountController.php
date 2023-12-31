<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Controller\Dashboard\Shared;

use App\Controller\Controller;
use App\Entity\User;
use App\Form\UpdateAvatarType;
use App\Form\UpdatePasswordType;
use App\Form\UpdateProfileType;
use App\Interface\UserProfile\UpdateAvatarInterface;
use App\Interface\UserProfile\UpdatePasswordInterface;
use App\Interface\UserProfile\UpdateProfileInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Logout\LogoutUrlGenerator;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted(User::DEFAULT)]
#[Route('/%website_dashboard_path%/account/settings', name: 'dashboard_user_account_settings_')]
class AccountController extends Controller
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {
    }

    #[Route('/update-profile', name: 'profile', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function profile(Request $request, UpdateProfileInterface $updateProfile): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(UpdateProfileType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updateProfile($user);

            $this->addFlash('success', $this->translator->trans('flash_success.update_profile_successfully'));

            return $this->redirectToRoute('dashboard_user_account_settings_profile');
        }

        return $this->render('dashboard/shared/account/update_profile.html.twig', compact('user', 'form'));
    }

    #[Route('/update-avatar', name: 'avatar', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function avatar(Request $request, UpdateAvatarInterface $updateAvatar): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(UpdateAvatarType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updateAvatar($user);

            $this->addFlash('success', $this->translator->trans('flash_success.update_avatar_successfully'));

            return $this->redirectToRoute('dashboard_user_account_settings_avatar');
        }

        return $this->render('dashboard/shared/account/update_avatar.html.twig', compact('user', 'form'));
    }

    #[Route('/update-password', name: 'password', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function password(Request $request, UpdatePasswordInterface $updatePassword, LogoutUrlGenerator $logoutUrlGenerator): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(UpdatePasswordType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updatePassword($user);

            $this->addFlash('success', $this->translator->trans('flash_success.update_password_successfully'));

            return $this->redirect($logoutUrlGenerator->getLogoutPath());
        }

        return $this->render('dashboard/shared/account/update_password.html.twig', compact('user', 'form'));
    }
}
