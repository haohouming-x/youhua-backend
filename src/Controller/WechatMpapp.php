<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use ApiPlatform\Core\Exception\{InvalidArgumentException, ItemNotFoundException};
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use EasyWeChat\Factory;
use App\Event\Events;
use App\DBAL\Types\SexType;
use App\DependencyInjection\WechatPay;
use App\Entity\{Consumer, Wechat, Order, Marketing};


class WechatMpapp extends Controller
{
    public function __construct(array $config, WechatPay $wechat_pay, EventDispatcherInterface $event_dispatcher, LoggerInterface $logger)
    {
        $this->app = Factory::miniProgram($config);
        $this->pay = $wechat_pay;
        $this->event_dispatcher = $event_dispatcher;
        $this->logger = $logger;
    }

    private function getOpenId(Consumer $consumer)
    {
        if(!$consumer) throw new ItemNotFoundException('Not found consumer');

        $openId = $consumer->getWechat()->getOpenId();

        if(!$openId) throw new ItemNotFoundException('Consumer don\'t relation with wechat');

        return $openId;
    }

    /**
     * @Route(
     *     name="mpapp_login_or_create",
     *     path="/mpapp/login_or_create",
     *     methods={"GET"},
     *     defaults={
     *         "_api_receive"= false,
     *         "_api_resource_class"=Consumer::class,
     *         "_api_collection_operation_name"="mp_login_or_create"
     *     }
     * )
     */
    public function loginOrCreateAction(Request $request): Consumer
    {
        $code = $request->query->get('code');

        $sessionInfos = $this->app->auth->session($code);

        if($sessionInfos == '45011') throw new \InvalidArgumentException('\'code\' is not validity');
 
        $openId = $sessionInfos['openid'];
        // $openId = '1';

        $em = $this->getDoctrine()->getManager();

        $wechat = $em->getRepository(Wechat::class)
                ->findOneBy(['openId' => $openId]);

        if (!$wechat) {
            $now = new \DateTime("now");

            $consumer = (new Consumer())
                      ->setImage('')
                      ->setNickName('')
                      ->setSex(SexType::MAN)
                      ->setFirstLoginAt($now);
            // ->setLastLoginAt($now);

            $wechat = (new Wechat())
                    ->setOpenId($openId)
                    ->setConsumer($consumer);

            $em->persist($wechat);
            $em->flush();

            return $consumer;
        }

        $consumer = $wechat->getConsumer();

        $consumer->setLastLoginAt(new \DateTime("now"));
        
        $em->persist($consumer);
        $em->flush();

        return $consumer;
    }

    /**
     * @Route(
     *     name="mpapp_pay_order",
     *     path="/mpapp/orders/{id}/pay",
     *     methods={"POST"},
     *     defaults={
     *         "_api_receive"= false,
     *         "_api_resource_class"=Order::class,
     *         "_api_collection_operation_name"="mp_pay_order"
     *     }
     * )
     */
    public function payOrder($id)
    {
        $em = $this->getDoctrine()->getManager();

        $order = $em->find(Order::class, $id);

        if(!$order) throw new ItemNotFoundException('Not found order by id');

        $out_trade_no = $order->getOrderNumber();
        $total_fee = $order->getTotal();

        if($total_fee == 0) return null;

        $openId = $this->getOpenId($order->getConsumer());

        return $this->pay->payment([
            'title' => '油画出租',
            'out_trade_no' => $out_trade_no,
            'total_fee' => $total_fee,
            'attach' => Events::ORDER_PAY_NOTIFY,
            'openid' => $openId
        ]);
    }

    /**
     * @Route(
     *     name="mpapp_pay_member",
     *     path="/mpapp/consumers/{consumer_id}/marketings/{id}/pay",
     *     methods={"POST"},
     *     defaults={
     *         "_api_receive"= false,
     *         "_api_resource_class"=Marketing::class,
     *         "_api_collection_operation_name"="mp_pay_member"
     *     }
     * )
     */
    public function payMember($consumer_id, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $marketing = $em->find(Marketing::class, $id);

        if(!$marketing) throw new ItemNotFoundException('Not found markeing by id');

        $total_fee = $marketing->getPresentPrice();

        if($total_fee == 0) return null;

        $openId = $this->getOpenId($em->find(Consumer::class, $consumer_id));

        $out_trade_no = 'HY'. date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);

        return $this->pay->payment([
            'title' => $marketing->getName(),
            'out_trade_no' => $out_trade_no . '_' . $marketing->getId(),
            'total_fee' => $total_fee,
            'attach' => Events::MEMBER_PAY_NOTIFY,
            'openid' => $openId
        ]);
    }

    /**
     * @Route(
     *     name="pay_notify",
     *     path="/mpapp/pay/notify",
     *     methods={"GET", "POST"},
     * )
     */
    public function payNotify()
    {
        $response = $this->pay->payNotify();

        return $response;
    }
}
