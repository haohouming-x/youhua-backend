<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Helper\{TimestampableEntity, FileUploadTrait};


/**
 * @ORM\Entity(repositoryClass="App\Repository\GoodsBannerRepository")
 * @Gedmo\Uploadable(path="uploads/images", filenameGenerator="SHA1", allowOverwrite=false, appendNumber=true)
 */
class GoodsBanner
{
    /**
     * Hook timestampable behavior
     * updates created_at, updated_at fields
     */
    use TimestampableEntity, FileUploadTrait;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Goods", inversedBy="pictures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $goods;

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

    public function getGoods(): ?Goods
    {
        return $this->goods;
    }

    public function setGoods(?Goods $goods): self
    {
        $this->goods = $goods;

        return $this;
    }
}
