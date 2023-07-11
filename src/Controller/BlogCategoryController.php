<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: '/blog')]
class BlogCategoryController extends AbstractController
{
    #[Route(path: '/category', name: 'blog_category_index', methods: [Request::METHOD_GET])]
    public function categoryIndex(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAllCategories(),
        ]);
    }

    #[Route(path: '/category/{slug}', name: 'blog_category_show', methods: [Request::METHOD_GET])]
    public function categoryShow(Category $category, PostRepository $postRepository): Response
    {
        $posts = $postRepository->findForCategory($category);

        return $this->render('category/show.html.twig', compact('category', 'posts'));
    }
}
