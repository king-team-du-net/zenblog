<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Contact;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullname', TextType::class, [
                'label' => false,
                'required' => true,
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.fullname',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'required' => true,
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.email',
                ],
            ])
            ->add('subject', TextType::class, [
                'label' => false,
                'required' => true,
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.subject',
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => false,
                'required' => true,
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.message',
                    'rows' => 5,
                ],
            ])
            /*
            ->add('recaptcha', EWZRecaptchaType::class, [
                'attr' => [
                    'options' => [
                        'theme' => 'light',
                        'type' => 'image',
                        'size' => 'normal',
                    ],
                ],
                'mapped' => false,
                'constraints' => [
                    new RecaptchaTrue(['groups' => 'Contact']),
                ],
            ])
            */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
