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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

final class PostSharedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sender_name', TextType::class, [
                'label' => 'label.fullname',
                'required' => true,
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.fullname',
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('sender_email', EmailType::class, [
                'label' => 'label.email',
                'required' => true,
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.email',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ],
            ])
            ->add('receiver_email', EmailType::class, [
                'label' => 'label.receiver_email',
                'required' => true,
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.receiver_email',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ],
            ])
            ->add('sender_comments', TextareaType::class, [
                'label' => 'label.sender_comments',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.sender_comments',
                    'rows' => 10,
                    'cols' => 30,
                ],
                'help' => 'help.sender_comments',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
