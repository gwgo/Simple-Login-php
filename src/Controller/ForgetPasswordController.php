<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ForgetPasswordForm;
use App\Entity\User;

final class ForgetPasswordController extends AbstractController
{
    #[Route('/forget', name: 'forget_password', methods: ['GET', 'POST'])]
    public function index(Request $req, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ForgetPasswordForm::class);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if (!$em->getRepository(User::class)->findOneBy(['email' => $data['email']])) {
                $this->addFlash('error', 'Email not found.');
                return $this->redirectToRoute('forget_password');
            }

            // Here you would typically generate a password reset token and send an email
            
            $this->addFlash('success', 'If your email is registered, you will receive a password reset link.');
            return $this->redirectToRoute('home');
        }
        return $this->render('./forget_password/forget_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
