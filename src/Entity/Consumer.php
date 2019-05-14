<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\DBAL\Types\{ConsumerType, SexType, OrderType};
use Gedmo\Mapping\Annotation as Gedmo;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use App\Entity\Helper\TimestampableEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ConsumerRepository")
 * @Gedmo\Uploadable(path="uploads/images", filenameGenerator="SHA1", allowOverwrite=false, appendNumber=true)
 * @Gedmo\SoftDeleteable(fieldName="deleted_at", timeAware=false, hardDelete=true)
 */
class Consumer
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
     * @ORM\Column(type="string", length=255)
     * @Gedmo\UploadableFilePath
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nick_name;

    // /**
    //  * @ORM\Column(type="ConsumerType")
    //  * @DoctrineAssert\Enum(entity="App\DBAL\Types\ConsumerType")
    //  */
    // private $type;

    /**
     * @ORM\Column(type="SexType")
     */
    private $sex;

    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deleted_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_login_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $first_login_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ReceiptInfo", mappedBy="consumer", orphanRemoval=true, fetch="EXTRA_LAZY")
     */
    private $receiptInfos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="consumer", fetch="EXTRA_LAZY")
     */
    private $orders;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Member", mappedBy="consumer", cascade={"persist", "remove"})
     */
    private $member;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Wechat", mappedBy="consumer", cascade={"persist", "remove"})
     */
    private $wechat;

    public function __construct()
    {
        $this->receiptInfos = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getNickName(): ?string
    {
        return $this->nick_name;
    }

    public function setNickName(string $nick_name): self
    {
        $this->nick_name = $nick_name;

        return $this;
    }

    // public function getType(): ?string
    // {
    //     return $this->type;
    // }

    // public function setType(string $type): self
    // {
    //     $this->type = $type;

    //     return $this;
    // }

    // 已收货手动退押金; 除取消、未付有押金
    public function getDeposit(): float
    {
        $return_deposit_arr = $this->orders->filter(function($entity) {
            return $entity->getStatus() === OrderType::ALREADY_TAKE;
        })->toArray();
        $return_deposit = array_reduce($return_deposit_arr, function($acc, $entity) {
            return $acc + $entity->getTotalExcl();
        }, 0);

        $append_deposit_arr = $this->orders->filter(function($entity) {
            return !in_array($entity->getStatus, [OrderType::WAIT_PAY, OrderType::ALREADY_CLOSE]);
        })->toArray();
        $append_deposit = array_reduce($append_deposit_arr, function($acc, $entity) {
            return $acc + $entity->getTotal();
        }, 0);

        return $append_deposit - $return_deposit;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        $this->sex = $sex;

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

    public function getLastLoginAt(): ?\DateTimeInterface
    {
        return $this->last_login_at;
    }

    public function setLastLoginAt(?\DateTimeInterface $last_login_at): self
    {
        $this->last_login_at = $last_login_at;

        return $this;
    }

    public function getFirstLoginAt(): ?\DateTimeInterface
    {
        return $this->first_login_at;
    }

    public function setFirstLoginAt(?\DateTimeInterface $first_login_at): self
    {
        $this->first_login_at = $first_login_at;

        return $this;
    }

    /**
     * @return Collection|ReceiptInfo[]
     */
    public function getReceiptInfos(): Collection
    {
        return $this->receiptInfos;
    }

    public function addReceiptInfo(ReceiptInfo $receiptInfo): self
    {
        if (!$this->receiptInfos->contains($receiptInfo)) {
            $this->receiptInfos[] = $receiptInfo;
            $receiptInfo->setConsumer($this);
        }

        return $this;
    }

    public function removeReceiptInfo(ReceiptInfo $receiptInfo): self
    {
        if ($this->receiptInfos->contains($receiptInfo)) {
            $this->receiptInfos->removeElement($receiptInfo);
            // set the owning side to null (unless already changed)
            if ($receiptInfo->getConsumer() === $this) {
                $receiptInfo->setConsumer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setConsumer($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getConsumer() === $this) {
                $order->setConsumer(null);
            }
        }

        return $this;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(Member $member): self
    {
        $this->member = $member;

        // set the owning side of the relation if necessary
        if ($this !== $member->getConsumer()) {
            $member->setConsumer($this);
        }

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getNickName();
    }

    public function getWechat(): ?Wechat
    {
        return $this->wechat;
    }

    public function setWechat(Wechat $wechat): self
    {
        $this->wechat = $wechat;

        // set the owning side of the relation if necessary
        if ($this !== $wechat->getConsumer()) {
            $wechat->setConsumer($this);
        }

        return $this;
    }
}
