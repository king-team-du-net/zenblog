<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route(path: '/category', name: 'category_index', methods: [Request::METHOD_GET])]
    public function categoryIndex(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route(path: '/category/{slug}', name: 'category_show', methods: [Request::METHOD_GET])]
    public function categoryShow(Category $category, PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAllCategory($category);

        return $this->render('category/show.html.twig', compact('categorie', 'posts'));
    }
}
