<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\RegisterForm;

final class RegisterController extends AbstractController
{
    #[Route('/register', name: 'register', methods: ['GET', 'POST'])]
    public function index(Request $req, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterForm::class, $user);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($em->getRepository(User::class)->findOneBy(['email' => $user->getEmail()])) {
                $this->addFlash('error', 'Email or username already exists.');
                return $this->redirectToRoute('register');
            }
            if ($em->getRepository(User::class)->findOneBy(['username' => $user->getUsername()])) {
                $this->addFlash('error', 'Email or username already exists.');
                return $this->redirectToRoute('register');
            }
            $user->setPassword(password_hash($user->getPassword(), PASSWORD_BCRYPT));
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('register_json', ['id' => $user->getId()]);
        }
        return $this->render('./register/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Affiche le JSON de l'utilisateur créé
    #[Route('/register/json/{id}', name: 'register_json', methods: ['GET', 'POST'])]
    public function registerJson(Request $req, UserRepository $userRepo): JsonResponse
    {
        $user = $userRepo->find($req->attributes->get('id'));
        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            // Ajoute d'autres champs si besoin
        ]);
    }
}
