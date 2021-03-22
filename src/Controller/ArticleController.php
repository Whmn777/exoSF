<?php


namespace App\Controller;


use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/articles/search", name="search_articles")
     */
    public function searchArticles(Request $request, ArticleRepository $articleRepository)
    {
        // faire une recherche en base de données avec ce parametre

        $search = $request->query->get('search');

        //Je récupère tous mes articles ($articles) contenant la valeur saisie de par l'utilisateur dans l'input = $search
        // dans ma BBD,
        // avec la méthode searchByTerm de la class ArticleRepository,

        $articles = $articleRepository->searchByTerm($search);

        return $this->render('list_articles.html.twig', [
            "articles" => $articles
        ]);
    }

    //Je crée une nouvelle route pour accéder à la page de chaque article, en utilisant la wilde card {id}

    /**
     * @Route("/articles/{id}", name="article_show" )
     */

    //Je crée une méthode publique articleShow ayant pour paramètres, la valeur de la wild card : {id}
    // l'injection de dépendances (class) ou encore l'autowire ayant pour class ArticleRepository
    // et pour valeur $articleRepository

    /**
     * @Route("/articles/{id}", name="show_article")
     */
    public function articleShow($id, ArticleRepository $articleRepository)
    {
        //Je récupère avec $article, tous mes articles dans ma BBD grâce à leur id
        // avec la méthode find de la class ArticleRepository,


        $article = $articleRepository->find($id);//find() de la class ArticleRepository
        //(qui me permet de générer des requêtes en BDD);


        //(return) J'envoie donc cette requête ( => SELECT id FROM article)
        //pour (=$this) la  faire afficher grâce à la méthode render() de ma class AbstractController.
        //Cette méthode render() aura pour paramètres : la vue (ou affichage sur le navigateur) et un tableau
        //ayant pour index 'article' (que je rappellerai dans dans ma vue) ayant pour valeur: $article
        // (le résultat de ma requête)
        return $this->render('article.html.twig', [
            'article' => $article
        ]);
    }
/*
    /**
     * @Route("/", name="home")
     */
/*    public function articleRecent(ArticleRepository $articleRepository)
    {
        //Je récupère mes articles publiés
        $articleRecent = $articleRepository ->findBy(['isPublished' => true],['id' => 'DESC'],2);

        return $this->render('home.html.twig',[
            'articleRecent' => $articleRecent
        ]);
    }
*/
}

