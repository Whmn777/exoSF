<?php


namespace App\Controller\admin;


use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Date;
use App\FilesServices\FilePersister;

class ArticleController extends AbstractController
{
    /**
     * @Route("/admin/articles", name="admin_list_articles")
     */

    public function listArticles(ArticleRepository $articleRepository)
    {

        $articles = $articleRepository->findAll();

        return $this->render('admin/list_articles.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/admin/articles/search", name="admin_search_articles")
     */
    public function searchArticles(Request $request, ArticleRepository $articleRepository)
    {
        // faire une recherche en base de données avec ce paramètre

        $search = $request->query->get('search'); // avec la class Request, je récupère la valeur dans l'url

        //Je récupère tous mes articles ($articles) contenant la valeur saisie de par l'utilisateur dans l'input = $search
        // dans ma BBD,
        // avec la méthode searchByTerm de la class ArticleRepository (que je vais créer),

        $articles = $articleRepository->searchByTerm($search);

        return $this->render('admin/list_articles.html.twig', [
            "articles" => $articles
        ]);
    }


    //Je crée une méthode publique articleShow ayant pour paramètres, la valeur de la wild card : {id}
    // l'injection de dépendances (class) ou encore l'autowire ayant pour class ArticleRepository
    // et pour valeur $articleRepository

    /**
     * @Route("/articles/{id}", name="admin_show_article")
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
        return $this->render('admin/show_article.html.twig', [
            'article' => $article
        ]);
    }
    /**
     * @route("/admin/articles/insert", name="admin_insert_article")
     */

    public function insertArticle(
        EntityManagerInterface $entityManager,
        Request $request,
        FilePersister $filePersister
    )
    {
        //Dans la variable $article, j'instancie un nouvel objet newArticle de la classe entité Article.
        //Je peux ainsi redéfinir à ma class entité des nouvelles propriétés, qui seront insérer comme des
        //nouveaux champs sur ma table article dans ma BDD.

        $article = new Article();

        //Je stocke mon gabarit de formulaire dans la variable $articleForm :
        //avec la méthode creatForm() de l'AbstractController je crée une nouvelle instance
        //et lui passe en paramètres ma class ArticleType (qui contient mon gabarit de formulaire)
        //et $article, le nouvel article à créer.

        $articleForm = $this->createForm(ArticleType::class, $article);

        //Je récupère les données entrées par l'utilisateur dans le formulaire, avec la class Request
        //Je le lie ces données à ma variable $articleForm
        $articleForm->handleRequest($request);

        //Si les données du formulaire sont envoyées et qu'elles sont valides
        if($articleForm->isSubmitted() && $articleForm->isValid())
        {

            //je les récupère avec la méthode getData() da la class FormInterface dans mon entité $article
            $article = $articleForm->getData();

            //J'utilise ma méthode saveFile() de ma class FilePersistor que j'ai crée dans un service.
            $article = $filePersister->saveFile($article, $articleForm);
            /*Upload d'image avant refactorisation dans un service : FilesServices
             * Je récupère les données de mon image rentrée par l'utilisateur dans le formulaire d'insertion :

            $image = $articleForm->get('image')->getData();


            //Si des données ont bien étaient rentrées :
            if($image)
            {
                //Je récupère l'image uploadé par l'utilisateur et la stocke dans un dossier temporaire :
                // chemin vers le dossier temporaire de l'image
                $originalImage = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

                //Pour cela avec la méthode slug() de la class SluggerInterface
                //Je crée un slugg de mon nom d'image.
                $safeImage = $slugger->slug($originalImage);

                //Je stocke ce slugg dans une variable $newImage, pour laquelle j'instancie une nouvelle extension unique avec les méthodes :
                //uniqid() => pour générer un nouvel id
                //guessExtension()
                $newImage = $safeImage.'-'.uniqid().'.'.$image->guessExtension();


                // Je lance un test  : si mon nom d'image a bien été saisi
                //et qu'il a bien bien été renommé avec un nom unique
                //donc je le déplace avec la méthode move()
                //dans mon dossier image (dans le dossier public => chemin gérer avec security yaml)
                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newImage

                    );

                //Je renvoie un message d'erreur si l'image n'a pu être enregistrée dans mon dossier images :

                } catch (FileException $e) {
                    throw new \Exception("l'image n'a pas été enregistrée");
                }*/

                //Avec mon setteur, j'enregistre ma nouvelle image dans l'entité $article que j'ai instancié :
                //$article->setImage($newFile);



            //J'envoie et enregistre tout en BDD.
            $entityManager->persist($article);
            $entityManager->flush();

        // après suppression d'un article, j'ajoute un message flash (dans la session)
        // pour l'afficher sur la prochaine page
        $this->addFlash("success", "L'article ". $article->getTitle() ." a bien été chargé !");

        return $this->redirectToRoute('admin_list_articles');
        }

        //Je retourne ma méthode insertArticle() pour l'afficher avec la méthode render():
        //avec la méthode createView() je crée une vue de $articleForm que pourra lire twig
        //afin de pouvoir afficher mon formulaire grâce à la variable "articleFormView"

        return $this->render("admin/insert_article.html.twig", [
            "articleFormView" => $articleForm->createView()

        ]);

        /*J'utilise les setteurs pour insérer mes nouvelles valeurs de mes propriétés
        $article->setTitle("Nouvel article inséré");
        $article->setContent("BLABLABLA");
        $article->setImage("https://static.fnac-static.com/multimedia/Images/FR/NR/20/2b/37/3615520/1540-1/tsp20180403100657/Toto-un-sacre-numero.jpg");
        $article->setCreatedAt(new \DateTime("NOW"));
        $article->setIsPublished("1");


        // A ma variable $entityManager, je pré-sauvegarde mes nouvelles données en utilisant la méthode persist
        // de la class EntityManagerInterface) (équivalent du git commit dans Git). la méthode persist me prépare
        // les entrées de mes nouvelles données dans chaque champs de ma BDD. (une unité de travail de doctrine)
        $entityManager ->persist($article);

        // J'insère et enregistre mes données statiques dans ma table article de ma BDD,
        // en utilisant la méthode flush de la class EntityManagerInterface
        $entityManager ->flush();


        //Je retourne donc le résultat de ma fonction en l'affichant sur la page admin/articles.html.twig, avec la méthode
        //render() de l'AbstractController.
        return $this->render('admin/article.html.twig');*/

    }

    /**
     * @route("/admin/articles/update/{id}", name ="admin_update_article")
     */

    public function updateArticle(
        EntityManagerInterface $entityManager,
        ArticleRepository $articleRepository,
        Request $request,
        $id)
    {
        //Je récupère l'article à modifier avec son {id};
        $article = $articleRepository -> find($id);

        //Erreur 404 au cas où $article a une valeur nulle :
        if(is_null($article))
        {
            throw $this->createNotFoundException('Article non trouvé.');
        }

        $articleForm = $this->createForm(ArticleType::class, $article);

        $articleForm->handleRequest($request);

        if($articleForm->isSubmitted() && $articleForm->isValid())
        {
            $article = $articleForm->getData();

            $entityManager->persist($article);
            $entityManager->flush();
        }

        return $this->render("admin/insert_article.html.twig",[
            "articleFormView" => $articleForm->createView()
        ]);

        return $this->redirectToRoute('admin_list_articles');

        /*
        //J'utilise les setteurs pour insérer mes nouvelles valeurs de mes propriétés
        $article->setTitle("Nouvel article modifié");
        $article->setContent("Cet article vient d'être modifié");
        $article->setImage("https://images.ladepeche.fr/api/v1/images/view/5c37588b3e454652f74478af/large/image.jpg");
        $article->setCreatedAt(new \DateTime("NOW"));
        $article->setIsPublished("1");

        $entityManager->flush();

        return $this->render("admin/article_update.html.twig", [
            'article' => $article
        ]);*/


    }

    /**
     * @Route("/admin/articles/delete/{id}", name="admin_delete_article")
     */
    public function deleteArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {
        $article = $articleRepository->find($id);

        if (is_null($article)) {
            throw $this->createNotFoundException('article non trouvée');
        }

        $this->addFlash('success', "l'article a bien été supprimé");


        $entityManager->remove($article);
        $entityManager->flush();



        return $this->redirectToRoute('admin_list_articles');
    }

}