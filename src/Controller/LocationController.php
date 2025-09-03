<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Location;
use App\Form\LocationType;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        CityRepository $cityRepository,
        int $cityId = null
    ): Response {
        $location = new Location();
        if($cityId) {
            // Retrieve the city by its identifier, check if the city isn't retrieved to throw a created not found exception and set the outing with the city retrieved
            $city = $cityRepository->find($cityId);
            if(!$city) {
                throw $this->createNotFoundException('Le status n\'existe pas');
            }
            $location->setCity($city);
        }

        // Create the location form
        $locationForm = $this->createform(LocationType::class, $location);
        $locationForm->handleRequest($request);
        if($locationForm->isSubmitted() && $locationForm->isValid()) {
            $entityManager->persist($location);
            $entityManager->flush();
            $this->addFlash("succes", "Le lieu a été ajoutée avec succès");
            return $this->redirectToRoute('location_list');
        }
        return $this->render('location/add.html.twig', [
            'locationForm' => $locationForm
        ]);
    }


    #[Route('/location', name: 'list')]
    public function listLocations() {

    }
}
