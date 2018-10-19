<?php
namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class ConsumerType extends AbstractEnumType
{
    public const YOU_KE = 'YK';

    public const DIALOG_IMG = 'DI';

    protected static $choices = [
        self::YOU_KE => '游客',
        self::DIALOG_IMG => 'dialog image',
    ];
}
