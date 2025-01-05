<?php
// src/Command/GenerateDealHandCommand.php

namespace App\Controller;

use App\Entity\Hand;
use App\Service\CardGameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CardGameController extends AbstractController
{
    public function __construct(
        private CardGameService $cardGameService,
        private SerializerInterface $serializer
    )
    {
    }

    #[Route('/api/random-hand', name: 'random_hand', methods: ['GET'])]
    public function getRandomHand(): JsonResponse
    {
        $hand = $this->cardGameService->generateRandomHand();
        $data = $this->serializer->serialize($hand, 'json', ['groups' => 'hand:read']);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/sorted-hand/{id}', name: 'sorted_hand', methods: ['GET'])]
    public function getSortedHand(Hand $hand): JsonResponse
    {
        $sortedHand = $this->cardGameService->sortCards($hand);
        $data = $this->serializer->serialize($sortedHand, 'json', ['groups' => 'hand:read']);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
}