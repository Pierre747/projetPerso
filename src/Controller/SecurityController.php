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
    # Définition d'une route pour l'action index
    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        // Rendu de la page d'accueil de la sécurité
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, ClientRepository $clientRepository, UserPasswordHasherInterface $hasher):Response
    {
        // Création d'un nouvel objet Client
        $client = new Client();
        // Création du formulaire à partir du ClientFormType
        $form = $this->createForm(ClientFormType::class, $client);
        // Gestion de la requête HTTP par le formulaire
        $form->handleRequest($request);
        // Vérification si le formulaire a été soumis et est valide
        if($form->isSubmitted() && $form->isValid())
        {
            // Hachage et attribution du mot de passe au client
            $client->setPassword($hasher->hashPassword($client, $client->getPlainPassword()));
            // Enregistrement du client dans le repository à la place de l'entity manager
            // vu que je n'ai qu'une seule action à effectuer
            $clientRepository->save($client, true);
            // Redirection vers la page d'accueil
            return $this->redirectToRoute("app_home");
        }
        // Rendu du formulaire d'inscription
        return $this->render("register.html.twig", [
            'register_form' => $form->createView()
        ]);
    }

    # Définition d'une route pour l'action login
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $utils):Response
    {
        // Rendu de la page de connexion
        return $this->render("login.html.twig", [
           "error"=>$utils->getLastAuthenticationError()
        ]);
    }
}
