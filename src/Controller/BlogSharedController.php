<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostSharedType;
use App\Service\SendMailService;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogSharedController extends AbstractController
{
    #[Route(path: '/blog-article/{slug}/shared', name: 'blog_article_shared', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function blogShared(
        Request $request,
        Post $post,
        PostRepository $postRepository,
        SendMailService $mail,
        TranslatorInterface $translator
    ): Response {
        if (!$post) {
            $this->addFlash('danger', $translator->trans('flash_danger.blog_post'));
            return $this->redirectToRoute('blog');
        }

        $form = $this->createForm(PostSharedType::class)->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();
                $subject = sprintf('%s recommends you to read "%s"', $data['sender_name'], $post->getTitle());
                $mail->send(
                    $data['receiver_email'],
                    $subject,
                    'blog-show-shared',
                    [
                        'post' => $post,
                        'sender_name' => $data['sender_name'],
                        'sender_comments' => $data['sender_comments'],
                    ],
                );
                $this->addFlash('success', $translator->trans('post.share_successfully'));
                return $this->redirectToRoute('blog');
            } else {
                $this->addFlash('danger', $translator->trans('post.invalid_data'));
            }

            return $this->redirectToRoute('blog_article_shared', ['slug' => $post->getSlug()]);
        }

        // Previous Post
        $previousPost = $postRepository->findPreviousPost($post);

        // Next Post
        $nextPost = $postRepository->findNextPost($post);

        return $this->render('post/blog-article-shared.html.twig', compact('post', 'form', 'previousPost', 'nextPost'));
    }
}
