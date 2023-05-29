<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitFormType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(ProduitRepository $repository): Response
    {
        return $this->render('produit/index.html.twig', [
            'liste_produit' => $repository->findAll()
        ]);
    }

    #[Route('/produit/edit/{id}', name: 'app_produit_edit')]
    #[Route('/produit/new', name: 'app_produit_new')]
    public function createOrEdit(Request $request, EntityManagerInterface $entityManager, Produit $produit=null)
    {
        if($produit == null)
        {
            $produit = new Produit();
        }
        $form = $this->createForm(ProduitFormType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($produit);
            $entityManager->flush();
            return $this->redirectToRoute('app_produit');
        }
        return $this->render("produit/form.html.twig", [
            'produit_form' => $form->createView()
        ]);
    }

        #[Route('/produit/delete/{id}', name: 'app_produit_delete')]
        public function delete(Produit $produit, EntityManagerInterface $entityManager): \Symfony\Component\HttpFoundation\RedirectResponse
        {
            $entityManager->remove($produit);
            $entityManager->flush();
            return $this->redirectToRoute('app_produit');
        }
}
