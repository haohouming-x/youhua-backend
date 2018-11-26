<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WechatRepository")
 */
class Wechat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $openId;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Consumer", inversedBy="wechat", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $consumer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOpenId(): ?string
    {
        return $this->openId;
    }

    public function setOpenId(string $openId): self
    {
        $this->openId = $openId;

        return $this;
    }

    public function getConsumer(): ?Consumer
    {
        return $this->consumer;
    }

    public function setConsumer(Consumer $consumer): self
    {
        $this->consumer = $consumer;

        return $this;
    }
}
