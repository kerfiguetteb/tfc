<?php

namespace App\Entity;

use App\Repository\VisiteurRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=VisiteurRepository::class)
 */
class Visiteur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:match"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Equipe::class, inversedBy="visiteur")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read:match"})

     */
    private $equipe;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"read:match"})
     */
    private $score;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEquipe(): ?Equipe
    {
        return $this->equipe;
    }

    public function setEquipe(?Equipe $equipe): self
    {
        $this->equipe = $equipe;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

}
