<?php

namespace App\Controller;

use App\Controller\Controller;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/', name: 'homepage', methods: [Request::METHOD_GET])]
class HomePageController extends Controller
{
    public function __invoke(
        PostRepository $postRepository,
        CategoryRepository $categoryRepository
    ): Response {
        $user = $this->getUser();
        $lastPosts = $postRepository->findRecent(5);
        $lastCategories = $categoryRepository->findRecent(5);

        return $this->render('home/homepage.html.twig', [
            'user' => $user,
            'lastPosts' => $lastPosts,
            'lastCategories' => $lastCategories
        ]);
    }
}