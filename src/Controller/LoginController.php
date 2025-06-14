<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\LoginForm;
use App\Entity\User;

final class LoginController extends AbstractController
{
    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function index(Request $req, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(LoginForm::class);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $em->getRepository(User::class)->findOneBy(['email' => $data['email']]);
            if ($user && password_verify($data['password'], $user->getPassword()))
                return $this->redirectToRoute('home');
            else
                $this->addFlash('error', 'Invalid email or password.');
        }
        return ($this->render("./login/login.html.twig", [
            'form' => $form->createView(),
        ]));
    }
}
