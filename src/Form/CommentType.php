<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

final class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /*->add('nickname', TextType::class, [
                'label' => 'label.username',
                'required' => true,
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.username',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'label.email',
                'required' => true,
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.email',
                ],
            ])*/
            ->add('rating', IntegerType::class, [
                'label' => 'label.comment_rating',
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.comment_rating',
                    'min' => 0, 
                    'max' => 5, 
                    'step' => 1
                ],
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
                'mapped' => false
            ])
            /*->add('isRGPD', CheckboxType::class, [
                'label' => 'Save my nickname and email',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
