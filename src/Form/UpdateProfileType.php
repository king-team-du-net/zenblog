<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UpdateProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('nickname', TextType::class, [
                'label' => 'label.username',
                'empty_data' => '',
                'required' => true,
                'disabled' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.username',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'label.email',
                'empty_data' => '',
                'required' => true,
                'disabled' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.email',
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'label.firstname',
                'empty_data' => '',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.firstname',
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'label.lastname',
                'empty_data' => '',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.lastname',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', User::class);
    }
}
