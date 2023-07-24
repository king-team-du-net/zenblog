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
use App\Entity\Page;
use App\Entity\User;
use App\Form\PageType;
use App\Repository\PageRepository;
use App\Twig\TwigSettingExtension;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted(User::ADMINISTRATOR)]
class PagesController extends Controller
{
    #[Route(path: '/%website_dashboard_path%/administrator/manage-pages', name: 'dashboard_administrator_page_index', methods: [Request::METHOD_GET])]
    public function index(
        #[CurrentUser] User $user,
        Request $request,
        PageRepository $pageRepository,
        PaginatorInterface $paginator
    ): Response {
        $query = $pageRepository->findAll();
        $page = $request->query->getInt('page', 1);

        $pages = $paginator->paginate(
            $query,
            $page,
            Page::PAGE_LIMIT
        );

        return $this->render('dashboard/administrator/pages/index.html.twig', compact('pages'));
    }

    #[Route(path: '/%website_dashboard_path%/administrator/manage-pages/add', name: 'dashboard_administrator_page_add', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    #[Route(path: '/%website_dashboard_path%/administrator/manage-pages/{slug}/edit', name: 'dashboard_administrator_page_edit', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function addedit(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $em,
        TwigSettingExtension $services,
        PageRepository $pageRepository,
        string $slug = null,
        TranslatorInterface $translator
    ): Response {
        if (!$slug) {
            $page = new Page();
        } else {
            /** @var Page $page */
            // $page = $pageRepository->getPages(array('slug' => $slug))->getQuery()->getOneOrNullResult();
            $page = $services->getPages(['slug' => $slug])->getQuery()->getOneOrNullResult();
            /** @var Page $page */
            if (!$page) {
                $this->addFlash('error', $translator->trans('page.no_pages_found'));

                return $this->redirectToRoute('dashboard_administrator_page_index');
            }
        }

        $form = $this->createForm(
            PageType::class,
            $page,
        )->add('saveAndCreateNew', SubmitType::class)->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em->persist($page);
                $em->flush();
                if (!$slug) {
                    $this->addFlash('success', $translator->trans('page.created_successfully'));
                } else {
                    $this->addFlash('success', $translator->trans('page.updated_successfully'));
                }
                /** @var SubmitButton $submit */
                $submit = $form->get('saveAndCreateNew');
                if ($submit->isClicked()) {
                    return $this->redirectToRoute('dashboard_administrator_page_add');
                }

                return $this->redirectToRoute('dashboard_administrator_page_index');
            }
            $this->addFlash('error', $translator->trans('content.invalid_data'));
        }

        return $this->render('dashboard/administrator/pages/add-edit.html.twig', compact('page', 'form'));
    }

    #[Route(path: '/%website_dashboard_path%/administrator/manage-pages/{slug}/disable', name: 'dashboard_administrator_page_disable', methods: [Request::METHOD_GET])]
    #[Route(path: '/%website_dashboard_path%/administrator/manage-pages/{slug}/delete', name: 'dashboard_administrator_page_delete', methods: [Request::METHOD_GET])]
    public function delete(Request $request, Page $page, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        /** @var string|null $token */
        $token = $request->request->get('token');

        if (!$this->isCsrfTokenValid('delete', $token)) {
            return $this->redirectToRoute('dashboard_administrator_page_index');
        }

        if (!$page) {
            $this->addFlash('error', $translator->trans('page.no_pages_found'));

            return $this->redirectToRoute('dashboard_administrator_page_index');
        }

        $em->remove($page);
        $em->flush();
        $this->addFlash('success', $translator->trans('page.deleted_successfully'));

        return $this->redirectToRoute('dashboard_administrator_page_index');
    }
}
