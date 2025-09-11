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
use App\Service\LocationApiService;
use App\Service\OutingPermissionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    #[IsGranted('ROLE_USER')]
    #[Route('/', name: 'main_home')]
    public function listOutings(
        Request $request,
        OutingRepository $outingRepository,
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
            'user' => $user,
        ]);

    }



    #[IsGranted('ROLE_USER')]
    #[Route('/inscription', name: 'main_inscription')]
    public function campus(OutingRepository $outingRepository, UtilisateurRepository $utilisateurRepository, Request $request): Response
    {
        $sortie = $outingRepository -> findAll();
        $user = $utilisateurRepository->findAll();




        return $this->render('main/campus_inscription.html.twig', [
            'sortie' => $sortie,
        ]);
    }



    #[Route('/detailSortie/{id}', name: 'app_detail_sortie')]
    public function detailCity(OutingRepository $outingRepository,  CampusRepository $campusRepository,UtilisateurRepository $utilisateurRepository, int $id): Response
    {

        $outing = $outingRepository->find($id);

        if(!$outing) {
            throw $this->createNotFoundException('Le sortie n\'existe pas');
        }


        return $this->render('main/detail.html.twig', [

            'outing' => $outing,

        ]);
    }

    #[Route('/admin', name: 'app_admin')]
    public function admin(UtilisateurRepository $utilisateurRepository): Response
    {

        return $this->render('main/admin.html.twig', [

        ]);
    }


    #[Route('', name: 'list')]
    public function listLocations() {

    }

    #[Route('/api/locations/{id}', name: 'api_locations')]
    public function getLocationsByCity(
        City $city,
        LocationApiService $locationApiService
    ): JsonResponse {
        $locations = $locationApiService->searchLocationsByCityEntity($city);
        return $this->json($locations);
    }



}
