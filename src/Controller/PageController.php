<?php


namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
class PageController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function showArticleRecent(ArticleRepository $articleRepository)

    {
        //Je récupère mes articles publiés selon les critères :
        $articleRecents = $articleRepository ->findBy(['isPublished' => true],['id' => 'DESC'],2);

        return $this->render('home.html.twig',[
            'articleRecents' => $articleRecents
        ]);
    }
}