<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommentaireReported
 *
 * @ORM\Table(name="commentaire_reported", indexes={@ORM\Index(name="id_com", columns={"id_com"}), @ORM\Index(name="id_user", columns={"id_user"})})
 * @ORM\Entity
 */
class CommentaireReported
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_rep", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRep;

    /**
     * @var string
     *
     * @ORM\Column(name="reason", type="string", length=7000, nullable=false)
     */
    private $reason;

    /**
     * @var \Commentaire
     *
     * @ORM\ManyToOne(targetEntity="Commentaire")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_com", referencedColumnName="idcom")
     * })
     */
    private $idCom;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="idUser")
     * })
     */
    private $idUser;

    public function getIdRep(): ?int
    {
        return $this->idRep;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getIdCom(): ?Commentaire
    {
        return $this->idCom;
    }

    public function setIdCom(?Commentaire $idCom): self
    {
        $this->idCom = $idCom;

        return $this;
    }

    public function getIdUser(): ?Utilisateur
    {
        return $this->idUser;
    }

    public function setIdUser(?Utilisateur $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }


}
