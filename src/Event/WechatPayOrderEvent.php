<?php 

namespace App\Event;  

use Symfony\Component\EventDispatcher\Event;  

class WechatPayOrderEvent extends Event
{ 
    private $config;
    private $result; 

     /**      
      * WechatPayOrderEvent constructor. 
      * @param array $config
      * @param array $result
      */ 
    public function __construct(array $config, array $result) 
    { 
        $this->config = $config;
        $this->result = $result; 
    }
 
    public function getConfig() 
    { 
        return $this->config; 
    } 
 
    public function setConfig($config) 
    { 
        $this->config = $config;
        return $this; 
    } 

    public function getResult() 
    { 
        return $this->result; 
    } 
 
    public function setResult($result) 
    { 
        $this->result = $result;
        return $this; 
    } 
}
