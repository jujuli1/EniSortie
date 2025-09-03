<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/location', name: 'location_')]
final class LocationController extends AbstractController
{
    #[Route('/add', name: 'add')]
    public function createLocation(): Response {
        return $this->render('outing/add_location.html.twig', []);
    }
}
