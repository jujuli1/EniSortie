<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationType;
use App\Service\LocationApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/location', name: 'location_')]
final class LocationController extends AbstractController
{
    #[Route('/add', name: 'add')]
    public function createLocation(
        Request $request,
        EntityManagerInterface $entityManager,
        LocationApiService $locationApiService,
    ): Response {
        // Instantiate the location entity
        $location = new Location();

        // Create the location form based on Location type and bind it to the location entity
        $locationForm = $this->createForm(LocationType::class, $location);

        // Handle the HTTP request
        $locationForm->handleRequest($request);

        // Check if the location form is submitted and have valid values according to fields constraints
        if ($locationForm->isSubmitted() && $locationForm->isValid()) {
            // Persist and flush the data to the database
            $entityManager->persist($location);
            $entityManager->flush();
            // Add flash to display successful message
            $this->addFlash('success', 'Lieu créé avec succès');
            // Redirect to the homa page
            return $this->redirectToRoute('main_home');
        }

        // Render the add location necessary data
        return $this->render('location/add.html.twig', [
            'locationForm' => $locationForm,
        ]);
    }

}
