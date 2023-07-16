<?php

namespace App\Controller\Dashboard\Administrator;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Entity\Category;
use App\Controller\Controller;
use App\Form\PostCategoryType;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\SubmitButton;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[IsGranted(User::ADMINISTRATOR)]
class CategoryController extends Controller
{
    #[Route(path: '/%website_dashboard_path%/administrator/manage-blog-categories', name: 'dashboard_administrator_blog_category_index', methods: [Request::METHOD_GET])]
    public function index(
        #[CurrentUser] User $user,
        Request $request,
        CategoryRepository $categoryRepository,
        PaginatorInterface $paginator
    ): Response {
        $query = $categoryRepository->findBy([], ['createdAt' => 'DESC']);
        $page = $request->query->getInt('page', 1);

        $categories = $paginator->paginate(
            $query,
            $page,
            Category::CATEGORY_LIMIT
        );

        return $this->render('dashboard/administrator/category/index.html.twig', compact('categories'));
    }

    #[Route(path: '/%website_dashboard_path%/administrator/manage-blog-categories/add', name: 'dashboard_administrator_blog_category_add', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    #[Route(path: '/%website_dashboard_path%/administrator/manage-blog-categories/{slug}/edit', name: 'dashboard_administrator_blog_category_edit', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function addedit(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $em,
        TranslatorInterface $translator
    ): Response {
        $category = new Category();

        $form = $this->createForm(
            PostCategoryType::class, 
            $category,
        )->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em->persist($category);
                $em->flush();
                if (!$category->getSlug()) {
                    $this->addFlash('success', $translator->trans('category.created_successfully'));
                } else {
                    $this->addFlash('success', $translator->trans('category.updated_successfully'));
                }
                return $this->redirectToRoute("dashboard_administrator_blog_category_index");
            } else {
                $this->addFlash('error', $translator->trans('content.invalid_data'));
            }
        }

        return $this->render('dashboard/administrator/category/add-edit.html.twig', compact('category', 'form'));
    }

    #[Route(path: '/%website_dashboard_path%/administrator/manage-blog-categories/{slug}/disable', name: 'dashboard_administrator_blog_category_disable', methods: [Request::METHOD_GET])]
    #[Route(path: '/%website_dashboard_path%/administrator/manage-blog-categories/{slug}/delete', name: 'dashboard_administrator_blog_category_delete', methods: [Request::METHOD_GET])]
    #[IsGranted('delete', subject: 'post')]
    public function delete(Request $request, Category $category, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        /** @var string|null $token */
        $token = $request->request->get('token');

        if (!$this->isCsrfTokenValid('delete', $token)) {
            return $this->redirectToRoute('dashboard_administrator_blog_category_index');
        }

        if (!$category) {
            $this->addFlash('error', $translator->trans('category.no_categories_found'));
            return $this->redirectToRoute('dashboard_administrator_blog_category_index');
        }

        if ($category->getDeletedAt() !== null) {
            $this->addFlash('error', $translator->trans('category.deleted_successfully'));
        } else {
            $this->addFlash('notice', $translator->trans('category.disabled_successfully'));
        }

        $category->setHidden(true);
        $em->persist($category);
        $em->flush();
        $em->remove($category);
        $em->flush();
        $this->addFlash('success', $translator->trans('category.deleted_successfully'));

        return $this->redirectToRoute('dashboard_administrator_blog_category_index');
    }

    #[Route(path: '/%website_dashboard_path%/administrator/manage-blog-categories/{slug}/restore', name: 'dashboard_administrator_blog_category_restore', methods: [Request::METHOD_GET])]
    public function restore(Request $request, Category $category, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        if (!$category) {
            $this->addFlash('error', $translator->trans('category.no_categories_foundd'));
            return $this->redirectToRoute('dashboard_administrator_blog_category_index');
        }

        $category->setDeletedAt(null);
        $em->persist($category);
        $em->flush();
        $this->addFlash('success', $translator->trans('category.restored_successfully'));

        return $this->redirectToRoute('dashboard_administrator_blog_category_index');
    }

    #[Route(path: '/%website_dashboard_path%/administrator/manage-blog-categories/{slug}/show', name: 'dashboard_administrator_blog_category_show', methods: [Request::METHOD_GET])]
    #[Route(path: '/%website_dashboard_path%/administrator/manage-blog-categories/{slug}/hide', name: 'dashboard_administrator_blog_category_hide', methods: [Request::METHOD_GET])]
    public function showhide(Request $request, Category $category, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        if (!$category) {
            $this->addFlash('error', $translator->trans('category.no_categories_found'));
            return $this->redirectToRoute('dashboard_administrator_blog_category_index');
        }

        if ($category->getHidden() === true) {
            $category->setHidden(false);
            $this->addFlash('success', $translator->trans('content.is_visible'));
        } else {
            $category->setHidden(true);
            $this->addFlash('error', $translator->trans('content.is_hidden'));
        }

        $em->persist($category);
        $em->flush();

        return $this->redirectToRoute('dashboard_administrator_blog_category_index');
    }
}
