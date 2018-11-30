<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Livre
 *
 * @ORM\Table(name="livre")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LivreRepository")
 */
class Livre
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="genre", type="string", length=255)
     */
    private $genre;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     */
    private $image;

    /**
     * @var int
     *
     * @ORM\Column(name="nbPages", type="integer")
     */
    private $nbPages;

    /**
     * @var string
     *
     * @ORM\Column(name="format", type="string", length=255)
     */
    private $format;

    /**
     * @ORM\Column(type="string")
     *  @Assert\Length(
     *      min = 1,
     *      max = 100,
     *     minMessage = "trop court ",
     *     maxMessage = "trop long ")
     */
    private $titre;

    /** un join entre livre et auteur
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Auteur", inversedBy="livre")
     */
    private $auteur;

    /**
     * @return mixed
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param mixed $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * @return mixed
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * @param mixed $auteur
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;
    }



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set genre
     *
     * @param string $genre
     *
     * @return Livre
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set nbPages
     *
     * @param integer $nbPages
     *
     * @return Livre
     */
    public function setNbPages($nbPages)
    {
        $this->nbPages = $nbPages;

        return $this;
    }

    /**
     * Get nbPages
     *
     * @return int
     */
    public function getNbPages()
    {
        return $this->nbPages;
    }

    /**
     * Set format
     *
     * @param string $format
     *
     * @return Livre
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get format
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }







}

