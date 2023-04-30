<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientFormType;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, ClientRepository $clientRepository, UserPasswordHasherInterface $hasher):Response
    {
        $client = new Client();
        $form = $this->createForm(ClientFormType::class, $client);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $client->setPassword($hasher->hashPassword($client, $client->getPlainPassword())); // Avant la persistence, je définis le mot de passe
            $clientRepository->save($client, true); // On travaille avec le repository à la place de l'entity manager
            return $this->redirectToRoute("app_home");
        }
        return $this->render("register.html.twig", [
            'register_form' => $form->createView()
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $utils):Response
    {
        return $this->render("login.html.twig", [
           "error"=>$utils->getLastAuthenticationError()
        ]);
    }

}
