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

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'label' => 'label.review_rating',
                'choices' => ['label.review_rating_5' => 5, 'label.review_rating_4' => 4, 'label.review_rating_3' => 3, 'label.review_rating_2' => 2, 'label.review_rating_1' => 1],
                'label_attr' => ['class' => 'radio-custom radio-inline'],
            ])
            ->add('headline', TextType::class, [
                // 'purify_html' => true,
                'label' => 'label.review_headlie',
            ])
            ->add('details', TextareaType::class, [
                // 'purify_html' => true,
                'label' => 'label.review_details',
                'attr' => ['rows' => 10],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'label.save',
                'attr' => ['class' => 'btn btn-primary'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
