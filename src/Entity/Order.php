<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\DBAL\Types\OrderType;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @Gedmo\SoftDeleteable(fieldName="deleted_at", timeAware=false, hardDelete=true)
 */
class Order
{
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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $order_number;

    /**
     * @ORM\Column(type="OrderType")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updated_at;

    /**
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

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
}
