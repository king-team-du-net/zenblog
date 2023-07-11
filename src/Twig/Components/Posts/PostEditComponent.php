<?php

declare(strict_types=1);

namespace App\Twig\Components\Posts;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsLiveComponent(name: 'post_edit', template: 'components/posts/post_edit.html.twig')]
final class PostEditComponent extends AbstractController
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp(exposed: ['title', 'content'])]
    #[Assert\Valid]
    public Post $post;

    public bool $isSaved = false;

    #[LiveAction]
    public function save(EntityManagerInterface $em): void
    {
        $this->validate();

        $this->isSaved = true;
        $em->flush();
    }
}
