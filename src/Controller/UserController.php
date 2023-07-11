<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Controller\Controller;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class UserController extends Controller
{
    #[Route(
        path: '/%website_dashboard_path%/profile/{slug}',
        name: 'dashboard_user_show',
        methods: [Request::METHOD_GET]
    )]
    public function profilUser(
        User $user, 
        CommentRepository $commentRepository,
        PostRepository $postRepository
    ): Response {
        return $this->render('user/profil.html.twig', [
            'user' => $user,
            'last_comments' => $commentRepository->findLastByUser($user, 4),
            'last_posts' => $postRepository->findLastByUser($user, 9),
        ]);
    }
}
