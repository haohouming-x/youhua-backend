<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\DBAL\Types\BannerType;
use App\Entity\Helper\{TimestampableEntity, FileUploadTrait};


/**
 * @Gedmo\Uploadable(path="uploads/images", filenameGenerator="SHA1", allowOverwrite=false, appendNumber=true)
 * @ORM\Entity(repositoryClass="App\Repository\BannerRepository")
 */
class Banner
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
     * @ORM\Column(type="BannerType", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Goods")
     * @ORM\JoinColumn(nullable=true)
     */
    private $goods;

    private $file;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CustomPage")
     * @ORM\JoinColumn(nullable=true)
     */
    private $custom_page;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

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

    public function getCustomPage(): ?CustomPage
    {
        return $this->custom_page;
    }

    public function setCustomPage(?CustomPage $custom_page): self
    {
        $this->custom_page = $custom_page;

        return $this;
    }
}
