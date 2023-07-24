<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TextEditorType extends TextareaType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'label' => 'label.content',
            'html5' => false,
            /*
            'widget' => 'single_text',
            'row_attr' => ['class' => '',],
            */
            'attr' => [
                'class' => 'form-control wysiwyg mb-2',
                'placeholder' => 'placeholder.content',
            ],
        ]);
    }
}
