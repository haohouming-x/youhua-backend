<?php

namespace App\DependencyInjection;

use Symfony\Component\Cache\Adapter\RedisAdapter;

class WechatPrePayIdStorage
{
    private const PREPAYID_KEY = 'prepay_id.';

    public function __construct(RedisAdapter $redis)
    {
        $this->redis = $redis;
    }

    public function get($trade_no)
    {
        $prepay_id = $this->redis->getItem(self::PREPAYID_KEY . $trade_no);

        return $prepay_id->get();
    } 

    public function set($trade_no, $value)
    {
        $prepay_id = $this->redis->getItem(self::PREPAYID_KEY . $trade_no);

        $prepay_id->set($value);
        $this->redis->save($prepay_id);
        
        return $this;
    }

    public function remove($trade_no)
    {
        $this->redis->deleteItem(self::PREPAYID_KEY . $trade_no);
        return $this;
    }
}
