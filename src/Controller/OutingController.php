<?php

namespace App\Controller;

use App\Entity\Outing;
use App\Form\Model\OutingSearch;
use App\Form\OutingSearchType;
use App\Form\OutingType;
use App\Repository\CampusRepository;
use App\Repository\LocationRepository;
use App\Repository\OutingRepository;
use App\Repository\StatusRepository;
use App\Service\OutingPermissionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sortie', name: 'sortie_')]
final class OutingController extends AbstractController
{
    /*
     * Method to create an outing
     *
     * @param EntityManagerInterface $entityManager, UserRepository $userRepository,
     CampusRepository $campusRepository,
     LocationRepository $locationRepository
     *
     * @return a Response
     */
    #[Route('/add', name: 'add')]
    public function createOuting(
        Request $request,
        EntityManagerInterface $entityManager,
        StatusRepository $statusRepository,
        LocationRepository $locationRepository,
        CampusRepository $campusRepository,
        $status = null,
        int $locationId = null,
        int $campusId = null
    ): Response
    {
        $user = $this->getUser();
        if(!$user) {
            return $this->redirectToRoute('app_login');
        }
        $outing = new Outing();

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
            // Retrieved the value of the button clicked and set the status label according the button value
            $action = $request->request->get('action');
            if ($action === 'save') {
                $status = $statusRepository->findOneBy(['label' => 'Créée']);
            } elseif ($action === 'publish') {
                $status = $statusRepository->findOneBy(['label' => 'Ouverte']);
            }

            if (!$status) {
                throw $this->createNotFoundException("Le statut demandé n'existe pas.");
            }
            $outing->setStatus($status);
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



}
