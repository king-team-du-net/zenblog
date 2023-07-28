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

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

final class CommentType extends AbstractType
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
            ->add('content', TextareaType::class, [
                'label' => 'label.comment_content',
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.comment_content',
                    'rows' => 10,
                    'cols' => 30,
                ],
                'help' => 'help.comment_content',
            ])
            ->add('parentid', HiddenType::class, [
                'mapped' => false,
            ])
            ->add('isRGPD', CheckboxType::class, [
                'label' => 'label.comment_rgpd',
                'data' => true, // Default checked
                'constraints' => [
                    new NotBlank(['message' => 'comment_rgpd.blank']),
                    /*new IsTrue([
                        'message' => 'comment_rgpd.isTrue',
                    ]),*/
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'label.publish_comment',
                'validate' => false,
                'attr' => ['class' => 'btn btn-primary'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
