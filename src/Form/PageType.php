<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Page;
use App\Form\Type\TextEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

final class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'label.title',
                'empty_data' => '',
                'attr' => [
                    'autofocus' => true,
                    'class' => 'form-control',
                    'placeholder' => "placeholder.title",
                ],
            ])
            ->add('content', TextEditorType::class, [
                'empty_data' => '',
            ])
            /*
            ->add('save', SubmitType::class, [
                'label' => 'label.save',
                'attr' => ['class' => 'btn btn-primary'],
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', Page::class);
    }
}
