<?php

namespace App\Controller\adminController;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminCategoriesController extends AbstractController{

    #[Route('/admin/categories', name: 'admin_categories')]
    public function adminCategories(CategorieRepository $categorieRepository)
    {
        return $this->render('admin/page/categories/AdminCategories.html.twig', ['categories' => $categorieRepository->findAll()]);
    }

    #[Route('/admin/categorie/delete/{id}', name: 'admin_categorie_delete')]
    public function deleteCategorie(int $id, CategorieRepository $categorieRepository, EntityManagerInterface $entityManager)
    {
        $categorie = $categorieRepository->find($id);

        if (!$categorie) {
            $html = $this->renderView('admin/404.html.twig');

            return new Response($html, 404);
        }

        try {
            $entityManager->remove($categorie);
            $entityManager->flush();

            // Permet d'afficher un message sur la page lorsqu'une suppression à été effectué avec succès
            $this->addFlash('success', 'La catégorie à bien été supprimé');
        } catch (\Exception $exception){
            $this->renderView('admin/errorMessage.html.twig', ['errorMessage' => $exception->getMessage()]);
        }

        return $this->redirectToRoute('admin_categories');
    }

    #[Route('/admin/categorie/insert', name: 'admin_categorie_insert')]
    public function insertCategorie(Request $request, EntityManagerInterface $entityManager){
        $categorie = null;

        if ($request->getMethod() === 'POST') {
            $title = $request->request->get('title');
            $color = $request->request->get('color');

            $categorie = new Categorie();

            $categorie->setTitle($title);
            $categorie->setColor($color);

            $entityManager->persist($categorie);
            $entityManager->flush();

            $this->addFlash('success', 'La catégorie à bien été enregistrer');
        }

        return $this->render('admin/page/categories/insertCategories.html.twig', ['categorie' => $categorie]);
    }

    #[Route('/admin/categorie/update/{id}', name: 'admin_categorie_update')]
    public function updateCategorie(int $id, Request $request, EntityManagerInterface $entityManager, CategorieRepository $categorieRepository)
    {
        $categorie = $categorieRepository->find($id);

        if (!$categorie) {
            $html = $this->renderView('admin/404.html.twig');
            return new Response($html, 404);
        }

        if ($request->getMethod() === 'POST') {
            $title = $request->request->get('title');
            $color = $request->request->get('color');

            $categorie->setTitle($title);
            $categorie->setColor($color);

            $entityManager->persist($categorie);
            $entityManager->flush();

            $this->addFlash('success', 'La catégorie à bien été modifié');

            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('admin/page/categories/updateCategorie.html.twig', ['categorie' => $categorie]);
    }
}