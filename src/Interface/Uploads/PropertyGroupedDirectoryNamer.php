<?php

declare(strict_types=1);

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Interface\Uploads;

use Symfony\Component\PropertyAccess\PropertyAccessor;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\ConfigurableInterface;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

final class PropertyGroupedDirectoryNamer implements DirectoryNamerInterface, ConfigurableInterface
{
    private int $modulo;
    private string $property;

    public function directoryName($object, PropertyMapping $mapping): string
    {
        $accessor = new PropertyAccessor();
        $value = $accessor->getValue($object, $this->property);

        return (string) ceil($value / $this->modulo);
    }

    public function configure(array $options): void
    {
        $options = array_merge(['property' => 'id', 'modulo' => 1000], $options);
        $this->property = $options['property'];
        $this->modulo = (int) $options['modulo'];
    }
}
