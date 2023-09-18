<?php

namespace App\Controller;


use App\Repository\UserRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ChangePasswordFormType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class PasswordController extends AbstractController
{
    /**
     * @Route("/password", name="password")
     */
    public function index(): Response
    {
        return $this->render('password/index.html.twig', [
            'controller_name' => 'PasswordController',
        ]);
    }

    /**
     * @Route ("/request", name="app_forgot_password_request")
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Swift_Mailer $mailer
     * @param EntityManagerInterface $em
     * @return Response
     * @throws Exception
     *
     * Cette méthode sert a nous envoyer un mot de passe qd l'adhérent à oublié le sien
     *
     */

    public function request(Request $request, UserRepository $userRepository,
                            UserPasswordEncoderInterface $passwordEncoder,
                            Swift_Mailer $mailer, EntityManagerInterface $em): Response
    {

        #nombre aléatoire à 8 chiffres
        $random = random_int(10000000, 99999999);


        //on verifie si l'utilisateur est bien enregistré en bdd.
        if ($utilisateur = $userRepository->findOneBy(['email' => $request->request->get('mail')])) {
            //on checke son pseudo.
            $utilsateurpseudo = $utilisateur->getUsername();
            $this->em = $em;

            //on checke son mail.
            $utilisateur1 = $utilisateur->getEmail();
            if ($utilisateur1) {

                //ici nous enverrons le mail avec le nouveau mdp aléatoire en utilisant un template emails.
                $message = (new Swift_Message('Réinitialisation du mot de passe'))
                    ->setFrom('vrnb2020@velorandonaturebruz.fr')
                    ->setTo($utilisateur1)
                    ->setBody(
                        $this->renderView(
                            'emails/change_mdp.html.twig', [
                                'random' => $random,
                                'utilisateurpseudo' => $utilsateurpseudo,
                            ]
                        ), 'text/html'
                    );

                #on envoie le mail
                $mailer->send($message);

                //on encode le mot de passe généré aléatoirement pour le mettre en bdd.
                $encodePassword = $passwordEncoder->encodePassword($utilisateur, $random);
                $utilisateur->setPassword($encodePassword);
                $this->em->flush();
                //On renvoie un message de success pour prévenir l'utilisateur de la réussite.
                $this->addFlash('success',"Un nouveau mot de passe vous a été envoyé par mail. Si besoin, veuillez vérifier vos spams" );

                return $this->redirectToRoute('home1', ['id' => $utilisateur->getId()]);
            } elseif ($request->isMethod('POST')) {
                $this->redirectToRoute('app_login');


            }
        } else
            return $this->render('user/reset_password/request.html.twig');

    }


    /**
     * @Route("/reset/{id}", name="app_reset_password", requirements={"id": "\d+"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param User $utilisateur
     * @param EntityManagerInterface $em
     * @return Response
     *
     * cette méthode est pour changer de mot de passe
     *
     */
    public function reset(Request $request, UserPasswordEncoderInterface $passwordEncoder,
                          User $utilisateur, EntityManagerInterface $em): Response
    {

        //On refuse l'accès a cette méthode si l'utilisateur n'a pas le rôle User.
        $this->denyAccessUnlessGranted("ROLE_USER");


        $this->em = $em;
        //On créer notre formulaire.
        $form = $this->createForm(ChangePasswordFormType::class);
        //On récupère les information saisi.
        $form->handleRequest($request);
        //Si le formulaire a bien été envoyer et qu'il est valide ...
        if ($form->isSubmitted() && $form->isValid()) {

            //on Encode le plain password, et on l'enregistre.
            $encodedPassword = $passwordEncoder->encodePassword(
                $utilisateur,
                $form->get('plainPassword')->getData()
            );

            $utilisateur->setPassword($encodedPassword);
            $this->em->flush();
            //On renvoie un message de success a l'utilisateur pour prévenir de la réussite.
            $this->addFlash('success', 'Votre mot de passe a bien été réinitialisé');
            //On redirige l'utilisateurs sur la page activite/index.html.twig (Programme)
            return $this->redirectToRoute('activite_index');
        }
        //On envoie les données sur la page reset.html.twig.
        return $this->render('user/reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
            'utilisateur' => $utilisateur,
        ]);
    }

}
