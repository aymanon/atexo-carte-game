<?php

namespace App\Entity;

use App\Repository\HandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HandRepository::class)]
class Hand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('hand:read')]
    private ?int $id = null;

    #[ORM\OneToMany(targetEntity: Card::class, mappedBy: "hand", cascade: ['persist'])]
    #[Groups('hand:read')]
    private Collection $cards;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setHand($this);
        }

        return $this;
    }

    public function getCards(): Collection
    {
        return $this->cards;
    }
}
