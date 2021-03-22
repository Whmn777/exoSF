<?php


namespace App\Controller\admin;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class ArticleController extends AbstractController
{
    /**
     * @route("/admin/articles/insert", name="admin_insert_article")
     */

    public function insertArticle(EntityManagerInterface $entityManager)
    {
        //Dans la variable $article, j'instancie un nouvel objet newArticle de la classe entité Article.
        //Je peux ainsi redéfinir à ma class entité des nouvelles propriétés, qui seront insérer comme des
        //nouveaux champs sur ma table article dans ma BDD.

        $article = new Article();

        //J'utilise les setteurs pour insérer mes nouvelles valeurs de mes propriétés
        $article->setTitle("Nouvel article inséré");
        $article->setContent("BLABLABLA");
        $article->setImage("https://static.fnac-static.com/multimedia/Images/FR/NR/20/2b/37/3615520/1540-1/tsp20180403100657/Toto-un-sacre-numero.jpg");
        $article->setCreatedAt(new \DateTime("NOW"));
        $article->setIsPublished("1");


        // A ma variable $entityManager, je pré-sauvegarde mes nouvelles données en utilisant la méthode persist
        // de la class EntityManagerInterface) (équivalent du git commit dans Git). la méthode persist me prépare
        // les entrées de mes nouvelles données dans chaque champs de ma BDD.
        $entityManager ->persist($article);

        // J'insère et enregistre mes données statiques dans ma table article de ma BDD,
        // en utilisant la méthode flush de la class EntityManagerInterface
        $entityManager ->flush();


        //Je retourne donc le résultat de ma fonction en l'affichant sur la page admin/articles.html.twig, avec la méthode
        //render() de l'AbstractController.
        return $this->render('admin/articles.html.twig');


    }
}