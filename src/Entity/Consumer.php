<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\DBAL\Types\{ConsumerType, SexType};
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConsumerRepository")
 * @Gedmo\Uploadable(path="/uploads/images", filenameGenerator="SHA1", allowOverwrite=false, appendNumber=true)
 * @Gedmo\SoftDeleteable(fieldName="deleted_at", timeAware=false, hardDelete=true)
 */
class Consumer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\UploadableFileName
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nick_name;

    /**
     * @ORM\Column(type="ConsumerType")
     */
    private $type;

    /**
     * @ORM\Column(type="SexType")
     */
    private $sex;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $serplusDate;

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
     * @ORM\OneToMany(targetEntity="App\Entity\ReceiptInfo", mappedBy="consumer", orphanRemoval=true)
     */
    private $receiptInfos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="consumer")
     */
    private $orders;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
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

    public function getSerplusDate(): ?int
    {
        return $this->serplusDate;
    }

    public function setSerplusDate(?int $serplusDate): self
    {
        $this->serplusDate = $serplusDate;

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

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
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
}
