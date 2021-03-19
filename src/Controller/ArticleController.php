<?php


namespace App\Controller;


use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
class ArticleController extends AbstractController
{
    /**
     * @Route("/articles", name="list_articles")
     */

    public function listArticles(ArticleRepository $articleRepository)
    {

        $articles = $articleRepository->findAll();

        return $this->render('list_articles.html.twig', [
            'articles' => $articles
        ]);
    }

    //Je crée une nouvelle route pour accéder à la page de chaque article, en utilisant la wilde card {id}

    /**
     * @Route("/article/{id}", name="article_show" )
     */

    //Je crée une méthode publique articleShow ayant pour paramètres, la valeur de la wilde card {id}
    // l'injection de dépendances (class) ou encore l'autowire ayant pour class ArticleRepository
    // et pour valeur $articleRepository

    public function articleShow($id, ArticleRepository $articleRepository)
    {
        //Je récupère tous mes articles dans ma BBD
        // Grâce à la méthode find de la classe ArticleRepository,
        //J'envoie une requête SELECT ..WHERE...pour récuperer l'id de l'article à faire afficher.

        $article = $articleRepository->find($id);

        return $this->render('article.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function articleRecent(ArticleRepository $articleRepository)
    {
        //Je récupère mes articles publiés
        $articleRecent = $articleRepository ->findBy(['isPublished' => true],['id' => 'DESC'],2);

        return $this->render('home.html.twig',[
            'articleRecent' => $articleRecent
        ]);
    }
}

