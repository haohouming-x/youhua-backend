<?php

namespace App\DependencyInjection;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use ApiPlatform\Core\Exception\{InvalidArgumentException, ItemNotFoundException};
use Psr\Log\LoggerInterface;
use EasyWeChat\Factory;
use App\Event\{Events, WechatPayNotifyEvent, WechatPayRefundEvent};

class WechatPayRefund
{
    public function __construct(array $config, array $refund_config, EventDispatcherInterface $event_dispatcher, LoggerInterface $logger)
    {
        $this->app = Factory::payment($config);
        $this->refund_config = $refund_config;
        $this->event_dispatcher = $event_dispatcher;
        $this->logger = $logger;
    }

    public function tradeRefund(string $out_trade_no, float $total_fee, float $refund_fee, array $config)
    {
        $new_config = array_merge($this->refund_config, $config);
        $refund_trade_no = $out_trade_no.'|refund';

        $result = $this->app->refund
                ->byOutTradeNumber($out_trade_no, $refund_trade_no, $total_fee*100, $refund_fee*100, $new_config);

        return $this->afterRefund($result, array_merge([
            'total_fee' => $total_fee*100,
            'refund_fee' => $refund_fee*100,
            'out_trade_no' => $out_trade_no,
            'refund_trade_no' => $refund_trade_no
        ], $new_config));
    }

    public function notify()
    {
        $response = $this->app->handleRefundedNotify(function ($message, $reqInfo, $fail) {
            $notify_event = new WechatPayNotifyEvent($message);

            if ($reqInfo['refund_status'] === 'SUCCESS') {
                $this->event_dispatcher->dispatch(
                    Events::SUCCESS_REFUND,
                    $notify_event
                );

                return true;
            } elseif ($reqInfo['refund_status'] === 'CHANGE') {
                $this->event_dispatcher->dispatch(
                    Events::FAIL_REFUND,
                    $notify_event
                );

                return $fail('参数格式校验错误');
            } elseif ($reqInfo['refund_status'] === 'REFUNDCLOSE') {
                $this->event_dispatcher->dispatch(
                    Events::REFUNDCLOSE_REFUND,
                    $notify_event
                );
                return $fail('closed');
            }
        });

        return $response;
    }

    private function afterRefund($result, $config)
    {
        $event = new WechatPayRefundEvent($config, $result);

        if($result['return_code'] === 'SUCCESS') {
            $this->event_dispatcher->dispatch(
                Events::POST_REFUND,
                $event
            );

            $this->logger->info('refund.post_success', $result);
        } else {
            $this->logger->error('refund.post_faid.config', $config);
            $this->logger->error('refund.post_faid', $result);

            $this->event_dispatcher->dispatch(
                Events::FAIL_POST_REFUND,
                $event
            );

            throw new InvalidArgumentException('Wecaht refund faid');
        }
    }
}
