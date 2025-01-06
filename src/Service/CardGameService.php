<?php
// src/Service/CardGameService.php

namespace App\Service;

use App\Entity\Card;
use App\Entity\Hand;
use Doctrine\ORM\EntityManagerInterface;

class CardGameService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function generateRandomHand(): Hand
    {
        $cards = [];
        foreach (Card::COLORS as $color) {
            foreach (Card::VALUES as $value) {
                $card = new Card();
                $card->setColor($color);
                $card->setValue($value);
                $cards[] = $card;
            }
        }

        shuffle($cards);
        $cardsOfHand = array_slice($cards, 0, 10);

        $hand = new Hand();

        // Ajout des cartes à la main
        foreach ($cardsOfHand as $card) {
            $hand->addCard($card);
        }

        $this->entityManager->persist($hand);
        $this->entityManager->flush();

        return $hand;
    }

    public function sortCards(Hand $hand): Hand
    {
        $cards = $hand->getCards()->toArray();
        usort($cards, function(Card $a, Card $b) {
            $colorOrder = array_flip(Card::COLORS);
            $valueOrder = array_flip(Card::VALUES);

            $colorComparison = $colorOrder[$a->getColor()] <=> $colorOrder[$b->getColor()];
            if ($colorComparison !== 0) {
                return $colorComparison;
            }

            return $valueOrder[$a->getValue()] <=> $valueOrder[$b->getValue()];
        });

        $sortedHand = new Hand();
        foreach ($cards as $card) {
            $sortedHand->addCard($card);
        }

        return $sortedHand;
    }
}