<?php


namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
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
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     */
    private $createAT;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;

}

