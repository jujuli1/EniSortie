<?php

namespace App\Controller;

class TestController
{
    #[Route('/outing/add', name: 'outing_add')]
    public function createOuting(
        Request $request,
        EntityManagerInterface $entityManager,
        StatusRepository $statusRepository
    ): Response {
        $outing = new Outing();

        // Crée le formulaire basé sur OutingType
        $form = $this->createForm(OutingType::class, $outing);

        // Lie la requête au formulaire
        $form->handleRequest($request);

        // Vérifie si soumis + valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Ajouter l'organisateur (ex: user connecté)
            $outing->setOrganizer($this->getUser());

            // Définir un statut par défaut (ex: "Créée")
            $status = $statusRepository->findOneBy(['name' => 'Créée']);
            $outing->setStatus($status);

            // Sauvegarde en base
            $entityManager->persist($outing);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie créée avec succès !');

            return $this->redirectToRoute('outing_list');
        }

        return $this->render('outing/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}