<?php

declare(strict_types=1);

namespace App\Twig\Components\Form;

use App\Entity\Post;
use App\Form\PostSharedType;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsLiveComponent('shared', template: 'components/form/shared.html.twig')]
final class SharedComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp(fieldName: 'data')]

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(PostSharedType::class);
    }
}
