<?php
// tests/Service/CardGameServiceTest.php

namespace App\Tests\Service;

use App\Service\CardGameService;
use App\Entity\Card;
use App\Entity\Hand;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CardGameServiceTest extends TestCase
{
    private EntityManagerInterface|MockObject $entityManagerMock;

    private CardGameService $cardGameService;

    protected function setUp(): void
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->cardGameService = new CardGameService($this->entityManagerMock);
    }

    public function testGenerateRandomHand(): void
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

        $hand = new Hand();
        foreach (array_slice($cards, 0, Hand::NBR_OF_CARDS_BY_HAND) as $card) {
            $hand->addCard($card);
        }

        $this->entityManagerMock->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Hand::class));

        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $generatedHand = $this->cardGameService->generateRandomHand();

        $this->assertInstanceOf(Hand::class, $generatedHand);

        $this->assertCount(Hand::NBR_OF_CARDS_BY_HAND, $generatedHand->getCards());
    }

    public function testSortCards(): void
    {
        $hand = new Hand();

        $card1 = new Card();
        $card1->setColor('hearts')->setValue('2');
        $hand->addCard($card1);

        $card2 = new Card();
        $card2->setColor('spades')->setValue('10');
        $hand->addCard($card2);

        $card3 = new Card();
        $card3->setColor('hearts')->setValue('10');
        $hand->addCard($card3);

        $sortedHand = $this->cardGameService->sortCards($hand);

        $this->assertCount(3, $sortedHand->getCards());

        $sortedCards = $sortedHand->getCards()->toArray();
        $this->assertEquals('hearts', $sortedCards[0]->getColor());
        $this->assertEquals('2', $sortedCards[0]->getValue());
        $this->assertEquals('hearts', $sortedCards[1]->getColor());
        $this->assertEquals('10', $sortedCards[1]->getValue());
        $this->assertEquals('spades', $sortedCards[2]->getColor());
        $this->assertEquals('10', $sortedCards[2]->getValue());
    }
}