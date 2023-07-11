<?php

namespace App\Controller;

use Faker\Factory;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_blog', methods: [Request::METHOD_GET])]
    public function index(): Response
    {
        return $this->render('test/index.html.twig');
    }

    #[Route('/test/search', name: 'app_search')]
    public function search(): Response
    {
        return $this->render('test/search.html.twig');
    }

    #[Route('/test/edit/{id}', name: 'app_edit')]
    public function edit(Post $post): Response
    {
        return $this->render('test/edit.html.twig', compact('post'));
    }

    #[Route('/test/generate', name: 'app_generate')]
    public function generate(EntityManagerInterface $em): Response
    {
        $faker = Factory::create('fr_FR');

        $post = (new Post())
            ->setTitle($faker->sentence())
            ->setContent($faker->paragraph())
        ;

        $em->persist($post);
        $em->flush();
        
        dd('Blog post created');
    }
}
