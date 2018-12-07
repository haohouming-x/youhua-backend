<?php

namespace App\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\Events;
use App\Entity\{Wechat, Consumer, Member, Marketing};


class MemberPaySubscriber implements EventSubscriberInterface
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function onRipWechatMemberPay($event)
    {
        $message = $event->getCallBackMessages();
        $em = $this->em;

        $marketing_id = strstr($message['out_trade_no'], '@').trim('@');
        $openId = $message['openid'];

        $marketing = $em->getRepository(Marketing::class)
                   ->find($marketing_id);

        if (!$marketing) {
            // TODO error log
            return true;
        }

        $wechat = $em->getRepository(Wechat::class)
                ->findOneBy(['openId' => $openId]);

        $consumer = $wechat->getConsumer();

        $validity_date = $marketing->getValidityDate();
        $recharge_at = new \DateTime('now');
        $expire_at = new \DateTime('now +'.$validity_date.' day');

        $member = (new Member())
                ->setMarket($marketing)
                ->setRechargeAt($recharge_at)
                ->setExpireAt($expire_at);

        $consumer->setMember($member);

        $em->persist($consumer);
        $em->flush();

        // TODO member payed log

        return true;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::MEMBER_PAY_NOTIFY => 'onRipWechatMemberPay',
        ];
    }
}
