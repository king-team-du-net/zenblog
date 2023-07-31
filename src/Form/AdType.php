<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Form;

use App\Entity\Ad;
use App\Entity\AdCategory;
use App\Form\Type\DropzoneType;
use App\Form\Type\TextEditorType;
use App\Form\Type\ApplicationType;
use App\Twig\TwigSettingExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends ApplicationType
{
    public function __construct(private readonly TwigSettingExtension $setting)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                $this->getAttributes('label.ad_title', 'placeholder.ad_title', [
                    'empty_data' => '',
                ])
            )
            ->add(
                'adCategory',
                EntityType::class,
                $this->getAttributes('label.category', false, [
                    'required' => true,
                    'class' => AdCategory::class,
                    'choice_label' => 'name',
                    /*
                    'help' => 'help.entity_category',
                    'query_builder' => function () {
                        return $this->setting->getAdCategories([]);
                    },
                    */
                ])
            )
            ->add(
                'coverFile',
                DropzoneType::class,
                $this->getAttributes('label.cover_file', 'placeholder.dropzone', [
                    'required' => false,
                ])
            )
            ->add(
                'medias',
                CollectionType::class,
                $this->getAttributes('label.medias', false, [
                    'entry_type' => MediaType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ])
            )
            ->add(
                'excerpt',
                TextareaType::class,
                $this->getAttributes('label.ad_excerpt', 'placeholder.ad_excerpt', [
                    'empty_data' => '',
                    'attr' => [
                        'class' => 'form-control flex-grow-1',
                        'rows' => 4,
                        'cols' => 30,
                    ],
                ])
            )
            ->add(
                'content',
                TextEditorType::class,
                $this->getAttributes('label.ad_content', 'placeholder.ad_content', [
                    'empty_data' => '',
                ])
            )
            ->add(
                'rooms',
                IntegerType::class,
                $this->getAttributes('label.ad_rooms', 'placeholder.ad_rooms', [
                    'attr' => [
                        'min' => 0,
                        'step' => 1
                    ]
                ])
            )
            ->add(
                'price',
                MoneyType::class,
                $this->getAttributes('label.ad_price', 'placeholder.ad_price')
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
