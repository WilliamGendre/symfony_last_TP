<?php

namespace App\Controller\PublicController;

use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class CategorieController extends AbstractController{

    #[Route('/categorie', name: 'categorie')]
    public function categories(CategorieRepository $categorieRepository){
        return $this->render('publicView/page/categories.html.twig', ['categories' => $categorieRepository->findAll()]);
    }

    #[Route('/categorie/{id}', name: 'categorieById')]
    public function categorieBuyId(int $id, categorieRepository $categorieRepository){
        $categorie = $categorieRepository->find($id);
        return $this->render('publicView/page/categorieById.html.twig', ['categorie' => $categorie]);
    }
}