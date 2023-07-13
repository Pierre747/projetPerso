<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProduitRepository $repository): Response
    {
        return $this->render('home/index.html.twig', [ 'liste_produit' => $repository->findAll()]);
    }

    #[Route('/search', name: 'app_home_search')]
    public function search(): Response
    {
        return $this->render('home/search.html.twig');
    }
}
