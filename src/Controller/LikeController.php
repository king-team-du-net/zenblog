<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class LikeController extends AbstractController
{
    #[Route(path: '/like/post/{id}', name: 'like_post', methods: [Request::METHOD_GET])]
    public function likePost(Post $post, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        $user = $this->getUser();

        if ($post->isLikedByUser($user)) {
            $post->removeLike($user);
            $em->flush();

            return $this->json([
                'message' => $translator->trans('like.been_removed'),
                'nbLike' => $post->howManyLikes(),
            ]);
        }

        $post->addLike($user);
        $em->flush();

        return $this->json([
            'message' => $translator->trans('like.been_added'),
            'nbLike' => $post->howManyLikes(),
        ]);
    }
}
