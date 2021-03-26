<?php


namespace App\Entity;

use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass=CategoryRepository::Class)
 */

class Category
{
    /**
     * @ORM\Id
     * déclare ma clé primaire sur ma BDD
     * @ORM\GeneratedValue
     * demande à la BDD d'e l'auto-incrémenter
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Veuillez renseigner un titre")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank(message="Veuillez rentrer une description")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\NotBlank(message="Vous devez remplir ce champ")
     *
     * @Assert\Type("Date")
     *
     * @Assert\Expression(
     *     "this.getpublicationdate() > this.getcreationdate()",
     *     message="La date de publication ne doit pas être antérieure à la date de creation"
     * )
     */
    private $createdAT;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;


    //Avec l'annotation @Orm(Object-Relationnel-Mapping) => Je crée des relations entre mes deux entités (tables en BDD).
    //Doctrine concrétise cette relation en reliant
    //l'entité ciblée => Article à l'entité Category.
    //Mon entité Category n'étant pas le côté prioritaire de mon association de tables (avec OneToMany):
    //J'utilise l'attribut "mappedBy" à qui je donne donc la valeur de ma table d'association secondaire:
    //mappedBy="category"
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="category")
     */

    //Je créee une propriété : private $articles, avec doctrine pour gérer la relation "OneToMany",
    //entre mes deux entités (et tables en BDD)  => depuis Category vers Article.
    //     * C'est à dire qu'un article ne peut avoir qu'une seule catégorie.
    private $articles;

    //Mais une catégorie peut avoir plusieurs articles :
    //Avec la publique fonction __construct() :
    //J'instancie sous forme d'une collection d'articles, un array (type : object)
    //qui pouurait contenir cette seule catégorie.

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    //Je crée mes méthodes getteur et setteur afin de pouvoir avoir accès à ces articles (=> les lire, modifier,
    //créer et supprimer)
    /**
     * @return mixed
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param mixed $articles
     */
    public function setArticles($articles): void
    {
        $this->articles = $articles;
    }



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getCreatedAT()
    {
        return $this->createdAT;
    }

    /**
     * @return mixed
     */
    public function getIsPublished()
    {
        return $this->isPublished;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @param mixed $createdAT
     */
    public function setCreatedAT($createdAT): void
    {
        $this->createdAT = $createdAT;
    }

    /**
     * @param mixed $isPublished
     */
    public function setIsPublished($isPublished): void
    {
        $this->isPublished = $isPublished;
    }

}

