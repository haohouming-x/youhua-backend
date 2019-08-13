<?php

namespace App\DependencyInjection;

use ApiPlatform\Core\Exception\{InvalidArgumentException, ItemNotFoundException};
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use EasyWeChat\Factory;
use App\Event\{Events, WechatPayNotifyEvent, WechatPayOrderEvent};

class WechatPay
{
    public function __construct(array $config, EventDispatcherInterface $event_dispatcher, LoggerInterface $logger)
    {
        $this->pay = Factory::payment($config);
        $this->event_dispatcher = $event_dispatcher;
        $this->logger = $logger;
    }

    public function payment(array $config)
    {
        $default_config = [
            'trade_type' => 'JSAPI',
            'body' => '琥珀艺术-' . $config['title']
        ];
        unset($config['title']);
        $config['total_fee'] = $config['total_fee'] * 100;

        $new_config = array_merge($default_config, $config);
        $result = $this->pay->order->unify($new_config);

        $this->afterPay($result, $new_config);
    }

    public function payNotify()
    {
        $response = $this->pay->handlePaidNotify(function($message, $fail) {
            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////
            if ($message['return_code'] === 'SUCCESS') {
                // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                $notify_event = new WechatPayNotifyEvent($message);

                if ($message['result_code'] === 'SUCCESS') {
                    $this->event_dispatcher->dispatch(
                        Events::SUCCESS_PAY,
                        $notify_event
                    );

                    if($notify_event->isPropagationStopped()) return true;

                    // TODO success log


                    // 用户支付失败
                } elseif ($message['result_code'] === 'FAIL') {
                    // TODO error log
                    $this->event_dispatcher->dispatch(
                        Events::FAIL_PAY,
                        $notify_event
                    );
                }
            } else {
                // TODO error log
                return $fail('通信失败，请稍后再通知我');
            }

            return true; // 返回处理完成
        });

        return $response;
    }

    private function afterPay($result, $config)
    {
        $event = new WechatPayOrderEvent($config, $result);

        if($result['return_code'] === 'SUCCESS') {
            $this->event_dispatcher->dispatch(
                Events::POST_ORDER,
                $event
            );

            return $this->pay->jssdk->sdkConfig($result['prepay_id']);
        } else {
            $this->logger->error('pay.faid.config', $config);
            $this->logger->error('pay.faid', $result);

            $this->event_dispatcher->dispatch(
                Events::FAIL_ORDER,
                $event
            );

            throw new InvalidArgumentException('Wecaht pay faid');
        }
    }
}
