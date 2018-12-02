<?php
namespace App\Event;

final class Events
{
    const ORDER_PAY_NOTIFY                = 'rip.wechat.order_pay';
    const MEMBER_PAY_NOTIFY                = 'rip.wechat.member_pay';

    final private function __construct()
    {
    }
}
