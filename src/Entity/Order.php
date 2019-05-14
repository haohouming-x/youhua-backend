<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\DBAL\Types\{OrderType, OrderBillType};
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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $consignee_address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $order_number;

    /**
     * @ORM\Column(type="OrderType", options={"default" : OrderType::WAIT_PAY})
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
     * @ORM\Column(type="float", options={"default" : 0})
     */
    private $total;

    /**
     * @ORM\Column(type="float", options={"default" : 0})
     */
    private $total_excl;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="change", field="status", value=OrderType::WAIT_SEND)
     */
    private $paid_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="change", field="status", value=OrderType::ALREADY_SEND)
     */
    private $sent_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="change", field="status", value=OrderType::ALREADY_TAKE)
     */
    private $took_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderBill", mappedBy="order_info", orphanRemoval=true, cascade={"persist"})
     */
    private $orderBill;

    public function __construct()
    {
        $this->orderBill = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    private function getTotalBillByStatus($type)
    {
        return $this->getOrderBill()->filter(function($entity) use($type) {
            return $entity->getStatus() === $type;
        })->count();
    }

    public function getTotalBillByReturn()
    {
        return $this->getTotalBillByStatus(OrderBillType::RETURN);
    }

    public function getTotalBillByAppend()
    {
        return $this->getTotalBillByStatus(OrderBillType::APPEND);
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

    public function getConsigneeAddress(): ?string
    {
        return $this->consignee_address;
    }

    public function setConsigneeAddress(string $consignee_address): self
    {
        $this->consignee_address = $consignee_address;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getTotalExcl(): ?float
    {
        return $this->total_excl;
    }

    public function setTotalExcl(float $total_excl): self
    {
        $this->total_excl = $total_excl;

        return $this;
    }

    public function getTotalProfit(): float
    {
        return $this->getTotalExcl() == 0 ? 0 : $this->getTotal() - $this->getTotalExcl();
    }

    public function __toString()
    {
        return $this->getOrderNumber();
    }

    public function getPaidAt(): ?\DateTimeInterface
    {
        return $this->paid_at;
    }

    public function setPaidAt(?\DateTimeInterface $paid_at): self
    {
        $this->paid_at = $paid_at;

        return $this;
    }

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sent_at;
    }

    public function setSentAt(?\DateTimeInterface $sent_at): self
    {
        $this->sent_at = $sent_at;

        return $this;
    }

    public function getTookAt(): ?\DateTimeInterface
    {
        return $this->took_at;
    }

    public function setTookAt(?\DateTimeInterface $took_at): self
    {
        $this->took_at = $took_at;

        return $this;
    }

    /**
     * @return Collection|OrderBill[]
     */
    public function getOrderBill(): Collection
    {
        return $this->orderBill;
    }

    public function addOrderBill(OrderBill $orderBill): self
    {
        if (!$this->orderBill->contains($orderBill)) {
            $this->orderBill[] = $orderBill;
            $orderBill->setOrderInfo($this);
        }

        return $this;
    }

    public function removeOrderBill(OrderBill $orderBill): self
    {
        if ($this->orderBill->contains($orderBill)) {
            $this->orderBill->removeElement($orderBill);
            // set the owning side to null (unless already changed)
            if ($orderBill->getOrderInfo() === $this) {
                $orderBill->setOrderInfo(null);
            }
        }

        return $this;
    }
}
