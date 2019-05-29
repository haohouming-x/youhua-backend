<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use EasyWeChat\Factory;
use App\DependencyInjection\{WechatMessage, WechatPrePayIdStorage};
use App\Event\{Events, WechatPayNotifyEvent, WechatPayOrderEvent};

class WechatPaySubscriber implements EventSubscriberInterface {

    public function __construct(EventDispatcherInterface $event_dispatcher, LoggerInterface $logger, WechatMessage $wechat_message, WechatPrePayIdStorage $wechat_prepayid_storage)
    {
        $this->event_dispatcher = $event_dispatcher;
        $this->logger = $logger;
        $this->wechat_message = $wechat_message;
        $this->wechat_prepayid_storage = $wechat_prepayid_storage;
    }

    public function onRipWechatSuccessPay(WechatPayNotifyEvent $event)
    {
        $message = $event->getCallBackMessages();
        $template_name = null;

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

    public function onRipWechatNotifyMessage(WechatPayNotifyEvent $event)
    {
        $message = $event->getCallBackMessages();
        [$template_name, $notify_messages] = $event->getNotifyMessages();
        if(!empty($notify_messages)) {
            $prepay_id = $this->wechat_prepayid_storage->get($message['out_trade_no']);
            $this->wechat_prepayid_storage->remove($message['out_trade_no']);

            $this->wechat_message->sendToWeApp($template_name, $message['openid'], [$prepay_id, $notify_messages]);
            $this->template_name = null;
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::SUCCESS_PAY => 'onRipWechatSuccessPay',
            Events::POST_ORDER  => 'onRipWechatPostOrder',
            Events::NOTIFY_MESSAGE => 'onRipWechatNotifyMessage'
        ];
    }
}
