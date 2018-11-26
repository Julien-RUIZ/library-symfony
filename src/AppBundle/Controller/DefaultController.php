<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Auteur;
use AppBundle\Entity\Livre;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

//-------------------------------------------------------------------------------------------------------------------
    /**
     * @Route("/livre", name="livre")
     */
    public function livreTest(){

        //on a besoin du repository Livre pour récupérer le contenu de la table Livre
        // pour récupérer ce repository :
        // on appelle Doctrine (qui gère les répository)
        // pour appeler la méthode getRepository qui récupère le repository Livre (avec Livre::class passé en parametre)
       $repository = $this->getDoctrine()->getRepository(Livre::class);
        $Livre = $repository->find(1);
        var_dump($Livre); die;
    }

    /**
     * @Route("/admin/livres", name="liste_livre")
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

//-------------------------------------------------------------------------------------------------------------------



//Création d'une route pour afficher, a l'aide de la methode AuteursAction(), la liste des nom d'auteurs que nous allons avoir a l'écran
    /**
     * @Route("/auteurs", name="auteur")
     */
    public function listeAuteursAction(){

        $repository = $this->getDoctrine()->getRepository(Auteur::class);
//la variable auteurs aura pour répository toutes les données de la base de donnée
        $auteurs = $repository->findAll();

//on retourne la vue auteurs
        return $this->render("@App/pages/auteurs.html.twig",
        [
            'auteurs' => $auteurs
        ]
        );
    }


    /**
     * @Route("/auteur/{id}", name="info_auteur")
     */
    public function AuteurAction($id){
//le repository sert a récuperer les entités depuis la base de donnée
        $repository = $this->getDoctrine()->getRepository(Auteur::class);
//on déclare la variable auteur en écrivant $id, car c est par l'id
        $auteur = $repository->find($id);
//cette partie permet de retourner la vue auteur, celle qui va au final afficher le résultat apres selection
        return $this->render("@App/pages/auteur.html.twig",
            [
                'auteur' => $auteur
            ]
        );
    }


//--------------------------------------------------------------------------------------------------------------
// les parties suivantes ont pour but de faire grace a des methode en répository, de faire du filtrage dans une base de donnée
    /**
     * @Route("/livresgenre/{genre}", name="livre_genre")
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


        /**
         * @Route("/AuteurPays/{pays}", name="pays_auteur")
         */
        public function PaysAuteur($pays)
        {

            $repository = $this->getDoctrine()->getRepository(Auteur::class);
            $results = $repository->getAuteurPays($pays);


            return $this->render("@App/pages/vupaysauteur.html.twig",
                [
                    'results' => $results
                ]

            );
        }

//------------------------------------------------------------------------------------------------------------------
// Exercice de recherche par mot cle dans un formulaire, identifier l'auteur comprenant ce mot dans sa biographie

// En premier nous affichons la page de la route @Route("/auteurs", name="auteur"), dans laquelle nous faisons un formulaire

            /**
             * @Route("/AuteurMotCle", name="auteur_mot_cle")
             */
            //methode qui va nous permettre de determiner la variable get provenant de notre formulaire et apparaissant dans l url
            public function AuteurMotCleAction(Request $request){
            // equivalent a $research=$_GET['reserch']
                $research=$request->query->get('research');


                //on a besoin du repository Livre pour récupérer le contenu de la table Auteur
                // pour récupérer ce repository :
                // on appelle Doctrine (qui gère les répository)
                // pour appeler la méthode getRepository qui récupère le repository Auteur (avec Auteur::class passé en parametre)
                $repository = $this->getDoctrine()->getRepository(Auteur::class);
                $results = $repository->getLikeAuteur($research);




                return $this->render("@App/pages/auteurMotCle.html.twig",
                    [
                        'results'=>$results,
                        'reserch'=>$research
                    ]

                    );


            }

//--------------------------------------------------------------------------------------------------------------
// partie qui va nous permettre d'enregistrer dans la base de donnée un nouveau livre
    /**
     * @Route("/ajoutlivre", name="livre_ajout")
     */
            public function ajoutLivreAction(){
// getDoctrine va appeler la methode getManager
// get manager va prendre les données et les convertir en données sql
                $entityManager= $this->getDoctrine()->getManager();




                $auteurRepository=$this->getDoctrine()->getRepository(Auteur::class);
                $auteur=$auteurRepository->find(3);




                $livre = new livre();

                $livre->setTitre('bob 2');
                $livre->setGenre('gosse');
                $livre->setFormat('poche');
                $livre->setnbPages('200');
                $livre->setAuteur($auteur);



                //indique à Doctrine que vous souhaitez (éventuellement) enregistrer le produit
                $entityManager->persist($livre);
                //exécute réellement les requêtes
                $entityManager->flush();


                return $this->redirectToRoute('liste_livre');
            }

//--------------------------------------------------------------------------------------------------------------
// partie qui va nous permettre d'enregistrer dans la base de donnée un nouveau auteur


            /**
             * @Route("/admin/ajoutauteur", name="auteur_ajout")
             */
            public function ajoutAuteurAction(){
        // getDoctrine va appeler la methode getManager
        // get manager va prendre les données et les convertir en données sql
                $entityManager= $this->getDoctrine()->getManager();


                $auteur = new auteur();

                $auteur->setNom('William Butler Yeats');
                $auteur->setDateMort(new \datetime("1939-04-22"));
                $auteur->setPays('Irlande');
                $auteur->setDateNaissance(new \datetime('1865-06-13'));
                $auteur->setBiographie('Déjà avant d écrire de la poésie, Yeats associait celle-ci à des idées religieuses.
La poésie de Yeats à cette période est largement imprégnée de mythes et de folklore irlandais mais aussi de la diction des vers pré-raphaélites. C est Percy Bysshe Shelley qui exerce alors sur lui la plus grande influence et cela demeurera ainsi tout au long de sa vie.
Fortement influencé par le théâtre Nô, Yeats traduit cette influence dans son style littéraire, contrairement à Brecht chez qui cette influence est principalement théâtrale.');



                //indique à Doctrine que vous souhaitez (éventuellement) enregistrer le produit
                $entityManager->persist($auteur);
                //exécute réellement les requêtes
                $entityManager->flush();

                return new Response('enregistrement ok');
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

// la partie update auteur

    /**
     * @Route("/admin/miseajourauteur/{id}", name="mise_a_jour_auteur")
     */

    public function MiseajourauteurAction($id){
        //on a besoin du repository Livre pour récupérer le contenu de la table Auteur
        // pour récupérer ce repository :
        // on appelle Doctrine (qui gère les répository)
        // pour appeler la méthode getRepository qui récupère le repository Auteur (avec Auteur::class passé en parametre)
        $repository = $this->getDoctrine()->getRepository(Auteur::class);
        $entityManager= $this->getDoctrine()->getManager();
        $auteur=$repository->find($id);


        $auteur->setpays('pays magique');

        //indique à Doctrine que vous souhaitez (éventuellement) enregistrer le produit
        $entityManager->persist($auteur);
        //exécute réellement les requêtes
        $entityManager->flush();

        return $this->redirectToRoute('auteur');
    }

//--------------------------------------------------------------------------------------------------------------
//Partie relation












}
