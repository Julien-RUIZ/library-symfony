<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Auteur;
use AppBundle\Entity\Livre;
use AppBundle\Form\LivreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LivreController extends Controller
{

    /**
     * @Route("/livres", name="liste_livre")
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


        //Comme on pouvait s'y attendre, la méthode remove () indique à Doctrine que vous souhaitez supprimer l'objet spécifié
        // de la base de données. Cependant, la requête DELETE n'est exécutée que lorsque la méthode flush ()
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

    public function MiseajourLivreAction($id){
        //on a besoin du repository Livre pour récupérer le contenu de la table Auteur
        // pour récupérer ce repository :
        // on appelle Doctrine (qui gère les répository)
        // pour appeler la méthode getRepository qui récupère le repository Auteur (avec Auteur::class passé en parametre)
        $repository = $this->getDoctrine()->getRepository(Livre::class);
        $entityManager= $this->getDoctrine()->getManager();
        $livre=$repository->find($id);


        $livre->setGenre('version enfants');

        //indique à Doctrine que vous souhaitez (éventuellement) enregistrer le produit
        $entityManager->persist($livre);
        //exécute réellement les requêtes
        $entityManager->flush();

        return $this->redirectToRoute('liste_livre');
    }


//----------------------------------------------------------------------------------------------------------------

    /**
     * @Route("formajoutlivre", name="form_ajout_livre")
     */

    public function formAjoutLivreAction(){



        $form=$this->createform(LivreType::class, new Livre())
            ->add('save', SubmitType::class, array('label' => 'valide'));

        return $this->render('@App/pages/formlivre.html.twig',
        [
            'formlivre' => $form->createView()
        ]

        );

}
}
