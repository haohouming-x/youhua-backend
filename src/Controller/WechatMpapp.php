<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use ApiPlatform\Core\Exception\{InvalidArgumentException, ItemNotFoundException};
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use EasyWeChat\Factory;
use App\Event\{Events, WechatPayNotifyEvent};
use App\DBAL\Types\SexType;
use App\Entity\{Consumer, Wechat, Order, Marketing};


class WechatMpapp extends Controller
{
    public function __construct(array $config, array $pay_config, EventDispatcherInterface $event_dispatcher)
    {
        $this->app = Factory::miniProgram($config);
        $this->pay = Factory::payment($pay_config);
        $this->event_dispatcher = $event_dispatcher;
    }

    private function payment(array $config)
    {
        $default_config = [
            'trade_type' => 'JSAPI',
            'body' => '琥珀艺术-' . $config['title']
        ];
        unset($config['title']);
        $config['total_fee'] = $config['total_fee'] * 100;

        $result = $this->pay->order->unify(array_merge($default_config, $config));
        
        if($result['return_code'] === 'SUCCESS') {
          return $this->pay->jssdk->sdkConfig($result['prepay_id']);
        } else {
          throw new InvalidArgumentException('wecaht pay faid'); 
        }
    }

    private function getOpenId(Consumer $consumer)
    {
        if(!$consumer) throw new ItemNotFoundException('Not found consumer');

        $openId = $consumer->getWechat()->getOpenId();

        if(!$openId) throw new ItemNotFoundException('Consumer not login');

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

        if(!$order) throw new ItemNotFoundException('Not found order');

        $out_trade_no = $order->getOrderNumber();
        $total_fee = $order->getTotal();

        if($total_fee == 0) return null;

        $openId = $this->getOpenId($order->getConsumer());

        return $this->payment([
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

        if(!$marketing) throw new ItemNotFoundException('An error occurred');

        $total_fee = $marketing->getPresentPrice();

        if($total_fee == 0) return null;

        $openId = $this->getOpenId($en->find(Consumer::class, $consumer_id));

        $out_trade_no = 'HY'. date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);

        return $this->payment([
            'title' => $marketing->getName(),
            'out_trade_no' => $out_trade_no . '@' . $marketing->getId(),
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
    public function payNotify(LoggerInterface $logger)
    {
        $response = $this->pay->handlePaidNotify(function($message, $fail) use($logger) {
            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////
            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if ($message['result_code'] === 'SUCCESS') {
                    $wechatPayNotifyEvent = new WechatPayNotifyEvent($message);
                    // TODO success log
                    switch ($message['attach']) {
                    case Events::ORDER_PAY_NOTIFY:
                        $this->event_dispatcher->dispatch(
                            Events::ORDER_PAY_NOTIFY,
                            $wechatPayNotifyEvent
                        );
                    case Events::MEMBER_PAY_NOTIFY:
                        $this->event_dispatcher->dispatch(
                            Events::MEMBER_PAY_NOTIFY,
                            $wechatPayNotifyEvent
                        );
                    }
                    
                    if ($wechatPayNotifyEvent->isPropagationStopped()) return true;

                    // 用户支付失败
                } elseif ($message['result_code'] === 'FAIL') {
                    // TODO error log
                }
            } else {
                // TODO error log
                return $fail('通信失败，请稍后再通知我');
            }

            return true; // 返回处理完成
        });

        return $response;
    }
}
