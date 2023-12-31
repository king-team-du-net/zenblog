<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\RememberMeToken;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly TranslatorInterface $translator,
        private readonly AccessDeniedHandler $accessDeniedHandler
    ) {
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        $previous = $authException ? $authException->getPrevious() : null;

        if ($authException instanceof InsufficientAuthenticationException
            && $previous instanceof AccessDeniedException
            && $authException->getToken() instanceof RememberMeToken
        ) {
            return $this->accessDeniedHandler->handle($request, $previous);
        }

        if (\in_array('application/json', $request->getAcceptableContentTypes(), true)) {
            return new JsonResponse(
                ['title' => $this->translator->trans('throw.authentication_entry_point')],
                Response::HTTP_FORBIDDEN
            );
        }

        return new RedirectResponse($this->urlGenerator->generate('auth_login'));
    }
}
