<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Helper\TimestampableEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\MarketingRepository")
 */
class Marketing
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
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $explain_text;

    /**
     * @ORM\Column(type="float")
     */
    private $original_price;

    /**
     * @ORM\Column(type="float")
     */
    private $present_price;

    /**
     * @ORM\Column(type="float")
     */
    private $discount_price;

    /**
     * @ORM\Column(type="integer")
     */
    private $validity_datet;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getExplainText(): ?string
    {
        return $this->explain_text;
    }

    public function setExplainText(string $explain_text): self
    {
        $this->explain_text = $explain_text;

        return $this;
    }

    public function getOriginalPrice(): ?float
    {
        return $this->original_price;
    }

    public function setOriginalPrice(float $original_price): self
    {
        $this->original_price = $original_price;

        return $this;
    }

    public function getPresentPrice(): ?float
    {
        return $this->present_price;
    }

    public function setPresentPrice(float $present_price): self
    {
        $this->present_price = $present_price;

        return $this;
    }

    public function getDiscountPrice(): ?float
    {
        return $this->discount_price;
    }

    public function setDiscountPrice(float $discount_price): self
    {
        $this->discount_price = $discount_price;

        return $this;
    }

    public function getValidityDatet(): ?int
    {
        return $this->validity_datet;
    }

    public function setValidityDatet(int $validity_datet): self
    {
        $this->validity_datet = $validity_datet;

        return $this;
    }
}
