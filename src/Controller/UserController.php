<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
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

    #[IsGranted('ROLE_USER')]
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

    #[IsGranted('ROLE_USER')]
    #[Route('/updateUser', name: 'update_user')]

    public function updateUser(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();

        if(!$user){
            throw $this->createNotFoundException('Oooppps! User not found !');
        }

        $userForm = $this->createForm(UserFormType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $plainPassword = $userForm->get('plainPassword')->getData();
            $password = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($password);

            $image = $userForm->get('userImage')->getData();
            $newFileName = uniqid() . '.' . $image->guessExtension();
            $image->move($this->getParameter('userImages_dir'), $newFileName);
            $user->setUserImage($newFileName);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'User profile updated !');
            return $this->redirectToRoute('detail');
        }

        return $this->render('user/update.html.twig', [
            'userForm' => $userForm
        ]);
    }
}
