<?php

namespace App\Controller;

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
        return $this->render('front/index.html.twig', []);
    }
}