<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CardRepository::class)]
class Card
{
    public const COLORS = ['diamonds', 'hearts', 'spades', 'clubs'];
    public const VALUES = ['AS', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'Valet', 'Dame', 'Roi'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('hand:read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('hand:read')]
    private ?string $color = null;

    #[ORM\Column(length: 255)]
    #[Groups('hand:read')]
    private ?string $value = null;

    #[ORM\ManyToOne(targetEntity: Hand::class, inversedBy: 'cards')]
    private $hand;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHand()
    {
        return $this->hand;
    }

    /**
     * @param mixed $hand
     */
    public function setHand($hand): void
    {
        $this->hand = $hand;
    }
}
