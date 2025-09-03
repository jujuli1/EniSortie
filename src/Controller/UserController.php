<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
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
    public function logout(): void
    {

    }

    #[Route('/detailUser', name: 'detail')]

    public function detail(): Response
    {
        $user = $this->getUser();

        if(!$user){
            throw $this->createNotFoundException('Oooppps! User not found !');
        }


        return $this->render('user/detail.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/updateUser', name: 'update_user')]

    public function updateUser(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();

        if(!$user){
            throw $this->createNotFoundException('Oooppps! User not found !');
        }
        $userForm = $this->createForm(UserFormType::class, $user);
        $userForm->handleRequest($request);
        return $this->render('user/update.html.twig');
    }
}
