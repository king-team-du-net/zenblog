<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Controller\Frontend;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/blog')]
class BlogCategoryController extends AbstractController
{
    #[Route(path: '/category', name: 'blog_category_index', methods: [Request::METHOD_GET])]
    public function categoryIndex(CategoryRepository $categoryRepository): Response
    {
        return $this->render('frontend/category/index.html.twig', [
            'categories' => $categoryRepository->findAllCategories(),
        ]);
    }

    #[Route(path: '/category/{slug}', name: 'blog_category_show', methods: [Request::METHOD_GET])]
    public function categoryShow(Category $category, PostRepository $postRepository): Response
    {
        $posts = $postRepository->findForCategory($category);

        return $this->render('frontend/category/show.html.twig', compact('category', 'posts'));
    }
}
