<?php
// src/Command/GenerateDealHandCommand.php

namespace App\Command;

use App\Entity\Hand;
use App\Service\CardGameService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateDealHandCommand extends Command
{
    private CardGameService $cardGameService;

    public function __construct(CardGameService $cardGameService)
    {
        $this->cardGameService = $cardGameService;

        parent::__construct();
    }

    protected static $defaultName = 'app:generate-deal-hand';

    protected function configure(): void
    {
        $this->setDescription('Génère une main de ' . Hand::NBR_OF_CARDS_BY_HAND . ' cartes et l\'a trier.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Générer une main aléatoire
        $hand = $this->cardGameService->generateRandomHand();

        // Trier les cartes dans la main
        $sortedHand = $this->cardGameService->sortCards($hand);

        // Afficher les cartes triées dans la console
        foreach ($sortedHand->getCards() as $card) {
            $output->writeln(sprintf('%s de %s', $card->getValue(), $card->getColor()));
        }

        return Command::SUCCESS;
    }
}
