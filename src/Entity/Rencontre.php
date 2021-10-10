<?php

namespace App\Entity;

use App\Repository\RencontreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RencontreRepository::class)
 */
class Rencontre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Journer::class, inversedBy="rencontre")
     * @ORM\JoinColumn(nullable=false)
     */
    private $journer;

    /**
     * @ORM\OneToMany(targetEntity=Domicile::class, mappedBy="rencontre")
     */
    private $domicile;

    /**
     * @ORM\OneToMany(targetEntity=Visiteur::class, mappedBy="rencontre")
     */
    private $visiteur;

    public function __construct()
    {
        $this->domicile = new ArrayCollection();
        $this->visiteur = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJourner(): ?Journer
    {
        return $this->journer;
    }

    public function setJourner(?Journer $journer): self
    {
        $this->journer = $journer;

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
            $domicile->setRencontre($this);
        }

        return $this;
    }

    public function removeDomicile(Domicile $domicile): self
    {
        if ($this->domicile->removeElement($domicile)) {
            // set the owning side to null (unless already changed)
            if ($domicile->getRencontre() === $this) {
                $domicile->setRencontre(null);
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
            $visiteur->setRencontre($this);
        }

        return $this;
    }

    public function removeVisiteur(Visiteur $visiteur): self
    {
        if ($this->visiteur->removeElement($visiteur)) {
            // set the owning side to null (unless already changed)
            if ($visiteur->getRencontre() === $this) {
                $visiteur->setRencontre(null);
            }
        }

        return $this;
    }

}
