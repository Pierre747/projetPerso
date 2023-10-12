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
    //C'est une annotation Symfony qui définit une route pour cette action du contrôleur.
    //L'URL '/produit' est associée à cette action et son nom est 'app_produit'.
    #[Route('/produit', name: 'app_produit')]
    public function index(ProduitRepository $repository): Response
    {
        //C'est la déclaration de la méthode index, qui gère l'affichage de la liste des produits.
        //$repository est une instance du ProduitRepository injectée automatiquement par le système de dépendances de Symfony.
        //Response est le type de retour de la méthode, indiquant qu'elle renvoie une réponse HTTP.
        return $this->render('produit/index.html.twig', [
            'liste_produit' => $repository->findAll()
        ]);
        //Utilisation de $this->render pour rendre les vues Twig associées à chaque action et passer les données nécessaires à ces vues.
        //Ici, je crée une variable liste_produit qui sera transmise à ma vue
    }

    //cette configuration requirements pour le paramètre 'id' indique que la route associée ne correspondra
    //que si le paramètre 'id' de l'URL contient une séquence de chiffres, ce qui est souvent utilisé
    //pour garantir que 'id' est un entier dans le contexte de certaines routes.
    #[Route('/produit/{id}', name: 'app_produit_id', requirements: ['id'=>"\d+"])]
    public function detailProduit(Request $request, Produit $produit): Response
    {
        return $this->render('produit/detail.html.twig', [
           'detail_produit' => $produit
        ]);
    }

    //1/ Annotation Symfony définissant une route pour l'édition d'un produit.
    //La route est de la forme /produit/edit/{id} où {id} est un paramètre dynamique.
    //Le nom de la route est 'app_produit_edit'.
    //2/ Annotation Symfony définissant une route pour la création d'un nouveau produit.
    //La route est /produit/new avec le nom 'app_produit_new'.
    #[Route('/produit/edit/{id}', name: 'app_produit_edit')]
    #[Route('/produit/new', name: 'app_produit_new')]
    public function createOrEdit(Request $request, EntityManagerInterface $entityManager, Produit $produit = null): Response
    {
        //Définition de la méthode createOrEdit du contrôleur.
        //Elle prend en paramètres : une requête ($request), l'entityManager ($entityManager), et un produit optionnel ($produit).
        $this->denyAccessUnlessGranted('ROLE_USER', null, "Vous n'avez pas le droit de créer une annonce sans être logué");
        //Vérification que l'utilisateur a le rôle 'ROLE_USER'. S'il n'a pas ce rôle, un accès non autorisé est généré.
        if ($produit == null) {
            //Si le produit n'est pas fourni en paramètre, on en crée un nouveau.
            $produit = new Produit();
        }
        $produit->setOwner($this->getUser());
        //Définition du propriétaire du produit avec l'utilisateur actuel.
        $lienAjoutCategorie = $this->generateUrl('app_categorie_new');
        //Génération d'une URL pour l'ajout d'une catégorie.
        $form = $this->createForm(ProduitFormType::class, $produit);
        //Création d'un formulaire à partir du ProduitFormType pour le produit en cours.
        $form->handleRequest($request);
        //Gestion de la requête dans le formulaire.
        if ($form->isSubmitted()) {
            //Vérification si le formulaire a été soumis.
            if($form->isValid()){
                //Vérification de la validité du formulaire
                if($form['photo']){
                    //Vérifie si le champ du formulaire avec la clé 'photo' est présent dans le formulaire soumis.
                    /**
                     * @var $file UploadedFile
                     * Annotation pour indiquer que $file est une instance de la classe UploadedFile.
                     */
                    $file = $form['photo']->getData();
                    //Récupération des données du champ 'photo' du formulaire, qui est un fichier téléchargé (UploadedFile).
                    $uuid = Uuid::v4();
                    //Génération d'un UUID (identifiant unique universel) version 4.
                    $extension = $file->guessExtension();
                    //Utilisation de la méthode guessExtension() pour obtenir l'extension du fichier (par exemple, 'jpg', 'png').
                    $fileName = $uuid . '.' . $extension;
                    //Composition du nom de fichier en utilisant l'UUID et l'extension du fichier.
                    try{
                        $file->move($this->getParameter("photos"), $fileName);
                        //Déplacement du fichier téléchargé vers le répertoire spécifié par le paramètre 'photos'
                        //(via getParameter) avec le nouveau nom de fichier.
                        $produit->setPhoto($fileName);
                        //Définition du nom du fichier dans l'entité Produit pour enregistrer le lien vers l'image.
                    } catch (FileException $ex)
                    {
                        $message = $ex->getMessage();
                        //Récupération du message d'exception
                        return $this->render("error/error.html.twig", [
                            //Si erreur, je redirige vers la page des erreurs
                            'error' => $message
                        ]);
                    }
                }
                $entityManager->persist($produit);
                // Persiste l'entité dans le "Unit of Work"
                $entityManager->flush();
                // Exécute les opérations en attente (insertions, mises à jour, suppressions)
                return $this->redirectToRoute('app_produit');
            }else{
                return $this->render("error/error.html.twig", [
                    //Si erreur, je redirige vers la page des erreurs
                    'errors' => $form->getErrors(true)
                    //getErrors est une méthode de l'objet FormInterface
                    //qui récupère les erreurs de validation du formulaire.
                ]);
            }
        }

        return $this->render("produit/form.html.twig", [
            'produit_form' => $form->createView(),
            'lienAjoutCategorie' => $lienAjoutCategorie
            //'lienAjoutCategorie' est une autre variable passée au template.
            //Elle contient un lien généré précédemment, utilisé pour rediriger
            //vers la création d'une nouvelle catégorie.
        ]);
    }

    //Définition de la méthode delete du contrôleur pour supprimer un produit.
    //Elle prend en paramètres : un produit et l'entityManager.
    #[Route('/produit/delete/{id}', name: 'app_produit_delete')]
    public function delete(Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($produit);
        //On demande à l'EM de prendre acte de la demande de supression de $produit
        $entityManager->flush();
        //On exécute toutes les demandes en attente avec le flush
        //Ici, on a juste la suppression
        return $this->redirectToRoute('app_produit');
        //Redirection vers la liste des produits après suppression.
    }
}
