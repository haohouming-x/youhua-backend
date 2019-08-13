<?php
namespace App\Event;

final class Events
{
    const ORDER_PAY_NOTIFY   = 'rip.wechat.order_pay';
    const MEMBER_PAY_NOTIFY  = 'rip.wechat.member_pay';

    const NOTIFY_MESSAGE     = 'rip.wechat.notify_message';

    const POST_ORDER         = 'rip.wechat.post_order';
    const FAIL_ORDER         = 'rip.wechat.fail_order';
    const SUCCESS_PAY        = 'rip.wechat.success_pay';
    const FAIL_PAY           = 'rip.wechat.fail_pay';

    const POST_REFUND        = 'rip.wechat.post_refund';
    const FAIL_POST_REFUND   = 'rip.wechat.fail_send_refund';
    const SUCCESS_REFUND     = 'rip.wechat.success_refund';
    const FAIL_REFUND        = 'rip.wechat.fail_refund';
    const REFUNDCLOSE_REFUND = 'rip.wechat.refundclose_refund';

    final private function __construct()
    {
    }
}
