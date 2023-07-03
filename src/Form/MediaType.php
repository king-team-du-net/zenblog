<?php

declare(strict_types=1);

namespace App\Form;

use App\Form\Dto\MediaDto;
use App\Form\Type\DropzoneType;
use Symfony\Component\Form\AbstractType;
use App\Form\DataTransformer\MediaTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

final class MediaType extends AbstractType
{
    public function __construct(private readonly MediaTransformer $mediaTransformer)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', HiddenType::class)
            ->add('file', DropzoneType::class, [
                'label' => 'label.image_file',
                'required' => false,
            ])
            ->add('alt', TextType::class, [
                'label' => 'label.alt',
                'required' => false,
                'empty_data' => '',
                'attr' => ['placeholder' => 'placeholder.alt'],
            ])
            ->add('url', UrlType::class, [
                'label' => 'label.url',
                'empty_data' => '',
                'required' => false,
            ])
            ->addModelTransformer($this->mediaTransformer)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', MediaDto::class);
    }
}
