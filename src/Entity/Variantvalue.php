<?php

namespace App\Entity;

use App\Repository\VariantvalueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VariantvalueRepository::class)]
class Variantvalue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['groupadminproductinfo','groupadminproductinfo2','groupproduct','groupvproduct'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['groupadminproductinfo','groupadminproductinfo2','groupproduct'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options:['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $created = null;

    #[ORM\ManyToOne(inversedBy: 'variantvalues')]
    #[Groups(['groupadminproductinfo2','groupvproduct'])]
    private ?Variant $variant = null;

    #[ORM\ManyToMany(targetEntity: Variantproduct::class, mappedBy: 'variantvalues')]
    private Collection $variantproducts;

    public function __construct()
    {
        $this->variantproducts = new ArrayCollection();
        $this->created = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getVariant(): ?Variant
    {
        return $this->variant;
    }

    public function setVariant(?Variant $variant): self
    {
        $this->variant = $variant;

        return $this;
    }

    /**
     * @return Collection<int, Variantproduct>
     */
    public function getVariantproducts(): Collection
    {
        return $this->variantproducts;
    }

    public function addVariantproduct(Variantproduct $variantproduct): self
    {
        if (!$this->variantproducts->contains($variantproduct)) {
            $this->variantproducts->add($variantproduct);
            $variantproduct->addVariantvalue($this);
        }

        return $this;
    }

    public function removeVariantproduct(Variantproduct $variantproduct): self
    {
        if ($this->variantproducts->removeElement($variantproduct)) {
            $variantproduct->removeVariantvalue($this);
        }

        return $this;
    }
}
