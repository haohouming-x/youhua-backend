<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psr\Log\LoggerInterface;
use EasyWeChat\Factory;
use App\DependencyInjection\{WechatMessage, WechatPrePayIdStorage};
use App\Event\{Events, WechatPayNotifyEvent};

class WechatNotifySubscriber implements EventSubscriberInterface
{

    public function __construct(LoggerInterface $logger, WechatMessage $wechat_message, WechatPrePayIdStorage $wechat_prepayid_storage)
    {
        $this->logger = $logger;
        $this->wechat_message = $wechat_message;
        $this->wechat_prepayid_storage = $wechat_prepayid_storage;
    }

    public function onRipWechatNotifyMessage(WechatPayNotifyEvent $event)
    {
        $message = $event->getCallBackMessages();
        [$template_name, $notify_messages] = $event->getNotifyMessages();
        if(!empty($notify_messages)) {
            $prepay_id = $this->wechat_prepayid_storage->get($message['out_trade_no']);
            // $this->wechat_prepayid_storage->remove($message['out_trade_no']);
            if(!$prepay_id) $this->logger->error('prepayid.get.fail', $message);

            $this->wechat_message->sendToWeApp($template_name, $message['openid'], [$prepay_id, $notify_messages]);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::NOTIFY_MESSAGE => 'onRipWechatNotifyMessage'
        ];
    }
}
