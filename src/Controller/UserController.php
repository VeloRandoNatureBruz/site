<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\User;
use App\Form\IntroPhoto\ProfilIntroPhotoType;
use App\Form\UserType;
use App\Repository\IntroPhotoRepository;
use App\Repository\PhotoRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     * @param UserRepository $userRepository
     * @return Response
     *
     *
     * Cette methode est en charge d'afficher la page Liste des Adhérents
     *
     */
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
     * @Route("/new", name="user_new", methods={"GET","POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param Swift_Mailer $mailer
     * @return Response
     *
     *
     * Cette methode est en charge de créer un Utilisateur
     *
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder, Swift_Mailer $mailer): Response
    {

        //On laisse l'accès à cette fonction seulement aux Administrateur.
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        //Creation d'un nouvel utilisateur
        $user = new User();

        //On récupère le formulaire dans UserType.
        $form = $this->createForm(UserType::class, $user);

        //Gere le traitement de la saisie du formulaire, on récupère les données depuis la requête
        $form->handleRequest($request);
        //Si le formulaire a été envoyer et est valide ...
        if ($form->isSubmitted() && $form->isValid()) {

            //On recupere le role dans le formulaire ...
            $role = $user->getRoles();

            $rolerecupere = $role[0];
            $role = [0 => $rolerecupere];

            //Et on l'inscrit correctement en BDD car sinon il s'sincrit mal
            $user->setRoles($role);

            //On recupere les coordonnées que le candidat à l'adhesion a envoyé
            $adherent = $form->getData();

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

            $entityManager = $this->getDoctrine()->getManager();

            //On recupere le mot de passe non hashé pour l'envoyer en clair au candidat a l'inscription dans le mail
            $plainpassword = $user->getPassword();

            //Hashe le mot de passe
            $hashed = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashed);


            $entityManager->persist($user);
            $entityManager->flush();

            //Ici nous enverrons automatiquement un mail avec le mot de passe non hashé
            //Pour que le nouvel adhérent puisse s'inscrire avec ses nouveaux identifiants
            $message = (new Swift_Message('Votre adhesion est validee'))
                ->setFrom('vrnb2020@velorandonaturebruz.fr')

                //On attribue le destinataire
                ->setTo($user->getEmail())

                //On créée le message avec la vue twig
                ->setBody(
                    $this->renderView(
                        'emails/buletin_valide.html.twig', [
                            'adherent' => $adherent,
                            'user' => $user,
                            'password' => $plainpassword,]
                    ), 'text/html'
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
     * @Route("/{id}", name="user_show")
     * @param User $user
     * @param UserRepository $userRepository
     * @return Response
     *
     *
     * Cette methode est en charge d'afficher la page profil
     *
     */
    public function show(EntityManagerInterface $entityManager, Request $request, IntroPhotoRepository $introPhotoRepository, User $user, UserRepository $userRepository): Response
    {

        //Il faut être minimum Adhérent pour avoir accès a cette methode
        $this->denyAccessUnlessGranted("ROLE_USER");
        //On recupere tout de l'adhérent en cours et la photo d'introduction
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
            if ($role != "ROLE_ADMIN" AND $this->getUser()->getUsername() != $user->getUsername()) {

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

    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @param Request $request
     * @param User $user
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     *
     *
     * Cette methode est en charge de modifier un Utilisateur en tant que Administrateur
     *
     */
    public
    function    edit(Request $request, User $user, UserPasswordEncoderInterface $encoder): Response
    {

        //Il faut être minimum Administrateur pour avoir accès a cette methode
        $this->denyAccessUnlessGranted("ROLE_ADMIN");

        //On recupere le pseudo de l'adhérent en cours
        $user1 = $this->getUser()->getUsername();

        //Utilisation du formulaire du user
        $form = $this->createForm(UserType::class, $user);

        //Gere le traitement du formulaire
        $form->handleRequest($request);

        //Si le formulaire a été envoyer et est valide ...
        if ($form->isSubmitted() && $form->isValid()) {


            //Hashe le mot de passe
            $hashed = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashed);

            //On recupere les photos envoyées
            $photos = $form->get('photos')->getData();
            dump($photos);

            //On boucle sur les photos
            foreach ($photos as $photo) {

                //On genere un nouveau no de fichier
                $fichier = md5(uniqid()) . '.' . $photo->guessExtension();
                dump($fichier);

                //Copie le fichier dans le dossier photo-profil
                $photo->move(
                    $this->getParameter('photo_directory'),
                    $fichier
                );

                //On stocke le nom de la photo dans la bdd
                $phot = new Photo();
                $phot->setName($fichier);
                $user->addPhoto($phot);
            }


            $this->getDoctrine()->getManager()->flush();
            //On renvoie un message de succes à l'utilisateur pour prévenir de la réussite de la modification.
            $this->addFlash('success', 'Le profil a bien été modifié !!');
            //On redirige l'utilisateur sur la page user/index.html.twig.
            return $this->redirectToRoute('user_index');
        }
        //On renvoie les données et l'affichage du formulaire sur la page edit.html.twig.
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'user1' => $user1
        ]);
    }

    /**
     * @Route("delete_adherent/{id}", name="supprimer_user")
     * @param Request $request
     * @param User $user
     * @param PhotoRepository $photoRepository
     * @return Response
     *
     * Cette méthode est en charge de supprimer un Utilisateur
     */

    public
    function supprimerAdherent(Request $request, User $user, PhotoRepository $photoRepository): Response
    {
        //On laisse l'accès à cette fonction seulement aux Administrateur.
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $photo = $photoRepository->findOneBy(['adhherent' => $user]);

        $entityManager = $this->getDoctrine()->getManager();

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
        return $this->redirectToRoute('home1');
    }


    /**
     * @Route("/supprime/photo/{id}", name="user_delete_photo", methods={"POST"})
     * @param Photo $photo
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     *
     *
     * Cette methode est en charge de supprimer son image profil
     *
     */
    public
    function deleteImage(Photo $photo, Request $request)
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
            $em = $this->getDoctrine()->getManager();
            $em->remove($photo);
            $em->flush();

            //On répond en json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
        //On redirige l'utilisateur sur la page user/index.html.twig.
        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route ("/profiledit/{id}", name="profiledit")
     * @param $id
     * @param UserRepository $userRepository
     * @param Request $request
     * @param User $user
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * Cette methode est en charge modifier son propre profil
     */

    public
    function profiledit($id, UserRepository $userRepository, Request $request, User $user, UserPasswordEncoderInterface $encoder): Response
    {
        $userInSession = $userRepository->findOneBy(["username" => $this->getUser()->getUsername()]);
        $userTest = $userRepository->find($id);
        $form = $this->createForm(UserType::class, $user);
        //Si l'id existe mais qu'il ne correspond pas au pseudo de l'utilisateur en session


        $this->denyAccessUnlessGranted("ROLE_USER");

        #On recupere le pseudo de l'adhérent en cours
        $user1 = $this->getUser()->getUsername();

        #Utilisation du formulaire de la classe user#

        #Gere le traitement du formulaire
        $form->handleRequest($request);
        if ($this->getUser()->getUsername() != $user->getUsername()) {
            $utilisateurParDefault = $userRepository->findOneBy(['username' => $user1]);
            $formRefresh = $this->createForm(UserType::class, $utilisateurParDefault);
            $this->addFlash('danger', 'Vous ne pouvez pas modifier le profil des autres adhérents !');
            return $this->render('user/profiledit.html.twig', [
                'user' => $utilisateurParDefault,
                'form' => $formRefresh->createView(),
                $id => $utilisateurParDefault->getId()
            ]);
        }
        if ($form->isSubmitted() && $form->isValid()) {


            //Hashe le mot de passe
            $hashed = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashed);

            //On recupere les photos envoyées
            $photos = $form->get('photos')->getData();
            dump($photos);
            //On boucle sur les photos
            foreach ($photos as $photo) {

                //On genere un nouveau no de fichier
                $fichier = md5(uniqid()) . '.' . $photo->guessExtension();
                dump($fichier);
                //Copie le fichier dans le dossier photo-profil
                $photo->move(
                    $this->getParameter('photo_directory'),
                    $fichier
                );

                //On stocke le nom de la photo dans la bdd
                $phot = new Photo();
                $phot->setName($fichier);
                $user->addPhoto($phot);
            }


            $this->getDoctrine()->getManager()->flush();
            //On renvoie un message de succes à l'utilisateur pour prévenir de la réussite de la modification.
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


