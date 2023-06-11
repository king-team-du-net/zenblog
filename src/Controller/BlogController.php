<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    public function __construct(private readonly CategoryRepository $categoryRepository)
    {
    }

    #[Route('/', name: 'blog_index')]
    public function index(): Response
    {
        $categories = $this->categoryRepository->findAll();

        return $this->render('blog/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}
