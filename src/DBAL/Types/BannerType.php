<?php
namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class BannerType extends AbstractEnumType
{
    public const LINK = 'LK';

    public const GOODS = 'GD';

    protected static $choices = [
        self::LINK => '链接',
        self::GOODS => '商品',
    ];
}
