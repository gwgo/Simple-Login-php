<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController {
    #[Route("/", name: "home")]
    function index(): Response {
        return $this->render('./home/home.html.twig', [
            'title' => 'Home Page',
            'message' => 'Welcome to the Home Page!'
        ]);
    }
}
