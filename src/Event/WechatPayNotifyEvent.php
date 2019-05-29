<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;

class WechatPayNotifyEvent extends Event
{

    private $call_back_messages;

    private $notify_messages;
    private $template_name;

    /**
     * WechatPayNotifyEvent constructor.
     * @param array $user
     */
    public function __construct(array $call_back_messages)
    {
        $this->call_back_messages = $call_back_messages;
    }

    public function getCallBackMessages()
    {
        return $this->call_back_messages;
    }

    public function setCallBackMessages($call_back_messages)
    {
        $this->call_back_messages = $call_back_messages;
        return $this;
    }

    public function getNotifyMessages()
    {
        return [$this->template_name, $this->notify_messages];
    }

    public function setNotifyMessages($template_name, $notify_messages)
    {
        $this->template_name = $template_name;
        $this->notify_messages = $notify_messages;
        return $this;
    }
}
