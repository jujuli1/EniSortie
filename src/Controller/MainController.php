<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Outing;
use App\Repository\CampusRepository;
use App\Repository\CityRepository;
use App\Repository\OutingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/home', name: 'main_home')]
    public function index(Security $security, CampusRepository $campusRepository): Response
    {

        $campusList = $campusRepository->findAll();

       // $user = $security->getUser();
      //  $idUser = $user->getId();

        return $this->render('main/home.html.twig', [
            'campus' => $campusList,
        ]);
    }

    #[Route('/detailCity/{id}', name: 'app_detail_city')]
    public function detailCity(City $city,CampusRepository $campusRepository,OutingRepository $outingRepository, Security $security, CityRepository $cityRepository, int $id): Response
    {

        $campus = $campusRepository->find($id);
        $outing= $outingRepository->find($id);


        return $this->render('main/detail.html.twig', [
            'city' => $city,
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
