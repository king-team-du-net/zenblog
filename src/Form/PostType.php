<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Post;
use App\Entity\Category;
use App\Form\DateTimePickerType;
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
                'label' => 'label.title',
                'empty_data' => '',
                'attr' => [
                    'autofocus' => true,
                    'class' => 'form-control',
                    'placeholder' => "placeholder.title",
                ],
            ])
            ->add('category', EntityType::class, [
                'label' => 'label.category',
                'class' => Category::class,
            ])
            ->add('excerpt', TextareaType::class, [
                'label' => 'label.excerpt',
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.excerpt',
                    'rows' => 4,
                    'cols' => 30,
                ],
                'help' => 'help.post_excerpt',
            ])
            ->add('content', null, [
                'label' => 'label.content',
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.content',
                    'rows' => 10,
                    'cols' => 30,
                ],
                'help' => 'help.post_content',
            ])
            ->add('publishedAt', DateTimePickerType::class, [
                'label' => 'label.published_at',
                'help' => 'help.post_publication',
            ])
            ->add('tags', TextType::class, [
                'label' => 'label.tags',
                'required' => false,
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'label.image_file',
                'required' => in_array('create', $options['validation_groups'] ?? [])
            ]);

        $builder->get('tags')->addModelTransformer($this->tagsTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', Post::class);
    }
}
