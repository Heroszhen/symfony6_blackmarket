<?php

namespace App\Entity;

use App\Repository\VariantproductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VariantproductRepository::class)]
class Variantproduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['groupadminproductinfo2','groupproductm','groupvproduct'])]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Groups(['groupadminproductinfo2','groupproductm','groupvproduct'])]
    private ?string $price = "";

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options:['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['groupadminproductinfo2'])]
    private ?\DateTimeInterface $created = null;

    #[ORM\ManyToMany(targetEntity: Variantvalue::class, inversedBy: 'variantproducts')]
    #[Groups(['groupadminproductinfo2','groupvproduct','groupvproduct'])]
    private Collection $variantvalues;

    #[ORM\ManyToOne(inversedBy: 'variantproducts')]
    private ?Product $product = null;

    #[ORM\Column(type: Types::ARRAY)]
    #[Groups(['groupadminproductinfo2','groupproductm','groupvproduct'])]
    private array $photos = [];

    #[ORM\Column]
    #[Groups(['groupadminproductinfo2','groupproductm','groupvproduct'])]
    private ?int $status = 1;

    public function __construct()
    {
        $this->variantvalues = new ArrayCollection();
        $this->created = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

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
        }

        return $this;
    }

    public function removeVariantvalue(Variantvalue $variantvalue): self
    {
        $this->variantvalues->removeElement($variantvalue);

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

    public function getPhotos(): array
    {
        return $this->photos;
    }

    public function setPhotos(array $photos): self
    {
        $this->photos = $photos;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
