<?php

namespace App\Entity;

use App\Repository\VariantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VariantRepository::class)]
class Variant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['groupadminproductinfo','groupadminproductinfo2','groupproduct','groupvproduct'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['groupadminproductinfo','groupadminproductinfo2','groupproduct'])]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'variants')]
    private ?Product $product = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options:['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['groupadminproductinfo'])]
    private ?\DateTimeInterface $created;

    #[ORM\OneToMany(mappedBy: 'variant', targetEntity: Variantvalue::class)]
    #[Groups(['groupadminproductinfo','groupproduct'])]
    private Collection $variantvalues;

    public function __construct()
    {
        $this->variantvalues = new ArrayCollection();
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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return Collection<int, Variantvalue>
     */
    public function getVariantvalues(): Collection
    {
        return $this->variantvalues;
    }

    public function addVariantvalue(Variantvalue $variantvalue): self
    {
        if (!$this->variantvalues->contains($variantvalue)) {
            $this->variantvalues->add($variantvalue);
            $variantvalue->setVariant($this);
        }

        return $this;
    }

    public function removeVariantvalue(Variantvalue $variantvalue): self
    {
        if ($this->variantvalues->removeElement($variantvalue)) {
            // set the owning side to null (unless already changed)
            if ($variantvalue->getVariant() === $this) {
                $variantvalue->setVariant(null);
            }
        }

        return $this;
    }
}
