<?php

declare(strict_types=1);

namespace App\Controller\PublicController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController{

    #[Route('/', name: 'index')]
    public function homePage(){
        return $this->render('publicView/index.html.twig');
    }

}