<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['groupadminproduct','groupproduct'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['groupadminproduct','groupproduct'])]
    private ?string $title = "";

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['groupadminproduct','groupproduct'])]
    private ?string $description = "";

    #[ORM\Column]
    #[Groups(['groupadminproduct','groupproduct'])]
    private ?int $status = 0;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true,  options:['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['groupadminproduct','groupproduct'])]
    private ?\DateTimeInterface $releasedate = null;

    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'products')]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Comment::class)]
    #[Groups(['groupproduct'])]
    private Collection $comments;

    #[ORM\Column(length: 255)]
    private ?string $uuid = "";

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options:['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $created = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Variant::class)]
    #[Groups(['groupproduct'])]
    private Collection $variants;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Variantproduct::class)]
    private Collection $variantproducts;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->uuid = (Uuid::v4())->jsonSerialize();
        $this->variants = new ArrayCollection();
        $this->variantproducts = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getReleasedate(): ?\DateTimeInterface
    {
        return $this->releasedate;
    }

    public function setReleasedate(?\DateTimeInterface $releasedate): self
    {
        $this->releasedate = $releasedate;

        return $this;
    }


    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addProduct($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeProduct($this);
        }

        return $this;
    }


    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setProduct($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getProduct() === $this) {
                $comment->setProduct(null);
            }
        }

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

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
     * @return Collection<int, Variant>
     */
    public function getVariants(): Collection
    {
        return $this->variants;
    }

    public function addVariant(Variant $variant): self
    {
        if (!$this->variants->contains($variant)) {
            $this->variants->add($variant);
            $variant->setProduct($this);
        }

        return $this;
    }

    public function removeVariant(Variant $variant): self
    {
        if ($this->variants->removeElement($variant)) {
            // set the owning side to null (unless already changed)
            if ($variant->getProduct() === $this) {
                $variant->setProduct(null);
            }
        }

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
            $variantproduct->setProduct($this);
        }

        return $this;
    }

    public function removeVariantproduct(Variantproduct $variantproduct): self
    {
        if ($this->variantproducts->removeElement($variantproduct)) {
            // set the owning side to null (unless already changed)
            if ($variantproduct->getProduct() === $this) {
                $variantproduct->setProduct(null);
            }
        }

        return $this;
    }
}
