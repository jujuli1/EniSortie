<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Utilisateur;
use App\Form\CampusSearchType;

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
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class MainController extends AbstractController
{
    #[Route('/', name: 'main_home')]
    public function home(CampusRepository $campusRepository): Response
    {


        return $this->render('main/home.html.twig', [

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
            'user' => $user
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
}
