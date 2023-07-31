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

use App\Entity\Post;
use App\Entity\Category;
use App\Form\Type\DropzoneType;
use App\Form\Type\TagInputType;
use App\Form\Type\TagsInputType;
use App\Form\Type\DatePickerType;
use App\Form\Type\TextEditorType;
use App\Form\Type\DateTimePickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Interface\DataTransformer\TagsTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

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
                    'placeholder' => 'placeholder.title',
                ],
            ])
            ->add('category', EntityType::class, [
                'label' => 'label.category',
                'class' => Category::class,
                'choice_label' => 'name',
                'required' => true,
                'help' => 'help.entity_category',
            ])
            ->add('readtime', TextType::class, [
                'required' => false,
                'label' => 'label.readtime',
                'attr' => ['class' => 'touchspin-integer mb-2', 'data-min' => 1, 'data-max' => 1000000],
            ])
            ->add('publishedAt', DateTimePickerType::class, [
                'label' => 'label.published_at',
                'help' => 'help.post_publication',
            ])
            ->add('tags', TagsInputType::class, [
                'required' => false,
            ])
            ->add('content', TextEditorType::class, [
                'empty_data' => '',
                'help' => 'help.post_content',
            ])
            ->add('excerpt', TextareaType::class, [
                'label' => 'label.excerpt',
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control flex-grow-1',
                    'placeholder' => 'placeholder.excerpt',
                    'rows' => 4,
                    'cols' => 30,
                ],
                'help' => 'help.post_excerpt',
            ])
            /*
            ->add('imageFile', FileType::class, [
                'label' => 'label.image_file',
                'required' => in_array('create', $options['validation_groups'] ?? [])
            ])

            ->add('imageFile', DropzoneType::class, [
                'label' => 'label.cover_file',
                'required' => in_array('create', $options['validation_groups'] ?? [])
            ])
            */

            ->add('coverFile', DropzoneType::class, [
                'label' => 'label.cover_file',
                'required' => \in_array('cover', $options['validation_groups'] ?? [], true),
            ])
            ->add('medias', CollectionType::class, [
                'label' => 'label.medias',
                'entry_type' => MediaType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
        ;

        $builder->get('tags')->addModelTransformer($this->tagsTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', Post::class);
    }
}
