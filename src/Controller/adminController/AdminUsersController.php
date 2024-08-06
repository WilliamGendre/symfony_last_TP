<?php

namespace App\Controller\adminController;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;

class AdminUsersController extends AbstractController
{
    #[Route('/admin/users/insert', name: 'admin_users_insert')]
    public function insertAdmin(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher){

        if ($request->getMethod() === 'POST') {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            // Empêche de créer un user sans mot de passe

            if(!$password){
                $this->addFlash('success', 'Il manque le mot de passe');
                return $this->render('admin/page/users/insertUser.html.twig');
            }

            // Créer un user

            $user = new User();

            try {

                // Permet de hacher le mot de passe pour plus de sécurité

                $hashedPassword = $passwordHasher->hashPassword($user, $password);

                $user->setEmail($email);
                $user->setRoles(['ROLE_ADMIN']);
                $user->setPassword($hashedPassword);

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'L\'admin à bien été créé');

            } catch (\Exception $exception ) {

                $this->addFlash('error', $exception->getMessage());

            }


        }

        return $this->render('admin/page/users/insertUser.html.twig');
    }

}