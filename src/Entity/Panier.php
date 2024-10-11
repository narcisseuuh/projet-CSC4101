<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * @var Collection<int, Fruit>
     */
    #[ORM\OneToMany(targetEntity: Fruit::class, mappedBy: 'Panier', orphanRemoval: true)]
    private Collection $fruits;

    public function __construct()
    {
        $this->fruits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getFruits(): Collection
    {
        return $this->fruits;
    }

    public function addFruit(Fruit $fruit): static 
    {
        if (!$this->fruits->contains($fruit)) {
            $this->fruits->add($fruit);
            $fruit->setPanier($this);
        }

        return $this;
    }

    public function removeFruit(Fruit $fruit): static
    {
        if ($this->fruits->contains($fruit)) {
            $this->fruits->removeElement($fruit);   
        }
        if ($fruit->getPanier() === $this) {
            $fruit->setPanier(null);
        }
        return $this;
    }
}
