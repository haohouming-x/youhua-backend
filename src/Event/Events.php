<?php
namespace App\Event;

final class Events
{
    const ORDER_PAY_NOTIFY                = 'rip.wechat.order_pay';
    const MEMBER_PAY_NOTIFY               = 'rip.wechat.member_pay';
  
    const POST_ORDER                      = 'rip.wechat.post_order';
    const FAIL_ORDER                      = 'rip.wechat.fail_order';
    const SUCCESS_PAY                     = 'rip.wechat.success_pay';
    const FAIL_PAY                        = 'rip.wechat.fail_pay'; 
  
    final private function __construct()
    {
    }
}
