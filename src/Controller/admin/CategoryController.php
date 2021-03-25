<?php

namespace App\Controller\admin;


use App\Repository\CategoryRepository ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


namespace App\Controller\admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository ;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/categories", name="admin_list_categories")
     */
    public function listArticles(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('admin/list_categories.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/categories/search", name="admin_search_categories")
     */
    public function searchCategories(Request $request, CategoryRepository $categoryRepository)
    {
        $search = $request->query->get('search');

        $categories = $categoryRepository->searchByTerm($search);

        return $this->render('admin/list_categories.html.twig', [
            "categories" => $categories
        ]);
    }


    /**
     * @route("/admin/categories/insert", name="admin_insert_categorie")
     */

    public function insertCategory(EntityManagerInterface $entityManager, Request $request)
    {
        $categorie = new Category();

        $categorieForm = $this->createForm(CategoryType::class, $categorie);

        $categorieForm->handleRequest($request);

        if($categorieForm->isSubmitted() && $categorieForm->isValid()){

            $categorie = $categorieForm->getData();

            $entityManager->persist($categorie);
            $entityManager->flush();
        }

        return $this->render("admin/insert_categorie.html.twig", [
            "categorieFormView" => $categorieForm->createView()
        ]);

    }

    /**
     * @Route("/admin/categories/{id}", name="admin_show_categorie")
     */
    public function categorieShow($id, CategoryRepository $categoryRepository)
    {

        $categorie = $categoryRepository->find($id);

        return $this->render('admin/show_categorie.html.twig', [
            'categorie' => $categorie
        ]);
    }

    /**
     * @route("/admin/categories/update/{id}", name ="admin_update_categorie")
     */
    public function updateArticle(
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository,
        Request $request,
        $id)
    {

        $categorie = $categoryRepository -> find($id);

        //Erreur 404 au cas où $article a une valeur nulle :
        if(is_null($categorie))
        {
            throw $this->createNotFoundException('Catégorie non trouvée.');
        }

        $categorieForm = $this->createForm(CategoryType::class, $categorie);

        $categorieForm->handleRequest($request);

        if($categorieForm->isSubmitted() && $categorieForm->isValid())
        {
            $categorie = $articleForm->getData();

            $entityManager->persist($categorie);
            $entityManager->flush();
        }

        return $this->render("admin/insert_categorie.html.twig",[
            "categorieFormView" => $categorieForm->createView()
        ]);


    }

    /**
     * @Route("/admin/categories/delete/{id}", name="admin_delete_categorie")
     */
    public function deleteCategorie($id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $categorie = $categoryRepository->find($id);

        if (is_null($categorie)) {
            throw $this->createNotFoundException('catégorie non trouvée');
        }

        $this->addFlash('success', "Cette catégorie a bien été supprimé");


        $entityManager->remove($categorie);
        $entityManager->flush();



        return $this->redirectToRoute('admin_list_categories');
    }

}