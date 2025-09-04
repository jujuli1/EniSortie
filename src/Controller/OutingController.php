<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Location;
use App\Entity\Outing;
use App\Entity\Utilisateur;
use App\Form\OutingType;
use App\Repository\CampusRepository;
use App\Repository\LocationRepository;
use App\Repository\OutingRepository;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sortie', name: 'sortie_')]
/*
 * Method to create an outing
 *
 * @param EntityManagerInterface $entityManager, UserRepository $userRepository,
  CampusRepository $campusRepository,
  LocationRepository $locationRepository
 *
 * @return a Response
 */
final class OutingController extends AbstractController
{
    #[Route('/list', name: 'sortie_list')]
    public function listOutings() {

    }



    #[Route('/add', name: 'add')]
    public function createOuting(
        Request $request,
        EntityManagerInterface $entityManager,
        StatusRepository $statusRepository,
        LocationRepository $locationRepository,
        CampusRepository $campusRepository,
        int $statusId = null,
        int $locationId = null,
        int $campusId = null
    ): Response
    {
        $outing = new Outing();

        if($statusId) {
            // Retrieve the status by its identifier, check if the status isn't retrieved to throw a created not found exception and set the outing with the status retrieved
            $status = $statusRepository->find($statusId);
            if(!$status) {
                throw $this->createNotFoundException('Le status n\'existe pas');
            }
            $outing->setStatus($status);
        }

         if($locationId) {
             // Retrieve the location by its identifier, check if the location isn't retrieved to throw a created not found exception and set the outing with the location retrieved
             $location = $locationRepository->find($locationId);
             if(!$location) {
                 throw $this->createNotFoundException('le location ne peut pas etre vide');
             }
             $outing->setLocation($location);

         }

         if($campusId) {
             // Retrieve the campus by its identifier,  Check if the campus isn't retrieved to throw a created not found exception and set the outing with the campus retrieved
             $campus = $campusRepository->find($campusId);
             if(!$campus) {
                 throw $this->createNotFoundException('le campus ne peut pas etre vide');
             }
             $outing->setCampus($campus);
         }


        // Create the outing form
        $outingForm = $this->createForm(OutingType::class, $outing);
        $outingForm->handleRequest($request);
        if($outingForm->isSubmitted() && $outingForm->isValid()) {
            $outing->setOrganizer($this->getUser());
            $entityManager->persist($outing);
            $entityManager->flush();
            $this->addFlash("succes", "Sortie ajoutée avec succès");
            return $this->redirectToRoute('sortie_list');
        }
        // Retrieve all cities to display to the form

        return $this->render('outing/add.html.twig', [
            "outingForm" => $outingForm
        ]);

    }
    #[Route('/inscription/{id}', name: 'app_inscription')]
    public function inscrire(OutingRepository $outingRepository, int $id, EntityManagerInterface $emi)
    {



        ///user connecté
        $user = $this->getUser();

        $sortie = $outingRepository->find($id);

        //verifier le nb max de participant et si la date limite d'inscrition nest pas depasser

        $date = new \DateTime('now');
        $dateInscription = $sortie->getRegistrationLimitDate();
        $nbParticipants = count($sortie->getParticipants());
        $sortieMax = $sortie->getNbMaxRegistration();

        if($nbParticipants >= $sortieMax ) {
            return $this->render('main/failed_registration.html.twig', [
                'max' => $sortieMax,
                'errorMax' => 'Nombre de participant maximal atteint !',
                'errorDate' => ' '
            ]);
        }

        if( $dateInscription > $date ) {
            return $this->render('main/failed_registration.html.twig', [
                'max' => $sortieMax,
                'errorMax' => '',
                'errorDate' => 'La date limite a été dépasser ... '
            ]);
        }

        $sortie->addParticipant($user);

        //dd($sortie);
        $emi->persist($sortie);
        $emi->flush();



        $this->addFlash('success', 'Inscription réussie !');

        return $this->render('main/campus_inscription.html.twig', [
            "user" => $user,
            'sortie' => $sortie,
            'max' => $sortieMax,


        ]);


    }


}
