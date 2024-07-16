<?php

namespace App\Entity;

use App\Repository\CategorieProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: CategorieProduitRepository::class)]
class CategorieProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $slug = null;

    #[ORM\Column(nullable:true)]
    private ?int $position = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;


    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: 'category')]
    private Collection $produits;

    public function __toString()
    {
        return $this->name;
    }
    
    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setCategory($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getCategory() === $this) {
                $produit->setCategory(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug?? $this->esc_url($this->name);
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function esc_url( $url, $protocols = null, $_context = 'display' ) {
        $original_url = $url;
    
        if ( '' === $url ) {
            return $url;
        }
    
        $url = str_replace( ' ', '%20', ltrim( $url ) );
        $url = preg_replace( '|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\[\]\\x80-\\xff]|i', '', $url );
    
        if ( '' === $url ) {
            return $url;
        }
    
        if ( 0 !== stripos( $url, 'mailto:' ) ) {
            $strip = array( '%0d', '%0a', '%0D', '%0A' );
            $url   = $this->_deep_replace( $strip, $url );
        }
    
        $url = str_replace( ';//', '://', $url );
        
        return $url;
    }
    public function _deep_replace( $search, $subject ) {
        $subject = (string) $subject;
    
        $count = 1;
        while ( $count ) {
            $subject = str_replace( $search, '', $subject, $count );
        }
    
        return $subject;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
