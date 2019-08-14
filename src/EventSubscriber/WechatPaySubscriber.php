<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use EasyWeChat\Factory;
use App\DependencyInjection\{WechatPrePayIdStorage};
use App\Event\{Events, WechatPayOrderEvent, WechatPayNotifyEvent};

class WechatPaySubscriber implements EventSubscriberInterface
{

    public function __construct(EventDispatcherInterface $event_dispatcher, LoggerInterface $logger, WechatPrePayIdStorage $wechat_prepayid_storage)
    {
        $this->event_dispatcher = $event_dispatcher;
        $this->logger = $logger;
        $this->wechat_prepayid_storage = $wechat_prepayid_storage;
    }

    public function onRipWechatSuccessPay(WechatPayNotifyEvent $event)
    {
        $message = $event->getCallBackMessages();

        switch ($message['attach']) {
        case Events::ORDER_PAY_NOTIFY:
            $this->event_dispatcher->dispatch(
                Events::ORDER_PAY_NOTIFY,
                $event
            );
            break;
        case Events::MEMBER_PAY_NOTIFY:
            $this->event_dispatcher->dispatch(
                 Events::MEMBER_PAY_NOTIFY,
                 $event
            );
            break;
        }
    }

    public function onRipWechatPostOrder(WechatPayOrderEvent $event)
    {
        $result = $event->getResult();
        $config = $event->getConfig();

        $this->wechat_prepayid_storage->set($config['out_trade_no'], $result['prepay_id']);
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::SUCCESS_PAY => 'onRipWechatSuccessPay',
            Events::POST_ORDER  => 'onRipWechatPostOrder'
        ];
    }
}
