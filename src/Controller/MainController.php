<?php

namespace App\Controller;

use App\Entity\City;
use App\Repository\CampusRepository;
use App\Repository\CityRepository;
use App\Repository\OutingRepository;
use App\Repository\UserRepository;
use App\Repository\UtilisateurRepository;
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
            'campuses' => $campus,
        ]);
    }

    #[Route('/detailCity/{id}', name: 'app_detail_city')]
    public function detailCity(OutingRepository $outingRepository, UtilisateurRepository $utilisateurRepository, CampusRepository $campusRepository, Security $security, CityRepository $cityRepository, int $id): Response
    {

        $sortie = $outingRepository->find($id);
        $uzer = $utilisateurRepository->findBy(['campus' => $id]);
        $campus = $campusRepository->find($id);
        $outing= $outingRepository->find($id);


        return $this->render('user/detailCity.html.twig', [

            'sortie' => $sortie,
            'user' => $uzer,
            'outing' => $outing,
            'campus' => $campus,
        ]);
    }

    #[Route('/admin', name: 'app_admin')]
    public function admin(): Response
    {
        return $this->render('main/admin.html.twig');
    }



}
