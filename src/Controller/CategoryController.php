<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use Symfony\Component\String\Slugger;
class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name = "list_categories")
     */

    public function categoriesList(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render("list_categories.html.twig",
        ["categories" => $categories]);
    }

}