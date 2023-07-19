<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Form\ResetPasswordType;
use App\Service\SendMailService;
use App\Repository\UserRepository;
use App\Service\JWTManagerService;
use App\Entity\ResetPasswordRequest;
use App\Form\ResetPasswordRequestType;
use Doctrine\ORM\EntityManagerInterface;
use App\Interface\Auth\ResendVerifInterface;
use App\Interface\Auth\RegistrationInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Interface\Auth\ResetPasswordInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Interface\Auth\ConfirmRegistrationInterface;
use App\Interface\Auth\RequestResetPasswordInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route(name: 'auth_')]
final class AuthController extends AbstractController
{
    use TargetPathTrait;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly UserRepository $userRepository
    ) {
    }

    #[Route(path: '/signin', name: 'login', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $this->addFlash('danger', $this->translator->trans('flash_danger.already_logged_in'));
            return $this->redirectToRoute('homepage');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/signout', name: 'logout')]
    /** @codeCoverageIgnore */
    public function logout(): void
    {
        
    }

    #[Route('/signup', name: 'registration', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function registration(Request $request, RegistrationInterface $registration): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $this->addFlash('danger', $this->translator->trans('flash_danger.already_logged_in'));
            return $this->redirectToRoute('homepage');
        }

        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $registration($user);

            $this->addFlash('success', $this->translator->trans('flash_success.create_account_successfully'));

            return $this->redirectToRoute('homepage');
        }

        return $this->render('auth/registration.html.twig', compact('form', 'user'));
    }

    #[Route('/signup/{registrationToken}/confirm', name: 'confirm_registration', methods: [Request::METHOD_GET])]
    public function confirmRegistration(User $user, ConfirmRegistrationInterface $confirmRegistration): RedirectResponse
    {
        $confirmRegistration($user);

        $this->addFlash('success', $this->translator->trans('flash_success.validated_account_successfully'));

        return $this->redirectToRoute('auth_login');
    }

    #[Route('/reset-password/request', name: 'reset_password_request', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function requestResetPassword(Request $request, RequestResetPasswordInterface $requestResetPassword): Response
    {
        $resetPasswordRequest = new ResetPasswordRequest();

        $form = $this->createForm(ResetPasswordRequestType::class, $resetPasswordRequest)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $requestResetPassword($resetPasswordRequest);

            $this->addFlash('success', $this->translator->trans('flash_success.password_reset_request_successfully'));

            return $this->redirectToRoute('homepage');
        }

        return $this->render('auth/request_reset_password.html.twig', [
            'form' => $form,
            'reset_password_request' => $resetPasswordRequest,
        ]);
    }

    #[Route('/reset-password/{token}', name: 'reset_password', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function resetPassword(
        ResetPasswordRequest $resetPasswordRequest,
        Request $request,
        ResetPasswordInterface $resetPassword
    ): Response {
        if ($resetPasswordRequest->isExpired()) {
            throw new BadRequestHttpException($this->translator->trans('throw.password_reset_link_expired'));
        }

        $form = $this->createForm(ResetPasswordType::class, $resetPasswordRequest->getUser())
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $resetPassword($resetPasswordRequest);

            $this->addFlash('success', $this->translator->trans('flash_success.reset-password_successfully'));

            return $this->redirectToRoute('auth_login');
        }

        return $this->render('auth/reset_password.html.twig', [
            'form' => $form,
            'user' => $resetPasswordRequest->getUser(),
        ]);
    }

    #[Route('/resend/{registrationToken}/verif', name: 'resend_verif', methods: [Request::METHOD_GET])]
    public function resendVerif(JWTManagerService $jWTManagerService, SendMailService $mail, User $user, ResendVerifInterface $resendVerif): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', $this->translator->trans('flash_danger.resend_verif'));
            return $this->redirectToRoute('auth_login');
        }

        if ($user->getIsVerified()) {
            $this->addFlash('warning', $this->translator->trans('flash_warning.resend_verif'));
            return $this->redirectToRoute('dashboard_user_account_settings_profile');
        }

        $resendVerif($user);

        $header = ['typ' => 'JWT', 'alg' => 'HS256'];
        $payload = ['user_id' => $user->getId()];
        $token = $jWTManagerService->generate($header, $payload, $this->getParameter('website_jwt_secret'));

        $mail->send(
            $user->getEmail(),
            $this->translator->trans('mail.subject_resend_verif'),
            'registration',
            compact('user', 'token')
        );

        $this->addFlash('success', $this->translator->trans('flash_success.resend_verif_successfully'));

        return $this->redirectToRoute('dashboard_user_account_settings_profile');
    }
}
