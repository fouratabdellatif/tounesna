<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\CommentaireRepository;
use App\Validator\ContainsBadWords;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire", indexes={@ORM\Index(name="id_ev_fk", columns={"id_event"}), @ORM\Index(name="id_hotel", columns={"id_hotel"})})
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 */
class Commentaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="idcom", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcom;

    /**
     * @var string
     *
     * @ORM\Column(name="contenue", type="string", length=7000, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50)
     * @ContainsBadWords
     */
    private $contenue;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateajc", type="date", nullable=false)
     */
    private $dateajc;

    /**
     * @var int
     *
     * @ORM\Column(name="auteur", type="integer", nullable=false)
     */
    private $auteur;

    /**
     * @var int
     *
     * @ORM\Column(name="id_hotel", type="integer", nullable=false)
     */
    private $idHotel;

    /**
     * @var int
     *
     * @ORM\Column(name="id_event", type="integer", nullable=false)
     */
    private $idCommentaire;

    public function getIdcom(): ?int
    {
        return $this->idcom;
    }

    public function getContenue(): ?string
    {
        return $this->contenue;
    }

    public function setContenue(string $contenue): self
    {
        $this->contenue = $contenue;

        return $this;
    }

    public function getDateajc(): ?\DateTimeInterface
    {
        return $this->dateajc;
    }

    public function setDateajc(\DateTimeInterface $dateajc): self
    {
        $this->dateajc = $dateajc;

        return $this;
    }

    public function getAuteur(): ?int
    {
        return $this->auteur;
    }

    public function setAuteur(int $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getIdHotel(): ?int
    {
        return $this->idHotel;
    }

    public function setIdHotel(int $idHotel): self
    {
        $this->idHotel = $idHotel;

        return $this;
    }

    public function getIdCommentaire(): ?int
    {
        return $this->idCommentaire;
    }

    public function setIdCommentaire(int $idCommentaire): self
    {
        $this->idCommentaire = $idCommentaire;

        return $this;
    }


}
