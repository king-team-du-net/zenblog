<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Controller\Frontend;

use App\Controller\Controller;
use App\Entity\Content;
use App\Entity\User;
use App\Form\BlogRevisionType;
use App\Security\Voter\RevisionVoter;
use App\Service\RevisionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @method User getUser()
 */
class BlogRevisionController extends Controller
{
    #[Route(path: '/blog-article/revision/{slug}', name: 'blog_article_revision', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    #[IsGranted(RevisionVoter::ADD_REVISION)]
    public function blogArticleRevision(
        Request $request,
        Content $content,
        TranslatorInterface $translator,
        RevisionService $service
    ): Response {
        $revision = $service->revisionFor($this->getUser(), $content);

        $form = $this->createForm(BlogRevisionType::class, $revision)->handleRequest($request);
        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->flashErrors($form);
            } else {
                $service->submitRevision($revision);
                $this->addFlash(
                    'success',
                    $translator->trans('flash_success.revision_successfully')
                );
            }

            return $this->redirectToRoute('blog');
        }

        return $this->render('frontend/post/blog-revision.html.twig', compact('revision', 'form'));
    }
}
