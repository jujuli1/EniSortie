<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Utilisateur;
use App\Form\CampusSearchType;

use App\Form\Model\OutingSearch;
use App\Form\OutingSearchType;
use App\Form\RegistrationFormType;
use App\Repository\CampusRepository;
use App\Repository\CityRepository;
use App\Repository\OutingRepository;

use App\Repository\UtilisateurRepository;
use App\Service\OutingPermissionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    public function __construct(private readonly OutingPermissionService $outingPermissionService)
    {
    }
    /*
     * Method to display outings according to the filter
     *
     * @param Request $request, OutingRepository $outingRepository,
     *
     * @return a Response
     */
    #[Route('/', name: 'main_home')]
    public function listOutings(
        Request $request,
        OutingRepository $outingRepository
    ): Response {
        // Create the search form based on OutingSearchType and bind it to the search model
        $searchOuting = new OutingSearch();
        $searchForm = $this->createForm(OutingSearchType::class, $searchOuting);
        // Handle the HTTP request
        $searchForm->handleRequest($request);

        // Debug
        //dump($searchOuting);

        //  Get the currently authenticated user
        $user = $this->getUser();
        if(!$user) {
            return $this->redirectToRoute('app_login');
        }
        // Fetch outings from repository with the applied filters and current user context
        $outings = $outingRepository->search($searchOuting, $user);

        // Render the list template with outings, permissions service, and search form
        return $this->render('outing/list.html.twig', [
            'outings' => $outings, // The filtered outings
            'permission' => $this->outingPermissionService, // Service used for actions display logic
            'searchForm' => $searchForm, // The search form for filtering outings
        ]);

    }

    #[Route('/inscription', name: 'main_inscription')]
    public function campus(OutingRepository $outingRepository, Request $request): Response
    {



        $sortie = $outingRepository -> findAll();





        //verifier le nb max de participant et si la date limite d'inscrition nest pas depasser




        return $this->render('main/campus_inscription.html.twig', [
            'sortie' => $sortie,



        ]);
    }



    #[Route('/detailCity/{id}', name: 'app_detail_city')]
    public function detailCity(OutingRepository $outingRepository,  CampusRepository $campusRepository, int $id): Response
    {
        $campus = $campusRepository->find($id);
        $outings = $outingRepository->findAll();





        return $this->render('user/detailCity.html.twig', [

            'outings' => $outings,
            'campus' => $campus,
        ]);
    }

    #[Route('/admin', name: 'app_admin')]
    public function admin(): Response
    {
        return $this->render('main/admin.html.twig');
    }



}
