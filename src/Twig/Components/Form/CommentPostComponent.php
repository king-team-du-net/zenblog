<?php

declare(strict_types=1);

namespace App\Twig\Components\Form;

use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('comment_post', template: 'components/form/comment_post.html.twig')]
final class CommentPostComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp(fieldName: 'data')]
    public ?Comment $comment = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(CommentType::class, $this->comment);
    }
}
