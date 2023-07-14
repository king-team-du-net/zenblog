<?php

namespace App\Controller;

use App\Controller\Controller;
use App\Repository\PostRepository;
use App\Entity\HomepageHeroSettings;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/', name: 'homepage', methods: [Request::METHOD_GET])]
class HomePageController extends Controller
{
    public function __invoke(
        EntityManagerInterface $em,
        PostRepository $postRepository,
        CategoryRepository $categoryRepository
    ): Response {
        $user = $this->getUser();
        $herosettings = $em->getRepository(HomepageHeroSettings::class)->find(1);
        $lastPosts = $postRepository->findRecent(5);
        $lastCategories = $categoryRepository->findRecent(5);

        return $this->render('home/homepage.html.twig', [
            'user' => $user,
            'herosettings' => $herosettings,
            'lastPosts' => $lastPosts,
            'lastCategories' => $lastCategories
        ]);
    }
}
