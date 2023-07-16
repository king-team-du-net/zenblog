<?php

namespace App\Controller\Dashboard\Administrator;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Controller\Controller;
use App\Repository\PostRepository;
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
class BlogController extends Controller
{
    #[Route(path: '/%website_dashboard_path%/administrator/manage-blog', name: 'dashboard_administrator_blog_index', methods: [Request::METHOD_GET])]
    public function index(
        #[CurrentUser] User $user,
        Request $request,
        PostRepository $postRepository,
        PaginatorInterface $paginator
    ): Response {
        $query = $postRepository->findBy(['author' => $user], ['publishedAt' => 'DESC']);
        $page = $request->query->getInt('page', 1);

        $posts = $paginator->paginate(
            $query,
            $page,
            Post::POST_LIMIT
        );

        return $this->render('dashboard/administrator/blog/index.html.twig', compact('posts'));
    }

    #[Route(path: '/%website_dashboard_path%/administrator/manage-blog/add', name: 'dashboard_administrator_blog_add', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    #[Route(path: '/%website_dashboard_path%/administrator/manage-blog/{slug}/edit', name: 'dashboard_administrator_blog_edit', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function addedit(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $em,
        TranslatorInterface $translator
    ): Response {
        $post = new Post();
        $post->setAuthor($user);

        $form = $this->createForm(
            PostType::class, 
            $post,
            ['validation_groups' => ['cover', 'image', 'Default']]
        )->add('saveAndCreateNew', SubmitType::class)->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em->persist($post);
                $em->flush();
                if (!$post->getSlug()) {
                    $this->addFlash('success', $translator->trans('post.created_successfully'));
                } else {
                    $this->addFlash('success', $translator->trans('post.updated_successfully'));
                }
                /** @var SubmitButton $submit */
                $submit = $form->get('saveAndCreateNew');
                if ($submit->isClicked()) {
                    return $this->redirectToRoute('dashboard_administrator_blog_add');
                }
                return $this->redirectToRoute("dashboard_administrator_blog_index");
            } else {
                $this->addFlash('error', $translator->trans('content.invalid_data'));
            }
        }

        return $this->render('dashboard/administrator/blog/add-edit.html.twig', compact('post', 'form'));
    }

    #[Route(path: '/%website_dashboard_path%/administrator/manage-blog/{slug}/disable', name: 'dashboard_administrator_blog_disable', methods: [Request::METHOD_GET])]
    #[Route(path: '/%website_dashboard_path%/administrator/manage-blog/{slug}/delete', name: 'dashboard_administrator_blog_delete', methods: [Request::METHOD_GET])]
    #[IsGranted('delete', subject: 'post')]
    public function delete(Request $request, Post $post, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        /** @var string|null $token */
        $token = $request->request->get('token');

        if (!$this->isCsrfTokenValid('delete', $token)) {
            return $this->redirectToRoute('dashboard_administrator_blog_index');
        }

        if (!$post) {
            $this->addFlash('error', $translator->trans('post.no_posts_found'));
            return $this->redirectToRoute('dashboard_administrator_blog_index');
        }

        if ($post->getDeletedAt() !== null) {
            $this->addFlash('error', $translator->trans('post.deleted_successfully'));
        } else {
            $this->addFlash('notice', $translator->trans('post.disabled_successfully'));
        }

        $post->getTags()->clear();
        $post->setHidden(true);
        $em->persist($post);
        $em->flush();
        $em->remove($post);
        $em->flush();
        $this->addFlash('success', $translator->trans('post.deleted_successfully'));

        return $this->redirectToRoute('dashboard_administrator_blog_index');
    }

    #[Route(path: '/%website_dashboard_path%/administrator/manage-blog/{slug}/restore', name: 'dashboard_administrator_blog_restore', methods: [Request::METHOD_GET])]
    public function restore(Request $request, Post $post, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        if (!$post) {
            $this->addFlash('error', $translator->trans('post.no_posts_found'));
            return $this->redirectToRoute('dashboard_administrator_blog_index');
        }

        $post->setDeletedAt(null);
        $em->persist($post);
        $em->flush();
        $this->addFlash('success', $translator->trans('post.restored_successfully'));

        return $this->redirectToRoute('dashboard_administrator_blog_index');
    }

    #[Route(path: '/%website_dashboard_path%/administrator/manage-blog/{slug}/show', name: 'dashboard_administrator_blog_show', methods: [Request::METHOD_GET])]
    #[Route(path: '/%website_dashboard_path%/administrator/manage-blog/{slug}/hide', name: 'dashboard_administrator_blog_hide', methods: [Request::METHOD_GET])]
    public function showhide(Request $request, Post $post, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        if (!$post) {
            $this->addFlash('error', $translator->trans('post.no_posts_found'));
            return $this->redirectToRoute('dashboard_administrator_blog_index');
        }

        if ($post->getHidden() === true) {
            $post->setHidden(false);
            $this->addFlash('success', $translator->trans('content.is_visible'));
        } else {
            $post->setHidden(true);
            $this->addFlash('error', $translator->trans('content.is_hidden'));
        }

        $em->persist($post);
        $em->flush();

        return $this->redirectToRoute('dashboard_administrator_blog_index');
    }
}
