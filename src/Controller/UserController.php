<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/registro', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        // crear formulario
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        // rellenar el objeto con los datos del formulario
        $form->handleRequest($request);
        // comprobar si se ha enviado al formulario
        if ($form->isSubmitted() && $form->isValid()) {
            // modificar el objeto para guardarlo
            $user->setRole('ROLE_USER');
            $user->setCreatedAt(new \DateTimeImmutable('now'));
            // cifrar contraseña
            $hasher = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hasher);
            // guardar usuario
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('tasks');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
        ]);
    }
}
