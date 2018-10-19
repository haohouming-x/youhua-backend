<?php
namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class OrderType extends AbstractEnumType
{
    public const WAIT_PAY = 'WP';
    public const WAIT_SEND = 'WS';
    public const ALREADY_SEND = 'AS';
    public const ALREADY_TAKE = 'AT';
    public const ALREADY_CLOSE = 'AC';

    protected static $choices = [
        self::WAIT_PAY => '未付款',
        self::WAIT_SEND => '待发货',
        self::ALREADY_SEND => '已发货',
        self::ALREADY_TAKE => '已收货',
        self::ALREADY_CLOSE => '已取消'
    ];
}
