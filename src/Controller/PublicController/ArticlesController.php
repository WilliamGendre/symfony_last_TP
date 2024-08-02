<?php

namespace App\Controller\PublicController;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticlesController extends AbstractController{

    #[Route('/articles', name: 'articles')]
    public function articles(ArticleRepository  $articleRepository){
        return $this->render('publicView/page/articles.html.twig', ['articles' => $articleRepository->findAll()]);
    }

    #[Route('/article/{id}', name: 'article_id')]
    public function articleById(ArticleRepository $articleRepository, int $id){
        $article = $articleRepository->find($id);
        if(!$article){
            $html = $this->renderView('publicView/404.html.twig');

            return new Response($html, 404);
        }
        return $this->render('publicView/page/articleById.html.twig', ['article' => $article]);
    }
}