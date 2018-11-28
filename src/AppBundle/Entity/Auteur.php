<?php
/**
 * Created by PhpStorm.
 * User: lapiscine
 * Date: 21/11/2018
 * Time: 14:50
 */

//identifier une classe
namespace AppBundle\Entity;


//on vient importer le namespace en lui donnant un alias, donc on l'apperlera ORM
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
//technique en annotation, ainsi on dit que la classe auteur est une entity et on lui donne un nom a la table
    /**
     * @ORM\Entity
     * @ORM\Table(name="auteur")
     * @ORM\Entity(repositoryClass="AppBundle\Repository\AuteurRepository")
     */
    class Auteur
    {
    //colonne type number, avec auto incrementation
        /**
         * @ORM\Column(type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        private $id;

        /**
         *@ORM\OneToMany(targetEntity="AppBundle\Entity\Livre", mappedBy="auteur")
         */
        private $livre;

        /**
         * @return mixed
         */
        public function getLivre()
        {
            return $this->livre;
        }

        /**
         * @param mixed $livre
         */
        public function setLivre($livre)
        {
            $this->livre = $livre;
        }

        /**
         * @ORM\Column(type="string")
         * @Assert\Length(
         *      min = 1,
         *      max = 20,
         *     minMessage = "trop court ",
         *     maxMessage = "trop long ")
         *
         *
         *
         */
        private $nom;

        /**
         * @return mixed
         */
        public function getNom()
        {
            return $this->nom;
        }

        /**
         * @param mixed $nom
         */
        public function setNom($nom)
        {
            $this->nom = $nom;
        }

        /**
         * @ORM\Column(type="date", name="date_naissance")
         */
        private $dateNaissance;

        /**
         * @ORM\Column(type="string")
         */
        private $pays;

        /**
         * @return mixed
         */
        public function getPays()
        {
            return $this->pays;
        }

        /**
         * @param mixed $pays
         */
        public function setPays($pays)
        {
            $this->pays = $pays;
        }

        /**
         * @ORM\Column(type="date", name="date_de_mort")
         */
        private $dateMort;

        /**
         * @return mixed
         */
        public function getDateNaissance()
        {
            return $this->dateNaissance;
        }

        /**
         * @param mixed $dateNaissance
         */
        public function setDateNaissance($dateNaissance)
        {
            $this->dateNaissance = $dateNaissance;
        }

        /**
         * @return mixed
         */
        public function getBiographie()
        {
            return $this->biographie;
        }

        /**
         * @param mixed $biographie
         */
        public function setBiographie($biographie)
        {
            $this->biographie = $biographie;
        }

        /**
         * @return mixed
         */
        public function getDateMort()
        {
            return $this->dateMort;
        }

        /**
         * @param mixed $dateMort
         */
        public function setDateMort($dateMort)
        {
            $this->dateMort = $dateMort;
        }


        /**
         * @ORM\Column(type="text")
         * @Assert\Length(
         *      min = 1,
         *      max = 300,
         *     minMessage = "trop court ",
         *     maxMessage = "trop long "
         *  )
         */
        private $biographie;

        /**
         * @return mixed
         */
        public function getId()
        {
            return $this->id;
        }



    }