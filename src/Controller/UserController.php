<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InscriptionCSVType;
use App\Form\UserFormType;
use App\Repository\OutingRepository;
use App\Repository\StatusRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use function PHPUnit\Framework\throwException;

class UserController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils,
                          ): Response
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

    #[Route('/logout', name: 'app_logout')]
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
    #[Route('/afficheUser/{id}', name: 'afficheUser', requirements: ['id' => '\d+'])]

    public function afficheUser(int $id, UtilisateurRepository $repository): Response
    {
        $user = $repository->find($id);

        if(!$user){
            throw $this->createNotFoundException('Oooppps! User not found !');
        }

        return $this->render('user/detail.html.twig', [
            'user' => $user
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/updateUser', name: 'update_user')]

    public function updateUser(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response
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

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/inscriptionCSV', name: 'inscriptionCSV')]

    public function inscriptionCSV(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager)
    {
        $userConnected = $this->getUser();
        if (!$userConnected) {
            return $this->redirectToRoute('app_login');
        }

        $csvForm = $this->createForm(InscriptionCSVType::class);
        $csvForm->handleRequest($request);

        if ($csvForm->isSubmitted() && $csvForm->isValid()) {

            $file = $csvForm->get('fichier')->getData();
            $campus = $csvForm->get('campus')->getData();
            $role = $csvForm->get('roles')->getData();

            // Open the file
            if (($handle = fopen($file->getPathname(), "r")) !== false) {
                // Read and process the lines.
                // Skip the first line if the file includes a header

                while (($data = fgetcsv($handle, 80, ";")) !== false) {
                    // Do the processing: Map line to entity, validate if needed

                    if ($data[0] == "pseudo") {
                        continue;
                    }
                    $user = new Utilisateur();
                    // Assign fields

                    $user->setPseudo($data[0]);
                    $user->setLastName($data[1]);
                    $user->setFirstName($data[2]);
                    $user->setEmail($data[3]);
                    $user->setPhoneNumber($data[4]);

                    $plainPassword = $csvForm->get('plainPassword')->getData();
                    $password = $passwordHasher->hashPassword($user, $plainPassword);
                    $user->setPassword($password);

                    $user->setActif(true);
                    $user->setRoles($role);
                    $user->setCampus($campus);
                    $entityManager->persist($user);

                }
                fclose($handle);
                $entityManager->flush();

                $this->addFlash('success', 'User profile created !');
                return $this->redirectToRoute('main_home');
            }

            return $this->render('user/inscriptionCSV.html.twig', [
                'csvForm' => $csvForm
            ]);
        }
        return $this->render('user/inscriptionCSV.html.twig', [
            'csvForm' => $csvForm
        ]);
    }

}
