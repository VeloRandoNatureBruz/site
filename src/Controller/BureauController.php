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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $bureau = new Bureau();
        $form = $this->createForm(BureauType::class, $bureau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($bureau);
            $this->entityManager->flush();
            $this->addFlash('success', 'Un nouveau bureau a été créé');

            return $this->redirectToRoute('bureau');
        }

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
