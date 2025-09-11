<?php

namespace App\Controller;

use App\Entity\Outing;
use App\Form\OutingType;
use App\Repository\CampusRepository;
use App\Repository\LocationRepository;
use App\Repository\OutingRepository;
use App\Repository\StatusRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    #[IsGranted('ROLE_USER')]
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
            $this->addFlash("success", "Sortie ajoutée avec succès");
            return $this->redirectToRoute('main_home');
        }

        return $this->render('outing/add.html.twig', [
            "outingForm" => $outingForm
        ]);

    }


    /*
     * Method to update an outing
     *
     * @param Request $request,
        OutingRepository $outingRepository,
        StatusRepository $statusRepository,
        EntityManagerInterface $entityManager,
        int $id
     *
     * @return a Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/update/{id}', name: 'update')]
    public function updateOuting(
        Request $request,
        OutingRepository $outingRepository,
        StatusRepository $statusRepository,
        EntityManagerInterface $entityManager,
        int $id
    ): Response {
        // Retrieve the outing by its unique identifier
        $outing = $outingRepository->find($id);

        // Check if the outing isn't retrieved to throw a created not found exception
        if (!$outing) {
            throw $this->createNotFoundException("La sortie n'existe pas.");
        }

        // Check if the connected user isn't the organizer of the outing to throw a denied  exception
        if ($outing->getOrganizer() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Vous n’êtes pas l’organisateur de cette sortie.");
        }
        // Crete an updating outing form and handle the request
        $updatingOutingForm = $this->createForm(OutingType::class, $outing);
        $updatingOutingForm->handleRequest($request);

        // Check if the form is submitted and valid
        if ($updatingOutingForm->isSubmitted() && $updatingOutingForm->isValid()) {
            // Get the action, to set the outing status according to the clicked button
            $action = $request->request->get('action');
            if($action === 'save') {
                $status = $statusRepository->findOneBy(['label' => 'Créée']);

            } elseif ($action === 'publish') {
                $status = $statusRepository->findOneBy(['label' => 'Ouverte']);

            } elseif ($action === 'delete') {
                $entityManager->remove($outing);
                $entityManager->flush();
                $this->addFlash("success", "Sortie supprimée.");
                return $this->redirectToRoute('main_home');

            } elseif ($action === 'cancel') {
                return $this->redirectToRoute('main_home');
            }
            // Only update the outing's status if a new status has been determined from the submitted action
            if (isset($status)) {
                $outing->setStatus($status);
            }
            // Flash the data
            $entityManager->flush();
             // Add the flash with the successful message
            $this->addFlash("success", "Sortie mise à jour avec succès !");
            // Redirect to the main home route
            return $this->redirectToRoute('main_home');
        }

        // Display the updating outing form with necessary data
        return $this->render('outing/update.html.twig', [
            'updateOutingForm' => $updatingOutingForm,
            'outing' => $outing,
        ]);
    }


    /*
     * Method to update the outing status from créée to Ouverte
     *
     * @param OutingRepository $outingRepository,
        StatusRepository $statusRepository,
        EntityManagerInterface $entityManager,
        int $id,
     *
     * @return a Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/publish/{id}', name: 'publish')]
    public function publishOuting(
        OutingRepository $outingRepository,
        StatusRepository $statusRepository,
        EntityManagerInterface $entityManager,
        int $id,
    ): Response
    {
        // Retrieve the outing by its unique identifier
        $outing = $outingRepository->find($id);
        // Check if the outing isn't retrieved to throw a  created a not found exception
        if(!$outing) {
            throw $this->createNotFoundException("La sortie sélectionnée n'existe pas");
        }

        // Retrieve the  status witch has "Ouverte" as label
        $status = $statusRepository->findOneBy(['label' => 'Ouverte']);
        if (!$status) {
            throw $this->createNotFoundException("Le statut 'Ouverte' n'existe pas");
        }
        // Set the outing status with the new label
        $outing->setStatus($status);
        // Flush the data
        $entityManager->flush();
        $this->addFlash("success", "La sortie a été publiée avec succès");
        // Redirect to the main home route
        return $this->redirectToRoute('main_home');

    }


    /*
    * Method to update the outing status from publiée to Annulée
    *
    * @param OutingRepository $outingRepository,
       StatusRepository $statusRepository,
       EntityManagerInterface $entityManager,
       int $id,
    *
    * @return a Response
    */#[IsGranted('ROLE_USER')]
    #[Route('/cancel/{id}', name: 'cancel')]
    public function cancelOuting(
        OutingRepository $outingRepository,
        StatusRepository $statusRepository,
        EntityManagerInterface $entityManager,
        int $id,
    ): Response
    {
        // Retrieve the outing by its unique identifier
        $outing = $outingRepository->find($id);
        // Check if the outing isn't retrieved to throw a  created a not found exception
        if(!$outing) {
            throw $this->createNotFoundException("La sortie sélectionnée n'existe pas");
        }

        // Retrieve the  status witch has "Annulée" as label
        $status = $statusRepository->findOneBy(['label' => 'Annulée']);
        if (!$status) {
            throw $this->createNotFoundException("Le statut 'Ouverte' n'existe pas");
        }
        // Set the outing status with the new label
        $outing->setStatus($status);
        // Flush the data
        $entityManager->flush();
        $this->addFlash("success", "la Sortie a été annulée");
        // Redirect to the main home route
        return $this->redirectToRoute('main_home');

    }



    #[IsGranted('ROLE_USER')]
    #[Route('/inscription/{id}', name: 'app_inscription')]
    public function inscrire(OutingRepository $outingRepository, int $id, EntityManagerInterface $emi)
    {



        ///user connecté
        $user = $this->getUser();

        $sortie = $outingRepository->find($id);


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


        $this->addFlash('type', 'Vous etes inscrit ! Bravo !');


        return $this->redirectToRoute('main_inscription', [
            "user" => $user,
            'sortie' => $sortie,
            'max' => $sortieMax,





        ]);


    }

    #[IsGranted('ROLE_USER')]
    #[Route('/delete/{id}', name: 'app_delete')]
    public function supprimer(UtilisateurRepository $utilisateurRepository, OutingRepository $outingRepository, int $id, EntityManagerInterface $emi)
    {


        // annuler la sortie

        $participant = $this->getUser();
        $sortie = $outingRepository->find($id);

        $sortie->removeParticipant($participant);

        if (!$participant) {
            throw $this->createNotFoundException('Aucun uzer trouvée avec l\'ID : ' . $id);
        }

        $emi->flush();

        return $this->render('main/campus_delete.html.twig', [

        ]);




    }



}
