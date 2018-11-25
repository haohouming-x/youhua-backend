<?php
namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class OrderActionType extends AbstractEnumType
{
    public const WAIT_PAY = OrderType::WAIT_PAY;
    public const WAIT_SEND = OrderType::WAIT_SEND;
    public const ALREADY_SEND  = OrderType::ALREADY_SEND;

    public const ACTION_SELECT = [
        self::WAIT_PAY => [OrderType::ALREADY_CLOSE],
        self::WAIT_SEND => [OrderType::ALREADY_SEND, OrderType::ALREADY_CLOSE],
        self::ALREADY_SEND => [OrderType::ALREADY_CLOSE],
    ];

    protected static $choices = self::ACTION_SELECT;
}
