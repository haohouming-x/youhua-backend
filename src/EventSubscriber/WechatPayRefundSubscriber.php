<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use EasyWeChat\Factory;
use App\DependencyInjection\{WechatPrePayIdStorage};
use App\Event\{Events, WechatPayNotifyEvent};

class WechatPayRefundSubscriber implements EventSubscriberInterface
{

    public function __construct(EventDispatcherInterface $event_dispatcher, LoggerInterface $logger, WechatPrePayIdStorage $wechat_prepayid_storage)
    {
        $this->event_dispatcher = $event_dispatcher;
        $this->logger = $logger;
    }

    public function onRipWechatSuccessRefund(WechatPayNotifyEvent $event)
    {
        $message = $event->getCallBackMessages();

        $event->setNotifyMessages(
            'return_success',
            [
                'keyword1' => $message['out_trade_no'],
                'keyword2' => $message['refund_fee']/100,
                'keyword3' => $message['success_time'],
                'keyword4' => '退款已经原路返回，具体到账时间可能会有1-3天延迟'
            ]
        );
        $this->event_dispatcher->dispatch(
            Events::NOTIFY_MESSAGE,
            $event
        );
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::SUCCESS_REFUND => 'onRipWechatSuccessRefund',
        ];
    }
}
