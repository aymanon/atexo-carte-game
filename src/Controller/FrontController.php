<?php

namespace App\Controller;

use App\Entity\Card;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/', name: 'home')]
    public function index()
    {
        $suitOrder = Card::COLORS;
        $valueOrder = Card::VALUES;

        return $this->render('front/index.html.twig', [
            'suitOrder' => $suitOrder,
            'valueOrder' => $valueOrder
        ]);
    }
}