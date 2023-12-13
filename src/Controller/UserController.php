<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class UserController extends AbstractController
{
    #[Route('/user' , name: 'user_list', methods: 'GET')]
    public function list(EntityManagerInterface $entityManager)
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('user.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/user' , name: 'user_add', methods: 'POST')]
    public function add(EntityManagerInterface $entityManager)
    {
        $request = Request::createFromGlobals();

        $firstname = $request->get("firstname");
        $lastname = $request->get("lastname");
        $address = $request->get("address");

        $user = new User();
        $user->setData( $firstname . " - " . $lastname . " - " . $address);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->list($entityManager);
    }

    #[Route('/user/delete/{id}' , name: 'user_delete', methods: 'GET')]
    public function delete(EntityManagerInterface $entityManager, int $id)
    {
        $request = Request::createFromGlobals();

        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->list($entityManager);
    }
}
