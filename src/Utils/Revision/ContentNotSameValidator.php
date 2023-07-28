<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Utils\Revision;

use App\Entity\Revision\Revision;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ContentNotSameValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ContentNotSame) {
            throw new UnexpectedTypeException($constraint, ContentNotSame::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!$value instanceof Revision) {
            throw new UnexpectedValueException($value, Revision::class);
        }

        if (null !== $value->getTarget() && $value->getContent() === $value->getTarget()->getContent()) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
