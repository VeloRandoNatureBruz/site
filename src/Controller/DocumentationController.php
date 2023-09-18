<?php


namespace App\Controller;


use App\Data\SearchData;
use App\Entity\Commentaire;
use App\Entity\Documentation;
use App\Form\CommentaireType;
use App\Form\DocumentationType;
use App\Form\IntroPhoto\DocumentationIntroPhotoType;
use App\Form\SearchForm;
use App\Form\SearchFormDocumentation;
use App\Repository\ActiviteRepository;
use App\Repository\CommentaireRepository;
use App\Repository\DocumentationRepository;
use App\Repository\IntroPhotoRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DocumentationController extends AbstractController
{
    /**
     * @Route ("/documentation", name="documentation")
     * @param DocumentationRepository $documentationRepository
     * @return Response
     *
     * Cette méthode permet de diriger les utilisateur vers la page documentation.
     * Elle affiche les documentations trié par date (dateModification) sur la page documentation.
     */
    public function documentation(EntityManagerInterface $entityManager, IntroPhotoRepository $introPhotoRepository, DocumentationRepository $documentationRepository, Request $request): Response
    {

        $photoIntroDocumentation = $introPhotoRepository->find("1");

        $formDoc = $this->createForm(DocumentationIntroPhotoType::class, $photoIntroDocumentation);

        $formDoc->handleRequest($request);


        //On récupère l'utilisateur en session et on le stocke dans la variable $user
        $user = $this->getUser();

        //On créé une nouvelle instance de dateTime et on récupère la date et l'heure actuelle
        $date = new \DateTime('now');
        //On créé une nouvelle instance de l'objet SearchData et on la stocke dans la variable $data
        $data = new SearchData();
        //On créé notre formulaire
        $form = $this->createForm(SearchFormDocumentation::class, $data);
        //On récupère les informations saisies
        $form->handleRequest($request);

        //on liste toutes les activités comme le findall mais en une requête
        $products = $documentationRepository->findSearchDocumentation($data);


        if ($formDoc->isSubmitted() && $formDoc->isValid()) {

            $introPhoto = $formDoc->get('DocumentationPhotoIntro')->getData();
            if ($introPhoto != null) {

                $fichier = md5(uniqid()) . '.' . $introPhoto->guessExtension();

                //Copie le fichier dans le dossier photo-profil dans le 'public'
                $introPhoto->move(
                    $this->getParameter('photo_intro'),
                    $fichier
                );
                $photoIntroDocumentation->setDocumentationPhotoIntro($fichier);
            } else {
                $photoIntroDocumentation->setDocumentationPhotoIntro($introPhoto);
            }
            $entityManager->persist($photoIntroDocumentation);
            $entityManager->flush();
        }
        //On envoie les données récupérer sur la page documentation.html.twig.
        return $this->render('documentation/documentation.html.twig',[
            'photoIntro' => $photoIntroDocumentation->getDocumentationPhotoIntro(),
            'formPhotoIntro' => $formDoc->createView(),
            'documentation' => $documentationRepository->findDocumentationOrderByDateModifier(),
            'user' => $user,
            'date' => $date,
            'documents'=> $products,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/show/documentation/{id}", name="show_documentation")
     * @param Documentation $documentation
     * @param DocumentationRepository $documentationRepository
     * @param CommentaireRepository $commentaireRepository
     * @param Request $request
     * @return Response
     *
     * Cette méthode permet l'affichage d'une documentation (plus détaillé) grace a son identifiant unique.
     * Cette méthode est aussi en charge de la création et l'affiche des commentaires.
     *
     */
    public function showDocumentation(Documentation $documentation,DocumentationRepository $documentationRepository,
                                      CommentaireRepository $commentaireRepository,Request $request): Response
    {
        //On refuse l'accès a cette méthode a l'utilisateur si l'utilisateur n'a pas le rôle User.
        $this->denyAccessUnlessGranted("ROLE_USER");
        //On récupère une documentation en fonction de son identifiant
        $documentations = $documentationRepository->findOneBy(['id' => $documentation]);
        //On récupère tous les commentaires
        $commentaires = $commentaireRepository->findCommentaireByDateCreation();
        //On créer une nouvelle instance de l'objet Commentaire et on le stock dans la variable $comment.
        $comment = new Commentaire;

        $entityManager = $this->getDoctrine()->getManager();
        //On récupère le pseudo(username) en session et on le stock dans la variable $user.
        $name = $this->getUser()->getUsername();
        //On créé notre formulaire.
        $form = $this->createForm(CommentaireType::class, $comment);
        //On récupère les informations saisi.
        $form->handleRequest($request);
        //Si le formulaire a bien été envoyer et qu'il est valide ...
        if($form->isSubmitted() && $form->isValid())
        {
            //On hydrate la propriété userName avec la valeur contenue dans la variable $name.
            $comment->setUserName($name);
            //On hydrate la propriété dateCreation avec la date et l'heure lors de l'envoie du formulaire.
            $comment->setDateCreation(new DateTime('now'));
            //On hydrate la propriété documentation de la table Commentaire avec l'identifiant contenue dans la variable
            // $documentation de la table documentation
            $comment->setDocumentation($documentation);

            //On envoie les informations a la base de donnée.
            $entityManager->persist($comment);
            $entityManager->flush();
            //On envoie un message de success pour prévenir l'utilisateur que le commentaire a bien été créé
            $this->addFlash('success', 'Le commentaire est bien pris en compte !');
        }
        //On envoie l'affichage du formulaire sur la page new_pdf.html.twig.
        return $this->render('documentation/show_documentation.html.twig',[
            'documentations' => $documentations,
            'commentaires' => $commentaires,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/create/documentation", name="create_documentation")
     * @param Request $request
     * @return Response
     *
     * Cette méthode est en charge de la création d'une documentation.
     *
     */
    public function createDocumentation(Request $request): Response
    {
        //On refuse l'accès a cette méthode a l'utilisateur si l'utilisateur n'a pas le rôle User.
        $this->denyAccessUnlessGranted("ROLE_USER");

        //On créer une nouvelle instance de l'objet Documentation et on le stock dans la variable $documentation.
        $documentation = new Documentation();
        //On récupère le pseudo(username) en session et on le stock dans la variable $user.
        $user = $this->getUser()->getUsername();
        //On créé notre formulaire.
        $formDocumentation = $this->createForm(DocumentationType::class, $documentation);
        //On récupère les informations saisi.
        $formDocumentation->handleRequest($request);
        //Si le formulaire a bien été envoyer et qu'il est valide ...
        if($formDocumentation->isSubmitted() && $formDocumentation->isValid())
        {
            //On hydrate la propriété auteur avec le pseudo stocké dans la variable user.
            $documentation->setAuteur($user);
            //On récupère la date et l'heure a la que le formulaire est envoyé et on hydrate les propriétés
            //dateCreation et dateModification.
            $documentation->setDateCreation(new DateTime('now'));
            $documentation->setDateModifier(new DateTime('now'));

            //Si la valeur contenue dans la propriété image est différente de null(rien)...
            if( $documentation->getImage() != null )
            {
                //On stock le nom du fichier dans la variable $file.
                $file = $documentation->getImage();
                //On renomme le fichier avec un nom unique et on lui ajoute l'extension contenue
                //dans la variable $file, on stock le tout dans la variable $fileName.
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                //On déplace le fichier dans le repository documentation-image.
                $file->move($this->getParameter('documentation_directory'),$fileName);
                //On hydrate le nouveau nom du fichier dans les propriétés image et imageModification.
                $documentation->setImage($fileName);
                $documentation->setImageModification($fileName);
            }
            //On applique la même logique ici que la condition au_dessus.
            if($documentation->getImage2() != null){
                $file = $documentation->getImage2();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('documentation_directory'),$fileName);
                $documentation->setImage2($fileName);
                $documentation->setImageModification2($fileName);
            }
            //On applique la même logique ici que la condition au_dessus.
            if($documentation->getPdf() != null){
                $file = $documentation->getPdf();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('pdf_directory'),$fileName);
                $documentation->setPdf($fileName);
                $documentation->setPdfModification($fileName);
            }

            //On envoie les informations a la base de donnée.
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($documentation);
            $entityManager->flush();
            //On renvoie un message de success a l'utilisateur pour prévenir de la réussite.
            $this->addFlash('success', 'Votre documentation a bien été créée !');
            //On redirige l'utilisateur sur la page documentation.html.twig
            return $this->redirectToRoute('documentation');
        }


        //On envoie les données et l'affichage du formulaire sur la page new_documentation.html.twig.
        return $this->render('documentation/new_documentation.html.twig',[
            'documentation'=> $documentation,
            'form'=> $formDocumentation->createView(),
            ]);
    }

    /**
     * @Route ("/update/documentation/{id}", name="update_documentation")
     * @param Documentation $documentation
     * @param Request $request
     * @return Response
     *
     * Cette méthode est en charge de la modification d'une documentation.
     *
     */
    public function updateDocumentation(Documentation $documentation, Request $request): Response
    {
        //On refuse l'accès a cette méthode a l'utilisateur si l'utilisateur n'a pas le rôle User.
        $this->denyAccessUnlessGranted("ROLE_USER");

        $entityManager = $this->getDoctrine()->getManager();
        //On récupère le nom de l'image stocké en base de donnée et on le stock dans la variable $image.
        $image = $documentation->getImage();
        //On récupère le nom de l'image2 stocké en base de donnée et on le stock dans la variable $image2.
        $image2 = $documentation->getImage2();
        //On récupère le nom de l'image stocké en base de donnée et on le stock dans la variable $imageModification.
        $imageModification =  $documentation->getImageModification();
        //On récupère le nom de l'image stocké en base de donnée et on le stock dans la variable $imageModification2.
        $imageModification2 = $documentation->getImageModification2();
        //On récupère le nom du pdf stocké en base de donnée et on le stock dans la variable $nompdf.
        $nomPdf = $documentation->getPdf();
        //On récupère le nom du pdf stocké en base de donnée et on le stock dans la variable $nomPdfModification.
        $nomPdfModification = $documentation->getPdfModification();

        //On créer notre formulaire.
        $form = $this->createForm(DocumentationType::class, $documentation);
        //On récupère les information saisi.
        $form->handleRequest($request);
        //Si le formulaire a bien été envoyer et qu'il est valide ...
        if($form->isSubmitted() && $form->isValid())
        {
            //On récupère le nom de l'image stocké en base de donnée et on le stock dans la variable $imageActuel.
            $imageActuel = $documentation->getImage();
            //On récupère le nom de l'image stocké en base de donnée et on le stock dans la variable $imageActuel2.
            $imageActuel2= $documentation->getImage2();
            //On récupère le nom du pdf stocké en base de donnée et on le stock dans la variable $pdfActuel.
            $pdfActuel= $documentation->getPdf();

            //On hydrate la propriété dateModificatiion avec la date et l'heure ou les formulaire est envoyé.
            $documentation->setDateModifier(new DateTime('now'));


                //Si la valeur contenue dans la variable $imageActuel est diffèrent de la valeur contenue
                // dans la variable $imageModification ET que la valeur contenue dans la variable $imageActuel
                // est différente de null(rien) ET que la variable $image est différente de null(rien)...
                if($imageActuel !== $imageModification && $imageActuel != null && $image != null )
                {
                    //On supprime le fichier stocké dans le repository documentation-image.
                    unlink($this->getParameter('documentation_directory') . '/' . $image);
                    //On stock le nom du fichier dans la variable $file.
                    $file = $documentation->getImage();
                    //On renomme le fichier avec un nom unique et on lui ajoute l'extension contenue
                    //dans la variable $file, on stock le tout dans la variable $fileName.
                    $fileName = md5(uniqid()). '.' .$file->guessExtension();
                    //On déplace le fichier dans le repository album-photo.
                    $file->move($this->getParameter('documentation_directory'),$fileName);
                    //On injecte le nouveau nom du fichier dans la propriété image.
                    $documentation->setImage($fileName);
                    //On injecte égale le nouveau dans la propriété imageModification.
                    $documentation->setImageModification($fileName);
                }
                //Sinon, Si la valeur contenue dans la variable $image est égale a null(rien) ET
                // que la  valeur contenue dans la variable $imageActuel est diffèrente de null(rien)...
                elseif ($image == null && $imageActuel != null) {
                    //On stock le nom du fichier dans la variable $file.
                    $file = $documentation->getImage();
                    //On renomme le fichier avec un nom unique et on lui ajoute l'extension contenue
                    //dans la variable $file, on stock le tout dans la variable $fileName.
                    $fileName = md5(uniqid()).'.'.$file->guessExtension();
                    //On déplace le fichier dans le repository documentation-image.
                    $file->move($this->getParameter('documentation_directory'),$fileName);
                    //On hydrate le nouveau nom du fichier dans les propriétés image et imageModification.
                    $documentation->setImage($fileName);
                    $documentation->setImageModification($fileName);
                }
                else {
                    //Si l'utilisateur ne modifie pas le champs image, on hydrate la propriété  image  avec la valeur
                    // contenue dans imageModification qui a conserver le nom du fichier actuel en base de donnée.
                    // On éviter une perte d'image lors de l'affichage après modification.
                    $documentation->setImage($imageModification);
                }



                //On applique la même logique ici que la condition au_dessus.
                 if($imageActuel2 !== $imageModification2 && $imageActuel2 != null && $image2 != null)
                 {
                     unlink($this->getParameter('documentation_directory') . '/' . $image2);

                     $file = $documentation->getImage2();
                     $fileName = md5(uniqid()). '.' .$file->guessExtension();
                     $file->move($this->getParameter('documentation_directory'),$fileName);
                     $documentation->setImage2($fileName);
                     $documentation->setImageModification2($fileName);
                 }
                 elseif ($image2 == null && $imageActuel2 != null)
                 {
                     $file = $documentation->getImage2();
                     $fileName = md5(uniqid()).'.'.$file->guessExtension();
                     $file->move($this->getParameter('documentation_directory'),$fileName);
                     $documentation->setImage2($fileName);
                     $documentation->setImageModification2($fileName);

                 }
                 else {
                     $documentation->setImage2($imageModification2);
                 }



                //On applique la même logique ici que la condition au_dessus.
                 if ($pdfActuel !== $nomPdfModification && $pdfActuel != null && $nomPdf != null){

                     unlink($this->getParameter('pdf_directory') . '/' . $nomPdf);

                     $file = $documentation->getPdf();
                     $fileName = md5(uniqid()). '.' .$file->guessExtension();
                     $file->move($this->getParameter('pdf_directory'),$fileName);
                     $documentation->setPdf($fileName);
                     $documentation->setPdfModification($fileName);
                 }
                 elseif ($nomPdf == null && $pdfActuel != null)
                 {
                     $file = $documentation->getPdf();
                     $fileName = md5(uniqid()).'.'.$file->guessExtension();
                     $file->move($this->getParameter('pdf_directory'),$fileName);
                     $documentation->setPdf($fileName);
                     $documentation->setPdfModification($fileName);
                 }
                 else {
                     $documentation->setPdf($nomPdfModification);
                 }

            //On envoie les informations a la base de donnée.
            $entityManager->persist($documentation);
            $entityManager->flush();
            //On envoie un message de success pour prévenir l'utilisateur de la réussite.
            $this->addFlash('success', 'La documentation à été modifié avec succès ! ');
            //On redirige l'utilisateur vers la page documentation.html.twig.
            return $this->redirectToRoute('documentation');
        }
        //On envoie l'affichage du formulaire sur la update_documentation.html.twig.
        return $this->render('documentation/update_documentation.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/delete/documentation/{id}", name="delete_documentation")
     * @param Documentation $documentation
     * @return RedirectResponse
     *
     * Cette méthode est en charge de la suppression d'une documentation.
     * Si cette documentation contient des images ou des commentaires, elles supprimé.
     *
     */
    public function deleteDocumentation(Documentation $documentation): RedirectResponse
    {
        //On refuse l'accès a cette méthode a l'utilisateur si l'utilisateur n'a pas le rôle User.
        $this->denyAccessUnlessGranted("ROLE_USER");

        $entityManager = $this->getDoctrine()->getManager();
        //Si la propriété image n'est pas null (vide)...
        if( $documentation->getImage() != null)
        {
            //On récupère le nom de l'image stocké en base de donnée.
            $nom = $documentation->getImage();
            //On supprime le fichier stocker dans le repository documentation-image.
            unlink($this->getParameter('documentation_directory') . '/' . $nom);

        }
        //On applique la même logique ici que la condition au_dessus.
        if( $documentation->getImage2() != null)
        {
            $nom = $documentation->getImage2();
            unlink($this->getParameter('documentation_directory') . '/' . $nom);

        }
        //On applique la même logique ici que la condition au_dessus.
        if( $documentation->getPdf() != null)
        {
            $nom = $documentation->getPdf();
            unlink($this->getParameter('pdf_directory') . '/' . $nom);

        }
        //On supprime les valeur stockées dans la base de donnée.
        $entityManager->remove($documentation);
        $entityManager->flush();
        //On revoie un message a l'utilisateur pour lui confirmé la suppression du commentaire
        $this->addFlash('success', 'La documentation à été supprimé avec succès !');
        //On redirige l'utilisateur sur la page documentation.html.twig.
        return $this->redirectToRoute('documentation');
    }

    /**
     * @Route ("/update/commentaire/{id}", name="update_commentaire")
     * @param Commentaire $commentaire
     * @param Request $request
     * @return RedirectResponse
     *
     * Cette méthode permet la modification d'un commentaire.
     *
     */
    public function updateCommentaire(Commentaire $commentaire,Request $request):Response
    {
        //On refuse l'accès a cette méthode a l'utilisateur si l'utilisateur n'a pas le rôle User.
        $this->denyAccessUnlessGranted("ROLE_USER");
        $entityManager = $this->getDoctrine()->getManager();
        //On créer notre formulaire.
        $form = $this->createForm(CommentaireType::class, $commentaire);
        //On récupère les information saisi.
        $form->handleRequest($request);
        //Si le formulaire a bien été envoyer et qu'il est valide ...
        if($form->isSubmitted() && $form->isValid()){
            //On hydrate la propriété dateModificatiion avec la date et l'heure ou les formulaire est envoyé.
            $commentaire->setDateModification(new DateTime('now'));
            //On envoie les informations a la base de donnée.
            $entityManager->persist($commentaire);
            $entityManager->flush();
            //On renvoie un message de success a l'utilisateur pour prévenir de la réussite.
            $this->addFlash('success', 'Le commentaire à été à été modifié avec succès !');
            //On redirige l'utilisateur sur la page documentation.html.twig.
            return $this->redirectToRoute('documentation');
        }
        //On envoie les données sur la page update_commentaire.html.twig
        return $this->render('documentation/update_commentaire.html.twig',[
           'form' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/delete/commentaire/{id}", name="delete_commentaire")
     * @param Commentaire $commentaire
     * @return RedirectResponse
     *
     * Cette méthode est en charge supprimer un commentaire.
     *
     */
    public function deleteCommentaire(Commentaire $commentaire): RedirectResponse
    {
        //On refuse l'accès a cette méthode a l'utilisateur si l'utilisateur n'a pas le rôle User.
        $this->denyAccessUnlessGranted("ROLE_USER");

        $entityManager = $this->getDoctrine()->getManager();
        //On supprime les valeur stockées dans la base de donnée.
        $entityManager->remove($commentaire);
        $entityManager->flush();
        //On revoie un message a l'utilisateur pour lui confirmé la suppression du commentaire
        $this->addFlash('success', 'Le commentaire à été supprimé avec succès !');
        //On redirige l'utilisateur sur la page documentation.html.twig.
        return $this->redirectToRoute('documentation');
    }
}