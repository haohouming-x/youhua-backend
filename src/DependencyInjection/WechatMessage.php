<?php

namespace App\DependencyInjection;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use EasyWeChat\Factory;
// use App\Event\Events;

class WechatMessage {

    public function __construct(array $config, array $template_ids, array $template_pages,/* EventDispatcherInterface $event_dispatcher,*/ LoggerInterface $logger)
    {
        // $this->event_dispatcher = $event_dispatcher;
        $this->template_ids = $template_ids;
        $this->template_pages = $template_pages;
        $this->app = Factory::miniProgram($config);
        $this->logger = $logger;
    }

    public function sendToWeApp($template_name, $openId, array $payload)
    {
        $template_ids = $this->template_ids;
        $template_pages = $this->template_pages;

        if(!array_key_exists($template_name, $template_ids))
        {
            $this->logger->error('wechat_message.faid.template.not_found_id', [
                'not_found' => $template_name,
                'meta_data' => $template_ids
            ]);

            return;
        }

        [$form_id, $data] = $payload;

        $this->sendMessage($openId, [
            'weapp',
            [
                'template_id' => $template_ids[$template_name],
                'page' => $template_pages[$template_name],
                'form_id' => $form_id,
                'data' => $data
            ]
        ]);
     }

     private function sendMessage($openId, $payload)
     {
        [$type, $data] = $payload;

        $message = [
            'touser' => $openId
        ];

        if($type === 'weapp') {
            $message['weapp_template_msg'] = $data;
        }else if($type === 'mp') {
            $message['mp_template_msg'] = $data;
        }

        $this->app->uniform_message->send($message);
        $this->logger->info('wechat_message.success.sent', $message);
     }
}
