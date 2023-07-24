<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Controller\Api;

use App\Entity\Post;
use App\Interface\Post\Listing\ListCommentsInterface;
use App\Interface\Post\Listing\ListPostsInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blogs', name: 'blog_')]
final class BlogController extends AbstractController
{
    #[Route(name: 'get_collection', methods: [Request::METHOD_GET])]
    public function getCollection(Request $request, ListPostsInterface $listPosts): JsonResponse
    {
        return $this->json(
            $listPosts($request->query->getInt('page', 1)),
            Response::HTTP_OK,
            ['Content-Type' => 'application/hal+json'],
            ['groups' => ['post:read']]
        );
    }

    #[Route('/{id}/comments', name: 'comments', methods: [Request::METHOD_GET])]
    public function getItem(Request $request, Post $post, ListCommentsInterface $listComments): JsonResponse
    {
        return $this->json(
            $listComments($post, $request->query->getInt('page', 1)),
            Response::HTTP_OK,
            ['Content-Type' => 'application/hal+json'],
            ['groups' => ['comment:read']]
        );
    }
}
