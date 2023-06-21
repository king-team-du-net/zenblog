<?php

declare(strict_types=1);

namespace App\Controller\Dashboard\User;

use App\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/** MyProfile */
#[IsGranted('ROLE_USER')]
final class MainController extends Controller
{
    #[Route(path: '/%website_dashboard_path%/profil', name: 'dashboard_user_index', methods: [Request::METHOD_GET])]
    public function dashboardUserIndex(): Response
    {
        $user = $this->getUserOrThrow();

        return $this->render('dashboard/user/index.html.twig', [
            'user' => $user
        ]);
    }
}
