<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Utils\Revision;

use Symfony\Component\Validator\Constraint;

/**
 * Constraint to ensure that a revision contains at least one change.
 *
 * @Annotation
 */
class ContentNotSame extends Constraint
{
    public string $message = 'The revision must have at least one change from the original article.';

    public function getTargets(): string
    {
        return Constraint::CLASS_CONSTRAINT;
    }
}
