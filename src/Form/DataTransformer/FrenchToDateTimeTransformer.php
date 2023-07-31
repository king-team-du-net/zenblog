<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Contracts\Translation\TranslatorInterface;

final class FrenchToDateTimeTransformer implements DataTransformerInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {
    }

    public function transform($date)
    {
        if (null === $date) {
            return '';
        }

        return $date->format('d/m/Y');
    }

    public function reverseTransform($frenchDate)
    {
        // frenchDate = 20/09-2023
        if (null === $frenchDate) {
            // Exception
            throw new TransformationFailedException($this->translator->trans('throw.transform_french_date'));
        }

        $date = \DateTime::createFromFormat('d/m/Y', $frenchDate);

        if (false === $date) {
            // Exception
            throw new TransformationFailedException($this->translator->trans('throw.transform_date'));
        }

        return $date;
    }
}
