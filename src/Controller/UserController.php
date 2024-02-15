<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\User;
use App\Form\IntroPhoto\ProfilIntroPhotoType;
use App\Form\UserType;
use App\Repository\BureauRepository;
use App\Repository\IntroPhotoRepository;
use App\Repository\PhotoRepository;
use App\Repository\ReferentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;


#[Route("/user")]

class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Cette methode est en charge d'afficher la page Liste des Adhérents
     * 
     * @param UserRepository $userRepository
     * @return Response
     */
    #[Route('/', name: 'user_index', methods: ['GET'])]

    public function index(UserRepository $userRepository): Response
    {
        //On laisse l'accès à cette fonction seulement aux Administrateur.
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $user = $userRepository->findAll();
        //On redirige l'utilisateur sur la page user/index.html.twig.
        return $this->render('user/index.html.twig', [
            'users' => $user
        ]);
    }

    /**
     * Cette méthode est en charge de créer un Utilisateur
     * 
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @param MailerInterface $mailer
     * @return Response
     */

    #[Route('/new', name: 'user_new', methods: ['GET', 'POST'])]

    public function new(Request $request, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer): Response
    {

        //On laisse l'accès à cette fonction seulement aux Administrateurs.
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        //Création d'un nouvel utilisateur
        $user = new User();

        //On récupère le formulaire dans UserType.
        $form = $this->createForm(UserType::class, $user);

        //Gérer le traitement de la saisie du formulaire, on récupère les données depuis la requête
        $form->handleRequest($request);
        //Si le formulaire a été envoyer et est valide
        if ($form->isSubmitted() && $form->isValid()) {

            //On récupère le rôle dans le formulaire
            $role = $user->getRoles();

            $rolerecupere = $role[0];
            // $role = [0 => $rolerecupere];
            $role = [$rolerecupere];

            //Et on l'inscrit correctement en BDD car sinon il s'sincrit mal
            $user->setRoles($role);

            //On recupere les coordonnées que le candidat à l'adhesion a envoyé
            // $adherent = $form->getData();
            $adherent = $user;

            //On recupere les photos envoyées s'il y en a a l'inscription ou a la modification du profil
            $photos = $form->get('photos')->getData();

            //On boucle sur les photos
            foreach ($photos as $photo) {

                //On genere un nouveau no de fichier photo
                $fichier = md5(uniqid()) . '.' . $photo->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $photo->move(
                    $this->getParameter('photo_directory'),
                    $fichier
                );

                //On stocke le nom de la photo dans la bdd
                $phot = new Photo();
                $phot->setName($fichier);
                $user->addPhoto($phot);
            }

            $entityManager = $this->entityManager;

            //On recupere le mot de passe non hashé pour l'envoyer en clair au candidat a l'inscription dans le mail
            $plainpassword = $user->getPassword();

            //Hashe le mot de passe
            $hashed = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashed);


            $entityManager->persist($user);
            $entityManager->flush();

            //Ici nous enverrons automatiquement un mail avec le mot de passe non hashé
            //Pour que le nouvel adhérent puisse s'inscrire avec ses nouveaux identifiants
            $message = (new Email())
                ->from('vrnb2020@velorandonaturebruz.fr')
                //On attribue le destinataire
                ->to($user->getEmail())
                ->subject('Votre adhesion est validee')
                //On créée le message avec la vue twig
                ->html(
                $this->renderView(
                'emails/buletin_valide.html.twig',
                [
                    'adherent' => $adherent,
                    'user' => $user,
                    'password' => $plainpassword,
                        ]
                    )
                );
            //On envoie le message
            $mailer->send($message);

            //On renvoie un message de succes à l'utilisateur pour prévenir de la réussite de la création.
            $this->addFlash('success', 'Votre profil a bien été créé');
            //on redirige l'utilisateur sur la page user/index.html.twig.
            return $this->redirectToRoute('user_index');
        }
        //On envoie les données et l'affichage du formulaire sur la page new.html.twig
        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Cette methode est en charge d'afficher la page profil
     * 
     * @param User $user
     * @param UserRepository $userRepository
     * @return Response
     */

    #[Route('/{id}', name: 'user_show')]

    public function show(EntityManagerInterface $entityManager, Request $request, IntroPhotoRepository $introPhotoRepository, User $user, UserRepository $userRepository): Response
    {

        //Il faut être minimum Adhérent pour avoir accès à cette methode
        $this->denyAccessUnlessGranted("ROLE_USER");
        //On recupère tout de l'adhérent en cours et la photo d'introduction
        $utilisateur = $userRepository->findOneBy(['username' => $user]);
        $photoIntroProfil = $introPhotoRepository->find("1");

        //On recupere le pseudo de l'adhérent en cours et son rôle
        $user1 = $this->getUser()->getUsername();
        $userrole = $this->getUser()->getRoles();
        $utilisateurParDefault = $userRepository->findOneBy(['username' => $user1]);


        //On créer le formulaire
        $formIntroProfil = $this->createForm(ProfilIntroPhotoType::class, $photoIntroProfil);

        $formIntroProfil->handleRequest($request);

        if ($formIntroProfil->isSubmitted() && $formIntroProfil->isValid()) {

            $introPhoto = $formIntroProfil->get('ProfilPhotoIntro')->getData();
            if ($introPhoto != null) {

                $fichier = md5(uniqid()) . '.' . $introPhoto->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $introPhoto->move(
                    $this->getParameter('photo_intro'),
                    $fichier
                );
                $photoIntroProfil->setProfilPhotoIntro($fichier);
            } else {
                $photoIntroProfil->setProfilPhotoIntro($introPhoto);
            }
            $entityManager->persist($photoIntroProfil);
            $entityManager->flush();
        }
        //Si l'id n'est pas le même
        foreach ($this->getUser()->getRoles() as $role) {
            if ($role != "ROLE_ADMIN" and $this->getUser()->getUsername() != $user->getUsername()) {

                $this->addFlash('danger', 'Vous ne pouvez pas voir le profil des autres adhérents !');
                return $this->render('user/show.html.twig', [
                    'user' => $utilisateurParDefault,
                    'user1' => $utilisateurParDefault,
                    'username' => $utilisateurParDefault,
                    'userrole' => $userrole,
                    'photoIntro' => $photoIntroProfil->getProfilPhotoIntro(),
                    'formPhotoIntro' => $formIntroProfil->createView()
                ]);
            } else {  //On envoie les données sur la page show.html.twig.
                return $this->render('user/show.html.twig', [
                    'user' => $user,
                    'user1' => $user1,
                    'username' => $utilisateur,
                    'userrole' => $userrole,
                    'photoIntro' => $photoIntroProfil->getProfilPhotoIntro(),
                    'formPhotoIntro' => $formIntroProfil->createView(),

                ]);
            }
        }
        // Ajouter un return par défaut si aucun des cas dans la boucle foreach n'est satisfait
        return new Response('Erreur 404: Page non trouvée', 404);
    }

    /**
     * Cette methode est en charge de modifier un Utilisateur en tant qu'Administrateur
     * 
     * @param Request $request
     * @param User $user
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */

    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]

    public function edit(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, int $id): Response
    {
        // Récupérer l'utilisateur à modifier en fonction de l'ID passé dans l'URL
        $user = $entityManager->getRepository(User::class)->find($id);

        // Vérifier si l'utilisateur en cours est autorisé à accéder à cette fonctionnalité
        $this->denyAccessUnlessGranted("ROLE_ADMIN");

        // Créer le formulaire en utilisant l'utilisateur à modifier
        $form = $this->createForm(UserType::class, $user);


        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hasher le mot de passe s'il a été modifié
            if ($form->get('password')->getData()) {
                $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));
            }

            // Enregistrer les modifications dans la base de données
            $entityManager->flush();

            // Ajouter un message de succès
            $this->addFlash('success', 'Profil modifié avec succès.');

            // Rediriger l'utilisateur vers la page de détail du profil (ou une autre page appropriée)
            return $this->redirectToRoute('user_index');
        }

        // Afficher le formulaire de modification de profil
        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
    /**
     * Cette méthode est en charge de supprimer un Utilisateur
     * 
     * @param Request $request
     * @param User $user
     * @param PhotoRepository $photoRepository
     * @return Response
     */
    #[Route('delete_adherent/{id}', name: 'supprimer_user')]


    public function supprimerAdherent(Request $request, User $user, PhotoRepository $photoRepository, EntityManagerInterface $entityManager): Response
    {
        //On laisse l'accès à cette fonction seulement aux Administrateur.
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $photo = $photoRepository->findOneBy(['adhherent' => $user]);

        while ($photo != null) {
            $nomphoto = $photo->getName();
            $photoexist = $this->getParameter('photo_directory') . '/' . $nomphoto;

            if ($photoexist) {
                unlink($photoexist);
            }
            $entityManager->remove($photo);
            $entityManager->flush();
            $photo = $photoRepository->findOneBy(['adhherent' => $user]);
        }

        $entityManager->remove($user);
        $entityManager->flush();
        //On renvoie un message de succes à l'utilisateur pour prévenir de la réussite de la suppresion.
        $this->addFlash('success', 'Le profil est bien supprimé');
        //On redirige l'utilisateur sur la page user/index.html.twig.
        return $this->redirectToRoute('user_index');
    }

    /**
     * Cette methode est en charge de supprimer son image profil
     * 
     * @param Photo $photo
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */

    #[Route('/supprime/photo/{id}', name: 'user_delete_photo', methods: ['POST'])]

    public function deleteImage(Photo $photo, Request $request, EntityManagerInterface $entityManager)
    {
        //Il faut être minimum Adhérent pour avoir accès a cette methode
        $this->denyAccessUnlessGranted("ROLE_USER");

        $data = json_decode($request->getContent(), true);

        //On vérifie si le token est valide
        if ($this->isCsrfTokenValid('DELETE' . $photo->getId(), $data['_token'])) {

            //On récupère le nom de l'image
            $nom = $photo->getName();

            //On supprime le fichier
            unlink($this->getParameter('photo_directory') . '/' . $nom);

            //On supprime l'entrée de la base
            $entityManager->remove($photo);
            $entityManager->flush();

            //On répond en json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }

    /**
     * Cette methode est en charge modifier son propre profil
     * 
     * @param $id
     * @param UserRepository $userRepository
     * @param Request $request
     * @param User $user
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */

    #[Route('/profiledit/{id}', name: 'profiledit')]

    public function profiledit($id, UserRepository $userRepository, Request $request, User $user, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        // Récupérer le pseudo de l'adhérent en cours
        $user1 = $this->getUser()->getUsername();

        // Utiliser un formulaire pour le profil actuel de l'utilisateur
        $form = $this->createForm(UserType::class, $user);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        // Récupérer l'utilisateur en session
        $userInSession = $userRepository->findOneBy(["username" => $this->getUser()->getUsername()]);
        // $userTest = $userRepository->find($id);

        // Si l'ID existe mais ne correspond pas au pseudo de l'utilisateur en session
        if ((int)$id !== $userInSession->getId()) {
            $this->addFlash('danger', 'Vous ne pouvez pas modifier le profil des autres adhérents !');
            return $this->redirectToRoute('home1'); // Rediriger vers une autre page, par exemple la page d'accueil
        }

        // if ($this->getUser()->getUsername() != $user->getUsername()) {
        //     $utilisateurParDefault = $userRepository->findOneBy(['username' => $user1]);
        //     $formRefresh = $this->createForm(UserType::class, $utilisateurParDefault);
        //     $this->addFlash('danger', 'Vous ne pouvez pas modifier le profil des autres adhérents !');
        //     return $this->render('user/profiledit.html.twig', [
        //         'user' => $utilisateurParDefault,
        //         'form' => $formRefresh->createView(),
        //         $id => $utilisateurParDefault->getId()
        //     ]);
        // }
        if ($form->isSubmitted() && $form->isValid()) {

            //Hashe le mot de passe
            $hashed = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashed);

            //On recupere les photos envoyées
            $photos = $form->get('photos')->getData();

            // Boucler sur les photos
            foreach ($photos as $photo) {

                // Générer un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $photo->guessExtension();
                
                // Copier le fichier dans le dossier photo-profil
                $photo->move(
                    $this->getParameter('photo_directory'),
                    $fichier
                );

                // Stocker le nom de la photo dans la base de données
                $phot = new Photo();
                $phot->setName($fichier);
                $user->addPhoto($phot);
            }

            $entityManager->flush();

            // Ajouter un message de succès pour informer l'utilisateur de la réussite de la modification
            $this->addFlash('success', 'Votre profil a bien été modifié !!');

            //On redirige l'utilisateur sur la page home/index.html.twig
            return $this->redirectToRoute('home1');
        }
        //On envoie les données et l'affichage du formulaire sur la page profiledit.html.twig.
        return $this->render('user/profiledit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'user1' => $user1,
        ]);
    }
}
