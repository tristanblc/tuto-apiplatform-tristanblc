<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PretRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * 
 * @ORM\Entity(repositoryClass=PretRepository::class)
 * @ApiResource(
 *     itemOperations ={
 *       "get" = {
 *           "method" = "GET",
 *           "path" = "/prets/{id}",
 *           "access_control" = "(is_granted('ROLE_ADHERENT') and object.getAdherent() == user) or (is_granted('ROLE_MANAGER'))",
 *           "access_control_message" = "Vous ne pouvez avoir accès à vos prets"
 * 
 *        } ,
 *      "put_item_pret_manager" = {
 *             "method" = "PUT",
 *             "path" = "/manager/prets/{id}",
 *             "access_control" = "is_granted('ROLE_MANAGER')",
 *             "access_controle_message"= "Vous n'avez pas les droits d'accès à ses ressources",
 *              "denormalization_context" ={
 *                    "groups" ={"put_manager"}
 * 
 * 
 *              } 
 *         },
 *      "put_item_pret_admin" = {
 *             "method" = "PUT",
 *             "path" = "/admin/prets/{id}",
 *             "access_control" = "is_granted('ROLE_ADMIN')",
 *             "access_controle_message"= "Vous n'avez pas les droits d'accès à ses ressources"
 *         },
 *     "delete" = {
 *             "method" = "DELETE",
 *             "path" = "/admin/prets/{id}",
 *             "access_control" = "is_granted('ROLE_ADMIN')",
 *             "access_controle_message"= "Vous n'avez pas les droits d'accès à ses ressources"
 *         }
 *   }
 * 
 * 
 * )
 */
class Pret
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     *@Groups({"put_manager"})
     */
    private $datePret;

    /**
     * @ORM\Column(type="datetime")
     *@Groups({"put_manager"})
     */
    private $dateRetourPrevue;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *@Groups({"put_manager"})
     */
    private $dateRetourReelle;

    /**
     * @ORM\ManyToOne(targetEntity=Livre::class, inversedBy="prets")
     */
    private $livre;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="prets")
     */
    private $adherent;

    public function __construct()
    {
        $this->datePret = new \Datetime();
        $dateRetourPrevue = date('Y-m-d H:m:n', strtotime('15 days', $this->getDatePret()->getTimestamp()));
        $dateRetourPrevue = \DateTime::createFromFormat('Y-m-d H:m:n', $dateRetourPrevue);
        $this->dateRetourPrevue = $dateRetourPrevue;
        $this->dateRetourReelle = null; 
    
    
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePret(): ?\DateTimeInterface
    {
        return $this->datePret;
    }

    public function setDatePret(\DateTimeInterface $datePret): self
    {
        $this->datePret = $datePret;

        return $this;
    }

    public function getDateRetourPrevue(): ?\DateTimeInterface
    {
        return $this->dateRetourPrevue;
    }

    public function setDateRetourPrevue(\DateTimeInterface $dateRetourPrevue): self
    {
        $this->dateRetourPrevue = $dateRetourPrevue;

        return $this;
    }

    public function getDateRetourReelle(): ?\DateTimeInterface
    {
        return $this->dateRetourReelle;
    }

    public function setDateRetourReelle(?\DateTimeInterface $dateRetourReelle): self
    {
        $this->dateRetourReelle = $dateRetourReelle;

        return $this;
    }

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): self
    {
        $this->livre = $livre;

        return $this;
    }

    public function getAdherent(): ?Adherent
    {
        return $this->adherent;
    }

    public function setAdherent(?Adherent $adherent): self
    {
        $this->adherent = $adherent;

        return $this;
    }
}
