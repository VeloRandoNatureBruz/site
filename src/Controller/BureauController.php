<?php

namespace App\Controller;

use App\Entity\Bureau;
use App\Form\BureauType;
use App\Repository\BureauRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class BureauController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/bureau', name: 'bureau')]
    public function bureau(BureauRepository $bureauRepository): Response
    {
        return $this->render('bureau/bureau.html.twig', [
            'bureaux' => $bureauRepository->findAll(),
        ]);
    }

    #[Route('/createBureau', name: 'createBureau')]
    public function createBureau(Request $request, BureauRepository $bureauRepository): Response
    {
        // Vérifie si l'utilisateur a le rôle administrateur
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // Crée une nouvelle instance de Bureau
        $bureau = new Bureau();
        // Crée un formulaire pour l'entité Bureau
        $form = $this->createForm(BureauType::class, $bureau);
        // Gère la soumission du formulaire
        $form->handleRequest($request);
        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Persiste l'entité Bureau dans la base de données
            $this->entityManager->persist($bureau);
            $this->entityManager->flush();
            // Ajoute un message flash pour notifier la création réussie
            $this->addFlash('success', 'Un nouveau bureau a été créé');
            // Redirige vers la page de liste des bureaux
            return $this->redirectToRoute('bureau');
        }
        // Rend la vue du formulaire de création de bureau
        return $this->render('bureau/newBureau.html.twig', [
            'bureau' => $bureau,
            'bureaux' => $bureauRepository->findBureauxByOrder(),
            'form' => $form->createView(),
        ]);
    }


    #[Route('/updateBureau/{id}', name: 'updateBureau')]
    public function updateBureau(Request $request, Bureau $bureau, BureauRepository $bureauRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(BureauType::class, $bureau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Le bureau a été modifié');

            return $this->redirectToRoute('bureau');
        }

        return $this->render('bureau/editBureau.html.twig', [
            'bureau' => $bureau,
            'bureaux' => $bureauRepository->findBureauxByOrder(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/deleteBureau/{id}', name: 'deleteBureau')]
    public function deleteBureau(Bureau $bureau): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $this->entityManager->remove($bureau);
        $this->entityManager->flush();
        $this->addFlash('success', 'Le bureau a été supprimé');

        return $this->redirectToRoute('bureau');
    }

    #[Route('/choixRefBureau', name: 'choixRefBureau')]
    public function choixCreation(): Response
    {
        return $this->render('choixCreationRefBureau.html.twig');
    }
}
