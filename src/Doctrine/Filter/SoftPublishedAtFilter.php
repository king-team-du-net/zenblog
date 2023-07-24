<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Doctrine\Filter;

use App\Entity\Post;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class SoftPublishedAtFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if (Post::class !== $targetEntity->getReflectionClass()->getName()) {
            return '';
        }

        return sprintf(
            '%s.published_at IS NOT NULL AND %s.published_at <= %s',
            $targetTableAlias,
            $targetTableAlias,
            $this->getParameter('current_datetime')
        );
    }
}
