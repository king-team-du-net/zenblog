<?php

declare(strict_types=1);

namespace App\Twig\Components\Form;

use App\Entity\User;
use App\Form\UpdateProfileType;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsLiveComponent('update_profile', template: 'components/form/update_profile.html.twig')]
final class UpdateProfileComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp(fieldName: 'data')]
    public User $user;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(UpdateProfileType::class, $this->user);
    }
}
