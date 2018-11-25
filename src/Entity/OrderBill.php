<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\DBAL\Types\OrderBillType;
use App\Entity\Helper\TimestampableEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderBillRepository")
 */
class OrderBill
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
     * @ORM\Column(type="float")
     */
    private $deposit_price;

    /**
     * @ORM\Column(type="OrderBillType")
     */
    private $status;

    /**
     * @ORM\Column(type="integer", options={"default" : 1})
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Goods")
     * @ORM\JoinColumn(nullable=false)
     */
    private $goods;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="orderBill")
     * @ORM\JoinColumn(nullable=false)
     */
    private $order_info;

    private $goods_id;

    public function getId()
    {
        return $this->id;
    }

    public function getGoodsId()
    {
        return $this->goods_id;
    }

    public function setGoodsId($goods_id)
    {
        $this->goods_id = $goods_id;
        return $this;
    }

    public function getDepositPrice(): ?float
    {
        return $this->deposit_price;
    }

    public function setDepositPrice(float $deposit_price): self
    {
        $this->deposit_price = $deposit_price;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getGoods(): ?Goods
    {
        return $this->goods;
    }

    public function setGoods(?Goods $goods): self
    {
        $this->goods = $goods;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getId();
    }

    public function getOrderInfo(): ?Order
    {
        return $this->order_info;
    }

    public function setOrderInfo(?Order $order_info): self
    {
        $this->order_info = $order_info;

        return $this;
    }
}
