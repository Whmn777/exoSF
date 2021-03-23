<?php

namespace App\Controller\admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/categories", name = "admin_list_categories")
     */

    public function categoriesList(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render("admin/categories.html.twig",
            ["categories" => $categories]);
    }

}