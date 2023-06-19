<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Form\ResetPasswordType;

final class UpdatePasswordType extends ResetPasswordType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Current Password',
                'empty_data' => '',
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Your current password',
                ],
                'constraints' => [
                    new UserPassword(),
                    new NotBlank(),
                ],
            ])
        ;
    }
}
