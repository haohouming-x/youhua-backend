<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GoodsRepository")
 * @Gedmo\Uploadable(path="/uploads/images", filenameGenerator="SHA1", allowOverwrite=false, appendNumber=true)
 */
class Goods
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\UploadableFileName
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ClassifyGoods", inversedBy="goods")
     */
    private $classify;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $describes;

    /**
     * @ORM\Column(type="float")
     */
    private $market_price;

    /**
     * @ORM\Column(type="float")
     */
    private $deposit_price;

    /**
     * @ORM\Column(type="integer")
     */
    private $long_size;

    /**
     * @ORM\Column(type="integer")
     */
    private $wide_size;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $details;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GoodsBanner", mappedBy="goods", orphanRemoval=true)
     */
    private $pictures;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OrderBill", inversedBy="goods")
     */
    private $orderBill;

    public function __construct()
    {
        $this->classify = new ArrayCollection();
        $this->pictures = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
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

    /**
     * @return Collection|ClassifyGoods[]
     */
    public function getClassify(): Collection
    {
        return $this->classify;
    }

    public function addClassify(ClassifyGoods $classify): self
    {
        if (!$this->classify->contains($classify)) {
            $this->classify[] = $classify;
        }

        return $this;
    }

    public function removeClassify(ClassifyGoods $classify): self
    {
        if ($this->classify->contains($classify)) {
            $this->classify->removeElement($classify);
        }

        return $this;
    }

    public function getDescribes(): ?string
    {
        return $this->describes;
    }

    public function setDescribes(?string $describes): self
    {
        $this->describes = $describes;

        return $this;
    }

    public function getMarketPrice(): ?float
    {
        return $this->market_price;
    }

    public function setMarketPrice(float $market_price): self
    {
        $this->market_price = $market_price;

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

    public function getLongSize(): ?int
    {
        return $this->long_size;
    }

    public function setLongSize(int $long_size): self
    {
        $this->long_size = $long_size;

        return $this;
    }

    public function getWideSize(): ?int
    {
        return $this->wide_size;
    }

    public function setWideSize(int $wide_size): self
    {
        $this->wide_size = $wide_size;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): self
    {
        $this->details = $details;

        return $this;
    }

    /**
     * @return Collection|GoodsBanner[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(GoodsBanner $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setGoods($this);
        }

        return $this;
    }

    public function removePicture(GoodsBanner $picture): self
    {
        if ($this->pictures->contains($picture)) {
            $this->pictures->removeElement($picture);
            // set the owning side to null (unless already changed)
            if ($picture->getGoods() === $this) {
                $picture->setGoods(null);
            }
        }

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
