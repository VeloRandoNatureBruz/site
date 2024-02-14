<?php

namespace App\Controller;

use App\Form\PdfStatusType;
use App\Entity\PhotoCarousel;
use App\Repository\UserRepository;
use App\Repository\ActiviteRepository;
use App\Form\AccueilText\FifthTextType;
use App\Form\AccueilText\FirstTextType;
use App\Form\AccueilText\SixthTextType;
use App\Form\AccueilText\ThirdTextType;
use App\Repository\ActualiteRepository;
use App\Form\AccueilText\FourthTextType;
use App\Form\AccueilText\SecondTextType;
use App\Form\ActiviteText\AutreFormType;
use App\Form\ActiviteText\MecaniqueType;
use App\Repository\IntroPhotoRepository;
use App\Repository\PhotoAlbumRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ActiviteText\AutresTextType;
use App\Form\ActiviteText\BaladeTextType;
use App\Repository\TextAccueilRepository;
use App\Form\PresentationText\TextOneType;
use App\Form\PresentationText\TextSixType;
use App\Form\PresentationText\TextTwoType;
use App\Form\ActiviteText\EscapadeFormType;
use App\Form\ActiviteText\SecuriteTextType;
use App\Form\PresentationText\TextFiveType;
use App\Form\PresentationText\TextFourType;
use App\Form\PresentationText\TitleOneType;
use App\Form\PresentationText\TitleTwoType;
use App\Form\IntroPhoto\AutreIntroPhotoType;
use App\Form\PresentationText\TextThreeType;
use App\Form\PresentationText\TitleFourType;
use App\Form\ActiviteText\RandoveloTextIntro;
use App\Form\ActiviteText\SecourismeTextType;
use App\Form\PresentationText\TitleThreeType;
use App\Repository\ActiviteContentRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EtiquetteContentRepository;
use App\Repository\TextPresentationRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ActiviteText\PhotosVideosTextType;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\IntroPhoto\FormationIntroPhotoType;
use App\Form\IntroPhoto\RandoVeloIntroPhotoType;
use App\Form\AccueilEtiquette\FirstEtiquetteType;
use App\Form\AccueilEtiquette\ThirdEtiquetteType;
use App\Form\ActiviteText\EcocitoyenneteFormType;
use App\Form\ActiviteText\EcocitoyenneteTextType;
use App\Form\ActiviteText\ProjectionFilmFormType;
use App\Form\ActiviteText\ProjectionfilmTextType;
use App\Form\AccueilEtiquette\FourthEtiquetteType;
use App\Form\AccueilEtiquette\SecondEtiquetteType;
use App\Form\IntroPhoto\OrganisationIntroPhotoType;
use App\Form\IntroPhoto\PresentationIntroPhotoType;
use App\Form\IntroPhoto\EcocitoyenneteIntroPhotoType;
use App\Form\IntroPhoto\ProjectionFilmIntroPhotoType;
use App\Form\ActiviteText\FormationTexteIntroTextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Cette méthode est en charge de rediriger l'utilisateur sur la page accueil lors d'une déconnexion.
     */

    #[Route('/', name: 'hometotal')]

    public function redirige(): Response
    {
        // Redirige vers la page index.html.twig.
        return $this->redirectToRoute('home1');
    }

    //ce controlleur gère les pages fixes du site

    /**
     * Cette méthode est en charge de rediriger l'utilisateur vers la page accueil.
     * 
     * @param ActiviteRepository $activiteRepository
     * @param ActualiteRepository $actualiteRepository
     * @param PhotoAlbumRepository $photoAlbumRepository
     * @return Response
     */

    #[Route('/home', name: 'home1')]

    public function index(EntityManagerInterface $entityManager, Request $request, TextAccueilRepository $textAccueilRepository, EtiquetteContentRepository $etiquetteContentRepository, ActiviteRepository $activiteRepository, ActualiteRepository $actualiteRepository, PhotoAlbumRepository $photoAlbumRepository): Response
    {

        $textAccueil = $textAccueilRepository->find("1");
        $etiquetteContent = $etiquetteContentRepository->find("1");

        //On récupère les formulaires des différents textes modifiables.
        $formFirst = $this->createForm(FirstTextType::class, $textAccueil);
        $formSecond = $this->createForm(SecondTextType::class, $textAccueil);
        $formThird = $this->createForm(ThirdTextType::class, $textAccueil);
        $formFourth = $this->createForm(FourthTextType::class, $textAccueil);
        $formFifth = $this->createForm(FifthTextType::class, $textAccueil);
        $formSixth = $this->createForm(SixthTextType::class, $textAccueil);

        //On récupère les formulaires des différentes étiquettes.
        $formFirstEtiquette = $this->createForm(FirstEtiquetteType::class, $etiquetteContent);
        $formSecondEtiquette = $this->createForm(SecondEtiquetteType::class, $etiquetteContent);
        $formThirdEtiquette = $this->createForm(ThirdEtiquetteType::class, $etiquetteContent);
        $formFourthEtiquette = $this->createForm(FourthEtiquetteType::class, $etiquetteContent);


        //Gere le traitement de la saisie des formulaires des textes modifiables, on récupère les données depuis la requête
        $formFirst->handleRequest($request);
        $formSecond->handleRequest($request);
        $formThird->handleRequest($request);
        $formFourth->handleRequest($request);
        $formFifth->handleRequest($request);
        $formSixth->handleRequest($request);

        //Gere le traitement de la saisie des formulaires des étiquettes, on récupère les données depuis la requête
        $formFirstEtiquette->handleRequest($request);
        $formSecondEtiquette->handleRequest($request);
        $formThirdEtiquette->handleRequest($request);
        $formFourthEtiquette->handleRequest($request);


        //Si le formulaire a été envoyer et est valide pour l'édition de texte
        // Formulaire d'édition de texte 1
        if ($formFirst->isSubmitted() && $formFirst->isValid()) {

            $text = $formFirst->getData();
            $entityManager->persist($text);
            $entityManager->flush();
        }
        // Formulaire d'édition de texte 2
        if ($formSecond->isSubmitted() && $formSecond->isValid()) {

            $text = $formSecond->getData();
            $entityManager->persist($text);
            $entityManager->flush();
        }
        // Formulaire d'édition de texte 3
        if ($formThird->isSubmitted() && $formThird->isValid()) {

            $text = $formThird->getData();
            $entityManager->persist($text);
            $entityManager->flush();
        }
        // Formulaire d'édition de texte 4
        if ($formFourth->isSubmitted() && $formFourth->isValid()) {

            $text = $formFourth->getData();
            $entityManager->persist($text);
            $entityManager->flush();
        }
        // Formulaire d'édition de texte 5
        if ($formFifth->isSubmitted() && $formFifth->isValid()) {

            $text = $formFifth->getData();
            $entityManager->persist($text);
            $entityManager->flush();
        }
        // Formulaire d'édition de texte 6
        if ($formSixth->isSubmitted() && $formSixth->isValid()) {

            $text = $formSixth->getData();
            $entityManager->persist($text);
            $entityManager->flush();
        }


        //Si le formulaire a été envoyer et est valide pour l'édition d'étiquette
        // Formulaire d'édition d'étiquette 1
        if ($formFirstEtiquette->isSubmitted() && $formFirstEtiquette->isValid()) {

            $text = $formFirstEtiquette->get('FirstEtiquetteText')->getData();
            $overlay = $formFirstEtiquette->get('FirstEtiquetteOverlay')->getData();
            $photo = $formFirstEtiquette->get('FirstEtiquettePhoto')->getData();

            if ($photo == null) {
                $etiquetteContent->setFirstEtiquetteText($text);
                $etiquetteContent->setFirstEtiquetteOverlay($overlay);

                $entityManager->persist($etiquetteContent);
                $entityManager->flush();
            } else {
                $fichierFirstEtiquette = md5(uniqid()) . '.' . $photo->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $photo->move(
                    $this->getParameter('photo_activite'),
                    $fichierFirstEtiquette
                );

                $etiquetteContent->setFirstEtiquetteText($text);
                $etiquetteContent->setFirstEtiquetteOverlay($overlay);
                $etiquetteContent->setFirstEtiquettePhoto($fichierFirstEtiquette);
                $entityManager->persist($etiquetteContent);
                $entityManager->flush();
            }
        }
        // Formulaire d'édition d'étiquette 2
        if ($formSecondEtiquette->isSubmitted() && $formSecondEtiquette->isValid()) {

            $text = $formSecondEtiquette->get('SecondEtiquetteText')->getData();
            $photo = $formSecondEtiquette->get('SecondEtiquettePhoto')->getData();

            if ($photo == null) {
                $etiquetteContent->setFirstEtiquetteText($text);

                $entityManager->persist($etiquetteContent);
                $entityManager->flush();
            } else {
                $fichierSecondEtiquette = md5(uniqid()) . '.' . $photo->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $photo->move(
                    $this->getParameter('photo_activite'),
                    $fichierSecondEtiquette
                );

                $etiquetteContent->setSecondEtiquetteText($text);
                $etiquetteContent->setSecondEtiquettePhoto($fichierSecondEtiquette);
                $entityManager->persist($etiquetteContent);
                $entityManager->flush();
            }
        }
        // Formulaire d'édition d'étiquette 3
        if ($formThirdEtiquette->isSubmitted() && $formThirdEtiquette->isValid()) {

            $text = $formThirdEtiquette->get('ThirdEtiquetteText')->getData();
            $photo = $formThirdEtiquette->get('ThirdEtiquettePhoto')->getData();

            if ($photo == null) {
                $etiquetteContent->setThirdEtiquetteText($text);

                $entityManager->persist($etiquetteContent);
                $entityManager->flush();
            } else {
                $fichierThirdEtiquette = md5(uniqid()) . '.' . $photo->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $photo->move(
                    $this->getParameter('photo_activite'),
                    $fichierThirdEtiquette
                );

                $etiquetteContent->setThirdEtiquetteText($text);
                $etiquetteContent->setThirdEtiquettePhoto($fichierThirdEtiquette);
                $entityManager->persist($etiquetteContent);
                $entityManager->flush();
            }
        }
        // Formulaire d'édition d'étiquette 4
        if ($formFourthEtiquette->isSubmitted() && $formFourthEtiquette->isValid()) {

            $text = $formFourthEtiquette->get('FourthEtiquetteText')->getData();
            $photo = $formFourthEtiquette->get('FourthEtiquettePhoto')->getData();

            if ($photo == null) {
                $etiquetteContent->setFourthEtiquetteText($text);

                $entityManager->persist($etiquetteContent);
                $entityManager->flush();
            } else {
                $fichierFourthEtiquette = md5(uniqid()) . '.' . $photo->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $photo->move(
                    $this->getParameter('photo_activite'),
                    $fichierFourthEtiquette
                );

                $etiquetteContent->setFourthEtiquetteText($text);
                $etiquetteContent->setFourthEtiquettePhoto($fichierFourthEtiquette);
                $entityManager->persist($etiquetteContent);
                $entityManager->flush();
            }
        }

        $photoCarousels = $entityManager->getRepository(PhotoCarousel::class)->find(1);

        return $this->render('home/index.html.twig', [

            'photoCarousels' => $photoCarousels,
            'textes' => $textAccueilRepository->findAll(),
            'textesEtiquettes' => $etiquetteContentRepository->findAll(),
            'photos' => $photoAlbumRepository->findBy([], ['id' => 'ASC'], 10),
            'actidispo' => $activiteRepository->affichepastille(),
            'actualites' => $actualiteRepository->afficheactu(),

            //Formulaire d'édition de texte
            'formFirst' => $formFirst->createView(),
            'formSecond' => $formSecond->createView(),
            'formThird' => $formThird->createView(),
            'formFourth' => $formFourth->createView(),
            'formFifth' => $formFifth->createView(),
            'formSixth' => $formSixth->createView(),

            //Formulaire d'édition d'étiquette
            'formFirstEtiquette' => $formFirstEtiquette->createView(),
            'overlayFirstEtiquette' => $etiquetteContent->getFirstEtiquetteOverlay(),
            'photoFirstEtiquette' => $etiquetteContent->getFirstEtiquettePhoto(),
            'formSecondEtiquette' => $formSecondEtiquette->createView(),
            'photoSecondEtiquette' => $etiquetteContent->getSecondEtiquettePhoto(),
            'formThirdEtiquette' => $formThirdEtiquette->createView(),
            'photoThirdEtiquette' => $etiquetteContent->getThirdEtiquettePhoto(),
            'formFourthEtiquette' => $formFourthEtiquette->createView(),
            'photoFourthEtiquette' => $etiquetteContent->getFourthEtiquettePhoto()
        ]);
    }


    /**
     * Cette méthode est en charge de rediriger vers la page Présentation de l'Association.
     */

    #[Route('/presentation', name: 'presentation')]

    public function pres(TextPresentationRepository $textPresentationRepository, IntroPhotoRepository $introPhotoRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Pour le texte et le titre
        $textPresentation = $textPresentationRepository->find("1");
        //On récupère les formulaires des différents textes modifiables
        $formTextOne = $this->createForm(TextOneType::class, $textPresentation);
        $formTextTwo = $this->createForm(TextTwoType::class, $textPresentation);
        $formTextThree = $this->createForm(TextThreeType::class, $textPresentation);
        $formTextFour = $this->createForm(TextFourType::class, $textPresentation);
        $formTextFive = $this->createForm(TextFiveType::class, $textPresentation);
        $formTextSix = $this->createForm(TextSixType::class, $textPresentation);
        $formTitleOne = $this->createForm(TitleOneType::class, $textPresentation);
        $formTitleTwo = $this->createForm(TitleTwoType::class, $textPresentation);
        $formTitleThree = $this->createForm(TitleThreeType::class, $textPresentation);
        $formTitleFour = $this->createForm(TitleFourType::class, $textPresentation);
        //Gere le traitement de la saisie des formulaires des textes modifiables, on récupère les données depuis la requête
        $formTextOne->handleRequest($request);
        $formTextTwo->handleRequest($request);
        $formTextThree->handleRequest($request);
        $formTextFour->handleRequest($request);
        $formTextFive->handleRequest($request);
        $formTextSix->handleRequest($request);
        $formTitleOne->handleRequest($request);
        $formTitleTwo->handleRequest($request);
        $formTitleThree->handleRequest($request);
        $formTitleFour->handleRequest($request);
        //Si le formulaire a été envoyé et est valide pour l'édition de texte
        // Formulaire d'édition de texte 1
        if ($formTextOne->isSubmitted() && $formTextOne->isValid()) {
            $text = $formTextOne->getData();
            $entityManager->persist($text);
            $entityManager->flush();
        }
        // Formulaire d'édition de texte 2
        if ($formTextTwo->isSubmitted() && $formTextTwo->isValid()) {
            $text = $formTextTwo->getData();
            $entityManager->persist($text);
            $entityManager->flush();
        }
        // Formulaire d'édition de texte 3
        if ($formTextThree->isSubmitted() && $formTextThree->isValid()) {
            $text = $formTextThree->getData();
            $entityManager->persist($text);
            $entityManager->flush();
        }
        // Formulaire d'édition de texte 4
        if ($formTextFour->isSubmitted() && $formTextFour->isValid()) {
            $text = $formTextFour->getData();
            $entityManager->persist($text);
            $entityManager->flush();
        }
        // Formulaire d'édition de texte 5
        if ($formTextFive->isSubmitted() && $formTextFive->isValid()) {
            $text = $formTextFive->getData();
            $entityManager->persist($text);
            $entityManager->flush();
        }
        // Formulaire d'édition de texte 6
        if ($formTextSix->isSubmitted() && $formTextSix->isValid()) {
            $text = $formTextSix->getData();
            $entityManager->persist($text);
            $entityManager->flush();
        }
        // Formulaire d'édition du titre 1
        if ($formTitleOne->isSubmitted() && $formTitleOne->isValid()) {
            $text = $formTitleOne->getData();
            $entityManager->persist($text);
            $entityManager->flush();
        }
        // Formulaire d'édition du titre 2
        if ($formTitleTwo->isSubmitted() && $formTitleTwo->isValid()) {
            $text = $formTitleTwo->getData();
            $entityManager->persist($text);
            $entityManager->flush();
        }
        // Formulaire d'édition du titre 3
        if ($formTitleThree->isSubmitted() && $formTitleThree->isValid()) {
            $text = $formTitleThree->getData();
            $entityManager->persist($text);
            $entityManager->flush();
        }
        // Formulaire d'édition du titre 4
        if ($formTitleFour->isSubmitted() && $formTitleFour->isValid()) {
            $text = $formTitleFour->getData();
            $entityManager->persist($text);
            $entityManager->flush();
        }

        //Pour la photo
        $photoIntroPresentation = $introPhotoRepository->find("1");
        $formPres = $this->createForm(PresentationIntroPhotoType::class, $photoIntroPresentation);
        $formPres->handleRequest($request);
        if ($formPres->isSubmitted() && $formPres->isValid()) {
            $introPhoto = $formPres->get('PresentationPhotoIntro')->getData();
            if ($introPhoto != null) {
                $fichier = md5(uniqid()) . '.' . $introPhoto->guessExtension();
                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $introPhoto->move(
                    $this->getParameter('photo_intro'),
                    $fichier
                );
                $photoIntroPresentation->setPresentationPhotoIntro($fichier);
            } else {
                $photoIntroPresentation->setPresentationPhotoIntro($introPhoto);
            }
            $entityManager->persist($photoIntroPresentation);
            $entityManager->flush();
        }

        //Permet de rediriger vers la page présentation.html.twig.
        return $this->render('Association/Presentation.html.twig', [
            'textes' => $textPresentationRepository->findAll(),
            'photoIntro' => $photoIntroPresentation->getPresentationPhotoIntro(),
            'formPhotoIntro' => $formPres->createView(),
            //Formulaire d'édition de texte
            'formTextOne' => $formTextOne->createView(),
            'formTextTwo' => $formTextTwo->createView(),
            'formTextThree' => $formTextThree->createView(),
            'formTextFour' => $formTextFour->createView(),
            'formTextFive' => $formTextFive->createView(),
            'formTextSix' => $formTextSix->createView(),
            'formTitleOne' => $formTitleOne->createView(),
            'formTitleTwo' => $formTitleTwo->createView(),
            'formTitleThree' => $formTitleThree->createView(),
            'formTitleFour' => $formTitleFour->createView()
        ]);
    }

    /**
     * Cette méthode est en charge de rediriger vers la page Organisation bureau.
     * 
     * @param UserRepository $userRepository
     * @return Response
     */

    #[Route('/organisation', name: 'organisation')]

    public function organisation(UserRepository $userRepository, IntroPhotoRepository $introPhotoRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $photoIntroOrganisation = $introPhotoRepository->find("1");

        $formOrg = $this->createForm(OrganisationIntroPhotoType::class, $photoIntroOrganisation);

        $formOrg->handleRequest($request);


        if ($formOrg->isSubmitted() && $formOrg->isValid()) {

            $introPhoto = $formOrg->get('OrganisationPhotoIntro')->getData();
            if ($introPhoto != null) {

                $fichier = md5(uniqid()) . '.' . $introPhoto->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $introPhoto->move(
                    $this->getParameter('photo_intro'),
                    $fichier
                );
                $photoIntroOrganisation->setOrganisationPhotoIntro($fichier);
            } else {
                $photoIntroOrganisation->setOrganisationPhotoIntro($introPhoto);
            }
            $entityManager->persist($photoIntroOrganisation);
            $entityManager->flush();
        }


        //Permet de rediriger vers la page organisation.html.twig (organisation bureau)
        // et permet de gérer l'affichage des adhérent par leur référencement.
        return $this->render('Association/Organisation.html.twig', [
            'photoIntro' => $photoIntroOrganisation->getOrganisationPhotoIntro(),
            'formPhotoIntro' => $formOrg->createView(),
            'users' => $userRepository->orderUserByBureau(),
        ]);
    }

    /**
     * Cette méthode est en charge de rediriger vers la page Organisation bureau.
     *
     * @param UserRepository $userRepository
     * @return Response
     */

    #[Route('/referents', name: 'referents')]

    public function referents(UserRepository $userRepository, IntroPhotoRepository $introPhotoRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $photoIntroOrganisation = $introPhotoRepository->find("1");

        $formOrg = $this->createForm(OrganisationIntroPhotoType::class, $photoIntroOrganisation);

        $formOrg->handleRequest($request);


        if ($formOrg->isSubmitted() && $formOrg->isValid()) {

            $introPhoto = $formOrg->get('OrganisationPhotoIntro')->getData();
            if ($introPhoto != null) {

                $fichier = md5(uniqid()) . '.' . $introPhoto->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $introPhoto->move(
                    $this->getParameter('photo_intro'),
                    $fichier
                );
                $photoIntroOrganisation->setOrganisationPhotoIntro($fichier);
            } else {
                $photoIntroOrganisation->setOrganisationPhotoIntro($introPhoto);
            }
            $entityManager->persist($photoIntroOrganisation);
            $entityManager->flush();
        }


        //Permet de rediriger vers la page organisation.html.twig (organisation bureau)
        // et permet de gérer l'affichage des adhérent par leur référencement.
        return $this->render('Association/Referents.html.twig', [
            'photoIntro' => $photoIntroOrganisation->getOrganisationPhotoIntro(),
            'formPhotoIntro' => $formOrg->createView(),
            'users' => $userRepository->orderUserByReferent(),
        ]);
    }


    /**
     * Cette méthode est en charge de rediriger vers la page Rando Vélos.
     */

    #[Route('/randosvelo', name: 'randosvelo')]

    public function randosvelo(IntroPhotoRepository $introPhotoRepository, EntityManagerInterface $entityManager, Request $request, ActiviteContentRepository $activiteContentRepository): Response
    {
        //Appel des méthodes dans le Repository
        $activiteText = $activiteContentRepository->find("1");
        $photoIntroRandoVelo = $introPhotoRepository->find("1");

        //Création des formulaires
        $introForm = $this->createForm(RandoveloTextIntro::class, $activiteText);
        $baladeForm = $this->createForm(BaladeTextType::class, $activiteText);
        $escapadeForm = $this->createForm(EscapadeFormType::class, $activiteText);
        $formRv = $this->createForm(RandoVeloIntroPhotoType::class, $photoIntroRandoVelo);

        //Récupération des données saisies dans chaques formulaires
        $introForm->handleRequest($request);
        $baladeForm->handleRequest($request);
        $escapadeForm->handleRequest($request);
        $formRv->handleRequest($request);

        //Si le formulaire a été envoyé et est valide ...
        if ($introForm->isSubmitted() && $introForm->isValid()) {
            $text = $introForm->getData();
            $entityManager->persist($text);
            $entityManager->flush();
        }

        //Si le formulaire a été envoyé et est valide ...
        if ($baladeForm->isSubmitted() && $baladeForm->isValid()) {
            $title = $baladeForm->get('BaladeTitle')->getData();
            $text = $baladeForm->get('BaladeText')->getData();
            $photo = $baladeForm->get('BaladePhoto')->getData();
            if ($photo == null) {
                $activiteText->setBaladeTitle($title);
                $activiteText->setBaladeText($text);
                $entityManager->persist($activiteText);
                $entityManager->flush();
            } else {
                $fichierBalade = md5(uniqid()) . '.' . $photo->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $photo->move(
                    $this->getParameter('photo_activite'),
                    $fichierBalade
                );

                $activiteText->setBaladeTitle($title);
                $activiteText->setBaladeText($text);
                $activiteText->setBaladePhoto($fichierBalade);
                $entityManager->persist($activiteText);
                $entityManager->flush();
            }
        }

        //Si le formulaire a été envoyé et est valide ...
        if ($escapadeForm->isSubmitted() && $escapadeForm->isValid()) {
            $title = $escapadeForm->get('EscapadeTitle')->getData();
            $text = $escapadeForm->get('EscapadeText')->getData();
            $photo = $escapadeForm->get('EscapadePhoto')->getData();

            if ($photo == null) {
                $activiteText->setEscapadeTitle($title);
                $activiteText->setEscapadeText($text);
                $entityManager->persist($activiteText);
                $entityManager->flush();
            } else {

                $fichierEscapade = md5(uniqid()) . '.' . $photo->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $photo->move(
                    $this->getParameter('photo_activite'),
                    $fichierEscapade

                );
                $activiteText->setEscapadeTitle($title);
                $activiteText->setEscapadeText($text);
                $activiteText->setEscapadePhoto($fichierEscapade);
                $entityManager->persist($activiteText);
                $entityManager->flush();
            }
        }
        if ($formRv->isSubmitted() && $formRv->isValid()) {

            $introPhoto = $formRv->get('RandoVeloPhotoIntro')->getData();
            if ($introPhoto != null) {

                $fichier = md5(uniqid()) . '.' . $introPhoto->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $introPhoto->move(
                    $this->getParameter('photo_intro'),
                    $fichier
                );
                $photoIntroRandoVelo->setRandoVeloPhotoIntro($fichier);
            } else {
                $photoIntroRandoVelo->setRandoVeloPhotoIntro($introPhoto);
            }
            $entityManager->persist($photoIntroRandoVelo);
            $entityManager->flush();
        }
        //Permet de rediriger vers la page rando-velo.html.twig. ( Rando vélos)
        return $this->render('activite/randos-velo.html.twig', [
            'textes' => $activiteContentRepository->findAll(),
            'formIntro' => $introForm->createView(),
            'formBalade' => $baladeForm->createView(),
            'formEscapade' => $escapadeForm->createView(),
            'photoBalade' => $activiteText->getBaladePhoto(),
            'photoEscapade' => $activiteText->getEscapadePhoto(),
            'photoIntro' => $photoIntroRandoVelo->getRandoVeloPhotoIntro(),
            'formPhotoIntro' => $formRv->createView(),
        ]);
    }


    /**
     * Cette méthode est en charge de rediriger vers la page Formations.
     */

    #[Route('/formations', name: 'formations')]

    public function formations(IntroPhotoRepository $introPhotoRepository, EntityManagerInterface $entityManager, Request $request, ActiviteContentRepository $activiteContentRepository): Response
    {
        //Appel des méthodes dans le Repository
        $activiteText = $activiteContentRepository->find("1");
        $photoIntroFormation = $introPhotoRepository->find("1");

        // Créations des formulaires
        $introForm = $this->createForm(FormationTexteIntroTextType::class, $activiteText);
        $mecaniqueForm = $this->createForm(MecaniqueType::class, $activiteText);
        $securiteForm = $this->createForm(SecuriteTextType::class, $activiteText);
        $secourismeForm = $this->createForm(SecourismeTextType::class, $activiteText);
        $photosVideosForm = $this->createForm(PhotosVideosTextType::class, $activiteText);
        $formIntroFormation = $this->createForm(FormationIntroPhotoType::class, $photoIntroFormation);

        //Récupération des données saisies dans chaque formulaire
        $formIntroFormation->handleRequest($request);
        $introForm->handleRequest($request);
        $mecaniqueForm->handleRequest($request);
        $securiteForm->handleRequest($request);
        $secourismeForm->handleRequest($request);
        $photosVideosForm->handleRequest($request);

        //Si le formulaire a été envoyé et est valide ...
        if ($introForm->isSubmitted() && $introForm->isValid()) {
            $text = $introForm->getData();
            $entityManager->persist($text);
            $entityManager->flush();
        }

        //1er encadré
        if ($mecaniqueForm->isSubmitted() && $mecaniqueForm->isValid()) {
            $title = $mecaniqueForm->get('MecaniqueTitle')->getData();
            $text = $mecaniqueForm->get('MecaniqueText')->getData();
            $photo = $mecaniqueForm->get('MecaniquePhoto')->getData();

            if ($photo == null) {
                $activiteText->setMecaniqueTitle($title);
                $activiteText->setMecaniqueText($text);
                $entityManager->persist($activiteText);
                $entityManager->flush();
            } else {
                $fichierMecanique = md5(uniqid()) . '.' . $photo->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $photo->move(
                    $this->getParameter('photo_activite'),
                    $fichierMecanique
                );
                $activiteText->setMecaniqueTitle($title);
                $activiteText->setMecaniqueText($text);
                $activiteText->setMecaniquePhoto($fichierMecanique);
                $entityManager->persist($activiteText);
                $entityManager->flush();
            }
        }

        //2eme encadré
        if ($securiteForm->isSubmitted() && $securiteForm->isValid()) {
            $title = $securiteForm->get('SecuriteTitle')->getData();
            $text = $securiteForm->get('SecuriteText')->getData();
            $photo = $securiteForm->get('SecuritePhoto')->getData();

            if ($photo == null) {
                $activiteText->setSecuriteTitle($title);
                $activiteText->setSecuriteText($text);
                $entityManager->persist($activiteText);
                $entityManager->flush();
            } else {
                $fichierSecurite = md5(uniqid()) . '.' . $photo->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $photo->move(
                    $this->getParameter('photo_activite'),
                    $fichierSecurite
                );
                $activiteText->setSecuriteTitle($title);
                $activiteText->setSecuriteText($text);
                $activiteText->setSecuritePhoto($fichierSecurite);
                $entityManager->persist($activiteText);
                $entityManager->flush();
            }
        }

        //3eme encadré
        if ($secourismeForm->isSubmitted() && $secourismeForm->isValid()) {
            $title = $secourismeForm->get('SecourismeTitle')->getData();
            $text = $secourismeForm->get('SecourismeText')->getData();
            $photo = $secourismeForm->get('SecourismePhoto')->getData();

            if ($photo == null) {
                $activiteText->setSecourismeTitle($title);
                $activiteText->setSecourismeText($text);
                $entityManager->persist($activiteText);
                $entityManager->flush();
            } else {
                $fichierSecourisme = md5(uniqid()) . '.' . $photo->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $photo->move(
                    $this->getParameter('photo_activite'),
                    $fichierSecourisme
                );
                $activiteText->setSecourismeTitle($title);
                $activiteText->setSecourismeText($text);
                $activiteText->setSecourismePhoto($fichierSecourisme);
                $entityManager->persist($activiteText);
                $entityManager->flush();
            }
        }

        //4eme encadré
        if ($photosVideosForm->isSubmitted() && $photosVideosForm->isValid()) {
            $title = $photosVideosForm->get('PhotoVideoTitle')->getData();
            $text = $photosVideosForm->get('PhotoVideoText')->getData();
            $photo = $photosVideosForm->get('PhotoVideoPhoto')->getData();

            if ($photo == null) {
                $activiteText->setPhotoVideoTitle($title);
                $activiteText->setPhotoVideoText($text);
                $entityManager->persist($activiteText);
                $entityManager->flush();
            } else {
                $fichierPhotoVideo = md5(uniqid()) . '.' . $photo->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $photo->move(
                    $this->getParameter('photo_activite'),
                    $fichierPhotoVideo
                );
                $activiteText->setPhotoVideoTitle($title);
                $activiteText->setPhotoVideoText($text);
                $activiteText->setPhotoVideoPhoto($fichierPhotoVideo);
                $entityManager->persist($activiteText);
                $entityManager->flush();
            }
        }

        if ($formIntroFormation->isSubmitted() && $formIntroFormation->isValid()) {

            $introPhoto = $formIntroFormation->get('FormationPhotoIntro')->getData();
            if ($introPhoto != null) {
                $fichier = md5(uniqid()) . '.' . $introPhoto->guessExtension();

                //Copie le fichier dans le dossier photo-intro dans le 'public'
                $introPhoto->move(
                    $this->getParameter('photo_intro'),
                    $fichier
                );
                $photoIntroFormation->setFormationPhotoIntro($fichier);
            } else {
                $photoIntroFormation->setFormationPhotoIntro($introPhoto);
            }
            $entityManager->persist($photoIntroFormation);
            $entityManager->flush();
        }

        //Permet de rediriger vers la page formations.html.twig. ( Formation)
        return $this->render('activite/formations.html.twig', [
            'textes' => $activiteContentRepository->findAll(),
            'formIntro' => $introForm->createView(),
            'formMecanique' => $mecaniqueForm->createView(),
            'formSecurite' => $securiteForm->createView(),
            'formSecourisme' => $secourismeForm->createView(),
            'formPhotosVideos' => $photosVideosForm->createView(),
            'photoMecanique' => $activiteText->getMecaniquePhoto(),
            'photoSecourisme' => $activiteText->getSecourismePhoto(),
            'photoSecurite' => $activiteText->getSecuritePhoto(),
            'photoPhotoVideo' => $activiteText->getPhotoVideoPhoto(),
            'photoIntro' => $photoIntroFormation->getFormationPhotoIntro(),
            'formPhotoIntro' => $formIntroFormation->createView(),

        ]);
    }


    /**
     * Cette méthode est en charge de rediriger vers la page Projections.
     */

    #[Route('/projections', name: 'projections')]

    public function projections(IntroPhotoRepository $introPhotoRepository, EntityManagerInterface $entityManager, ActiviteContentRepository $activiteContentRepository, Request $request): Response
    {
        $activiteText = $activiteContentRepository->find("1");
        $photoIntroProj = $introPhotoRepository->find("1");

        $introForm = $this->createForm(ProjectionfilmTextType::class, $activiteText);
        $projectionFilmForm = $this->createForm(ProjectionFilmFormType::class, $activiteText);
        $formIntroProj = $this->createForm(ProjectionFilmIntroPhotoType::class, $photoIntroProj);

        $introForm->handleRequest($request);
        $projectionFilmForm->handleRequest($request);
        $formIntroProj->handleRequest($request);

        //Si le formulaire a été envoyer et est valide ...
        if ($introForm->isSubmitted() && $introForm->isValid()) {
            $text = $introForm->getData();
            $entityManager->persist($text);
            $entityManager->flush();
        }

        //Si le formulaire a été envoyer et est valide ...
        if ($projectionFilmForm->isSubmitted() && $projectionFilmForm->isValid()) {
            $title = $projectionFilmForm->get('ProjectionFilmTitle')->getData();
            $text = $projectionFilmForm->get('ProjectionFilmText')->getData();
            $photo = $projectionFilmForm->get('ProjectionFilmPhoto')->getData();

            if ($photo == null) {
                $activiteText->setProjectionFilmTitle($title);
                $activiteText->setProjectionFilmText($text);
                $entityManager->persist($activiteText);
                $entityManager->flush();
            } else {
                $fichierProjectionFilm = md5(uniqid()) . '.' . $photo->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $photo->move(
                    $this->getParameter('photo_activite'),
                    $fichierProjectionFilm
                );
                $activiteText->setProjectionFilmTitle($title);
                $activiteText->setProjectionFilmText($text);
                $activiteText->setProjectionFilmPhoto($fichierProjectionFilm);
                $entityManager->persist($activiteText);
                $entityManager->flush();
            }
        }
        if ($formIntroProj->isSubmitted() && $formIntroProj->isValid()) {

            $introPhoto = $formIntroProj->get('ProjectionFilmPhotoIntro')->getData();
            if ($introPhoto != null) {
                $fichier = md5(uniqid()) . '.' . $introPhoto->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $introPhoto->move(
                    $this->getParameter('photo_intro'),
                    $fichier
                );
                $photoIntroProj->setProjectionFilmPhotoIntro($fichier);
            } else {
                $photoIntroProj->setProjectionFilmPhotoIntro($introPhoto);
            }
            $entityManager->persist($photoIntroProj);
            $entityManager->flush();
        }

        //Permet de rediriger vers la page projectionsfilms.html.twig ( Projection )
        return $this->render('activite/projectionsfilms.html.twig', [
            'textes' => $activiteContentRepository->findAll(),
            'formIntro' => $introForm->createView(),
            'formProjectionFilm' => $projectionFilmForm->createView(),
            'photoProjectionFilm' => $activiteText->getProjectionFilmPhoto(),
            'photoIntro' => $photoIntroProj->getProjectionFilmPhotoIntro(),
            'formPhotoIntro' => $formIntroProj->createView(),
        ]);
    }


    /**
     * Cette méthode est en charge de rediriger vers la page Écocitoyenneté.
     */

    #[Route('/ecocitoyennete', name: 'ecocitoyennete')]

    public function ecocitoyennete(IntroPhotoRepository $introPhotoRepository, EntityManagerInterface $entityManager, ActiviteContentRepository $activiteContentRepository, Request $request): Response
    {
        $activiteText = $activiteContentRepository->find("1");
        $photoIntroEco = $introPhotoRepository->find("1");

        $introForm = $this->createForm(EcocitoyenneteTextType::class, $activiteText);
        $ecocitoyenneteForm = $this->createForm(EcocitoyenneteFormType::class, $activiteText);
        $formIntroEco = $this->createForm(EcocitoyenneteIntroPhotoType::class, $photoIntroEco);

        $introForm->handleRequest($request);
        $ecocitoyenneteForm->handleRequest($request);
        $formIntroEco->handleRequest($request);

        //Si le formulaire a été envoyé et est valide ...
        if ($introForm->isSubmitted() && $introForm->isValid()) {
            $text = $introForm->getData();
            $entityManager->persist($text);
            $entityManager->flush();
        }

        //Si le formulaire a été envoyé et est valide ...
        if ($ecocitoyenneteForm->isSubmitted() && $ecocitoyenneteForm->isValid()) {
            $title = $ecocitoyenneteForm->get('EcocitoyenneteTitle')->getData();
            $text = $ecocitoyenneteForm->get('EcocitoyenneteText')->getData();
            $photo = $ecocitoyenneteForm->get('EcocitoyennetePhoto')->getData();

            if ($photo == null) {
                $activiteText->setEcocitoyenneteTitle($title);
                $activiteText->setEcocitoyenneteText($text);
                $entityManager->persist($activiteText);
                $entityManager->flush();
            } else {
                $fichierEcocitoyennete = md5(uniqid()) . '.' . $photo->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $photo->move(
                    $this->getParameter('photo_activite'),
                    $fichierEcocitoyennete
                );
                $activiteText->setEcocitoyenneteTitle($title);
                $activiteText->setEcocitoyenneteText($text);
                $activiteText->setEcocitoyennetePhoto($fichierEcocitoyennete);
                $entityManager->persist($activiteText);
                $entityManager->flush();
            }
        }

        if ($formIntroEco->isSubmitted() && $formIntroEco->isValid()) {

            $introPhoto = $formIntroEco->get('EcocitoyennetePhotoIntro')->getData();
            if ($introPhoto != null) {
                $fichier = md5(uniqid()) . '.' . $introPhoto->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $introPhoto->move(
                    $this->getParameter('photo_intro'),
                    $fichier
                );
                $photoIntroEco->setEcocitoyennetePhotoIntro($fichier);
            } else {
                $photoIntroEco->setEcocitoyennetePhotoIntro($introPhoto);
            }
            $entityManager->persist($photoIntroEco);
            $entityManager->flush();
        }

        //Permet de rediriger vers la page ecocitoyennete.html.twig ( Écocitoyenneté )
        return $this->render('activite/ecocitoyennete.html.twig', [
            'textes' => $activiteContentRepository->findAll(),
            'formIntro' => $introForm->createView(),
            'formEcocitoyennete' => $ecocitoyenneteForm->createView(),
            'photoEcocitoyennete' => $activiteText->getEcocitoyennetePhoto(),
            'photoIntro' => $photoIntroEco->getEcocitoyennetePhotoIntro(),
            'formPhotoIntro' => $formIntroEco->createView(),
        ]);
    }

    /**
     * Cette méthode est en charge de rediriger vers la page Autres activités de pleine air..
     */

    #[Route('/pleinair', name: 'pleinair')]

    public function pleinair(IntroPhotoRepository $introPhotoRepository, EntityManagerInterface $entityManager, ActiviteContentRepository $activiteContentRepository, Request $request): Response
    {

        $activiteText = $activiteContentRepository->find("1");
        $photoIntroAutre = $introPhotoRepository->find("1");

        $introForm = $this->createForm(AutresTextType::class, $activiteText);
        $autreForm = $this->createForm(AutreFormType::class, $activiteText);
        $formIntroAutre = $this->createForm(AutreIntroPhotoType::class, $photoIntroAutre);

        $introForm->handleRequest($request);
        $autreForm->handleRequest($request);
        $formIntroAutre->handleRequest($request);

        //Si le formulaire a été envoyé et est valide ...
        if ($introForm->isSubmitted() && $introForm->isValid()) {
            $text = $introForm->getData();
            $entityManager->persist($text);
            $entityManager->flush();
        }

        //Si le formulaire a été envoyé et est valide ...
        if ($autreForm->isSubmitted() && $autreForm->isValid()) {
            $title = $autreForm->get('AutreTitle')->getData();
            $text = $autreForm->get('AutreText')->getData();
            $photo = $autreForm->get('AutrePhoto')->getData();

            if ($photo == null) {
                $activiteText->setAutreTitle($title);
                $activiteText->setAutreText($text);
                $entityManager->persist($activiteText);
                $entityManager->flush();
            } else {
                $fichierAutre = md5(uniqid()) . '.' . $photo->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $photo->move(
                    $this->getParameter('photo_activite'),
                    $fichierAutre
                );
                $activiteText->setAutreTitle($title);
                $activiteText->setAutreText($text);
                $activiteText->setAutrePhoto($fichierAutre);
                $entityManager->persist($activiteText);
                $entityManager->flush();
            }
        }

        if ($formIntroAutre->isSubmitted() && $formIntroAutre->isValid()) {

            $introPhoto = $formIntroAutre->get('AutrePhotoIntro')->getData();
            if ($introPhoto != null) {
                $fichier = md5(uniqid()) . '.' . $introPhoto->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $introPhoto->move(
                    $this->getParameter('photo_intro'),
                    $fichier
                );
                $photoIntroAutre->setAutrePhotoIntro($fichier);
            } else {
                $photoIntroAutre->setAutrePhotoIntro($introPhoto);
            }
            $entityManager->persist($photoIntroAutre);
            $entityManager->flush();
        }

        //Permet de rediriger vers la page pleinair.html.twig ( Autres activité de pleine air )
        return $this->render('activite/pleinair.html.twig', [
            'textes' => $activiteContentRepository->findAll(),
            'formIntro' => $introForm->createView(),
            'formAutre' => $autreForm->createView(),
            'photoAutre' => $activiteText->getAutrePhoto(),
            'photoIntro' => $photoIntroAutre->getAutrePhotoIntro(),
            'formPhotoIntro' => $formIntroAutre->createView(),
        ]);
    }

    /**
     * Cette méthode est en charge de rediriger vers la page Gestion des adhérents.
     */

    #[Route('/adherent', name: 'adherent')]

    public function adherent(): Response
    {
        //Permet de rediriger vers la page index.html.twig ( gestion adhérent )
        return $this->render('user/index.html.twig');
    }

    /**
     * Cette méthode est en charge de rediriger vers la page Qui sommes nous ?.
     */

    #[Route('/quisommesnous', name: ' quisommesnous')]

    public function quisommesnous(): Response
    {
        //Permet de rediriger vers la page Qui-sommes-nous.html.twig( Qui somme nous ?)
        return $this->render('pagesfooter/Qui-sommes-nous.html.twig', []);
    }

    /**
     * Cette méthode est en charge de rediriger vers la page Mentions Légales.
     */

    #[Route('/mentionslegales', name: 'mentions_legales')]

    public function mentions(): Response
    {
        //Permet de rediriger vers la page présentation.html.twig.
        return $this->render('Association/MentionsLegales.html.twig');
    }


    /**
     * Cette méthode est en charge de créer un pdf.
     * 
     * @param Request $request
     * @return Response
     */

    #[Route('/pdfstatus', name: 'pdfstatus')]

    public function editpdf(Request $request): Response
    {
        //On refuse l'accès a cette méthode a l'utilisateur si l'utilisateur n'a pas le rôle Admin.
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        //On créer notre formulaire.
        $form = $this->createForm(PdfStatusType::class);
        //On récupère les information saisi.
        $form->handleRequest($request);
        //Si le formulaire a bien été envoyer et qu'il est valide ...
        if ($form->isSubmitted() && $form->isValid()) {

            //on le recupere depuis le formulaire.
            $uploadedfile = ($form['field_name']->getData());
            $destination = $this->getParameter('upload_directory');
            $originalFilename = pathinfo($uploadedfile->getClientOriginalName(), PATHINFO_FILENAME);

            //on lui donne toujours le meme nom.
            $newFilename = 'Statuts_asso_Rando nature Bruz.' . 'pdf';

            //on l'enregistre dans le dossier upload_directory dans public.
            $uploadedfile->move(
                $destination,
                $newFilename
            );
            //On renvoie un message de success a l'utilisateur pour prévenir de la réussite.
            $this->addFlash('message', 'Le pdf a bien été modifié');
            //On redirige l'utilisateur sur la page index.html.twig (accueil).
            return $this->redirectToRoute('home1');
        }
        //On envoie les données sur la page edit_pdfStatus.html.twig.
        return $this->render('pagesfooter/edit_pdfStatus.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Cette méthode est en charge de créer un pdf.
     * 
     * @param Request $request
     * @return Response
     */

    #[Route('/chartestatus', name: 'chartestatus')]

    public function editpdfcharte(Request $request): Response
    {
        # cette fonction sert à uploader le pdf de la charte.
        # il aura toujours le meme nom car on lui donne ce nom quand on l'upload

        $this->denyAccessUnlessGranted("ROLE_ADMIN");

        $form = $this->createForm(PdfStatusType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //on le recupere depuis le formulaire.
            $uploadedfile = ($form['field_name']->getData());
            $destination = $this->getParameter('upload_directory');
            $originalFilename = pathinfo($uploadedfile->getClientOriginalName(), PATHINFO_FILENAME);
            //on lui donne toujours le meme nom.
            $newFilename = 'Charte_asso_Rando nature Bruz.' . 'pdf';

            //on l'enregistre dans le dossier upload_directory dans public.
            $uploadedfile->move(
                $destination,
                $newFilename
            );
            //On renvoie un message de success pour prévenir l'utilisateur de la réussite.
            $this->addFlash('message', 'Le pdf a bien été modifié');
            //On redirige l'utilisateur sur la page index.html.twig (accueil).
            return $this->redirectToRoute('home1');
        }
        //On envoie les données sur la page edit_pdfCharte.html.twig.
        return $this->render('pagesfooter/edit_pdfCharte.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * Affiche la page des statistiques générées par le plugin Matomo .
     */

    #[Route('/statistiques', name: 'statistiques')]

    public function Statistiques(): Response
    {
        // Accès a la page refusé si le user loggé n'est pas admin
        $this->denyAccessUnlessGranted("ROLE_ADMIN");

        // Renvoie la vue
        return $this->render('Association/Statistiques.html.twig');
    }

    /**
     * Cette méthode est en charge de créer un pdf.
     * 
     * @param Request $request
     * @return Response
     */

    #[Route('/pdf_mentionslegales', name: 'pdfmentionslegales')]

    public function editpdfmentions(Request $request): Response
    {
        # cette fonction sert à uploader le pdf des mentions légales.
        # il aura toujours le meme nom car on lui donne ce nom quand on l'upload

        //On refuse l'accès a cette méthode si l'utilisateur n'a pas le rôle Admin.
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        //On créer notre formulaire.
        $form = $this->createForm(PdfStatusType::class);
        //On récupère les information saisi.
        $form->handleRequest($request);
        //Si le formulaire a bien été envoyer et qu'il est valide ...
        if ($form->isSubmitted() && $form->isValid()) {
            //on le recupere depuis le formulaire.
            $uploadedfile = ($form['field_name']->getData());
            $destination = $this->getParameter('upload_directory');
            $originalFilename = pathinfo($uploadedfile->getClientOriginalName(), PATHINFO_FILENAME);
            //on lui donne toujours le meme nom
            $newFilename = 'Mentions_legales.' . 'pdf';

            //on l'enregistre dans le dossier upload_directory dans public.
            $uploadedfile->move(
                $destination,
                $newFilename
            );
            //On renvoie un message de success pour prévenir l'utilisateur de la réussite.
            $this->addFlash('message', 'Le pdf a bien été modifié');
            //On redirige l'utilisateur sur la page index.html.twig (accueil).
            return $this->redirectToRoute('home1');
        }
        //On envoie les données sur la page edit_pdfMentions.html.twig.
        return $this->render('pagesfooter/edit_pdfMentions.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
