<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name = "list_categories")
     */

    public function categoriesList(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render("categories.html.twig",
        ["categories" => $categories]);
    }

}