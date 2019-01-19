<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Auteur;
use AppBundle\Entity\Livre;
use AppBundle\Form\LivreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LivreController extends Controller
{

    /**
     * @Route("/", name="liste_livre")
     */
    public function livrelistAction(){


        $repository = $this->getDoctrine()->getRepository(Livre::class);
        $livres = $repository->findAll();

        return $this->render("@App/pages/listeLivre.html.twig",
            [
                'livres' => $livres
            ]
        );
    }

// les parties suivantes ont pour but de faire grace a des methode en répository, de faire du filtrage dans une base de donnée
    /**
     * @Route("/admin/livresgenre/{genre}", name="livre_genre")
     */
    public function livresgenre($genre)
    {
        //la variable repository elle contient la class repository livre
        $repository = $this->getDoctrine()->getRepository(Livre::class);
        //
        $results = $repository->getBooKsGenre($genre);


        return $this->render("@App/pages/vulivresgenre.html.twig",
            [
                'results' => $results
            ]

        );
    }

    //--------------------------------------------------------------------------------------------------------------
// partie qui va nous permettre d'enregistrer dans la base de donnée un nouveau livre
    /**
     * @Route("/admin/ajoutlivre", name="livre_ajout")
     */
    public function ajoutLivreAction(){
// getDoctrine va appeler la methode getManager
// get manager va prendre les données et les convertir en données sql
        $entityManager= $this->getDoctrine()->getManager();



//cette ligne va nous faire un join sur auteur alors que nous ajoutons un livre
        $auteurRepository=$this->getDoctrine()->getRepository(Auteur::class);
        //nous déterminons l'auteur qui va etre en lien
        $auteur=$auteurRepository->find(1);



        $livre = new livre();

        $livre->setTitre('bob 2');
        $livre->setGenre('gosse');
        $livre->setFormat('poche');
        $livre->setnbPages('200');
        //Dans l'entity de livre nous avons modifier auteur pour qu il corresponde, donc on va ecrire
        $livre->setAuteur($auteur);



        //indique à Doctrine que vous souhaitez (éventuellement) enregistrer le produit
        $entityManager->persist($livre);
        //exécute réellement les requêtes
        $entityManager->flush();


        return $this->redirectToRoute('liste_livre');
    }


    //--------------------------------------------------------------------------------------------------------------
// partie qui va nous permettre d'effacer une donnée


    /**
     * @Route("/admin/supprlivre/{id}", name="suppr_livre")
     */
    public function supprLivreAction($id){

        //on a besoin du repository Livre pour récupérer le contenu de la table Auteur
        // pour récupérer ce repository :
        // on appelle Doctrine (qui gère les répository)
        // pour appeler la méthode getRepository qui récupère le repository Auteur (avec Auteur::class passé en parametre)
        $repository = $this->getDoctrine()->getRepository(Livre::class);

        // getDoctrine va appeler la methode getManager
        // get manager va prendre les données et les convertir en données sql
        $entityManager= $this->getDoctrine()->getManager();

        //on déclare la variable auteur en écrivant $id, car c est par l'id
        $livre=$repository->find($id);


        //La méthode remove() indique à Doctrine que vous souhaitez supprimer l'objet spécifié
        // de la base de données. Cependant, la requête DELETE n'est exécutée que lorsque la méthode flush()
        // est appelée.
        $entityManager->remove($livre);
        $entityManager->flush();

        return $this->redirectToRoute('liste_livre');

    }
//--------------------------------------------------------------------------------------------------------------
    // la partie update livre

    /**
     * @Route("/admin/miseajourlivre/{id}", name="mise_a_jour_livre")
     */

    public function MiseajourLivreAction(Request $request, $id)
    {

        //on a besoin du repository Livre pour récupérer le contenu de la table Auteur
        // pour récupérer ce repository :
        // on appelle Doctrine (qui gère les répository)
        // pour appeler la méthode getRepository qui récupère le repository Auteur (avec Auteur::class passé en parametre)
        $repository = $this->getDoctrine()->getRepository(Livre::class);
        //on déclare la variable auteur en écrivant $id, car c est par l'id
        $livre = $repository->find($id);



        //
        $form = $this->createform(LivreType::class, $livre);
//associe les données envoyé via le formulaire a mettre sur la variable $form, donc la variable $form contient bien le $°post[]
        $form->handleRequest($request);


        //on regarde si le formulaire a etait envoyé
        if ($form->isSubmitted() && $form->isValid()) {

            $livre = $form->getData();


//-----------------Partie inscription de l image en base de donnée avec extension nom---------------------

            $image = $livre->getImage();
//va creer l image dans la base de donnée avec une extension
            $imageName = md5(uniqid()).'.'.$image->guessExtension();
//tente de creer la photo et la place dans le dossier créé par images directory(dans le config.yml
            try {
                $image->move(
                    $this->getParameter('images_directory'),
                    $imageName
                );
//sinon erreur
            } catch (FileException $e) {
                ('erreur');
            }
//recupere le nom et l'extension
            $livre->setImage($imageName);

//----------------------------------------------------------






            // getDoctrine va appeler la methode getManager
            // get manager va prendre les données et les convertir en données sql
            $entityManager = $this->getDoctrine()->getManager();

            //indique à Doctrine que vous souhaitez (éventuellement) enregistrer le produit
            $entityManager->persist($livre);
            //exécute réellement les requêtes
            $entityManager->flush();

            return $this->redirectToRoute('liste_livre');

        } else {

            return $this->render('@App/pages/formlivre.html.twig',
                [
                    'formlivre' => $form->createView(),

                ]

            );

        }
    }


//----------------------------------------------------------------------------------------------------------------

    /**
     * @Route("/admin/formajoutlivre", name="form_ajout_livre")
     */

    public function formAjoutLivreAction(Request $request){



        $form=$this->createform(LivreType::class, new Livre())
            ->add('save', SubmitType::class, array('label' => 'valide'));

        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){

//-----------------Partie inscription de l image en base de donnée avec extension nom---------------------
            $livre=$form->getData();
            $image = $livre->getImage();
//va creer l image dans la base de donnée avec une extension
            $imageName = md5(uniqid()).'.'.$image->guessExtension();
//tente de creer la photoet la place dans le dossier créé par images directory(dans le config.yml
            try {
                $image->move(
                    $this->getParameter('images_directory'),
                    $imageName
                );
//sinon erreur
            } catch (FileException $e) {
                ('erreur');
            }
//recupere le nom de l'extension
            $livre->setImage($imageName);
//----------------------------------------------------------


            $entityManager=$this->getDoctrine()->getManager();

            $entityManager->persist($livre);
            $entityManager->flush();
            return $this->redirectToRoute('liste_livre');

        }else{
            return $this->render('@App/pages/formlivre.html.twig',
            [
                'formlivre' => $form->createView()
            ]

        );
}
}
}
