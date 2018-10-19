<?php
namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class SexType extends AbstractEnumType
{
    public const MAN = 'nan';

    public const WOMAN = 'nv';

    protected static $choices = [
        self::MAN => '男',
        self::WOMAN => '女',
    ];
}
