<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\DBAL\Types\OrderType;
use App\Entity\Helper\TimestampableEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @Gedmo\SoftDeleteable(fieldName="deleted_at", timeAware=false, hardDelete=true)
 * @ORM\Table(name="`order`")
 */
class Order
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
     * @ORM\Column(type="string", length=12)
     */
    private $consignee_name;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $consignee_concat;

    // /**
    //  * @ORM\Column(type="string", length=255)
    //  */
    // private $consignee_address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $order_number;

    /**
     * @ORM\Column(type="OrderType")
     */
    private $status;

    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deleted_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Consumer", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $consumer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logistics_number;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OrderBill", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderBill;

    public function getId()
    {
        return $this->id;
    }

    public function getConsigneeName(): ?string
    {
        return $this->consignee_name;
    }

    public function setConsigneeName(string $consignee_name): self
    {
        $this->consignee_name = $consignee_name;

        return $this;
    }

    public function getConsigneeConcat(): ?string
    {
        return $this->consignee_concat;
    }

    public function setConsigneeConcat(string $consignee_concat): self
    {
        $this->consignee_concat = $consignee_concat;

        return $this;
    }

    public function getOrderNumber(): ?string
    {
        return $this->order_number;
    }

    public function setOrderNumber(string $order_number): self
    {
        $this->order_number = $order_number;

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

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(\DateTimeInterface $deleted_at): self
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }

    public function getConsumer(): ?Consumer
    {
        return $this->consumer;
    }

    public function setConsumer(?Consumer $consumer): self
    {
        $this->consumer = $consumer;

        return $this;
    }

    public function getLogisticsNumber(): ?string
    {
        return $this->logistics_number;
    }

    public function setLogisticsNumber(?string $logistics_number): self
    {
        $this->logistics_number = $logistics_number;

        return $this;
    }

    public function getOrderBill(): ?OrderBill
    {
        return $this->orderBill;
    }

    public function setOrderBill(?OrderBill $orderBill): self
    {
        $this->orderBill = $orderBill;

        return $this;
    }

    public function getConsigneeAddress(): ?string
    {
        return $this->consignee_address;
    }

    public function setConsigneeAddress(string $consignee_address): self
    {
        $this->consignee_address = $consignee_address;

        return $this;
    }
}
