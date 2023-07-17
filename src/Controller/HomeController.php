<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function search(ProduitRepository $produitRepository, CategorieRepository $categorieRepository, Request $request): Response
    {
        $resultats = [];
        $data = $request->query;
        if($data->count()==2){
            $annonce = $data->get('annonce');
            $categorie = $data->get('categorie');
            $resultats = $produitRepository->search($categorie, $annonce);
        }

        return $this->render('home/search.html.twig', [
            'categorie_list' => $categorieRepository->findAll(),
            'resultats' => $resultats
        ]);
    }
}
