<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Controller\Dashboard\Main;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MainController extends AbstractController
{
    #[Route('/%website_dashboard_path%', name: 'dashboard_index')]
    public function indexDashboardAction(AuthorizationCheckerInterface $authChecker): Response
    {
        if ($authChecker->isGranted(User::ADMINISTRATOR)) {
            return $this->redirectToRoute('dashboard_administrator_index');
        } elseif ($authChecker->isGranted(User::ADMIN)) {
            return $this->redirectToRoute('dashboard_admin_index');
        } elseif ($authChecker->isGranted(User::EDITOR)) {
            return $this->redirectToRoute('dashboard_editor_index');
        } elseif ($authChecker->isGranted(User::DEFAULT)) {
            return $this->redirectToRoute('dashboard_user_account_settings_index');
        }

        return $this->redirectToRoute('auth_login');
    }
}
