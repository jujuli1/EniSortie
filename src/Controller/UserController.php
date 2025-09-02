<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
           return $this->redirectToRoute('main_home');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {

        return $this->render('main/home.html.twig');



    }

    #[Route('detail/{id}', name: 'detail', requirements: ['id'=>'\d+'])]

    public function detail(int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        if(!$user){
            throw $this->createNotFoundException('Oooppps! User not found !');
        }


        return $this->render('user/detail.html.twig', [
            'user' => $user
        ]);
    }
}
