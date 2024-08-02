<?php

namespace App\Controller\adminController;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminArticlesController extends AbstractController{

    #[Route('/admin/articles', name: 'admin_articles')]
    public function adminArticles(ArticleRepository  $articleRepository){
        return $this->render('admin/page/articles/adminArticles.html.twig', ['articles' => $articleRepository->findAll()]);
    }

    #[Route('/admin/article/delete/{id}', name: 'admin_article_delete')]
    public function deleteArticle(ArticleRepository $articleRepository, int $id, EntityManagerInterface $entityManager){
        $article = $articleRepository->find($id);

        if(!$article){
            $html = $this->renderView('admin/404.html.twig');

            return new Response($html, 404);
        }

        try {
            $entityManager->remove($article);
            $entityManager->flush();

            // Permet d'afficher un message sur la page lorsqu'une suppression à été effectué avec succès
            $this->addFlash('gagner', 'L\'article à bien été supprimé');
        } catch (\Exception $exception){
            $this->renderView('admin/errorMessage.html.twig', ['errorMessage' => $exception->getMessage()]);
        }


        return $this->redirectToRoute('admin_articles');
    }
}