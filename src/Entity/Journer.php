<?php

namespace App\Entity;

use App\Repository\JournerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JournerRepository::class)
 */
class Journer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity=Rencontre::class, mappedBy="journer")
     */
    private $rencontre;

    public function __construct()
    {
        $this->rencontre = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|Rencontre[]
     */
    public function getRencontre(): Collection
    {
        return $this->rencontre;
    }

    public function addRencontre(Rencontre $rencontre): self
    {
        if (!$this->rencontre->contains($rencontre)) {
            $this->rencontre[] = $rencontre;
            $rencontre->setJourner($this);
        }

        return $this;
    }

    public function removeRencontre(Rencontre $rencontre): self
    {
        if ($this->rencontre->removeElement($rencontre)) {
            // set the owning side to null (unless already changed)
            if ($rencontre->getJourner() === $this) {
                $rencontre->setJourner(null);
            }
        }

        return $this;
    }
}
