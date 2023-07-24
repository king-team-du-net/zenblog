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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/** MyProfile */
#[IsGranted(User::DEFAULT)]
class MainController extends Controller
{
    #[Route(path: '/%website_dashboard_path%/account/settings', name: 'dashboard_user_account_settings_index', methods: [Request::METHOD_GET])]
    public function dashboardUserIndex(): Response
    {
        $user = $this->getUserOrThrow();

        return $this->render('dashboard/shared/index.html.twig', [
            'user' => $user,
        ]);
    }
}
