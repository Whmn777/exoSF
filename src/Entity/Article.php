<?php

namespace App\Entity;



use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Date;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner un titre")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Veuillez renseigner une description")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=700)
     * @Assert\Url(message="Veuillez rentrer une url")
     * @Assert\NotBlank(message="Veuillez rentrer une url")
     */
    private $image;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\NotBlank(message="Tu dois remplir ce champ")
     *
     * @Assert\Type("Date")
     *
     * @Assert\Expression(
     *     "this.getpublicationdate() > this.getcreationdate()",
     *     message="La date de publication ne doit pas être antérieure à la date de creation"
     * )
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     */
    private $isPublished;


    //Avec l'annotation @Orm(Object-Relationnel-Mapping) => Je crée une relation  entre mes deux entités (tables en BDD).
    //Doctrine concrétise cette relation en générant des Foreign Key (ici sur la table Article => category_id) pour relier
    //l'entité ciblée => Category à l'entité Article.
    //Mon entité Article étant le côté prioritaire de mon association de tables (avec ManyToOne):
    //J'utilise l'attribut "inversedBy" à qui je donne donc la valeur de ma table d'association prioritaire:
    //inversedBy="articles"

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="articles")
     */

    //Je créee une propriété : private $category, avec doctrine pour gérer la relation "ManyToOne",
    //entre mes deux entités (et tables en BDD)  => depuis Article vers Category.
    //     * C'est à dire qu'une catégorie peut avoir plusieurs articles.
    private  $category;


    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(?bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }
}
