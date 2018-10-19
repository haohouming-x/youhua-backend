<?php
namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class OrderBillType extends AbstractEnumType
{
    public const RETURN = 'RT';

    public const APPEND = 'AE';

    protected static $choices = [
        self::RETURN => '新增',
        self::APPEND => '退还',
    ];
}
