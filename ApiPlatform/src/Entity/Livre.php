<?php

namespace App\Entity;

use App\Entity\Pret;
use App\Entity\Genre;
use App\Entity\Auteur;
use App\Entity\Editeur;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LivreRepository;
use ApiPlatform\Core\Api\FilterInterface;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Serializer\Annotation\Groups;

use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=LivreRepository::class)
 * @ApiResource(
 *   attributes={
 *      "order" = {
 *           "titre": "ASC",

 *      }
 *  },
 *     collectionOperations={
 *        "get_col_role_adherent" = {
 *             "method" = "GET",
 *             "path" = "/adherent/livres",
 *             "access_control" = "is_granted('ROLE_ADHERENT')",
 *             "access_controle_message"= "Vous n'avez pas les droits d'accès à ses ressources",
 *              
 *             "normalization_context" ={
 *                    "groups" ={"get_role_adherent"}
 * 
 * 
 *              }
 *         },
 *        "get_col_role_manager" = {
 *             "method" = "GET",
 *             "path" = "/manager/livres",
 *             "access_control" = "is_granted('ROLE_MANAGER')",
 *             "access_controle_message"= "Vous n'avez pas les droits d'accès à ses ressources"
 *              
 *         },
 *        "post" ={
 *             "method" = "POST",
 *            "access_control" = "is_granted('ROLE_MANAGER')",
 *            "access_controle_message"= "Vous n'avez pas les droits d'accès à ses ressources"
 *              
 *         }
 * 
 * 
 *    },
 *    itemOperations = {
 *     "get_item_role_adherent" = {
 *             "method" = "GET",
 *             "path" = "/adherent/livres/{id}",
 *             "access_control" = "is_granted('ROLE_ADHERENT')",
 *             "access_controle_message"= "Vous n'avez pas les droits d'accès à ses ressources",
 *              
 *             "normalization_context" ={
 *                    "groups" ={"get_role_adherent"}
 * 
 * 
 *              }
 *         },
 *      "get_item_role_manager" = {
 *             "method" = "GET",
 *             "path" = "/manager/livres/{id}",
 *             "access_control" = "is_granted('ROLE_MANAGER')",
 *             "access_controle_message"= "Vous n'avez pas les droits d'accès à ses ressources"
 *              
 *         },
 *      "put_item_role_manager" = {
 *             "method" = "PUT",
 *             "path" = "/manager/livres/{id}",
 *             "access_control" = "is_granted('ROLE_MANAGER')",
 *             "access_controle_message"= "Vous n'avez pas les droits d'accès à ses ressources",
 *              "denormalization_context" ={
 *                    "groups" ={"put_manager"}
 * 
 * 
 *              } 
 *         },
 *      "put_item_role_admin" = {
 *             "method" = "PUT",
 *             "path" = "/admin/livres/{id}",
 *             "access_control" = "is_granted('ROLE_ADMIN')",
 *             "access_controle_message"= "Vous n'avez pas les droits d'accès à ses ressources"
 *         },
 *       "delete" = {
 *             "method" = "DELETE",
 *             "path" = "/admin/livres/{id}",
 *             "access_control" = "is_granted('ROLE_ADMIN')",
 *             "access_controle_message"= "Vous n'avez pas les droits d'accès à ses ressources"
 *         }
 *         
 *         
 *    }
 * ),
 * @ApiFilter(
 *   SearchFilter::class,
 *   properties={
 *      "titre": "ipartial",
 *      "author": "exact",
 *      "genre" : "exact"
 *   }
 * 
 * 
 * )
 * @ApiFilter(
 *   OrderFilter::class,
 *   properties={
 *      "titre",
 *      "prix",
 *      "auteur.nom"
 * 
 *   }
 * 
 * )
 * 
 * @ApiFilter(
 *   PropertyFilter::class,
 *   arguments ={
 *   "parameterName" = "properties",
 *   "overrideDefaultProperties": false,
 *  
 *  
 * }
 * 
 * )
 */
class Livre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"listeAuteurFull"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listeGenreFull"})
     * @Groups({"listeAuteurFull"})
     * @Groups({"get_role_adherent"})
     * @Groups({"put_manager"})
     */
    private $isbn;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listeGenreFull"})
     * @Groups({"listeAuteurFull"})
     * @Groups({"get_role_adherent"})
     * @Groups({"put_manager"})
     */
    private $titre;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"listeAuteurFull"})
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=Genre::class, inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"listeAuteurFull"})
     * @Groups({"get_role_adherent"})
     * @Groups({"put_manager"})
     */
    private $genre;

    /**
     * @ORM\ManyToOne(targetEntity=Editeur::class, inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"listeGenreFull"})
     * @Groups({"get_role_adherent"})
     * @Groups({"listeAuteurFull"})
     * @Groups({"put_manager"})
     */
    private $editeur;

    /**
     * @ORM\ManyToOne(targetEntity=Auteur::class, inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"listeGenreFull"})
     * @Groups({"put_manager"})
     */
    private $auteur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"listeGenreFull"})
     * @Groups({"listeAuteurFull"})
     * @Groups({"get_role_adherent"})
     * @Groups({"put_manager"})
     */
    private $annee;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"listeGenreFull"})
     * @Groups({"listeAuteurFull"})
     * @Groups({"get_role_adherent"})
     * @Groups({"put_manager"})
     */
    private $langue;

    /**
     * @ORM\OneToMany(targetEntity=Pret::class, mappedBy="livre")
     */
    private $prets;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $dispo;

    public function __construct()
    {
        $this->prets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getEditeur(): ?Editeur
    {
        return $this->editeur;
    }

    public function setEditeur(?Editeur $editeur): self
    {
        $this->editeur = $editeur;

        return $this;
    }

    public function getAuteur(): ?Auteur
    {
        return $this->auteur;
    }

    public function setAuteur(?Auteur $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(?int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(?string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }
    public function _toString(){
        return (string) $this->titre;
         
        }

    /**
     * @return Collection|Pret[]
     */
    public function getPrets(): Collection
    {
        return $this->prets;
    }

    public function addPret(Pret $pret): self
    {
        if (!$this->prets->contains($pret)) {
            $this->prets[] = $pret;
            $pret->setLivre($this);
        }

        return $this;
    }

    public function removePret(Pret $pret): self
    {
        if ($this->prets->removeElement($pret)) {
            // set the owning side to null (unless already changed)
            if ($pret->getLivre() === $this) {
                $pret->setLivre(null);
            }
        }

        return $this;
    }

    public function getDispo(): ?bool
    {
        return $this->dispo;
    }

    public function setDispo(?bool $dispo): self
    {
        $this->dispo = $dispo;

        return $this;
    }
}
