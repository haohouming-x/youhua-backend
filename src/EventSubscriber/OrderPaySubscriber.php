<?php

namespace App\EventSubscriber;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\DBAL\Types\OrderType;
use App\Event\Events;
use App\Entity\Order;


class OrderPaySubscriber implements EventSubscriberInterface
{
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function onRipWechatOrderPay($event)
    {
        $message = $event->getCallBackMessages();
        // ...
        $order = $em
               ->getRepository(Order::class)
               ->findOneBy(['order_number' => $message['out_trade_no']]);

        if (!$order || $order->getPaidAt()) { // 如果订单不存在 或者 订单已经支付过了
            return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
        }

        $order->setStatus(OrderType::WAIT_SEND);

        $em->persist($order);
        $em->flush(); // 保存订单

        // TODO order payed log

        return true;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::ORDER_PAY_NOTIFY => 'onRipWechatOrderPay',
        ];
    }
}
