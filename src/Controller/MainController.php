<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Utilisateur;
use App\Form\CampusSearchType;

use App\Form\RegistrationFormType;
use App\Repository\CampusRepository;
use App\Repository\CityRepository;
use App\Repository\OutingRepository;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'main_home')]
    public function home(CampusRepository $campusRepository): Response
    {

        $campus = $campusRepository->findAll();
        return $this->render('main/home.html.twig', [

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
