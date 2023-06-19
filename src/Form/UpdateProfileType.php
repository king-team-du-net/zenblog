<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

final class UpdateProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('nickname', TextType::class, [
                'label' => 'Username',
                'empty_data' => '',
                'required' => true,
                'disabled' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Your username',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'empty_data' => '',
                'required' => true,
                'disabled' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Your email address',
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'First name',
                'empty_data' => '',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Your firstname',
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Last name',
                'empty_data' => '',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Your lastname',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', User::class);
    }
}
