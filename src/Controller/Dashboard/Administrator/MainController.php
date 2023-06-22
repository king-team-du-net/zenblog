<?php

namespace App\Controller\Dashboard\Administrator;

use App\Entity\User;
use App\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ADMINISTRATOR)]
class MainController extends Controller
{
    #[Route(path: '/%website_dashboard_path%/administrator', name: 'dashboard_administrator_index', methods: [Request::METHOD_GET])]
    public function dashboardUserIndex(): Response
    {
        return $this->render('dashboard/administrator/index.html.twig');
    }
}
