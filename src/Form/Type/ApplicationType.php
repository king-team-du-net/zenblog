<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType
{
    /** Allows you to have the basic configuration of a field ! */
    protected function getAttributes(string $label, ?string $placeholder = '...', array $options = []): array
    {
        return array_merge_recursive([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder,
            ],
        ], $options);
    }
}
