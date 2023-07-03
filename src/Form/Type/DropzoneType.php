<?php

namespace App\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class DropzoneType extends \Symfony\UX\Dropzone\Form\DropzoneType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'html5' => false,
            'widget' => 'single_text',
            'attr' => ['placeholder' => 'placeholder.dropzone'],
        ]);
    }
}
