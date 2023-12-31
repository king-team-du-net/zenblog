<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Twig\Components\Form;

use App\Entity\ResetPasswordRequest;
use App\Form\ResetPasswordRequestType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('request_reset_password', template: 'components/form/request_reset_password.html.twig')]
final class RequestResetPasswordComponent extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    #[LiveProp(fieldName: 'data')]
    public ?ResetPasswordRequest $resetPasswordRequest = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ResetPasswordRequestType::class, $this->resetPasswordRequest);
    }
}
