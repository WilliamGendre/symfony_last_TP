<?php

namespace App\Controller\adminController;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
            $this->addFlash('success', 'L\'article à bien été supprimé');
        } catch (\Exception $exception){
            $this->renderView('admin/errorMessage.html.twig', ['errorMessage' => $exception->getMessage()]);
        }

        return $this->redirectToRoute('admin_articles');
    }

    #[Route('/admin/article/insert', name: 'admin_article_insert')]
    public function insertArticle(EntityManagerInterface $entityManager, Request $request)
    {
        $article = new Article();

        $articleCreatForm = $this->createForm(ArticleType::class, $article);

        $articleCreatForm->handleRequest($request);

        if($articleCreatForm->isSubmitted() && $articleCreatForm->isValid()){
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'L\'article à bien été enregistrer');
        }

        return $this->render('admin/page/articles/insertArticle.html.twig', ['articleForm' => $articleCreatForm->createView()]);

    }

    #[Route('/admin/article/update/{id}', name: 'admin_article_update')]
    public function updateArticle(int $id, EntityManagerInterface $entityManager, Request $request, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);

        $articleUpdateForm = $this->createForm(ArticleType::class, $article);

        $articleUpdateForm->handleRequest($request);

        if($articleUpdateForm->isSubmitted() && $articleUpdateForm->isValid()){
            $article->setUpdatedAt(new \DateTime('now'));
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'L\'article à bien été modifié');

            return $this->redirectToRoute('admin_articles');
        }

        return $this->render('admin/page/articles/updateArticle.html.twig', ['articleUpdateForm' => $articleUpdateForm->createView()]);

    }
}