<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class TagInputType extends \Symfony\Component\Form\Extension\Core\Type\TextType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'label' => 'label.tags',
            'html5' => false,
            'widget' => 'single_text',
            'attr' => ['class' => 'tags-input'],
            'help' => 'help.post_tags',
        ]);
    }
}
