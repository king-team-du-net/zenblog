<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Controller\Dashboard\Administrator;

use App\Controller\Controller;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted(User::ADMINISTRATOR)]
class UserController extends Controller
{
    #[Route(path: '/%website_dashboard_path%/administrator/manage-users', name: 'dashboard_administrator_user_index', methods: [Request::METHOD_GET])]
    public function index(
        #[CurrentUser] User $user,
        Request $request,
        UserRepository $userRepository,
        PaginatorInterface $paginator
    ): Response {
        $query = $userRepository->findAll();
        $page = $request->query->getInt('page', 1);

        $users = $paginator->paginate(
            $query,
            $page,
            User::USER_LIMIT
        );

        return $this->render('dashboard/administrator/user/index.html.twig', compact('users'));
    }

    #[Route(path: '/%website_dashboard_path%/administrator/manage-users/{slug}/delete-permanently', name: 'dashboard_administrator_user_delete_permanently', methods: [Request::METHOD_GET])]
    #[Route(path: '/%website_dashboard_path%/administrator/manage-users/{slug}/delete', name: 'dashboard_administrator_user_delete', methods: [Request::METHOD_GET])]
    public function delete(Request $request, User $user, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        /** @var string|null $token */
        $token = $request->request->get('token');

        if (!$this->isCsrfTokenValid('delete', $token)) {
            return $this->redirectToRoute('dashboard_administrator_user_index');
        }

        if (!$user) {
            $this->addFlash('error', $translator->trans('user.no_users_found'));

            return $this->redirectToRoute('dashboard_administrator_user_index');
        }

        if (null !== $user->getDeletedAt()) {
            $this->addFlash('error', $translator->trans('user.permanently_deleted_successfully'));
        } else {
            $this->addFlash('notice', $translator->trans('user.deleted_successfully'));
        }

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('dashboard_administrator_user_index');
    }

    #[Route(path: '/%website_dashboard_path%/administrator/manage-users/{slug}/restore', name: 'dashboard_administrator_user_restore', methods: [Request::METHOD_GET])]
    public function restore(Request $request, User $user, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        if (!$user) {
            $this->addFlash('error', $translator->trans('user.no_users_foundd'));

            return $this->redirectToRoute('dashboard_administrator_user_index');
        }

        $user->setDeletedAt(null);
        $em->persist($user);
        $em->flush();
        $this->addFlash('success', $translator->trans('user.restored_successfully'));

        return $this->redirectToRoute('dashboard_administrator_user_index');
    }

    #[Route(path: '/%website_dashboard_path%/administrator/manage-users/{slug}/enable', name: 'dashboard_administrator_user_enable', methods: [Request::METHOD_GET])]
    #[Route(path: '/%website_dashboard_path%/administrator/manage-users/{slug}/disable', name: 'dashboard_administrator_user_disable', methods: [Request::METHOD_GET])]
    public function enabledisable(Request $request, User $user, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        if (!$user) {
            $this->addFlash('error', $translator->trans('user.no_users_foundd'));

            return $this->redirectToRoute('dashboard_administrator_user_index');
        }

        if ($user->isIsVerified()) {
            $user->setIsVerified(false);
            $this->addFlash('notice', $translator->trans('user.disabled_successfully'));
        } else {
            $user->setIsVerified(true);
            $this->addFlash('success', $translator->trans('user.enabled_successfully'));
        }

        $em->persist($user);
        $em->flush();
        $this->addFlash('success', $translator->trans('user.restored_successfully'));

        return $this->redirectToRoute('dashboard_administrator_user_index');
    }

    #[Route(path: '/%website_dashboard_path%/administrator/manage-users/{slug}/more-information', name: 'dashboard_administrator_user_information', methods: [Request::METHOD_GET])]
    public function details(Request $request, User $user, TranslatorInterface $translator): Response
    {
        if (!$user) {
            $this->addFlash('error', $translator->trans('user.no_users_foundd'));
        }

        return $this->render('dashboard/administrator/user/information.html.twig', compact('user'));
    }
}
