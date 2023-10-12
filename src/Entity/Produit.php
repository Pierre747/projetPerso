<?php

namespace App\Entity;
//Déclare le namespace où cette classe (Produit) est située.
use App\Repository\ProduitRepository;
//Importe la classe ProduitRepository du namespace App\Repository.
use Doctrine\ORM\Mapping as ORM;
//Importe la classe ORM du Doctrine ORM.
use Symfony\Component\Form\Extension\Core\Type\FileType;
//Cette ligne importe la classe FileType du composant Form de Symfony.
// FileType est souvent utilisée pour gérer les champs de formulaire liés à l'upload de fichiers.
use Symfony\Component\HttpFoundation\File\File;
//Cette ligne importe la classe File du composant HttpFoundation de Symfony.
// File est utilisée pour travailler avec des fichiers dans l'application Symfony.
use Symfony\Component\HttpFoundation\File\UploadedFile;
//Cette ligne importe la classe UploadedFile du composant HttpFoundation de Symfony.
// UploadedFile est une extension de File spécifiquement conçue pour représenter un fichier uploadé à partir d'un formulaire.
use Symfony\Component\Validator\Constraints as Assert;
//Ça, c'est super intéressant, mais on va le voir plus tard 😌

//Indique que cette classe est une entité Doctrine et spécifie la classe du repository associée.
#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    //Attributs
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[Assert\NotBlank]
    #[ORM\Column]
    private ?int $prix = null;

    #[Assert\NotBlank]
    #[ORM\Column]
    private ?int $stock = null;

    #[Assert\NotBlank]
    #[Assert\Type(Categorie::class)]
    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;
    #[ORM\ManyToOne(targetEntity: Client::class)]
    private Client $owner;

    #[ORM\Column]
    private ?string $photo = null;

    //Méthodes
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): void
    {
        $this->photo = $photo;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getOwner(): ?Client
    {
        return $this->owner;
    }

    public function setOwner(?Client $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
