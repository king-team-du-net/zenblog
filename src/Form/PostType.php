<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Post;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Interface\DataTransformer\TagsTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

final class PostType extends AbstractType
{
    public function __construct(private readonly TagsTransformer $tagsTransformer)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Enter your title",
                ],
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Category::class,
            ])
            ->add('excerpt', TextareaType::class, [
                'label' => 'Excerpt',
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter your excerpt',
                    'rows' => 4,
                    'cols' => 30,
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Content',
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter your content',
                    'rows' => 10,
                    'cols' => 30,
                ],
            ])
            ->add('tags', TextType::class, [
                'label' => 'Tags',
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image',
                'required' => in_array('create', $options['validation_groups'] ?? [])
            ]);

        $builder->get('tags')->addModelTransformer($this->tagsTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', Post::class);
    }
}
