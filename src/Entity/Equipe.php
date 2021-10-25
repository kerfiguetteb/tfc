<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipeRepository::class)
 */
class Equipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:match"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read:match"})
     */
    private $pts;

    /**
     * @ORM\OneToMany(targetEntity=Domicile::class, mappedBy="equipe")
     */
    private $domicile;

    /**
     * @ORM\OneToMany(targetEntity=Visiteur::class, mappedBy="equipe")
     */
    private $visiteur;

    /**
     * @ORM\Column(type="integer")
     */
    private $jo; // nombre de match

    /**
     * @ORM\Column(type="integer")
     */
    private $ga; // match gagner

    /**
     * @ORM\Column(type="integer")
     */
    private $nu; // match null

    /**
     * @ORM\Column(type="integer")
     */
    private $pe; // penality

    /**
     * @ORM\Column(type="integer")
     */
    private $bp; //but
    
    /**
     * @ORM\Column(type="integer")
     */
    private $bc; // but encaisser


    /**
     * @ORM\Column(type="integer")
     */
    private $diff;

    /**
     * @ORM\OneToMany(targetEntity=Joueur::class, mappedBy="equipe")
     */
    private $joueurs;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="equipe")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie; // difference entre but et but encaisser bp - bc = diff

    public function __construct()
    {
        $this->domicile = new ArrayCollection();
        $this->visiteur = new ArrayCollection();
        $this->joueurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPts(): ?int
    {
        return $this->pts;
    }

    public function setPts(int $pts): self
    {
        $this->pts = $pts;

        return $this;
    }

    /**
     * @return Collection|Domicile[]
     */
    public function getDomicile(): Collection
    {
        return $this->domicile;
    }

    public function addDomicile(Domicile $domicile): self
    {
        if (!$this->domicile->contains($domicile)) {
            $this->domicile[] = $domicile;
            $domicile->setEquipe($this);
        }

        return $this;
    }

    public function removeDomicile(Domicile $domicile): self
    {
        if ($this->domicile->removeElement($domicile)) {
            // set the owning side to null (unless already changed)
            if ($domicile->getEquipe() === $this) {
                $domicile->setEquipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Visiteur[]
     */
    public function getVisiteur(): Collection
    {
        return $this->visiteur;
    }

    public function addVisiteur(Visiteur $visiteur): self
    {
        if (!$this->visiteur->contains($visiteur)) {
            $this->visiteur[] = $visiteur;
            $visiteur->setEquipe($this);
        }

        return $this;
    }

    public function removeVisiteur(Visiteur $visiteur): self
    {
        if ($this->visiteur->removeElement($visiteur)) {
            // set the owning side to null (unless already changed)
            if ($visiteur->getEquipe() === $this) {
                $visiteur->setEquipe(null);
            }
        }

        return $this;
    }

    public function getJo(): ?int
    {
        return $this->jo;
    }

    public function setJo(int $jo): self
    {
        $this->jo = $jo;

        return $this;
    }

    public function getGa(): ?int
    {
        return $this->ga;
    }

    public function setGa(int $ga): self
    {
        $this->ga = $ga;

        return $this;
    }

    public function getNu(): ?int
    {
        return $this->nu;
    }

    public function setNu(int $nu): self
    {
        $this->nu = $nu;

        return $this;
    }

    public function getPe(): ?int
    {
        return $this->pe;
    }

    public function setPe(int $pe): self
    {
        $this->pe = $pe;

        return $this;
    }

    public function getBc(): ?int
    {
        return $this->bc;
    }

    public function setBc(int $bc): self
    {
        $this->bc = $bc;

        return $this;
    }

    public function getBp(): ?int
    {
        return $this->bp;
    }

    public function setBp(int $bp): self
    {
        $this->bp = $bp;

        return $this;
    }

    public function getDiff(): ?int
    {
        return $this->diff;
    }

    public function setDiff(int $diff): self
    {
        $this->diff = $diff;

        return $this;
    }

    /**
     * @return Collection|Joueur[]
     */
    public function getJoueurs(): Collection
    {
        return $this->joueurs;
    }

    public function addJoueur(Joueur $joueur): self
    {
        if (!$this->joueurs->contains($joueur)) {
            $this->joueurs[] = $joueur;
            $joueur->setEquipe($this);
        }

        return $this;
    }

    public function removeJoueur(Joueur $joueur): self
    {
        if ($this->joueurs->removeElement($joueur)) {
            // set the owning side to null (unless already changed)
            if ($joueur->getEquipe() === $this) {
                $joueur->setEquipe(null);
            }
        }

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

}
