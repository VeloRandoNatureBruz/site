<?php

namespace App\Controller;

use App\Entity\Referent;
use App\Form\ReferentType;
use App\Repository\ReferentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ReferentController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Cette méthode sert à rediriger l'utilisateur sur la page Gestion referent et affiche tous les référents.
     * 
     * @param ReferentRepository $referentRepository
     * @return Response
     */

    #[Route('/referent', name: 'referent')]

    public function referent(ReferentRepository $referentRepository): Response
    {
        //On récupère les toutes les données de la table referent avec la méthode
        //On envoie les données  sur la page referent.html.twig.
        return $this->render('referent/referent.html.twig', [
            'referents' => $referentRepository->findReferent(),
        ]);
    }

    /**
     * Cette méthode sert à créer un référent
     *
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param $referentRepository
     * @return Response
     */

    #[Route ('/createRef', name : 'createRef')]

    public function createReferent(EntityManagerInterface $entityManager, Request $request, ReferentRepository $referentRepository): Response
    {
        //On refuse l'accès a cette méthode a l'utilisateur si l'utilisateur n'a pas le rôle Admin.
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        //On créer une nouvelle instance de l'objet Referent et on le stock dans la variable $referent.
        $referent = new Referent();
        //On créer notre formulaire.
        $form = $this->createForm(ReferentType::class, $referent);
        //On récupère les information saisi.
        $form->handleRequest($request);
        //Si le formulaire a bien été envoyer et qu'il est valide ...
        if ($form->isSubmitted() && $form->isValid()) {

            //On envoie les informations a la base de donnée.
            $entityManager->persist($referent);
            $entityManager->flush();
            //On renvoie un message de success a l'utilisateur pour prévenir de la réussite.
            $this->addFlash('success', 'Un nouveau référent a été créé');
            //On redirige l'utilisateur sur la page referent.html.twig
            return $this->redirectToRoute('referent');
        }
        //On envoie les données sur la page newref.html.twig.
        return $this->render('referent/newref.html.twig', [
            'referent' => $referent,
            'referents' => $referentRepository->findReferentByOrder(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * Cette méthode sert à modifier un référent.
     * 
     * @param Request $request
     * @param Referent $referent
     * @return Response
     */

    #[Route ('/updateRef/{id}', name : 'updateRef')]

    public function updateReferent(EntityManagerInterface $entityManager, Request $request, Referent $referent, ReferentRepository $referentRepository): Response
    {

        //On refuse l'accès a cette méthode a l'utilisateur si l'utilisateur n'a pas le rôle Admin.
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        //On créer notre formulaire.
        $form = $this->createForm(ReferentType::class, $referent);
        //On récupère les information saisi.
        $form->handleRequest($request);
        //Si le formulaire a bien été envoyer et qu'il est valide ...
        if ($form->isSubmitted() && $form->isValid()) {
            //On envoie les informations modifiées a la base de donnée.
            $entityManager->flush();
            //On renvoie un message de success a l'utilisateur pour prévenir de la réussite.
            $this->addFlash('success', 'Le référent a été modifié');
            //On redirige l'utilisateur sur la page referent.html.twig
            return $this->redirectToRoute('referent');
        }
        //On envoie les données sur la page editref.html.twig.
        return $this->render('referent/editref.html.twig', [
            'referent' => $referent,
            'referents' => $referentRepository->findReferentByOrder(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * Cette méthode sert à supprimer un référent.
     * 
     * @param Referent $referent
     * @return Response
     */

    #[Route ('/deleteRef/{id}', name : 'deleteRef')]

    public function deleteReferent(EntityManagerInterface $entityManager, Referent $referent): Response
    {
        #fonction qui supprime un référent#

        //On refuse l'accès a cette méthode a l'utilisateur si l'utilisateur n'a pas le rôle Admin.
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //On supprime le referent.
        $entityManager->remove($referent);
        $entityManager->flush();
        //On renvoie un message de success à l'utilisateur pour prévenir de la réussite.
        $this->addFlash('success', 'Le référent a été supprimé');
        //On envoie les données sur la page referent.html.twig.
        return $this->redirectToRoute('referent');
    }
}
