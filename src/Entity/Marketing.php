<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Helper\{TimestampableEntity, FileUploadTrait};


/**
 * @Gedmo\Uploadable(path="uploads/images", filenameGenerator="SHA1", allowOverwrite=false, appendNumber=true)
 * @ORM\Entity(repositoryClass="App\Repository\MarketingRepository")
 */
class Marketing
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
    private $discount;

    /**
     * @ORM\Column(type="integer")
     */
    private $validity_date;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Member", mappedBy="market")
     */
    private $members;

    public function __construct()
    {
        $this->members = new ArrayCollection();
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

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getValidityDate(): ?int
    {
        return $this->validity_date;
    }

    public function setValidityDate(int $validity_date): self
    {
        $this->validity_date = $validity_date;

        return $this;
    }

    /**
     * @return Collection|Member[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Member $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->setMarket($this);
        }

        return $this;
    }

    public function removeMember(Member $member): self
    {
        if ($this->members->contains($member)) {
            $this->members->removeElement($member);
            // set the owning side to null (unless already changed)
            if ($member->getMarket() === $this) {
                $member->setMarket(null);
            }
        }

        return $this;
    }
}
