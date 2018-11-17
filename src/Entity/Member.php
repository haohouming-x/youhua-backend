<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Helper\TimestampableEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\MemberRepository")
 */
class Member
{
    /**
     * Hook timestampable behavior
     * updates created_at, updated_at fields
     */
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Consumer", inversedBy="member", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $consumer;

    /**
     * @ORM\Column(type="datetime")
     */
    private $recharge_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expire_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Marketing", inversedBy="members")
     * @ORM\JoinColumn(nullable=false)
     */
    private $market;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRechargeAt(): ?\DateTimeInterface
    {
        return $this->recharge_at;
    }

    public function setRechargeAt(\DateTimeInterface $recharge_at): self
    {
        $this->recharge_at = $recharge_at;

        return $this;
    }

    public function getExpireAt(): ?\DateTimeInterface
    {
        return $this->expire_at;
    }

    public function setExpireAt(\DateTimeInterface $expire_at): self
    {
        $this->expire_at = $expire_at;

        return $this;
    }

    public function getMarket(): ?Marketing
    {
        return $this->market;
    }

    public function setMarket(?Marketing $market): self
    {
        $this->market = $market;

        return $this;
    }
}
