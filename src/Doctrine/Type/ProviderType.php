<?php

declare(strict_types=1);

namespace App\Doctrine\Type;

use App\Entity\Image\Provider;
use Doctrine\DBAL\Platforms\AbstractPlatform;

final class ProviderType extends AbstractEnumType
{
    public const NAME = 'status';

    public function getName(): string
    {
        return self::NAME;
    }

    public static function getEnumsClass(): string
    {
        return Provider::class;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'varchar(10)';
    }
}
