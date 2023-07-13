<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitFormType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(ProduitRepository $repository): Response
    {
        return $this->render('produit/index.html.twig', [
            'liste_produit' => $repository->findAll()
        ]);
    }

    #[Route('/produit/{id}', name: 'app_produit_id', requirements: ['id'=>"\d+"])]
    public function detailProduit(Request $request, Produit $produit): Response
    {
        return $this->render('produit/detail.html.twig', [
           'detail_produit' => $produit
        ]);
    }

    #[Route('/produit/edit/{id}', name: 'app_produit_edit')]
    #[Route('/produit/new', name: 'app_produit_new')]
    public function createOrEdit(Request $request, EntityManagerInterface $entityManager, Produit $produit = null): Response
    {
        if ($produit == null) {
            $produit = new Produit();
        }
        $form = $this->createForm(ProduitFormType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var $file UploadedFile
             */
            $file = $form['photo']->getData();
            $uuid = Uuid::v4();
            $extension = $file->guessExtension();
            $fileName = $uuid . '.' . $extension;
            try{
                $file->move($this->getParameter("photos"), $fileName);
                $produit->setPhoto($fileName);
            } catch (FileException $ex)
            {
                $message = $ex->getMessage();
                echo "<script type='text/javascript'>alert(`Erreur d'upload de votre image à cause de : $message`)";
            }
            $entityManager->persist($produit);
            $entityManager->flush();
            return $this->redirectToRoute('app_produit');
        }

        return $this->render("produit/form.html.twig", [
            'produit_form' => $form->createView()
        ]);
    }

    #[Route('/produit/delete/{id}', name: 'app_produit_delete')]
    public function delete(Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($produit);
        $entityManager->flush();
        return $this->redirectToRoute('app_produit');
    }
}
